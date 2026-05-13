<?php

class ProcessoLegislativoSchemaHelper
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
            "CREATE TABLE IF NOT EXISTS processo_legislativo (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                tipo_processo VARCHAR(120) NOT NULL,
                numero_processo VARCHAR(80) NOT NULL,
                ano VARCHAR(4) NOT NULL,
                numero_protocolo VARCHAR(80) NOT NULL,
                ementa VARCHAR(255) NOT NULL,
                assunto VARCHAR(255) NULL,
                autor_principal VARCHAR(255) NOT NULL,
                coautores TEXT NULL,
                tipo_autor VARCHAR(120) NOT NULL,
                situacao_status VARCHAR(120) NOT NULL,
                departamento_gabinete VARCHAR(255) NULL,
                sessao_vinculada VARCHAR(255) NULL,
                sessao_apresentacao VARCHAR(255) NULL,
                sessao_apreciacao VARCHAR(255) NULL,
                data_sessao DATETIME NULL,
                status_sessao VARCHAR(120) NULL,
                despacho_texto TEXT NULL,
                downloads INT UNSIGNED NOT NULL DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                UNIQUE KEY uq_processo_legislativo_numero_processo (numero_processo),
                UNIQUE KEY uq_processo_legislativo_numero_protocolo (numero_protocolo),
                KEY idx_processo_legislativo_tipo (tipo_processo),
                KEY idx_processo_legislativo_ano (ano),
                KEY idx_processo_legislativo_status (situacao_status),
                KEY idx_processo_legislativo_autor (autor_principal),
                KEY idx_processo_legislativo_departamento (departamento_gabinete),
                KEY idx_processo_legislativo_sessao (sessao_vinculada)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS processo_legislativo_anexo (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                processo_legislativo_id INT UNSIGNED NOT NULL,
                nome VARCHAR(255) NOT NULL,
                arquivo VARCHAR(255) NOT NULL,
                principal TINYINT(1) NOT NULL DEFAULT 0,
                ordem INT UNSIGNED NOT NULL DEFAULT 1,
                created_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_processo_legislativo_anexo_processo (processo_legislativo_id),
                CONSTRAINT fk_processo_legislativo_anexo_processo
                    FOREIGN KEY (processo_legislativo_id) REFERENCES processo_legislativo(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS processo_legislativo_tramitacao (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                processo_legislativo_id INT UNSIGNED NOT NULL,
                data_tramitacao DATETIME NOT NULL,
                situacao VARCHAR(120) NOT NULL,
                descricao_andamento VARCHAR(255) NOT NULL,
                remetente VARCHAR(255) NULL,
                destinatario VARCHAR(255) NULL,
                usuario_responsavel VARCHAR(255) NULL,
                observacao TEXT NULL,
                created_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_processo_legislativo_tram_proc (processo_legislativo_id),
                KEY idx_processo_legislativo_tram_data (data_tramitacao),
                CONSTRAINT fk_processo_legislativo_tram_processo
                    FOREIGN KEY (processo_legislativo_id) REFERENCES processo_legislativo(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }
}
