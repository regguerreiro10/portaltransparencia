<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    $items = [
        [
            'start_date' => '2026-03-18',
            'contract_type' => 'Obras',
            'orgao' => 'Secretaria Municipal de Infraestrutura',
            'supplier' => 'Engemax Obras Ltda',
            'supplier_document' => '22.333.444/0001-55',
            'object' => 'Pavimentacao de vias urbanas',
            'registry_number' => 'OBR-2201',
            'contract_number' => 'CO-021/2026',
            'value' => 1245000.00,
        ],
        [
            'start_date' => '2026-02-09',
            'contract_type' => 'Obras',
            'orgao' => 'Secretaria Municipal de Educacao',
            'supplier' => 'Construtora Horizonte Ltda',
            'supplier_document' => '12.345.678/0001-90',
            'object' => 'Reforma de unidade escolar',
            'registry_number' => 'OBR-2140',
            'contract_number' => 'CO-013/2026',
            'value' => 368400.75,
        ],
        [
            'start_date' => '2026-01-27',
            'contract_type' => 'Obras',
            'orgao' => 'Secretaria Municipal de Saude',
            'supplier' => 'Vital Engenharia e Construcoes',
            'supplier_document' => '56.777.888/0001-11',
            'object' => 'Adequacao estrutural de unidade basica',
            'registry_number' => 'OBR-2098',
            'contract_number' => 'CO-007/2026',
            'value' => 512900.00,
        ],
        [
            'start_date' => '2025-12-11',
            'contract_type' => 'Obras',
            'orgao' => 'Secretaria Municipal de Esportes',
            'supplier' => 'Arena Construcoes Publicas',
            'supplier_document' => '89.654.321/0001-09',
            'object' => 'Revitalizacao de complexo esportivo',
            'registry_number' => 'OBR-1985',
            'contract_number' => 'CO-142/2025',
            'value' => 874230.40,
        ],
    ];

    $orgaos = [];
    $dates = array_column($items, 'start_date');
    sort($dates);

    foreach ($items as $item) {
        $orgaos[$item['orgao']] = $item['orgao'];
    }

    sort($orgaos);

    $minDate = $dates[0] ?? date('Y-m-01');
    $maxDate = $dates[count($dates) - 1] ?? date('Y-m-d');
    $defaultEndDate = $maxDate;
    $defaultStartDate = date('Y-m-01', strtotime(date('Y-m-01', strtotime($defaultEndDate)) . ' -2 months'));

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

    $filters = [
        'min_date' => $minDate,
        'max_date' => $maxDate,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'contract_type' => 'Obras',
        'orgao' => is_scalar($_GET['orgao'] ?? null) ? trim((string) $_GET['orgao']) : '',
        'supplier' => is_scalar($_GET['supplier'] ?? null) ? trim((string) $_GET['supplier']) : '',
        'supplier_document' => is_scalar($_GET['supplier_document'] ?? null) ? trim((string) $_GET['supplier_document']) : '',
        'object' => is_scalar($_GET['object'] ?? null) ? trim((string) $_GET['object']) : '',
        'registry_number' => is_scalar($_GET['registry_number'] ?? null) ? trim((string) $_GET['registry_number']) : '',
        'orgaos' => array_values($orgaos),
    ];

    $filteredItems = array_values(array_filter($items, static function (array $item) use ($filters) {
        if ($item['start_date'] < $filters['start_date'] || $item['start_date'] > $filters['end_date']) {
            return false;
        }

        if ($filters['orgao'] !== '' && $item['orgao'] !== $filters['orgao']) {
            return false;
        }

        if ($filters['supplier'] !== '' && stripos($item['supplier'], $filters['supplier']) === false) {
            return false;
        }

        if ($filters['supplier_document'] !== '' && stripos($item['supplier_document'], $filters['supplier_document']) === false) {
            return false;
        }

        if ($filters['object'] !== '' && stripos($item['object'], $filters['object']) === false) {
            return false;
        }

        if ($filters['registry_number'] !== '' && stripos($item['registry_number'], $filters['registry_number']) === false) {
            return false;
        }

        return true;
    }));

    $totalValue = array_sum(array_map(static fn (array $item) => (float) $item['value'], $filteredItems));

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
