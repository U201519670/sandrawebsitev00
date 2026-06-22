<?php

declare(strict_types=1);

/**
 * Ajusta si el sitio no está en la raíz del dominio (ej: /subcarpeta/).
 * En producción en public_html raíz, dejar ''.
 */
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}

function asset_url(string $path): string
{
    $path = ltrim($path, '/');
    return rtrim(BASE_URL, '/') . '/' . $path;
}
