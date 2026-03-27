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

    $baseDateExpr = "COALESCE(c.dt_pagamento, c.dt_emissao, c.dt_vencimento)";

    $rangeStmt = $pdo->query("
        SELECT
            MIN($baseDateExpr) AS min_date,
            MAX($baseDateExpr) AS max_date
        FROM conta c
        WHERE c.tipo_conta_id = 1
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

    $sourceStmt = $pdo->query("
        SELECT DISTINCT
            cat.id,
            cat.nome
        FROM conta c
        LEFT JOIN categoria cat ON cat.id = c.categoria_id
        WHERE c.tipo_conta_id = 1
        ORDER BY cat.nome ASC
    ");

    $sources = [];
    foreach ($sourceStmt->fetchAll() as $source) {
        if (empty($source['id']) && empty($source['nome'])) {
            continue;
        }

        $sources[] = [
            'id' => $source['id'],
            'name' => $source['nome'] ?: 'Sem categoria',
        ];
    }

    $sourceId = $_GET['source_id'] ?? '';
    $sourceId = is_scalar($sourceId) ? trim((string) $sourceId) : '';

    $params = [
        ':start_date' => $startDate,
        ':end_date' => $endDate,
    ];

    $sourceFilterSql = '';
    if ($sourceId !== '' && ctype_digit($sourceId)) {
        $sourceFilterSql = ' AND c.categoria_id = :source_id ';
        $params[':source_id'] = (int) $sourceId;
    } else {
        $sourceId = '';
    }

    $query = "
        SELECT
            COALESCE(cat.nome, 'Sem categoria') AS source_name,
            DATE_FORMAT($baseDateExpr, '%c/%Y') AS month_year,
            YEAR($baseDateExpr) AS year_value,
            MONTH($baseDateExpr) AS month_value,
            SUM(COALESCE(c.valor, 0)) AS total_value
        FROM conta c
        LEFT JOIN categoria cat ON cat.id = c.categoria_id
        WHERE c.tipo_conta_id = 1
          AND $baseDateExpr BETWEEN :start_date AND :end_date
          $sourceFilterSql
        GROUP BY source_name, year_value, month_value
        ORDER BY year_value DESC, month_value DESC, source_name ASC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $rows = [];
    $totalValue = 0.0;

    foreach ($stmt->fetchAll() as $row) {
        $value = (float) $row['total_value'];
        $totalValue += $value;

        $rows[] = [
            'source_name' => $row['source_name'],
            'month_year' => $row['month_year'],
            'total_value' => $value,
        ];
    }

    echo json_encode([
        'filters' => [
            'min_date' => $minDate,
            'max_date' => $maxDate,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'source_id' => $sourceId,
            'sources' => $sources,
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
