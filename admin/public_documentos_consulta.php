<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    require_once __DIR__ . '/app/model/DocumentoPublicoSchemaHelper.php';

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

    DocumentoPublicoSchemaHelper::ensureSchemaWithPdo($pdo);

    $filters = [
        'number' => is_scalar($_GET['number'] ?? null) ? trim((string) $_GET['number']) : '',
        'type' => is_scalar($_GET['type'] ?? null) ? trim((string) $_GET['type']) : '',
        'date' => is_scalar($_GET['date'] ?? null) ? trim((string) $_GET['date']) : '',
        'subject' => is_scalar($_GET['subject'] ?? null) ? trim((string) $_GET['subject']) : '',
        'name' => is_scalar($_GET['name'] ?? null) ? trim((string) $_GET['name']) : '',
        'office' => is_scalar($_GET['office'] ?? null) ? trim((string) $_GET['office']) : '',
        'period' => is_scalar($_GET['period'] ?? null) ? trim((string) $_GET['period']) : '',
        'attachment_filter' => is_scalar($_GET['attachment_filter'] ?? null) ? trim((string) $_GET['attachment_filter']) : '',
        'download_min' => max(0, (int) ($_GET['download_min'] ?? 0)),
        'sort' => is_scalar($_GET['sort'] ?? null) ? trim((string) $_GET['sort']) : 'recent',
    ];

    $where = ["d.status = 'published'"];
    $params = [];

    if ($filters['number'] !== '') {
        $where[] = 'd.numero_documento LIKE :number';
        $params[':number'] = '%' . $filters['number'] . '%';
    }
    if ($filters['type'] !== '') {
        $where[] = 'd.tipo = :type';
        $params[':type'] = $filters['type'];
    }
    if ($filters['date'] !== '') {
        $where[] = 'd.data_documento = :date';
        $params[':date'] = $filters['date'];
    }
    if ($filters['subject'] !== '') {
        $where[] = 'd.assunto LIKE :subject';
        $params[':subject'] = '%' . $filters['subject'] . '%';
    }
    if ($filters['name'] !== '') {
        $where[] = 'd.nome LIKE :name';
        $params[':name'] = '%' . $filters['name'] . '%';
    }
    if ($filters['office'] !== '') {
        $where[] = 'd.orgao = :office';
        $params[':office'] = $filters['office'];
    }
    if ($filters['period'] !== '') {
        $where[] = "DATE_FORMAT(d.data_documento, '%Y') = :period";
        $params[':period'] = $filters['period'];
    }
    if ($filters['download_min'] > 0) {
        $where[] = 'd.downloads >= :download_min';
        $params[':download_min'] = $filters['download_min'];
    }
    if ($filters['attachment_filter'] === 'com-anexos') {
        $where[] = 'COALESCE(a.total_anexos, 0) > 0';
    }
    if ($filters['attachment_filter'] === 'sem-anexos') {
        $where[] = 'COALESCE(a.total_anexos, 0) = 0';
    }

    $orderBy = 'd.data_documento DESC, d.id DESC';
    if ($filters['sort'] === 'downloads') {
        $orderBy = 'd.downloads DESC, d.data_documento DESC';
    } elseif ($filters['sort'] === 'name') {
        $orderBy = 'd.nome ASC, d.data_documento DESC';
    }

    $sql = "
        SELECT
            d.id,
            d.numero_documento,
            d.tipo,
            d.data_documento,
            d.assunto,
            d.nome,
            d.orgao,
            d.downloads
        FROM documento_publico d
        LEFT JOIN (
            SELECT documento_publico_id, COUNT(*) AS total_anexos
            FROM documento_publico_anexo
            GROUP BY documento_publico_id
        ) a ON a.documento_publico_id = d.id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY {$orderBy}
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $attachmentStmt = $pdo->prepare("
        SELECT id, documento_publico_id, nome
        FROM documento_publico_anexo
        WHERE documento_publico_id = :documento_publico_id
        ORDER BY ordem ASC, id ASC
    ");

    foreach ($rows as &$row) {
        $attachmentStmt->execute([':documento_publico_id' => $row['id']]);
        $attachments = $attachmentStmt->fetchAll();
        $row['attachments'] = array_map(static function (array $attachment) {
            return [
                'id' => (int) $attachment['id'],
                'name' => $attachment['nome'],
                'download_url' => 'admin/public_documento_download.php?id=' . (int) $attachment['id'],
            ];
        }, $attachments);
    }
    unset($row);

    $types = $pdo->query("SELECT DISTINCT tipo FROM documento_publico WHERE status = 'published' ORDER BY tipo ASC")->fetchAll(PDO::FETCH_COLUMN);
    $offices = $pdo->query("SELECT DISTINCT orgao FROM documento_publico WHERE status = 'published' ORDER BY orgao ASC")->fetchAll(PDO::FETCH_COLUMN);
    $periods = $pdo->query("SELECT DISTINCT DATE_FORMAT(data_documento, '%Y') AS periodo FROM documento_publico WHERE status = 'published' ORDER BY periodo DESC")->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'filters' => [
            'number' => $filters['number'],
            'type' => $filters['type'],
            'date' => $filters['date'],
            'subject' => $filters['subject'],
            'name' => $filters['name'],
            'office' => $filters['office'],
            'period' => $filters['period'],
            'attachment_filter' => $filters['attachment_filter'],
            'download_min' => $filters['download_min'],
            'sort' => $filters['sort'],
            'types' => array_values(array_filter($types)),
            'offices' => array_values(array_filter($offices)),
            'periods' => array_values(array_filter($periods)),
        ],
        'summary' => [
            'records' => count($rows),
            'attachments' => array_sum(array_map(static fn (array $row) => count($row['attachments']), $rows)),
            'downloads' => array_sum(array_map(static fn (array $row) => (int) $row['downloads'], $rows)),
        ],
        'rows' => array_map(static function (array $row) {
            return [
                'id' => (int) $row['id'],
                'number' => $row['numero_documento'],
                'type' => $row['tipo'],
                'date' => $row['data_documento'],
                'subject' => $row['assunto'],
                'name' => $row['nome'],
                'office' => $row['orgao'],
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
