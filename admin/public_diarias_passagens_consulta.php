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
    $serverExpr = "COALESCE(NULLIF(TRIM(p.nome), ''), 'Nao informado')";

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

    $orgao = is_scalar($_GET['orgao'] ?? null) ? trim((string) $_GET['orgao']) : '';
    $server = is_scalar($_GET['server_name'] ?? null) ? trim((string) $_GET['server_name']) : '';

    $params = [
        ':start_date' => $startDate,
        ':end_date' => $endDate,
    ];

    $filtersSql = '';

    if ($orgao !== '') {
        $filtersSql .= " AND $orgaoExpr = :orgao ";
        $params[':orgao'] = $orgao;
    }

    if ($server !== '') {
        $filtersSql .= " AND $serverExpr LIKE :server_name ";
        $params[':server_name'] = '%' . $server . '%';
    }

    $query = "
        SELECT
            $orgaoExpr AS orgao_name,
            $serverExpr AS server_name,
            $baseDateExpr AS payment_date,
            COALESCE(c.valor, 0) AS total_value
        FROM conta c
        LEFT JOIN pessoa p ON p.id = c.pessoa_id
        LEFT JOIN system_departamento sd ON sd.id = c.system_departamento_id
        LEFT JOIN system_unit su ON su.id = c.system_unit_id
        LEFT JOIN system_entidade se ON se.id = c.system_entidade_id
        WHERE c.tipo_conta_id = 2
          AND $baseDateExpr BETWEEN :start_date AND :end_date
          $filtersSql
        ORDER BY $baseDateExpr DESC, server_name ASC
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
            'server_name' => $row['server_name'],
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
            'server_name' => $server,
            'orgaos' => $orgaos,
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
