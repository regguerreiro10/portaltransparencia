<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    require_once __DIR__ . '/app/model/FinanceiroPublicoSchemaHelper.php';

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

    $filters = [
        'cadastro' => is_scalar($_GET['cadastro'] ?? null) ? trim((string) $_GET['cadastro']) : '',
        'categoria' => is_scalar($_GET['categoria'] ?? null) ? trim((string) $_GET['categoria']) : '',
        'ano' => is_scalar($_GET['ano'] ?? null) ? trim((string) $_GET['ano']) : '',
        'subcategoria' => is_scalar($_GET['subcategoria'] ?? null) ? trim((string) $_GET['subcategoria']) : '',
        'arquivo' => is_scalar($_GET['arquivo'] ?? null) ? trim((string) $_GET['arquivo']) : '',
        'status' => is_scalar($_GET['status'] ?? null) ? trim((string) $_GET['status']) : '',
        'upload' => is_scalar($_GET['upload'] ?? null) ? trim((string) $_GET['upload']) : '',
        'visibilidade' => is_scalar($_GET['visibilidade'] ?? null) ? trim((string) $_GET['visibilidade']) : '',
        'date' => is_scalar($_GET['date'] ?? null) ? trim((string) $_GET['date']) : '',
        'sort' => is_scalar($_GET['sort'] ?? null) ? trim((string) $_GET['sort']) : 'recent',
    ];

    $where = ['1 = 1'];
    $params = [];

    if ($filters['cadastro'] !== '') {
        $where[] = 'f.nome LIKE :cadastro';
        $params[':cadastro'] = '%' . $filters['cadastro'] . '%';
    }
    if ($filters['categoria'] !== '') {
        $where[] = 'c.nome = :categoria';
        $params[':categoria'] = $filters['categoria'];
    }
    if ($filters['ano'] !== '') {
        $where[] = 's.ano = :ano';
        $params[':ano'] = $filters['ano'];
    }
    if ($filters['subcategoria'] !== '') {
        $where[] = 's.nome LIKE :subcategoria';
        $params[':subcategoria'] = '%' . $filters['subcategoria'] . '%';
    }
    if ($filters['arquivo'] !== '') {
        $where[] = 'EXISTS (
            SELECT 1
            FROM financeiro_arquivo fa2
            WHERE (fa2.subcategoria_id = s.id OR fa2.financeiro_cadastro_id = f.id)
              AND fa2.nome_arquivo LIKE :arquivo
        )';
        $params[':arquivo'] = '%' . $filters['arquivo'] . '%';
    }
    if ($filters['upload'] === 'com-arquivo') {
        $where[] = 'EXISTS (
            SELECT 1
            FROM financeiro_arquivo fa3
            WHERE (fa3.subcategoria_id = s.id OR fa3.financeiro_cadastro_id = f.id)
        )';
    }
    if ($filters['upload'] === 'sem-arquivo') {
        $where[] = 'NOT EXISTS (
            SELECT 1
            FROM financeiro_arquivo fa4
            WHERE (fa4.subcategoria_id = s.id OR fa4.financeiro_cadastro_id = f.id)
        )';
    }

    $visibilityFilter = $filters['visibilidade'] !== '' ? $filters['visibilidade'] : $filters['status'];
    if (in_array($visibilityFilter, ['published', 'ativa', 'ativo', 'visivel', 'publicado'], true)) {
        $where[] = "s.visivel = 'Y'";
    }
    if (in_array($visibilityFilter, ['hidden', 'inativa', 'inativo', 'oculto'], true)) {
        $where[] = "s.visivel = 'N'";
    }

    if ($filters['date'] !== '') {
        $where[] = 'DATE(f.updated_at) = :date';
        $params[':date'] = $filters['date'];
    }

    $orderBy = 's.ano DESC, f.nome ASC, s.nome ASC';
    if ($filters['sort'] === 'category') {
        $orderBy = 'c.nome ASC, s.ano DESC, f.nome ASC';
    } elseif ($filters['sort'] === 'year') {
        $orderBy = 's.ano DESC, c.nome ASC, s.nome ASC';
    }

    $sql = "
        SELECT
            s.id,
            f.id AS financeiro_id,
            f.nome AS financeiro_nome,
            f.descricao AS financeiro_descricao,
            c.nome AS categoria_nome,
            s.nome AS subcategoria_nome,
            s.ano,
            s.visivel
        FROM financeiro_subcategoria s
        INNER JOIN financeiro_cadastro f ON f.id = s.financeiro_cadastro_id
        INNER JOIN financeiro_categoria c ON c.id = s.categoria_id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY {$orderBy}
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $attachmentStmt = $pdo->prepare("
        SELECT id, nome_arquivo, caminho_arquivo, link_externo, tipo, extensao
        FROM financeiro_arquivo
        WHERE subcategoria_id = :subcategoria_id OR (financeiro_cadastro_id = :financeiro_id AND subcategoria_id IS NULL)
        ORDER BY id DESC
    ");

    foreach ($rows as &$row) {
        $attachmentStmt->execute([
            ':subcategoria_id' => $row['id'],
            ':financeiro_id' => $row['financeiro_id'],
        ]);

        $attachments = $attachmentStmt->fetchAll();
        $row['attachments'] = array_map(static function (array $attachment) {
            $isLink = ($attachment['tipo'] ?? '') === 'link' && !empty($attachment['link_externo']);

            return [
                'id' => (int) $attachment['id'],
                'name' => $attachment['nome_arquivo'],
                'type' => $attachment['tipo'],
                'url' => $isLink
                    ? $attachment['link_externo']
                    : 'admin/public_financeiro_download.php?id=' . (int) $attachment['id'],
                'source_label' => $isLink ? 'Link externo' : strtoupper((string) ($attachment['extensao'] ?: 'arquivo')),
            ];
        }, $attachments);
    }
    unset($row);

    $categories = $pdo->query("SELECT nome FROM financeiro_categoria ORDER BY nome ASC")->fetchAll(PDO::FETCH_COLUMN);
    $years = $pdo->query("SELECT DISTINCT ano FROM financeiro_subcategoria ORDER BY ano DESC")->fetchAll(PDO::FETCH_COLUMN);

    $visibleCount = 0;
    $attachmentCount = 0;
    foreach ($rows as $row) {
        if (($row['visivel'] ?? 'N') === 'Y') {
            $visibleCount++;
        }
        $attachmentCount += count($row['attachments']);
    }

    echo json_encode([
        'filters' => [
            'cadastro' => $filters['cadastro'],
            'categoria' => $filters['categoria'],
            'ano' => $filters['ano'],
            'subcategoria' => $filters['subcategoria'],
            'arquivo' => $filters['arquivo'],
            'status' => $filters['status'],
            'upload' => $filters['upload'],
            'visibilidade' => $filters['visibilidade'],
            'date' => $filters['date'],
            'sort' => $filters['sort'],
            'categories' => array_values(array_filter($categories)),
            'years' => array_values(array_filter($years)),
        ],
        'summary' => [
            'records' => count($rows),
            'attachments' => $attachmentCount,
            'visible' => $visibleCount,
        ],
        'rows' => array_map(static function (array $row) {
            return [
                'id' => (int) $row['id'],
                'financeiro_id' => (int) $row['financeiro_id'],
                'name' => $row['financeiro_nome'],
                'description' => $row['financeiro_descricao'],
                'category' => $row['categoria_nome'],
                'subcategory' => $row['subcategoria_nome'],
                'year' => (int) $row['ano'],
                'visible' => ($row['visivel'] ?? 'N') === 'Y',
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
