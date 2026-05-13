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

    $periodSql = "
        SELECT DISTINCT DATE_FORMAT($baseDateExpr, '%Y-%m') AS period
        FROM conta c
        WHERE c.tipo_conta_id = :tipo_conta
          AND $baseDateExpr IS NOT NULL
        ORDER BY period DESC
    ";

    $periodStmt = $pdo->prepare($periodSql);
    $periodStmt->execute([':tipo_conta' => 2]);
    $periods = array_values(array_filter(array_column($periodStmt->fetchAll(), 'period')));

    $requestedPeriod = $_GET['period'] ?? null;
    $period = (is_string($requestedPeriod) && preg_match('/^\d{4}-\d{2}$/', $requestedPeriod))
        ? $requestedPeriod
        : '';

    $where = [
        'c.tipo_conta_id = :tipo_conta',
    ];
    $params = [
        ':tipo_conta' => 2,
    ];

    if ($period !== '') {
        $where[] = "DATE_FORMAT($baseDateExpr, '%Y-%m') = :period";
        $params[':period'] = $period;
    }

    $dataSql = "
        SELECT
            c.id,
            COALESCE(NULLIF(TRIM(c.obs), ''), CONCAT('Conta #', c.id)) AS conta_nome,
            COALESCE(cat.nome, 'Sem categoria') AS categoria,
            COALESCE(p.nome, 'Sem fornecedor') AS fornecedor,
            COALESCE(c.valor, 0) AS total,
            c.dt_vencimento
        FROM conta c
        LEFT JOIN categoria cat ON cat.id = c.categoria_id
        LEFT JOIN pessoa p ON p.id = c.pessoa_id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY total DESC, categoria ASC, conta_nome ASC
    ";

    $dataStmt = $pdo->prepare($dataSql);
    $dataStmt->execute($params);

    $items = $dataStmt->fetchAll();
    $totalGeral = array_sum(array_map(static fn ($item) => (float) $item['total'], $items));
    $maxValue = 0.0;

    foreach ($items as $item) {
        $maxValue = max($maxValue, (float) $item['total']);
    }

    $formattedItems = array_map(
        static function (array $item) use ($maxValue) {
            $value = (float) $item['total'];
            $vencimento = null;
            if (!empty($item['dt_vencimento'])) {
                $timestamp = strtotime((string) $item['dt_vencimento']);
                if ($timestamp !== false) {
                    $vencimento = date('d/m/Y', $timestamp);
                }
            }

            return [
                'id' => (int) $item['id'],
                'label' => $item['conta_nome'],
                'category' => $item['categoria'],
                'supplier' => $item['fornecedor'],
                'due_date' => $vencimento,
                'value' => $value,
                'percentage' => $maxValue > 0 ? round(($value / $maxValue) * 100, 2) : 0,
            ];
        },
        $items
    );

    $formattedPeriods = array_map(
        static function (string $periodValue) {
            [$year, $month] = explode('-', $periodValue);

            return [
                'value' => $periodValue,
                'label' => sprintf('%s/%s', $month, $year),
            ];
        },
        $periods
    );

    echo json_encode([
        'period' => $period,
        'periods' => $formattedPeriods,
        'total' => round($totalGeral, 2),
        'items' => $formattedItems,
        'links' => [
            'list' => 'admin/index.php?class=ContaPagarList',
            'create' => 'admin/index.php?class=ContaPagarForm',
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
