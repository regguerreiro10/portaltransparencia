<?php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    require_once __DIR__ . '/app/model/ProcessoLegislativoSchemaHelper.php';

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

    ProcessoLegislativoSchemaHelper::ensureSchemaWithPdo($pdo);

    $filters = [
        'tipo_processo' => is_scalar($_GET['tipo_processo'] ?? null) ? trim((string) $_GET['tipo_processo']) : '',
        'numero_processo' => is_scalar($_GET['numero_processo'] ?? null) ? trim((string) $_GET['numero_processo']) : '',
        'ano' => is_scalar($_GET['ano'] ?? null) ? trim((string) $_GET['ano']) : '',
        'numero_protocolo' => is_scalar($_GET['numero_protocolo'] ?? null) ? trim((string) $_GET['numero_protocolo']) : '',
        'ementa' => is_scalar($_GET['ementa'] ?? null) ? trim((string) $_GET['ementa']) : '',
        'autor' => is_scalar($_GET['autor'] ?? null) ? trim((string) $_GET['autor']) : '',
        'situacao_status' => is_scalar($_GET['situacao_status'] ?? null) ? trim((string) $_GET['situacao_status']) : '',
        'departamento_gabinete' => is_scalar($_GET['departamento_gabinete'] ?? null) ? trim((string) $_GET['departamento_gabinete']) : '',
        'sessao_vinculada' => is_scalar($_GET['sessao_vinculada'] ?? null) ? trim((string) $_GET['sessao_vinculada']) : '',
        'data_tramitacao_inicial' => is_scalar($_GET['data_tramitacao_inicial'] ?? null) ? trim((string) $_GET['data_tramitacao_inicial']) : '',
        'data_tramitacao_final' => is_scalar($_GET['data_tramitacao_final'] ?? null) ? trim((string) $_GET['data_tramitacao_final']) : '',
        'sessao_apresentacao' => is_scalar($_GET['sessao_apresentacao'] ?? null) ? trim((string) $_GET['sessao_apresentacao']) : '',
        'sessao_apreciacao' => is_scalar($_GET['sessao_apreciacao'] ?? null) ? trim((string) $_GET['sessao_apreciacao']) : '',
        'data_sessao' => is_scalar($_GET['data_sessao'] ?? null) ? trim((string) $_GET['data_sessao']) : '',
        'status_sessao' => is_scalar($_GET['status_sessao'] ?? null) ? trim((string) $_GET['status_sessao']) : '',
        'assunto' => is_scalar($_GET['assunto'] ?? null) ? trim((string) $_GET['assunto']) : '',
        'texto' => is_scalar($_GET['texto'] ?? null) ? trim((string) $_GET['texto']) : '',
    ];

    $where = ['1 = 1'];
    $params = [];

    if ($filters['tipo_processo'] !== '') {
        $where[] = 'p.tipo_processo = :tipo_processo';
        $params[':tipo_processo'] = $filters['tipo_processo'];
    }
    if ($filters['numero_processo'] !== '') {
        $where[] = 'p.numero_processo LIKE :numero_processo';
        $params[':numero_processo'] = '%' . $filters['numero_processo'] . '%';
    }
    if ($filters['ano'] !== '') {
        $where[] = 'p.ano = :ano';
        $params[':ano'] = $filters['ano'];
    }
    if ($filters['numero_protocolo'] !== '') {
        $where[] = 'p.numero_protocolo LIKE :numero_protocolo';
        $params[':numero_protocolo'] = '%' . $filters['numero_protocolo'] . '%';
    }
    if ($filters['ementa'] !== '') {
        $where[] = 'p.ementa LIKE :ementa';
        $params[':ementa'] = '%' . $filters['ementa'] . '%';
    }
    if ($filters['autor'] !== '') {
        $where[] = '(p.autor_principal LIKE :autor OR p.coautores LIKE :autor)';
        $params[':autor'] = '%' . $filters['autor'] . '%';
    }
    if ($filters['situacao_status'] !== '') {
        $where[] = 'p.situacao_status = :situacao_status';
        $params[':situacao_status'] = $filters['situacao_status'];
    }
    if ($filters['departamento_gabinete'] !== '') {
        $where[] = 'p.departamento_gabinete = :departamento_gabinete';
        $params[':departamento_gabinete'] = $filters['departamento_gabinete'];
    }
    if ($filters['sessao_vinculada'] !== '') {
        $where[] = 'p.sessao_vinculada = :sessao_vinculada';
        $params[':sessao_vinculada'] = $filters['sessao_vinculada'];
    }
    if ($filters['data_tramitacao_inicial'] !== '') {
        $where[] = 'DATE(lt.data_tramitacao) >= :data_tramitacao_inicial';
        $params[':data_tramitacao_inicial'] = $filters['data_tramitacao_inicial'];
    }
    if ($filters['data_tramitacao_final'] !== '') {
        $where[] = 'DATE(lt.data_tramitacao) <= :data_tramitacao_final';
        $params[':data_tramitacao_final'] = $filters['data_tramitacao_final'];
    }
    if ($filters['sessao_apresentacao'] !== '') {
        $where[] = 'p.sessao_apresentacao LIKE :sessao_apresentacao';
        $params[':sessao_apresentacao'] = '%' . $filters['sessao_apresentacao'] . '%';
    }
    if ($filters['sessao_apreciacao'] !== '') {
        $where[] = 'p.sessao_apreciacao LIKE :sessao_apreciacao';
        $params[':sessao_apreciacao'] = '%' . $filters['sessao_apreciacao'] . '%';
    }
    if ($filters['data_sessao'] !== '') {
        $where[] = 'DATE(p.data_sessao) = :data_sessao';
        $params[':data_sessao'] = $filters['data_sessao'];
    }
    if ($filters['status_sessao'] !== '') {
        $where[] = 'p.status_sessao = :status_sessao';
        $params[':status_sessao'] = $filters['status_sessao'];
    }
    if ($filters['assunto'] !== '') {
        $where[] = '(p.assunto LIKE :assunto OR p.ementa LIKE :assunto)';
        $params[':assunto'] = '%' . $filters['assunto'] . '%';
    }
    if ($filters['texto'] !== '') {
        $where[] = '(p.ementa LIKE :texto OR p.assunto LIKE :texto OR p.despacho_texto LIKE :texto)';
        $params[':texto'] = '%' . $filters['texto'] . '%';
    }

    $sql = "
        SELECT
            p.*,
            COALESCE(a.total_anexos, 0) AS total_anexos,
            principal.id AS principal_anexo_id,
            principal.nome AS principal_anexo_nome,
            principal.arquivo AS principal_anexo_arquivo,
            lt.data_tramitacao AS ultima_data_tramitacao,
            lt.situacao AS ultima_situacao,
            lt.descricao_andamento AS ultimo_andamento,
            lt.remetente AS ultimo_remetente,
            lt.destinatario AS ultimo_destinatario
        FROM processo_legislativo p
        LEFT JOIN (
            SELECT processo_legislativo_id, COUNT(*) AS total_anexos
            FROM processo_legislativo_anexo
            GROUP BY processo_legislativo_id
        ) a ON a.processo_legislativo_id = p.id
        LEFT JOIN processo_legislativo_anexo principal
            ON principal.processo_legislativo_id = p.id
           AND principal.principal = 1
        LEFT JOIN (
            SELECT t1.*
            FROM processo_legislativo_tramitacao t1
            INNER JOIN (
                SELECT processo_legislativo_id, MAX(data_tramitacao) AS max_data
                FROM processo_legislativo_tramitacao
                GROUP BY processo_legislativo_id
            ) t2
                ON t2.processo_legislativo_id = t1.processo_legislativo_id
               AND t2.max_data = t1.data_tramitacao
        ) lt ON lt.processo_legislativo_id = p.id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY p.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $historyStmt = $pdo->prepare("
        SELECT data_tramitacao, situacao, descricao_andamento, remetente, destinatario, usuario_responsavel, observacao
        FROM processo_legislativo_tramitacao
        WHERE processo_legislativo_id = :processo_id
        ORDER BY data_tramitacao DESC, id DESC
    ");

    $attachmentStmt = $pdo->prepare("
        SELECT id, nome, principal
        FROM processo_legislativo_anexo
        WHERE processo_legislativo_id = :processo_id
        ORDER BY ordem ASC, id ASC
    ");

    foreach ($rows as &$row) {
        $historyStmt->execute([':processo_id' => $row['id']]);
        $historyRows = $historyStmt->fetchAll();
        $row['history'] = array_map(static function (array $entry) {
            return [
                'date' => $entry['data_tramitacao'],
                'situation' => $entry['situacao'],
                'movement' => $entry['descricao_andamento'],
                'sender' => $entry['remetente'] ?: '',
                'recipient' => $entry['destinatario'] ?: '',
                'responsible' => $entry['usuario_responsavel'] ?: '',
                'note' => $entry['observacao'] ?: '',
            ];
        }, $historyRows);

        $attachmentStmt->execute([':processo_id' => $row['id']]);
        $attachments = $attachmentStmt->fetchAll();
        $row['attachments'] = array_map(static function (array $attachment) {
            return [
                'id' => (int) $attachment['id'],
                'name' => $attachment['nome'],
                'is_main' => (int) $attachment['principal'] === 1,
                'download_url' => 'admin/public_processo_legislativo_download.php?id=' . (int) $attachment['id'],
            ];
        }, $attachments);
    }
    unset($row);

    $types = $pdo->query("SELECT DISTINCT tipo_processo FROM processo_legislativo WHERE tipo_processo IS NOT NULL AND tipo_processo <> '' ORDER BY tipo_processo ASC")->fetchAll(PDO::FETCH_COLUMN);
    $statuses = $pdo->query("SELECT DISTINCT situacao_status FROM processo_legislativo WHERE situacao_status IS NOT NULL AND situacao_status <> '' ORDER BY situacao_status ASC")->fetchAll(PDO::FETCH_COLUMN);
    $departments = $pdo->query("SELECT DISTINCT departamento_gabinete FROM processo_legislativo WHERE departamento_gabinete IS NOT NULL AND departamento_gabinete <> '' ORDER BY departamento_gabinete ASC")->fetchAll(PDO::FETCH_COLUMN);
    $sessions = $pdo->query("SELECT DISTINCT sessao_vinculada FROM processo_legislativo WHERE sessao_vinculada IS NOT NULL AND sessao_vinculada <> '' ORDER BY sessao_vinculada ASC")->fetchAll(PDO::FETCH_COLUMN);
    $sessionStatuses = $pdo->query("SELECT DISTINCT status_sessao FROM processo_legislativo WHERE status_sessao IS NOT NULL AND status_sessao <> '' ORDER BY status_sessao ASC")->fetchAll(PDO::FETCH_COLUMN);

    $summary = [
        'records' => count($rows),
        'in_progress' => count(array_filter($rows, static function (array $row) {
            return in_array($row['situacao_status'], ['Protocolado', 'Em analise', 'Em pauta'], true);
        })),
        'in_session' => count(array_filter($rows, static function (array $row) {
            return !empty($row['sessao_vinculada']);
        })),
        'attachments' => array_sum(array_map(static fn (array $row) => (int) $row['total_anexos'], $rows)),
    ];

    echo json_encode([
        'filters' => [
            'tipo_processo' => $filters['tipo_processo'],
            'numero_processo' => $filters['numero_processo'],
            'ano' => $filters['ano'],
            'numero_protocolo' => $filters['numero_protocolo'],
            'ementa' => $filters['ementa'],
            'autor' => $filters['autor'],
            'situacao_status' => $filters['situacao_status'],
            'departamento_gabinete' => $filters['departamento_gabinete'],
            'sessao_vinculada' => $filters['sessao_vinculada'],
            'data_tramitacao_inicial' => $filters['data_tramitacao_inicial'],
            'data_tramitacao_final' => $filters['data_tramitacao_final'],
            'sessao_apresentacao' => $filters['sessao_apresentacao'],
            'sessao_apreciacao' => $filters['sessao_apreciacao'],
            'data_sessao' => $filters['data_sessao'],
            'status_sessao' => $filters['status_sessao'],
            'assunto' => $filters['assunto'],
            'texto' => $filters['texto'],
            'types' => array_values(array_filter($types)),
            'statuses' => array_values(array_filter($statuses)),
            'departments' => array_values(array_filter($departments)),
            'sessions' => array_values(array_filter($sessions)),
            'session_statuses' => array_values(array_filter($sessionStatuses)),
        ],
        'summary' => $summary,
        'rows' => array_map(static function (array $row) {
            return [
                'id' => (int) $row['id'],
                'type' => $row['tipo_processo'],
                'process_number' => $row['numero_processo'],
                'year' => $row['ano'],
                'protocol_number' => $row['numero_protocolo'],
                'ementa' => $row['ementa'],
                'subject' => $row['assunto'] ?: '',
                'main_author' => $row['autor_principal'],
                'coauthors' => $row['coautores'] ?: '',
                'author_type' => $row['tipo_autor'],
                'status' => $row['situacao_status'],
                'department' => $row['departamento_gabinete'] ?: '',
                'linked_session' => $row['sessao_vinculada'] ?: '',
                'session_presentation' => $row['sessao_apresentacao'] ?: '',
                'session_appreciation' => $row['sessao_apreciacao'] ?: '',
                'session_date' => $row['data_sessao'],
                'session_status' => $row['status_sessao'] ?: '',
                'dispatch_text' => $row['despacho_texto'] ?: '',
                'created_at' => $row['created_at'],
                'attachments_count' => (int) $row['total_anexos'],
                'main_attachment' => $row['principal_anexo_id'] ? [
                    'id' => (int) $row['principal_anexo_id'],
                    'name' => $row['principal_anexo_nome'],
                    'download_url' => 'admin/public_processo_legislativo_download.php?id=' . (int) $row['principal_anexo_id'],
                ] : null,
                'latest' => [
                    'date' => $row['ultima_data_tramitacao'],
                    'situation' => $row['ultima_situacao'] ?: $row['situacao_status'],
                    'movement' => $row['ultimo_andamento'] ?: '',
                    'sender' => $row['ultimo_remetente'] ?: '',
                    'recipient' => $row['ultimo_destinatario'] ?: '',
                ],
                'history' => $row['history'],
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
