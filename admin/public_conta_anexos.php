<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    $config = require __DIR__ . '/app/config/minierp.php';

    if (($config['type'] ?? '') !== 'mysql') {
        throw new RuntimeException('Tipo de banco nao suportado para este endpoint.');
    }

    $contaId = isset($_GET['conta_id']) ? (int) $_GET['conta_id'] : 0;
    if ($contaId <= 0) {
        throw new RuntimeException('Conta invalida.');
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

    $stmt = $pdo->prepare("
        SELECT
            ca.id,
            ca.descricao,
            ca.arquivo,
            ta.nome AS tipo_anexo
        FROM conta_anexo ca
        LEFT JOIN tipo_anexo ta ON ta.id = ca.tipo_anexo_id
        WHERE ca.conta_id = :conta_id
        ORDER BY ca.id DESC
    ");
    $stmt->execute([':conta_id' => $contaId]);
    $rows = $stmt->fetchAll();

    $items = [];

    foreach ($rows as $row) {
        $rawFile = (string) ($row['arquivo'] ?? '');
        $fileName = $rawFile;

        if (strpos($rawFile, '%7B') !== false) {
            $decoded = json_decode(urldecode($rawFile));
            if (!empty($decoded->fileName)) {
                $fileName = $decoded->fileName;
            }
        }

        if ($fileName === '') {
            continue;
        }

        $items[] = [
            'id' => (int) $row['id'],
            'type' => $row['tipo_anexo'] ?: 'Arquivo',
            'description' => $row['descricao'] ?: '',
            'file_name' => basename($fileName),
            'url' => 'admin/download.php?file=' . rawurlencode($fileName),
        ];
    }

    echo json_encode([
        'conta_id' => $contaId,
        'items' => $items,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
