<?php

class FinanceiroPublicoSchemaHelper
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
            "CREATE TABLE IF NOT EXISTS financeiro_cadastro (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                nome VARCHAR(255) NOT NULL,
                descricao TEXT NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_financeiro_cadastro_nome (nome)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS financeiro_categoria (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                nome VARCHAR(255) NOT NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                UNIQUE KEY uniq_financeiro_categoria_nome (nome)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS financeiro_subcategoria (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                financeiro_cadastro_id INT UNSIGNED NOT NULL,
                categoria_id INT UNSIGNED NOT NULL,
                nome VARCHAR(255) NOT NULL,
                ano SMALLINT UNSIGNED NOT NULL,
                visivel CHAR(1) NOT NULL DEFAULT 'Y',
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_financeiro_subcategoria_cadastro (financeiro_cadastro_id),
                KEY idx_financeiro_subcategoria_categoria (categoria_id),
                KEY idx_financeiro_subcategoria_ano (ano),
                KEY idx_financeiro_subcategoria_visivel (visivel),
                CONSTRAINT fk_financeiro_subcategoria_cadastro
                    FOREIGN KEY (financeiro_cadastro_id) REFERENCES financeiro_cadastro(id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_financeiro_subcategoria_categoria
                    FOREIGN KEY (categoria_id) REFERENCES financeiro_categoria(id)
                    ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS financeiro_arquivo (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                financeiro_cadastro_id INT UNSIGNED NULL,
                subcategoria_id INT UNSIGNED NULL,
                nome_arquivo VARCHAR(255) NOT NULL,
                caminho_arquivo VARCHAR(255) NULL,
                link_externo VARCHAR(500) NULL,
                tipo VARCHAR(20) NOT NULL DEFAULT 'arquivo',
                extensao VARCHAR(20) NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_financeiro_arquivo_cadastro (financeiro_cadastro_id),
                KEY idx_financeiro_arquivo_subcategoria (subcategoria_id),
                KEY idx_financeiro_arquivo_tipo (tipo),
                CONSTRAINT fk_financeiro_arquivo_cadastro
                    FOREIGN KEY (financeiro_cadastro_id) REFERENCES financeiro_cadastro(id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_financeiro_arquivo_subcategoria
                    FOREIGN KEY (subcategoria_id) REFERENCES financeiro_subcategoria(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }
}
