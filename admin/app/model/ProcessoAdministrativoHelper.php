<?php

class ProcessoAdministrativoHelper
{
    public static function getCurrentUserContext(): array
    {
        $openedTransaction = false;
        if (!TTransaction::get()) {
            TTransaction::open('minierp');
            ProcessoAdministrativoSchemaHelper::ensureSchema();
            $openedTransaction = true;
        }

        $userId = (int) TSession::getValue('userid');
        $login = (string) TSession::getValue('login');
        $name = $login ?: 'Usuario';
        $departmentIds = [];
        $departmentNames = [];

        if ($userId > 0) {
            $user = SystemUsers::find($userId);
            if ($user instanceof SystemUsers && !empty($user->name)) {
                $name = $user->name;
            }

            $conn = TTransaction::get();
            $sql = "SELECT pd.system_departamento_id, sd.nome
                    FROM pessoa p
                    INNER JOIN pessoa_departamento pd ON pd.pessoa_id = p.id
                    INNER JOIN system_departamento sd ON sd.id = pd.system_departamento_id
                    WHERE p.system_user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $departmentIds[] = (int) $row['system_departamento_id'];
                $departmentNames[] = $row['nome'];
            }
        }

        $context = [
            'user_id' => $userId,
            'login' => $login,
            'name' => $name,
            'department_ids' => array_values(array_unique(array_filter($departmentIds))),
            'department_names' => array_values(array_unique(array_filter($departmentNames))),
            'primary_department_id' => $departmentIds[0] ?? null,
            'primary_department_name' => $departmentNames[0] ?? 'Departamento nao vinculado',
        ];

        if ($openedTransaction) {
            TTransaction::close();
        }

        return $context;
    }

    public static function isAdmin(): bool
    {
        return TSession::getValue('login') === 'admin';
    }

    public static function getTiposProcesso(): array
    {
        return [
            'Administrativo' => 'Administrativo',
            'Financeiro' => 'Financeiro',
            'Juridico' => 'Juridico',
            'Pessoal' => 'Pessoal',
            'Compras' => 'Compras',
            'Ouvidoria' => 'Ouvidoria',
            'Geral' => 'Geral',
        ];
    }

    public static function getSituacoes(): array
    {
        return [
            '' => 'Todas',
            'Rascunho' => 'Rascunho',
            'Enviado' => 'Enviado',
            'Recebido' => 'Recebido',
            'Em andamento' => 'Em andamento',
            'Em analise' => 'Em analise',
            'Aguardando parecer' => 'Aguardando parecer',
            'Despachado' => 'Despachado',
            'Devolvido' => 'Devolvido',
            'Concluido' => 'Concluido',
            'Arquivado' => 'Arquivado',
        ];
    }

    public static function getStatusLeitura(): array
    {
        return [
            '' => 'Todos',
            'Nao lido' => 'Nao lido',
            'Lido' => 'Lido',
            'Rascunho' => 'Rascunho',
            'Arquivado' => 'Arquivado',
        ];
    }

    public static function getSigiloOptions(bool $withAll = false): array
    {
        $items = [
            'Publico' => 'Publico',
            'Restrito' => 'Restrito',
            'Sigiloso' => 'Sigiloso',
        ];

        return $withAll ? ['' => 'Todos'] + $items : $items;
    }

    public static function getPrazoStatusOptions(): array
    {
        return [
            '' => 'Todos',
            'No prazo' => 'No prazo',
            'Proximo do vencimento' => 'Proximo do vencimento',
            'Vencido' => 'Vencido',
            'Interrompido' => 'Interrompido',
            'Prorrogado' => 'Prorrogado',
            'Arquivado' => 'Arquivado',
        ];
    }

    public static function calculatePrazoFinal(string $startDate, int $days, string $type): string
    {
        $date = new DateTime(substr($startDate, 0, 10));
        $remaining = max(0, $days);

        if ($type === 'Dias uteis') {
            while ($remaining > 0) {
                $date->modify('+1 day');
                $weekday = (int) $date->format('N');
                if ($weekday < 6) {
                    $remaining--;
                }
            }
        } else {
            $date->modify("+{$remaining} days");
        }

        return $date->format('Y-m-d');
    }

    public static function determinePrazoStatus(?string $prazoFinal, ?string $currentStatus = null): string
    {
        if (!empty($currentStatus) && in_array($currentStatus, ['Interrompido', 'Arquivado', 'Prorrogado'], true)) {
            return $currentStatus;
        }

        if (empty($prazoFinal)) {
            return 'No prazo';
        }

        $today = new DateTime(date('Y-m-d'));
        $final = new DateTime(substr($prazoFinal, 0, 10));
        $days = (int) $today->diff($final)->format('%r%a');

        if ($days < 0) {
            return 'Vencido';
        }

        if ($days <= 3) {
            return 'Proximo do vencimento';
        }

        return 'No prazo';
    }

    public static function resolveDepartmentName(?int $departmentId, ?string $fallback = null): string
    {
        if (!empty($departmentId)) {
            $department = SystemDepartamento::find((int) $departmentId);
            if ($department instanceof SystemDepartamento && !empty($department->nome)) {
                return $department->nome;
            }
        }

        return $fallback ?: 'Departamento nao informado';
    }

    public static function canChangeSigilo(ProcessoAdministrativo $processo): bool
    {
        if (self::isAdmin()) {
            return true;
        }

        $context = self::getCurrentUserContext();

        return (int) $processo->remetente_user_id === (int) $context['user_id']
            || in_array((int) $processo->departamento_atual_id, $context['department_ids'], true);
    }

    public static function buildAccessFilterSql(): ?string
    {
        if (self::isAdmin()) {
            return null;
        }

        $context = self::getCurrentUserContext();
        $conditions = [];

        if ($context['user_id'] > 0) {
            $conditions[] = 'remetente_user_id = ' . (int) $context['user_id'];
        }

        if (!empty($context['department_ids'])) {
            $ids = implode(',', array_map('intval', $context['department_ids']));
            $conditions[] = "departamento_origem_id IN ({$ids})";
            $conditions[] = "departamento_atual_id IN ({$ids})";
        }

        return $conditions ? '(SELECT id FROM processo_administrativo WHERE ' . implode(' OR ', $conditions) . ')' : '(SELECT 0)';
    }

    public static function canAccess(ProcessoAdministrativo $processo): bool
    {
        if (self::isAdmin()) {
            return true;
        }

        $context = self::getCurrentUserContext();
        $isCreator = (int) $processo->remetente_user_id === (int) $context['user_id'];
        $isDepartmentUser = in_array((int) $processo->departamento_origem_id, $context['department_ids'], true)
            || in_array((int) $processo->departamento_atual_id, $context['department_ids'], true)
            || in_array((int) $processo->departamento_destino_id, $context['department_ids'], true);

        if (in_array($processo->nivel_sigilo, ['Restrito', 'Sigiloso'], true)) {
            return $isCreator || $isDepartmentUser;
        }

        return $isCreator || $isDepartmentUser;
    }

    public static function createTramitacao(
        ProcessoAdministrativo $processo,
        string $tipoEvento,
        string $acaoExecutada,
        string $despachoTexto,
        ?string $observacao = null,
        ?string $departamentoOrigem = null,
        ?string $departamentoDestino = null
    ): void {
        $context = self::getCurrentUserContext();

        $tramitacao = new ProcessoAdministrativoTramitacao();
        $tramitacao->processo_administrativo_id = $processo->id;
        $tramitacao->data_tramitacao = date('Y-m-d H:i:s');
        $tramitacao->tipo_evento = $tipoEvento;
        $tramitacao->acao_executada = $acaoExecutada;
        $tramitacao->departamento_origem = $departamentoOrigem ?: $processo->departamento_origem;
        $tramitacao->departamento_destino = $departamentoDestino ?: $processo->departamento_atual;
        $tramitacao->usuario_responsavel = $context['name'];
        $tramitacao->despacho_texto = $despachoTexto;
        $tramitacao->observacao = $observacao;
        $tramitacao->prazo_tipo = $processo->prazo_tipo;
        $tramitacao->prazo_dias = $processo->prazo_dias;
        $tramitacao->prazo_final = $processo->prazo_final;
        $tramitacao->prazo_status = $processo->prazo_status;
        $tramitacao->created_at = date('Y-m-d H:i:s');
        $tramitacao->store();
    }
}
