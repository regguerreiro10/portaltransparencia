<?php

class NoticiaSchemaHelper
{
    public static function ensureSchema(): void
    {
        $conn = TTransaction::get();

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS noticia (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                titulo VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL,
                categoria VARCHAR(120) NOT NULL,
                data_publicacao DATE NOT NULL,
                resumo TEXT NULL,
                conteudo MEDIUMTEXT NULL,
                imagem VARCHAR(255) NULL,
                status VARCHAR(20) NOT NULL DEFAULT 'published',
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                PRIMARY KEY (id),
                UNIQUE KEY uniq_noticia_slug (slug),
                KEY idx_noticia_status_data (status, data_publicacao),
                KEY idx_noticia_categoria (categoria)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }

    public static function slugify(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return 'noticia';
        }

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
            if ($converted !== false) {
                $value = $converted;
            }
        }

        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
        $value = trim($value, '-');

        return $value !== '' ? $value : 'noticia';
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = self::slugify($title);
        $slug = $base;
        $suffix = 2;

        while (self::slugExists($slug, $ignoreId)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private static function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('slug', '=', $slug));

        if ($ignoreId) {
            $criteria->add(new TFilter('id', '<>', $ignoreId));
        }

        return Noticia::countObjects($criteria) > 0;
    }
}
