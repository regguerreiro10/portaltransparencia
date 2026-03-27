<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    $items = [
        [
            'start_date' => '2026-03-10',
            'contract_type' => 'Prestacao de Servicos',
            'orgao' => 'Secretaria Municipal de Administracao',
            'supplier' => 'Construtora Horizonte Ltda',
            'supplier_document' => '12.345.678/0001-90',
            'object' => 'Manutencao predial preventiva',
            'registry_number' => 'ARP-1024',
            'contract_number' => 'CT-018/2026',
            'value' => 185400.50,
        ],
        [
            'start_date' => '2026-02-22',
            'contract_type' => 'Fornecimento',
            'orgao' => 'Secretaria Municipal de Educacao',
            'supplier' => 'Papelaria Central ME',
            'supplier_document' => '44.222.111/0001-56',
            'object' => 'Aquisicao de material escolar',
            'registry_number' => 'ARP-0981',
            'contract_number' => 'CT-011/2026',
            'value' => 68450.20,
        ],
        [
            'start_date' => '2026-01-14',
            'contract_type' => 'Locacao',
            'orgao' => 'Secretaria Municipal de Saude',
            'supplier' => 'Mobiliza Frotas SA',
            'supplier_document' => '98.765.432/0001-10',
            'object' => 'Locacao de veiculos utilitarios',
            'registry_number' => 'ARP-0940',
            'contract_number' => 'CT-004/2026',
            'value' => 92000.00,
        ],
        [
            'start_date' => '2025-12-03',
            'contract_type' => 'Licenciamento',
            'orgao' => 'Secretaria Municipal de Financas',
            'supplier' => 'Inova Sistemas Publicos',
            'supplier_document' => '65.432.109/0001-44',
            'object' => 'Licenciamento de software de gestao',
            'registry_number' => 'ARP-0877',
            'contract_number' => 'CT-145/2025',
            'value' => 210000.00,
        ],
    ];

    $types = [];
    $orgaos = [];
    $dates = array_column($items, 'start_date');
    sort($dates);

    foreach ($items as $item) {
        $types[$item['contract_type']] = $item['contract_type'];
        $orgaos[$item['orgao']] = $item['orgao'];
    }

    sort($types);
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
        'contract_type' => is_scalar($_GET['contract_type'] ?? null) ? trim((string) $_GET['contract_type']) : '',
        'orgao' => is_scalar($_GET['orgao'] ?? null) ? trim((string) $_GET['orgao']) : '',
        'supplier' => is_scalar($_GET['supplier'] ?? null) ? trim((string) $_GET['supplier']) : '',
        'supplier_document' => is_scalar($_GET['supplier_document'] ?? null) ? trim((string) $_GET['supplier_document']) : '',
        'object' => is_scalar($_GET['object'] ?? null) ? trim((string) $_GET['object']) : '',
        'registry_number' => is_scalar($_GET['registry_number'] ?? null) ? trim((string) $_GET['registry_number']) : '',
        'types' => array_values($types),
        'orgaos' => array_values($orgaos),
    ];

    $filteredItems = array_values(array_filter($items, static function (array $item) use ($filters) {
        if ($item['start_date'] < $filters['start_date'] || $item['start_date'] > $filters['end_date']) {
            return false;
        }

        if ($filters['contract_type'] !== '' && $item['contract_type'] !== $filters['contract_type']) {
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
