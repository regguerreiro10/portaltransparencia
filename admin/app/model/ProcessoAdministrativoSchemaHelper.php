<?php

class ProcessoAdministrativoSchemaHelper
{
    public static function ensureSchema(): void
    {
        $conn = TTransaction::get();
        self::executeStatements($conn);
    }

    public static function ensureSchemaWithPdo(PDO $pdo): void
    {
        self::executeStatements($pdo);
    }

    private static function executeStatements($conn): void
    {
        $conn->exec(
            "CREATE TABLE IF NOT EXISTS processo_administrativo (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                numero_processo VARCHAR(80) NOT NULL,
                numero_protocolo VARCHAR(80) NOT NULL,
                tipo_processo VARCHAR(120) NULL,
                assunto VARCHAR(255) NOT NULL,
                ementa TEXT NULL,
                descricao TEXT NULL,
                status VARCHAR(80) NOT NULL,
                status_leitura VARCHAR(40) NOT NULL DEFAULT 'Nao lido',
                nivel_sigilo VARCHAR(30) NOT NULL DEFAULT 'Publico',
                sigilo_motivo TEXT NULL,
                sigilo_alterado_em DATETIME NULL,
                sigilo_alterado_por VARCHAR(255) NULL,
                remetente_user_id INT NULL,
                remetente_nome VARCHAR(255) NULL,
                autor_nome VARCHAR(255) NULL,
                departamento_origem VARCHAR(255) NOT NULL,
                departamento_origem_id INT NULL,
                departamento_atual VARCHAR(255) NOT NULL,
                departamento_atual_id INT NULL,
                departamento_destino VARCHAR(255) NULL,
                departamento_destino_id INT NULL,
                destinatario_nome VARCHAR(255) NULL,
                responsavel VARCHAR(255) NOT NULL,
                permissao_grupo VARCHAR(255) NULL,
                requerente VARCHAR(255) NULL,
                solicitante VARCHAR(255) NULL,
                data_abertura DATE NOT NULL,
                data_envio DATETIME NULL,
                prazo_tipo VARCHAR(30) NULL,
                prazo_dias INT NULL,
                prazo_inicio DATE NULL,
                prazo_final DATE NULL,
                prazo_status VARCHAR(40) NULL,
                prazo_interrompido_em DATETIME NULL,
                prazo_prorrogado_ate DATE NULL,
                lido_em DATETIME NULL,
                rascunho_em DATETIME NULL,
                arquivado_em DATETIME NULL,
                observacoes TEXT NULL,
                integra_gerada_em DATETIME NULL,
                downloads INT UNSIGNED NOT NULL DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                UNIQUE KEY uq_processo_administrativo_numero_processo (numero_processo),
                UNIQUE KEY uq_processo_administrativo_numero_protocolo (numero_protocolo),
                KEY idx_processo_administrativo_data (data_abertura),
                KEY idx_processo_administrativo_status (status),
                KEY idx_processo_administrativo_sigilo (nivel_sigilo),
                KEY idx_processo_administrativo_departamento (departamento_atual)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS processo_administrativo_anexo (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                processo_administrativo_id INT UNSIGNED NOT NULL,
                nome VARCHAR(255) NOT NULL,
                arquivo VARCHAR(255) NOT NULL,
                ordem INT UNSIGNED NOT NULL DEFAULT 1,
                created_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_processo_administrativo_anexo_processo (processo_administrativo_id),
                CONSTRAINT fk_processo_administrativo_anexo_processo
                    FOREIGN KEY (processo_administrativo_id) REFERENCES processo_administrativo(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS processo_administrativo_tramitacao (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                processo_administrativo_id INT UNSIGNED NOT NULL,
                data_tramitacao DATETIME NOT NULL,
                tipo_evento VARCHAR(80) NOT NULL,
                acao_executada VARCHAR(80) NULL,
                departamento_origem VARCHAR(255) NULL,
                departamento_destino VARCHAR(255) NULL,
                usuario_responsavel VARCHAR(255) NULL,
                despacho_texto TEXT NULL,
                prazo_tipo VARCHAR(30) NULL,
                prazo_dias INT NULL,
                prazo_final DATE NULL,
                prazo_status VARCHAR(40) NULL,
                anexo_descricao TEXT NULL,
                observacao TEXT NULL,
                created_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_processo_administrativo_tram_proc (processo_administrativo_id),
                KEY idx_processo_administrativo_tram_data (data_tramitacao),
                CONSTRAINT fk_processo_administrativo_tram_processo
                    FOREIGN KEY (processo_administrativo_id) REFERENCES processo_administrativo(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        self::ensureColumn($conn, 'processo_administrativo', 'tipo_processo', "ALTER TABLE processo_administrativo ADD COLUMN tipo_processo VARCHAR(120) NULL AFTER numero_protocolo");
        self::ensureColumn($conn, 'processo_administrativo', 'ementa', "ALTER TABLE processo_administrativo ADD COLUMN ementa TEXT NULL AFTER assunto");
        self::ensureColumn($conn, 'processo_administrativo', 'status_leitura', "ALTER TABLE processo_administrativo ADD COLUMN status_leitura VARCHAR(40) NOT NULL DEFAULT 'Nao lido' AFTER status");
        self::ensureColumn($conn, 'processo_administrativo', 'sigilo_motivo', "ALTER TABLE processo_administrativo ADD COLUMN sigilo_motivo TEXT NULL AFTER nivel_sigilo");
        self::ensureColumn($conn, 'processo_administrativo', 'sigilo_alterado_em', "ALTER TABLE processo_administrativo ADD COLUMN sigilo_alterado_em DATETIME NULL AFTER sigilo_motivo");
        self::ensureColumn($conn, 'processo_administrativo', 'sigilo_alterado_por', "ALTER TABLE processo_administrativo ADD COLUMN sigilo_alterado_por VARCHAR(255) NULL AFTER sigilo_alterado_em");
        self::ensureColumn($conn, 'processo_administrativo', 'remetente_user_id', "ALTER TABLE processo_administrativo ADD COLUMN remetente_user_id INT NULL AFTER sigilo_alterado_por");
        self::ensureColumn($conn, 'processo_administrativo', 'remetente_nome', "ALTER TABLE processo_administrativo ADD COLUMN remetente_nome VARCHAR(255) NULL AFTER remetente_user_id");
        self::ensureColumn($conn, 'processo_administrativo', 'autor_nome', "ALTER TABLE processo_administrativo ADD COLUMN autor_nome VARCHAR(255) NULL AFTER remetente_nome");
        self::ensureColumn($conn, 'processo_administrativo', 'departamento_origem_id', "ALTER TABLE processo_administrativo ADD COLUMN departamento_origem_id INT NULL AFTER departamento_origem");
        self::ensureColumn($conn, 'processo_administrativo', 'departamento_atual_id', "ALTER TABLE processo_administrativo ADD COLUMN departamento_atual_id INT NULL AFTER departamento_atual");
        self::ensureColumn($conn, 'processo_administrativo', 'departamento_destino', "ALTER TABLE processo_administrativo ADD COLUMN departamento_destino VARCHAR(255) NULL AFTER departamento_atual_id");
        self::ensureColumn($conn, 'processo_administrativo', 'departamento_destino_id', "ALTER TABLE processo_administrativo ADD COLUMN departamento_destino_id INT NULL AFTER departamento_destino");
        self::ensureColumn($conn, 'processo_administrativo', 'destinatario_nome', "ALTER TABLE processo_administrativo ADD COLUMN destinatario_nome VARCHAR(255) NULL AFTER departamento_destino_id");
        self::ensureColumn($conn, 'processo_administrativo', 'requerente', "ALTER TABLE processo_administrativo ADD COLUMN requerente VARCHAR(255) NULL AFTER permissao_grupo");
        self::ensureColumn($conn, 'processo_administrativo', 'data_envio', "ALTER TABLE processo_administrativo ADD COLUMN data_envio DATETIME NULL AFTER data_abertura");
        self::ensureColumn($conn, 'processo_administrativo', 'prazo_tipo', "ALTER TABLE processo_administrativo ADD COLUMN prazo_tipo VARCHAR(30) NULL AFTER data_envio");
        self::ensureColumn($conn, 'processo_administrativo', 'prazo_dias', "ALTER TABLE processo_administrativo ADD COLUMN prazo_dias INT NULL AFTER prazo_tipo");
        self::ensureColumn($conn, 'processo_administrativo', 'prazo_inicio', "ALTER TABLE processo_administrativo ADD COLUMN prazo_inicio DATE NULL AFTER prazo_dias");
        self::ensureColumn($conn, 'processo_administrativo', 'prazo_final', "ALTER TABLE processo_administrativo ADD COLUMN prazo_final DATE NULL AFTER prazo_inicio");
        self::ensureColumn($conn, 'processo_administrativo', 'prazo_status', "ALTER TABLE processo_administrativo ADD COLUMN prazo_status VARCHAR(40) NULL AFTER prazo_final");
        self::ensureColumn($conn, 'processo_administrativo', 'prazo_interrompido_em', "ALTER TABLE processo_administrativo ADD COLUMN prazo_interrompido_em DATETIME NULL AFTER prazo_status");
        self::ensureColumn($conn, 'processo_administrativo', 'prazo_prorrogado_ate', "ALTER TABLE processo_administrativo ADD COLUMN prazo_prorrogado_ate DATE NULL AFTER prazo_interrompido_em");
        self::ensureColumn($conn, 'processo_administrativo', 'lido_em', "ALTER TABLE processo_administrativo ADD COLUMN lido_em DATETIME NULL AFTER prazo_prorrogado_ate");
        self::ensureColumn($conn, 'processo_administrativo', 'rascunho_em', "ALTER TABLE processo_administrativo ADD COLUMN rascunho_em DATETIME NULL AFTER lido_em");
        self::ensureColumn($conn, 'processo_administrativo', 'arquivado_em', "ALTER TABLE processo_administrativo ADD COLUMN arquivado_em DATETIME NULL AFTER rascunho_em");
        self::ensureColumn($conn, 'processo_administrativo', 'integra_gerada_em', "ALTER TABLE processo_administrativo ADD COLUMN integra_gerada_em DATETIME NULL AFTER observacoes");

        self::ensureColumn($conn, 'processo_administrativo_tramitacao', 'acao_executada', "ALTER TABLE processo_administrativo_tramitacao ADD COLUMN acao_executada VARCHAR(80) NULL AFTER tipo_evento");
        self::ensureColumn($conn, 'processo_administrativo_tramitacao', 'despacho_texto', "ALTER TABLE processo_administrativo_tramitacao ADD COLUMN despacho_texto TEXT NULL AFTER usuario_responsavel");
        self::ensureColumn($conn, 'processo_administrativo_tramitacao', 'prazo_tipo', "ALTER TABLE processo_administrativo_tramitacao ADD COLUMN prazo_tipo VARCHAR(30) NULL AFTER despacho_texto");
        self::ensureColumn($conn, 'processo_administrativo_tramitacao', 'prazo_dias', "ALTER TABLE processo_administrativo_tramitacao ADD COLUMN prazo_dias INT NULL AFTER prazo_tipo");
        self::ensureColumn($conn, 'processo_administrativo_tramitacao', 'prazo_final', "ALTER TABLE processo_administrativo_tramitacao ADD COLUMN prazo_final DATE NULL AFTER prazo_dias");
        self::ensureColumn($conn, 'processo_administrativo_tramitacao', 'prazo_status', "ALTER TABLE processo_administrativo_tramitacao ADD COLUMN prazo_status VARCHAR(40) NULL AFTER prazo_final");
        self::ensureColumn($conn, 'processo_administrativo_tramitacao', 'anexo_descricao', "ALTER TABLE processo_administrativo_tramitacao ADD COLUMN anexo_descricao TEXT NULL AFTER prazo_status");
    }

    private static function ensureColumn($conn, string $table, string $column, string $alterSql): void
    {
        $stmt = $conn->prepare("SHOW COLUMNS FROM {$table} LIKE ?");
        $stmt->execute([$column]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$exists) {
            $conn->exec($alterSql);
        }
    }
}
