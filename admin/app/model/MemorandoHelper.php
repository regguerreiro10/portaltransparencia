<?php

class MemorandoHelper
{
    public static function getTemplates(): array
    {
        return [
            'padrao' => 'Memorando padrao',
            'solicitacao' => 'Solicitacao interna',
            'resposta' => 'Resposta oficial',
            'encaminhamento' => 'Encaminhamento',
            'urgente' => 'Comunicacao urgente',
        ];
    }

    public static function getTemplateContent(?string $codigo): string
    {
        $templates = [
            'padrao' => '<p><strong>Senhor(a),</strong></p><p>Encaminhamos o presente memorando para conhecimento e providencias.</p><p>Atenciosamente,</p>',
            'solicitacao' => '<p><strong>Prezados,</strong></p><p>Solicitamos a adocao das providencias necessarias sobre o assunto informado.</p><p>Favor retornar com parecer.</p>',
            'resposta' => '<p><strong>Em resposta ao memorando recebido,</strong></p><p>Informamos que as medidas cabiveis estao em andamento.</p>',
            'encaminhamento' => '<p><strong>Encaminha-se</strong> este memorando para analise e manifestacao do setor competente.</p>',
            'urgente' => '<p><strong>Com prioridade alta,</strong></p><p>Solicitamos atendimento imediato ao tema apresentado.</p>',
        ];

        return $templates[$codigo] ?? '';
    }

    public static function getStatusOptions(): array
    {
        return [
            '' => 'Todos',
            'Enviado' => 'Enviado',
            'Recebido' => 'Recebido',
            'Lido' => 'Lido',
            'Respondido' => 'Respondido',
            'Arquivado' => 'Arquivado',
            'Recuperado' => 'Recuperado',
        ];
    }

    public static function getCurrentUserContext(): array
    {
        $openedTransaction = false;
        if (!TTransaction::get()) {
            TTransaction::open('minierp');
            MemorandoSchemaHelper::ensureSchema();
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

    public static function canAccessMemorando(Memorando $memorando): bool
    {
        if (self::isAdmin()) {
            return true;
        }

        $context = self::getCurrentUserContext();

        if ((int) $memorando->remetente_user_id === (int) $context['user_id']) {
            return true;
        }

        if (!empty($context['department_ids']) && in_array((int) $memorando->departamento_origem_id, $context['department_ids'])) {
            return true;
        }

        foreach ($memorando->getMemorandoDestinatarios() as $destinatario) {
            if ((int) $destinatario->system_user_id === (int) $context['user_id']) {
                return true;
            }

            if (!empty($context['department_ids']) && in_array((int) $destinatario->system_departamento_id, $context['department_ids'])) {
                return true;
            }
        }

        return false;
    }

    public static function canEditMemorando(Memorando $memorando): bool
    {
        return self::isAdmin() || (int) $memorando->remetente_user_id === (int) TSession::getValue('userid');
    }

    public static function buildAccessFilterSql(): ?string
    {
        if (self::isAdmin()) {
            return null;
        }

        $context = self::getCurrentUserContext();
        $conditions = [];
        $userId = (int) $context['user_id'];

        if ($userId > 0) {
            $conditions[] = "m.remetente_user_id = {$userId}";
            $conditions[] = "md.system_user_id = {$userId}";
        }

        if (!empty($context['department_ids'])) {
            $ids = implode(',', array_map('intval', $context['department_ids']));
            $conditions[] = "m.departamento_origem_id IN ({$ids})";
            $conditions[] = "md.system_departamento_id IN ({$ids})";
        }

        if (!$conditions) {
            return '(SELECT 0)';
        }

        return "(SELECT DISTINCT m.id
                 FROM memorando m
                 LEFT JOIN memorando_destinatario md ON md.memorando_id = m.id
                 WHERE " . implode(' OR ', $conditions) . ')';
    }

    public static function getUserNameById($userId): string
    {
        if (empty($userId)) {
            return '';
        }

        $openedTransaction = false;
        if (!TTransaction::get()) {
            TTransaction::open('minierp');
            $openedTransaction = true;
        }

        $user = SystemUsers::find((int) $userId);
        $name = $user instanceof SystemUsers ? (string) $user->name : '';

        if ($openedTransaction) {
            TTransaction::close();
        }

        return $name;
    }

    public static function getDepartmentNameById($departmentId): string
    {
        if (empty($departmentId)) {
            return '';
        }

        $openedTransaction = false;
        if (!TTransaction::get()) {
            TTransaction::open('minierp');
            $openedTransaction = true;
        }

        $department = SystemDepartamento::find((int) $departmentId);
        $name = $department instanceof SystemDepartamento ? (string) $department->nome : '';

        if ($openedTransaction) {
            TTransaction::close();
        }

        return $name;
    }

    public static function updateOverallStatus(Memorando $memorando): void
    {
        $statuses = [];
        foreach ($memorando->getMemorandoDestinatarios() as $destinatario) {
            $statuses[] = $destinatario->status;
        }

        $status = 'Enviado';
        if (in_array('Respondido', $statuses, true)) {
            $status = 'Respondido';
        } elseif (in_array('Lido', $statuses, true)) {
            $status = 'Lido';
        } elseif (in_array('Recebido', $statuses, true)) {
            $status = 'Recebido';
        }

        if ($statuses && count(array_unique($statuses)) === 1 && $statuses[0] === 'Arquivado') {
            $status = 'Arquivado';
            $memorando->arquivado_em = date('Y-m-d H:i:s');
        }

        if ($memorando->status === 'Recuperado') {
            $status = 'Recuperado';
        }

        $memorando->status = $status;
        if ($status === 'Lido' && empty($memorando->lido_em)) {
            $memorando->lido_em = date('Y-m-d H:i:s');
        }
        $memorando->updated_at = date('Y-m-d H:i:s');
        $memorando->store();
    }

    public static function createTramitacao(
        int $memorandoId,
        string $acao,
        ?string $status,
        ?string $descricao = null,
        ?int $memorandoDestinatarioId = null
    ): void {
        $context = self::getCurrentUserContext();
        $tramite = new MemorandoTramitacao();
        $tramite->memorando_id = $memorandoId;
        $tramite->memorando_destinatario_id = $memorandoDestinatarioId;
        $tramite->acao = $acao;
        $tramite->status_resultante = $status;
        $tramite->usuario_id = $context['user_id'];
        $tramite->usuario_nome = $context['name'];
        $tramite->departamento_nome = $context['primary_department_name'];
        $tramite->descricao = $descricao;
        $tramite->data_evento = date('Y-m-d H:i:s');
        $tramite->created_at = date('Y-m-d H:i:s');
        $tramite->store();
    }
}
