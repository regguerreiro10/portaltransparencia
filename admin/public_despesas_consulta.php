<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
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

    $baseDateExpr = 'COALESCE(c.dt_pagamento, c.dt_emissao, c.dt_vencimento)';
    $orgaoExpr = "COALESCE(NULLIF(TRIM(sd.nome), ''), NULLIF(TRIM(su.name), ''), NULLIF(TRIM(se.nome), ''), 'Nao informado')";
    $supplierExpr = "COALESCE(NULLIF(TRIM(p.nome), ''), 'Nao informado')";
    $documentExpr = "COALESCE(NULLIF(TRIM(p.documento), ''), 'Nao informado')";
    $categoryExpr = "COALESCE(NULLIF(TRIM(cat.nome), ''), 'Sem categoria')";

    $rangeStmt = $pdo->query("
        SELECT
            MIN($baseDateExpr) AS min_date,
            MAX($baseDateExpr) AS max_date
        FROM conta c
        WHERE c.tipo_conta_id = 2
          AND $baseDateExpr IS NOT NULL
    ");
    $range = $rangeStmt->fetch() ?: [];

    $maxDate = $range['max_date'] ?? null;
    $minDate = $range['min_date'] ?? null;

    $defaultEndDate = $maxDate ?: date('Y-m-d');
    $defaultStartDate = $maxDate
        ? date('Y-m-01', strtotime(date('Y-m-01', strtotime($maxDate)) . ' -2 months'))
        : date('Y-m-01');

    $startDate = $_GET['start_date'] ?? $defaultStartDate;
    $endDate = $_GET['end_date'] ?? $defaultEndDate;

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $startDate)) {
        $startDate = $defaultStartDate;
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $endDate)) {
        $endDate = $defaultEndDate;
    }

    if ($startDate > $endDate) {
        [$startDate, $endDate] = [$endDate, $startDate];
    }

    $orgaosStmt = $pdo->query("
        SELECT DISTINCT $orgaoExpr AS nome
        FROM conta c
        LEFT JOIN system_departamento sd ON sd.id = c.system_departamento_id
        LEFT JOIN system_unit su ON su.id = c.system_unit_id
        LEFT JOIN system_entidade se ON se.id = c.system_entidade_id
        WHERE c.tipo_conta_id = 2
        ORDER BY nome ASC
    ");

    $orgaos = [];
    foreach ($orgaosStmt->fetchAll() as $orgao) {
        if (!empty($orgao['nome'])) {
            $orgaos[] = $orgao['nome'];
        }
    }

    $categoriesStmt = $pdo->query("
        SELECT DISTINCT $categoryExpr AS nome
        FROM conta c
        LEFT JOIN categoria cat ON cat.id = c.categoria_id
        WHERE c.tipo_conta_id = 2
        ORDER BY nome ASC
    ");

    $categories = [];
    foreach ($categoriesStmt->fetchAll() as $category) {
        if (!empty($category['nome'])) {
            $categories[] = $category['nome'];
        }
    }

    $orgao = is_scalar($_GET['orgao'] ?? null) ? trim((string) $_GET['orgao']) : '';
    $supplier = is_scalar($_GET['supplier'] ?? null) ? trim((string) $_GET['supplier']) : '';
    $document = is_scalar($_GET['document'] ?? null) ? trim((string) $_GET['document']) : '';
    $category = is_scalar($_GET['category'] ?? null) ? trim((string) $_GET['category']) : '';

    $params = [
        ':start_date' => $startDate,
        ':end_date' => $endDate,
    ];

    $filtersSql = '';

    if ($orgao !== '') {
        $filtersSql .= " AND $orgaoExpr = :orgao ";
        $params[':orgao'] = $orgao;
    }

    if ($supplier !== '') {
        $filtersSql .= " AND $supplierExpr LIKE :supplier ";
        $params[':supplier'] = '%' . $supplier . '%';
    }

    if ($document !== '') {
        $filtersSql .= " AND $documentExpr LIKE :document ";
        $params[':document'] = '%' . $document . '%';
    }

    if ($category !== '') {
        $filtersSql .= " AND $categoryExpr = :category ";
        $params[':category'] = $category;
    }

    $query = "
        SELECT
            $orgaoExpr AS orgao_name,
            $supplierExpr AS supplier_name,
            $documentExpr AS supplier_document,
            $categoryExpr AS category_name,
            $baseDateExpr AS payment_date,
            COALESCE(c.valor, 0) AS total_value
        FROM conta c
        LEFT JOIN pessoa p ON p.id = c.pessoa_id
        LEFT JOIN categoria cat ON cat.id = c.categoria_id
        LEFT JOIN system_departamento sd ON sd.id = c.system_departamento_id
        LEFT JOIN system_unit su ON su.id = c.system_unit_id
        LEFT JOIN system_entidade se ON se.id = c.system_entidade_id
        WHERE c.tipo_conta_id = 2
          AND $baseDateExpr BETWEEN :start_date AND :end_date
          $filtersSql
        ORDER BY $baseDateExpr DESC, supplier_name ASC
        LIMIT 200
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $rows = [];
    $totalValue = 0.0;

    foreach ($stmt->fetchAll() as $row) {
        $value = (float) $row['total_value'];
        $totalValue += $value;

        $rows[] = [
            'orgao_name' => $row['orgao_name'],
            'supplier_name' => $row['supplier_name'],
            'supplier_document' => $row['supplier_document'],
            'category_name' => $row['category_name'],
            'payment_date' => $row['payment_date'],
            'total_value' => $value,
        ];
    }

    echo json_encode([
        'filters' => [
            'min_date' => $minDate,
            'max_date' => $maxDate,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'orgao' => $orgao,
            'supplier' => $supplier,
            'document' => $document,
            'category' => $category,
            'orgaos' => $orgaos,
            'categories' => $categories,
        ],
        'summary' => [
            'records' => count($rows),
            'total_value' => round($totalValue, 2),
        ],
        'rows' => $rows,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
