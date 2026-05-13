<?php

class MemorandoSchemaHelper
{
    public static function ensureSchema(): void
    {
        self::executeStatements(TTransaction::get());
    }

    private static function executeStatements($conn): void
    {
        $conn->exec(
            "CREATE TABLE IF NOT EXISTS memorando (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                numero_memorando VARCHAR(80) NOT NULL,
                assunto VARCHAR(255) NOT NULL,
                texto_memorando LONGTEXT NULL,
                status VARCHAR(40) NOT NULL DEFAULT 'Enviado',
                tipo VARCHAR(40) NOT NULL DEFAULT 'Normal',
                template_codigo VARCHAR(80) NULL,
                template_nome VARCHAR(150) NULL,
                remetente_user_id INT NULL,
                remetente_nome VARCHAR(255) NOT NULL,
                departamento_origem_id INT NULL,
                departamento_origem_nome VARCHAR(255) NOT NULL,
                data_memorando DATETIME NOT NULL,
                memorando_pai_id INT UNSIGNED NULL,
                pode_virar_processo CHAR(1) NOT NULL DEFAULT 'N',
                processo_referencia VARCHAR(120) NULL,
                downloads INT UNSIGNED NOT NULL DEFAULT 0,
                lido_em DATETIME NULL,
                arquivado_em DATETIME NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                UNIQUE KEY uq_memorando_numero (numero_memorando),
                KEY idx_memorando_status (status),
                KEY idx_memorando_data (data_memorando),
                KEY idx_memorando_remetente (remetente_user_id),
                KEY idx_memorando_departamento_origem (departamento_origem_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS memorando_destinatario (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                memorando_id INT UNSIGNED NOT NULL,
                tipo_destino VARCHAR(20) NOT NULL DEFAULT 'Para',
                system_user_id INT NULL,
                destinatario_nome VARCHAR(255) NOT NULL,
                system_departamento_id INT NULL,
                departamento_destino_nome VARCHAR(255) NOT NULL,
                status VARCHAR(40) NOT NULL DEFAULT 'Enviado',
                recebido_em DATETIME NULL,
                lido_em DATETIME NULL,
                respondido_em DATETIME NULL,
                arquivado_em DATETIME NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_memorando_destinatario_memorando (memorando_id),
                KEY idx_memorando_destinatario_usuario (system_user_id),
                KEY idx_memorando_destinatario_departamento (system_departamento_id),
                KEY idx_memorando_destinatario_status (status),
                CONSTRAINT fk_memorando_destinatario_memorando
                    FOREIGN KEY (memorando_id) REFERENCES memorando(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS memorando_anexo (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                memorando_id INT UNSIGNED NOT NULL,
                nome VARCHAR(255) NOT NULL,
                arquivo VARCHAR(255) NOT NULL,
                ordem INT UNSIGNED NOT NULL DEFAULT 1,
                created_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_memorando_anexo_memorando (memorando_id),
                CONSTRAINT fk_memorando_anexo_memorando
                    FOREIGN KEY (memorando_id) REFERENCES memorando(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS memorando_tramitacao (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                memorando_id INT UNSIGNED NOT NULL,
                memorando_destinatario_id INT UNSIGNED NULL,
                acao VARCHAR(80) NOT NULL,
                status_resultante VARCHAR(40) NULL,
                usuario_id INT NULL,
                usuario_nome VARCHAR(255) NULL,
                departamento_nome VARCHAR(255) NULL,
                descricao TEXT NULL,
                data_evento DATETIME NOT NULL,
                created_at DATETIME NULL,
                PRIMARY KEY (id),
                KEY idx_memorando_tramitacao_memorando (memorando_id),
                KEY idx_memorando_tramitacao_data (data_evento),
                CONSTRAINT fk_memorando_tramitacao_memorando
                    FOREIGN KEY (memorando_id) REFERENCES memorando(id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_memorando_tramitacao_destinatario
                    FOREIGN KEY (memorando_destinatario_id) REFERENCES memorando_destinatario(id)
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }
}
