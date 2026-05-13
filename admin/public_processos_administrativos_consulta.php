<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    require_once __DIR__ . '/app/model/ProcessoAdministrativoSchemaHelper.php';

    $config = require __DIR__ . '/app/config/minierp.php';

    if (($config['type'] ?? '') !== 'mysql') {
        throw new RuntimeException('Tipo de banco nao suportado para este endpoint.');
    }

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        $config['host'] ?? 'localhost',
        $config['name'] ?? ''
    );

    $pdo = new PDO(
        $dsn,
        $config['user'] ?? '',
        $config['pass'] ?? '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    ProcessoAdministrativoSchemaHelper::ensureSchemaWithPdo($pdo);

    $filters = [
        'period' => is_scalar($_GET['period'] ?? null) ? trim((string) $_GET['period']) : '',
        'status' => is_scalar($_GET['status'] ?? null) ? trim((string) $_GET['status']) : '',
        'sigilo' => is_scalar($_GET['sigilo'] ?? null) ? trim((string) $_GET['sigilo']) : '',
        'keyword' => is_scalar($_GET['keyword'] ?? null) ? trim((string) $_GET['keyword']) : '',
        'numero_processo' => is_scalar($_GET['numero_processo'] ?? null) ? trim((string) $_GET['numero_processo']) : '',
        'texto_busca' => is_scalar($_GET['texto_busca'] ?? null) ? trim((string) $_GET['texto_busca']) : '',
        'conteudo_arquivo' => is_scalar($_GET['conteudo_arquivo'] ?? null) ? trim((string) $_GET['conteudo_arquivo']) : '',
        'department' => is_scalar($_GET['department'] ?? null) ? trim((string) $_GET['department']) : '',
        'responsible' => is_scalar($_GET['responsible'] ?? null) ? trim((string) $_GET['responsible']) : '',
        'permission' => is_scalar($_GET['permission'] ?? null) ? trim((string) $_GET['permission']) : '',
        'has_attachments' => is_scalar($_GET['has_attachments'] ?? null) ? trim((string) $_GET['has_attachments']) : '',
    ];

    $where = ['1 = 1'];
    $params = [];

    if ($filters['period'] !== '') {
        $where[] = "DATE_FORMAT(p.data_abertura, '%Y-%m') = :period";
        $params[':period'] = $filters['period'];
    }
    if ($filters['status'] !== '') {
        $where[] = 'p.status = :status';
        $params[':status'] = $filters['status'];
    }
    if ($filters['sigilo'] !== '') {
        $where[] = 'p.nivel_sigilo = :sigilo';
        $params[':sigilo'] = $filters['sigilo'];
    }
    if ($filters['keyword'] !== '') {
        $where[] = '(p.numero_processo LIKE :keyword OR p.numero_protocolo LIKE :keyword OR p.assunto LIKE :keyword OR p.descricao LIKE :keyword)';
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if ($filters['numero_processo'] !== '') {
        $where[] = '(p.numero_processo LIKE :numero_processo OR p.numero_protocolo LIKE :numero_processo)';
        $params[':numero_processo'] = '%' . $filters['numero_processo'] . '%';
    }
    if ($filters['texto_busca'] !== '') {
        $where[] = '(p.assunto LIKE :texto_busca OR p.descricao LIKE :texto_busca OR p.tipo_processo LIKE :texto_busca OR p.requerente LIKE :texto_busca OR p.solicitante LIKE :texto_busca)';
        $params[':texto_busca'] = '%' . $filters['texto_busca'] . '%';
    }
    if ($filters['conteudo_arquivo'] !== '') {
        $where[] = 'EXISTS (
            SELECT 1
            FROM processo_administrativo_anexo paa
            WHERE paa.processo_administrativo_id = p.id
              AND (paa.nome LIKE :conteudo_arquivo OR paa.arquivo LIKE :conteudo_arquivo)
        )';
        $params[':conteudo_arquivo'] = '%' . $filters['conteudo_arquivo'] . '%';
    }
    if ($filters['department'] !== '') {
        $where[] = 'p.departamento_atual = :department';
        $params[':department'] = $filters['department'];
    }
    if ($filters['responsible'] !== '') {
        $where[] = 'p.responsavel = :responsible';
        $params[':responsible'] = $filters['responsible'];
    }
    if ($filters['permission'] !== '') {
        $where[] = 'p.permissao_grupo = :permission';
        $params[':permission'] = $filters['permission'];
    }
    if ($filters['has_attachments'] === 'sim') {
        $where[] = 'COALESCE(a.total_anexos, 0) > 0';
    }
    if ($filters['has_attachments'] === 'nao') {
        $where[] = 'COALESCE(a.total_anexos, 0) = 0';
    }

    $sql = "
        SELECT
            p.id,
            p.numero_processo,
            p.numero_protocolo,
            p.assunto,
            p.descricao,
            p.status,
            p.nivel_sigilo,
            p.departamento_atual,
            p.responsavel,
            p.permissao_grupo,
            p.data_abertura,
            COALESCE(a.total_anexos, 0) AS total_anexos
        FROM processo_administrativo p
        LEFT JOIN (
            SELECT processo_administrativo_id, COUNT(*) AS total_anexos
            FROM processo_administrativo_anexo
            GROUP BY processo_administrativo_id
        ) a ON a.processo_administrativo_id = p.id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY p.data_abertura DESC, p.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $historyStmt = $pdo->prepare("
        SELECT data_tramitacao, tipo_evento, departamento_origem, departamento_destino, usuario_responsavel, observacao
        FROM processo_administrativo_tramitacao
        WHERE processo_administrativo_id = :processo_id
        ORDER BY data_tramitacao DESC, id DESC
    ");

    $attachmentStmt = $pdo->prepare("
        SELECT id, nome
        FROM processo_administrativo_anexo
        WHERE processo_administrativo_id = :processo_id
        ORDER BY ordem ASC, id ASC
    ");

    foreach ($rows as &$row) {
        $historyStmt->execute([':processo_id' => $row['id']]);
        $historyRows = $historyStmt->fetchAll();
        $row['history'] = array_map(static function (array $entry) {
            return [
                'date' => $entry['data_tramitacao'],
                'event' => $entry['tipo_evento'],
                'from_department' => $entry['departamento_origem'] ?: '',
                'to_department' => $entry['departamento_destino'] ?: '',
                'responsible' => $entry['usuario_responsavel'] ?: '',
                'note' => $entry['observacao'] ?: '',
            ];
        }, $historyRows);

        $attachmentStmt->execute([':processo_id' => $row['id']]);
        $attachments = $attachmentStmt->fetchAll();
        $row['attachments'] = array_map(static function (array $attachment) {
            return [
                'id' => (int) $attachment['id'],
                'name' => $attachment['nome'],
                'download_url' => 'admin/public_processo_administrativo_download.php?id=' . (int) $attachment['id'],
            ];
        }, $attachments);
    }
    unset($row);

    $periodsRaw = $pdo->query("
        SELECT DISTINCT DATE_FORMAT(data_abertura, '%Y-%m') AS periodo
        FROM processo_administrativo
        ORDER BY periodo DESC
    ")->fetchAll(PDO::FETCH_COLUMN);

    $departments = $pdo->query("
        SELECT DISTINCT departamento_atual
        FROM processo_administrativo
        WHERE departamento_atual IS NOT NULL AND departamento_atual <> ''
        ORDER BY departamento_atual ASC
    ")->fetchAll(PDO::FETCH_COLUMN);

    $responsibles = $pdo->query("
        SELECT DISTINCT responsavel
        FROM processo_administrativo
        WHERE responsavel IS NOT NULL AND responsavel <> ''
        ORDER BY responsavel ASC
    ")->fetchAll(PDO::FETCH_COLUMN);

    $permissions = $pdo->query("
        SELECT DISTINCT permissao_grupo
        FROM processo_administrativo
        WHERE permissao_grupo IS NOT NULL AND permissao_grupo <> ''
        ORDER BY permissao_grupo ASC
    ")->fetchAll(PDO::FETCH_COLUMN);

    $statusesRaw = $pdo->query("
        SELECT DISTINCT status
        FROM processo_administrativo
        WHERE status IS NOT NULL AND status <> ''
        ORDER BY status ASC
    ")->fetchAll(PDO::FETCH_COLUMN);

    $sigilosRaw = $pdo->query("
        SELECT DISTINCT nivel_sigilo
        FROM processo_administrativo
        WHERE nivel_sigilo IS NOT NULL AND nivel_sigilo <> ''
        ORDER BY nivel_sigilo ASC
    ")->fetchAll(PDO::FETCH_COLUMN);

    $periods = array_map(static function ($period) {
        return [
            'value' => $period,
            'label' => substr((string) $period, 5, 2) . '/' . substr((string) $period, 0, 4),
        ];
    }, array_values(array_filter($periodsRaw)));

    $statuses = array_map(static function ($status) {
        return ['value' => $status, 'label' => $status];
    }, array_values(array_filter($statusesRaw)));

    $sigilos = array_map(static function ($sigilo) {
        return ['value' => $sigilo, 'label' => $sigilo];
    }, array_values(array_filter($sigilosRaw)));

    $summary = [
        'records' => count($rows),
        'in_progress' => count(array_filter($rows, static function (array $row) {
            return in_array($row['status'], ['Em andamento', 'Em analise', 'Aguardando parecer'], true);
        })),
        'restricted' => count(array_filter($rows, static function (array $row) {
            return in_array($row['nivel_sigilo'], ['Restrito', 'Sigiloso'], true);
        })),
        'attachments' => array_sum(array_map(static fn (array $row) => (int) $row['total_anexos'], $rows)),
    ];

    echo json_encode([
        'filters' => [
            'period' => $filters['period'],
            'status' => $filters['status'],
            'sigilo' => $filters['sigilo'],
            'keyword' => $filters['keyword'],
            'numero_processo' => $filters['numero_processo'],
            'texto_busca' => $filters['texto_busca'],
            'conteudo_arquivo' => $filters['conteudo_arquivo'],
            'department' => $filters['department'],
            'responsible' => $filters['responsible'],
            'permission' => $filters['permission'],
            'has_attachments' => $filters['has_attachments'],
            'periods' => $periods,
            'statuses' => $statuses,
            'sigilos' => $sigilos,
            'departments' => array_values(array_filter($departments)),
            'responsibles' => array_values(array_filter($responsibles)),
            'permissions' => array_values(array_filter($permissions)),
        ],
        'summary' => $summary,
        'rows' => array_map(static function (array $row) {
            return [
                'id' => (int) $row['id'],
                'process_number' => $row['numero_processo'],
                'protocol_number' => $row['numero_protocolo'],
                'subject' => $row['assunto'],
                'description' => $row['descricao'] ?: '',
                'status' => $row['status'],
                'sigilo' => $row['nivel_sigilo'],
                'department' => $row['departamento_atual'],
                'responsible' => $row['responsavel'],
                'permission' => $row['permissao_grupo'] ?: 'Nao informado',
                'date' => $row['data_abertura'],
                'attachments_count' => (int) $row['total_anexos'],
                'history' => $row['history'],
                'attachments' => $row['attachments'],
            ];
        }, $rows),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
