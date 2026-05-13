<?php

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    require_once __DIR__ . '/app/model/FinanceiroPublicoSchemaHelper.php';

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        throw new InvalidArgumentException('Arquivo nao informado.');
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

    FinanceiroPublicoSchemaHelper::ensureSchemaWithPdo($pdo);

    $stmt = $pdo->prepare("
        SELECT
            a.id,
            a.nome_arquivo,
            a.caminho_arquivo,
            a.link_externo,
            a.tipo,
            a.extensao,
            s.visivel
        FROM financeiro_arquivo a
        LEFT JOIN financeiro_subcategoria s ON s.id = a.subcategoria_id
        WHERE a.id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    $attachment = $stmt->fetch();

    if (!$attachment) {
        throw new RuntimeException('Arquivo nao encontrado.');
    }

    if (($attachment['tipo'] ?? '') === 'link' && !empty($attachment['link_externo'])) {
        header('Location: ' . $attachment['link_externo']);
        exit;
    }

    $relativePath = ltrim(str_replace(['..\\', '../'], '', (string) $attachment['caminho_arquivo']), '/\\');
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);

    if (!is_file($filePath)) {
        throw new RuntimeException('Arquivo financeiro nao localizado.');
    }

    $fileName = basename((string) $attachment['nome_arquivo']);
    if ($fileName === '') {
        $fileName = basename((string) $attachment['caminho_arquivo']);
    }

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

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment; filename="' . addslashes($fileName) . '"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
} catch (Throwable $e) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo $e->getMessage();
}
