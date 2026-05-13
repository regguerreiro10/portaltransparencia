<?php

class DocumentoPublicoSchemaHelper
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
            "CREATE TABLE IF NOT EXISTS documento_publico (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                numero_documento VARCHAR(80) NOT NULL,
                tipo VARCHAR(80) NOT NULL,
                data_documento DATE NOT NULL,
                assunto VARCHAR(255) NOT NULL,
                nome VARCHAR(255) NOT NULL,
                orgao VARCHAR(255) NOT NULL,
                status VARCHAR(20) NOT NULL DEFAULT 'published',
                downloads INT UNSIGNED NOT NULL DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_documento_publico_tipo (tipo),
                KEY idx_documento_publico_data (data_documento),
                KEY idx_documento_publico_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS documento_publico_anexo (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                documento_publico_id INT UNSIGNED NOT NULL,
                nome VARCHAR(255) NOT NULL,
                arquivo VARCHAR(255) NOT NULL,
                ordem INT UNSIGNED NOT NULL DEFAULT 1,
                created_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_documento_publico_anexo_documento (documento_publico_id),
                CONSTRAINT fk_documento_publico_anexo_documento
                    FOREIGN KEY (documento_publico_id) REFERENCES documento_publico(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }
}
