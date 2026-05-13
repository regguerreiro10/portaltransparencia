<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    require_once __DIR__ . '/app/model/LicitacaoSchemaHelper.php';

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

    LicitacaoSchemaHelper::ensureSchemaWithPdo($pdo);

    $filters = [
        'period' => is_scalar($_GET['period'] ?? null) ? trim((string) $_GET['period']) : '',
        'status' => is_scalar($_GET['status'] ?? null) ? trim((string) $_GET['status']) : '',
        'keyword' => is_scalar($_GET['keyword'] ?? null) ? trim((string) $_GET['keyword']) : '',
        'notice_number' => is_scalar($_GET['notice_number'] ?? null) ? trim((string) $_GET['notice_number']) : '',
        'origin_process' => is_scalar($_GET['origin_process'] ?? null) ? trim((string) $_GET['origin_process']) : '',
        'manager' => is_scalar($_GET['manager'] ?? null) ? trim((string) $_GET['manager']) : '',
        'modality' => is_scalar($_GET['modality'] ?? null) ? trim((string) $_GET['modality']) : '',
        'supplier_document' => is_scalar($_GET['supplier_document'] ?? null) ? trim((string) $_GET['supplier_document']) : '',
    ];

    $where = ['1 = 1'];
    $params = [];

    if ($filters['period'] !== '') {
        $where[] = "DATE_FORMAT(l.data_licitacao, '%Y-%m') = :period";
        $params[':period'] = $filters['period'];
    }
    if ($filters['status'] !== '') {
        $where[] = 'l.status = :status';
        $params[':status'] = $filters['status'];
    }
    if ($filters['keyword'] !== '') {
        $where[] = '(l.objeto LIKE :keyword OR l.numero_edital LIKE :keyword OR l.processo_origem LIKE :keyword OR l.fornecedor_nome LIKE :keyword)';
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if ($filters['notice_number'] !== '') {
        $where[] = 'l.numero_edital LIKE :notice_number';
        $params[':notice_number'] = '%' . $filters['notice_number'] . '%';
    }
    if ($filters['origin_process'] !== '') {
        $where[] = 'l.processo_origem LIKE :origin_process';
        $params[':origin_process'] = '%' . $filters['origin_process'] . '%';
    }
    if ($filters['manager'] !== '') {
        $where[] = 'l.gestor = :manager';
        $params[':manager'] = $filters['manager'];
    }
    if ($filters['modality'] !== '') {
        $where[] = 'l.modalidade = :modality';
        $params[':modality'] = $filters['modality'];
    }
    if ($filters['supplier_document'] !== '') {
        $where[] = 'l.fornecedor_documento LIKE :supplier_document';
        $params[':supplier_document'] = '%' . $filters['supplier_document'] . '%';
    }

    $sql = "
        SELECT
            l.id,
            l.numero_edital,
            l.processo_origem,
            l.objeto,
            l.status,
            l.modalidade,
            l.gestor,
            l.fornecedor_nome,
            l.fornecedor_documento,
            l.valor_estimado,
            l.data_licitacao,
            l.downloads
        FROM licitacao l
        WHERE " . implode(' AND ', $where) . "
        ORDER BY l.data_licitacao DESC, l.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $attachmentStmt = $pdo->prepare("
        SELECT id, nome
        FROM licitacao_anexo
        WHERE licitacao_id = :licitacao_id
        ORDER BY ordem ASC, id ASC
    ");

    foreach ($rows as &$row) {
        $attachmentStmt->execute([':licitacao_id' => $row['id']]);
        $attachments = $attachmentStmt->fetchAll();
        $row['attachments'] = array_map(static function (array $attachment) {
            return [
                'id' => (int) $attachment['id'],
                'name' => $attachment['nome'],
                'download_url' => 'admin/public_licitacao_download.php?id=' . (int) $attachment['id'],
            ];
        }, $attachments);
    }
    unset($row);

    $periodsRaw = $pdo->query("
        SELECT DISTINCT DATE_FORMAT(data_licitacao, '%Y-%m') AS periodo
        FROM licitacao
        WHERE status IS NOT NULL AND status <> ''
        ORDER BY periodo DESC
    ")->fetchAll(PDO::FETCH_COLUMN);

    $managers = $pdo->query("
        SELECT DISTINCT gestor
        FROM licitacao
        WHERE gestor IS NOT NULL AND gestor <> ''
        ORDER BY gestor ASC
    ")->fetchAll(PDO::FETCH_COLUMN);

    $modalities = $pdo->query("
        SELECT DISTINCT modalidade
        FROM licitacao
        WHERE modalidade IS NOT NULL AND modalidade <> ''
        ORDER BY modalidade ASC
    ")->fetchAll(PDO::FETCH_COLUMN);

    $statusesRaw = $pdo->query("
        SELECT DISTINCT status
        FROM licitacao
        WHERE status IS NOT NULL AND status <> ''
        ORDER BY status ASC
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

    $totalValue = array_sum(array_map(static fn (array $row) => (float) $row['valor_estimado'], $rows));

    echo json_encode([
        'filters' => [
            'period' => $filters['period'],
            'status' => $filters['status'],
            'keyword' => $filters['keyword'],
            'notice_number' => $filters['notice_number'],
            'origin_process' => $filters['origin_process'],
            'manager' => $filters['manager'],
            'modality' => $filters['modality'],
            'supplier_document' => $filters['supplier_document'],
            'periods' => $periods,
            'statuses' => $statuses,
            'managers' => array_values(array_filter($managers)),
            'modalities' => array_values(array_filter($modalities)),
        ],
        'summary' => [
            'records' => count($rows),
            'total_value' => round($totalValue, 2),
        ],
        'rows' => array_map(static function (array $row) {
            return [
                'id' => (int) $row['id'],
                'notice_number' => $row['numero_edital'],
                'origin_process' => $row['processo_origem'],
                'object' => $row['objeto'],
                'status' => $row['status'],
                'modality' => $row['modalidade'],
                'manager' => $row['gestor'],
                'supplier_name' => $row['fornecedor_nome'] ?: 'Nao informado',
                'supplier_document' => $row['fornecedor_documento'] ?: 'Nao informado',
                'estimated_value' => (float) $row['valor_estimado'],
                'date' => $row['data_licitacao'],
                'downloads' => (int) $row['downloads'],
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
