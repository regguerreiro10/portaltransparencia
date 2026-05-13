<?php

class LicitacaoSchemaHelper
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
            "CREATE TABLE IF NOT EXISTS licitacao (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                numero_edital VARCHAR(80) NOT NULL,
                processo_origem VARCHAR(120) NOT NULL,
                objeto VARCHAR(255) NOT NULL,
                status VARCHAR(80) NOT NULL,
                modalidade VARCHAR(120) NOT NULL,
                gestor VARCHAR(255) NOT NULL,
                fornecedor_nome VARCHAR(255) NULL,
                fornecedor_documento VARCHAR(30) NULL,
                valor_estimado DECIMAL(14,2) NOT NULL DEFAULT 0,
                data_licitacao DATE NOT NULL,
                downloads INT UNSIGNED NOT NULL DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_licitacao_numero_edital (numero_edital),
                KEY idx_licitacao_data (data_licitacao),
                KEY idx_licitacao_status (status),
                KEY idx_licitacao_modalidade (modalidade)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS licitacao_anexo (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                licitacao_id INT UNSIGNED NOT NULL,
                nome VARCHAR(255) NOT NULL,
                arquivo VARCHAR(255) NOT NULL,
                ordem INT UNSIGNED NOT NULL DEFAULT 1,
                created_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_licitacao_anexo_licitacao (licitacao_id),
                CONSTRAINT fk_licitacao_anexo_licitacao
                    FOREIGN KEY (licitacao_id) REFERENCES licitacao(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }
}
