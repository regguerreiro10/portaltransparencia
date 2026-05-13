<?php

try {
    require_once __DIR__ . '/app/model/ProcessoLegislativoSchemaHelper.php';

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        throw new RuntimeException('Anexo nao informado.');
    }

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

    ProcessoLegislativoSchemaHelper::ensureSchemaWithPdo($pdo);

    $stmt = $pdo->prepare("
        SELECT id, nome, arquivo
        FROM processo_legislativo_anexo
        WHERE id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    if (!$row) {
        throw new RuntimeException('Anexo nao localizado.');
    }

    $file = __DIR__ . '/' . ltrim((string) $row['arquivo'], '/\\');
    if (!is_file($file)) {
        throw new RuntimeException('Arquivo do anexo nao localizado.');
    }

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $contentTypeList = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'zip' => 'application/zip',
    ];
    $contentType = $contentTypeList[$extension] ?? 'application/octet-stream';

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: inline; filename="' . basename((string) $row['nome']) . '"');
    header('Content-Length: ' . filesize($file));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    readfile($file);
} catch (Throwable $e) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo $e->getMessage();
}
