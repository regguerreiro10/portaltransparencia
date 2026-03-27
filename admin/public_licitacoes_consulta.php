<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    $items = [
        [
            'period' => '2026-03',
            'status' => 'Em andamento',
            'keyword' => 'manutencao predial',
            'notice_number' => 'PE-014/2026',
            'origin_process' => 'PROC-1201/2026',
            'manager' => 'Secretaria Municipal de Administracao',
            'modality' => 'Pregao Eletronico',
            'supplier_document' => '12.345.678/0001-90',
            'supplier_name' => 'Construtora Horizonte Ltda',
            'estimated_value' => 185400.50,
        ],
        [
            'period' => '2026-02',
            'status' => 'Homologada',
            'keyword' => 'locacao de veiculos',
            'notice_number' => 'PE-009/2026',
            'origin_process' => 'PROC-1008/2026',
            'manager' => 'Secretaria Municipal de Saude',
            'modality' => 'Pregao Eletronico',
            'supplier_document' => '98.765.432/0001-10',
            'supplier_name' => 'Mobiliza Frotas SA',
            'estimated_value' => 92000.00,
        ],
        [
            'period' => '2026-01',
            'status' => 'Revogada',
            'keyword' => 'material escolar',
            'notice_number' => 'CC-003/2026',
            'origin_process' => 'PROC-0770/2026',
            'manager' => 'Secretaria Municipal de Educacao',
            'modality' => 'Carta Convite',
            'supplier_document' => '44.222.111/0001-56',
            'supplier_name' => 'Papelaria Central ME',
            'estimated_value' => 38450.90,
        ],
        [
            'period' => '2025-12',
            'status' => 'Em andamento',
            'keyword' => 'software de gestao',
            'notice_number' => 'TP-021/2025',
            'origin_process' => 'PROC-4520/2025',
            'manager' => 'Secretaria Municipal de Financas',
            'modality' => 'Tomada de Precos',
            'supplier_document' => '65.432.109/0001-44',
            'supplier_name' => 'Inova Sistemas Publicos',
            'estimated_value' => 210000.00,
        ],
    ];

    $periods = [];
    $statuses = [];
    $managers = [];
    $modalities = [];

    foreach ($items as $item) {
        $periods[$item['period']] = [
            'value' => $item['period'],
            'label' => substr($item['period'], 5, 2) . '/' . substr($item['period'], 0, 4),
        ];
        $statuses[$item['status']] = $item['status'];
        $managers[$item['manager']] = $item['manager'];
        $modalities[$item['modality']] = $item['modality'];
    }

    krsort($periods);
    sort($statuses);
    sort($managers);
    sort($modalities);

    $filters = [
        'period' => is_scalar($_GET['period'] ?? null) ? trim((string) $_GET['period']) : '',
        'status' => is_scalar($_GET['status'] ?? null) ? trim((string) $_GET['status']) : '',
        'keyword' => is_scalar($_GET['keyword'] ?? null) ? trim((string) $_GET['keyword']) : '',
        'notice_number' => is_scalar($_GET['notice_number'] ?? null) ? trim((string) $_GET['notice_number']) : '',
        'origin_process' => is_scalar($_GET['origin_process'] ?? null) ? trim((string) $_GET['origin_process']) : '',
        'manager' => is_scalar($_GET['manager'] ?? null) ? trim((string) $_GET['manager']) : '',
        'modality' => is_scalar($_GET['modality'] ?? null) ? trim((string) $_GET['modality']) : '',
        'supplier_document' => is_scalar($_GET['supplier_document'] ?? null) ? trim((string) $_GET['supplier_document']) : '',
        'periods' => array_values($periods),
        'statuses' => array_values($statuses),
        'managers' => array_values($managers),
        'modalities' => array_values($modalities),
    ];

    $filteredItems = array_values(array_filter($items, static function (array $item) use ($filters) {
        if ($filters['period'] !== '' && $item['period'] !== $filters['period']) {
            return false;
        }

        if ($filters['status'] !== '' && $item['status'] !== $filters['status']) {
            return false;
        }

        if ($filters['keyword'] !== '' && stripos($item['keyword'], $filters['keyword']) === false) {
            return false;
        }

        if ($filters['notice_number'] !== '' && stripos($item['notice_number'], $filters['notice_number']) === false) {
            return false;
        }

        if ($filters['origin_process'] !== '' && stripos($item['origin_process'], $filters['origin_process']) === false) {
            return false;
        }

        if ($filters['manager'] !== '' && $item['manager'] !== $filters['manager']) {
            return false;
        }

        if ($filters['modality'] !== '' && $item['modality'] !== $filters['modality']) {
            return false;
        }

        if ($filters['supplier_document'] !== '' && stripos($item['supplier_document'], $filters['supplier_document']) === false) {
            return false;
        }

        return true;
    }));

    $totalValue = array_sum(array_map(static fn (array $item) => (float) $item['estimated_value'], $filteredItems));

    echo json_encode([
        'filters' => $filters,
        'summary' => [
            'records' => count($filteredItems),
            'total_value' => round($totalValue, 2),
        ],
        'rows' => $filteredItems,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
