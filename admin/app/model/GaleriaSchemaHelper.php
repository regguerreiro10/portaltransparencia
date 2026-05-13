<?php

class GaleriaSchemaHelper
{
    public static function ensureSchema(): void
    {
        $conn = TTransaction::get();

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS galeria_item (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                titulo VARCHAR(255) NOT NULL,
                descricao TEXT NULL,
                tipo VARCHAR(20) NOT NULL DEFAULT 'foto',
                arquivo VARCHAR(255) NULL,
                imagem_capa VARCHAR(255) NULL,
                texto_alternativo VARCHAR(255) NULL,
                ordem INT NOT NULL DEFAULT 0,
                status VARCHAR(20) NOT NULL DEFAULT 'published',
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_galeria_tipo_status_ordem (tipo, status, ordem),
                KEY idx_galeria_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }
}
