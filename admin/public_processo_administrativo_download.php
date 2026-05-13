<?php

try {
    require_once __DIR__ . '/app/model/ProcessoAdministrativoSchemaHelper.php';

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

    ProcessoAdministrativoSchemaHelper::ensureSchemaWithPdo($pdo);

    $stmt = $pdo->prepare("
        SELECT a.id, a.nome, a.arquivo, p.nivel_sigilo
        FROM processo_administrativo_anexo a
        INNER JOIN processo_administrativo p ON p.id = a.processo_administrativo_id
        WHERE a.id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    if (!$row) {
        throw new RuntimeException('Anexo nao localizado.');
    }

    if (($row['nivel_sigilo'] ?? '') === 'Sigiloso') {
        throw new RuntimeException('Anexos de processos sigilosos so podem ser acessados no admin do sistema.');
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
