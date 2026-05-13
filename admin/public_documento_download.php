<?php

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    require_once __DIR__ . '/app/model/DocumentoPublicoSchemaHelper.php';

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        throw new InvalidArgumentException('Anexo nao informado.');
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

    DocumentoPublicoSchemaHelper::ensureSchemaWithPdo($pdo);

    $stmt = $pdo->prepare("
        SELECT a.id, a.nome, a.arquivo, a.documento_publico_id
        FROM documento_publico_anexo a
        INNER JOIN documento_publico d ON d.id = a.documento_publico_id
        WHERE a.id = :id AND d.status = 'published'
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    $attachment = $stmt->fetch();

    if (!$attachment) {
        throw new RuntimeException('Anexo nao encontrado.');
    }

    $relativePath = ltrim(str_replace(['..\\', '../'], '', (string) $attachment['arquivo']), '/\\');
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);

    if (!is_file($filePath)) {
        throw new RuntimeException('Arquivo do anexo nao localizado.');
    }

    $update = $pdo->prepare("UPDATE documento_publico SET downloads = downloads + 1 WHERE id = :id");
    $update->execute([':id' => (int) $attachment['documento_publico_id']]);

    $fileName = basename((string) $attachment['nome']);
    if ($fileName === '') {
        $fileName = basename((string) $attachment['arquivo']);
    }

    $extension = strtolower((string) pathinfo($filePath, PATHINFO_EXTENSION));
    $contentType = 'application/octet-stream';

    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $detectedType = finfo_file($finfo, $filePath);
            if (is_string($detectedType) && $detectedType !== '') {
                $contentType = $detectedType;
            }
            finfo_close($finfo);
        }
    }

    if ($contentType === 'application/octet-stream' && $extension === 'pdf') {
        $contentType = 'application/pdf';
    }

    $disposition = $extension === 'pdf' ? 'inline' : 'attachment';

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: ' . $disposition . '; filename="' . addslashes($fileName) . '"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
} catch (Throwable $e) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo $e->getMessage();
}
