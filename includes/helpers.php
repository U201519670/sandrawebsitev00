<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

/**
 * Funciones auxiliares para el sitio web
 */

/**
 * Genera URL de WhatsApp con mensaje personalizado
 * 
 * @param string|null $message Mensaje personalizado (usa default si es null)
 * @param string|null $number Número de WhatsApp (usa default si es null)
 * @return string URL completa de WhatsApp
 */
function getWhatsAppURL(?string $message = null, ?string $number = null): string
{
    $phone = $number ?? WHATSAPP_NUMBER;
    $msg = $message ?? WHATSAPP_MESSAGE_DEFAULT;
    
    // Agregar contexto de página actual
    $page = basename($_SERVER['PHP_SELF'] ?? 'unknown', '.php');
    $pageContext = " (Desde: $page)";
    
    return "https://api.whatsapp.com/send/?phone={$phone}&text=" . 
           urlencode($msg . $pageContext);
}

/**
 * Genera URL de WhatsApp para cotizar un producto específico
 * 
 * @param string $productName Nombre del producto
 * @return string URL de WhatsApp
 */
function getWhatsAppQuoteURL(string $productName): string
{
    $message = str_replace('{producto}', $productName, WHATSAPP_MESSAGE_PRODUCT);
    return getWhatsAppURL($message);
}

/**
 * Formatea número de teléfono para mostrar
 * 
 * @return string Teléfono formateado
 */
function getContactPhone(): string
{
    return CONTACT_PHONE;
}

/**
 * Obtiene el email de contacto principal
 * 
 * @return string Email de contacto
 */
function getContactEmail(): string
{
    return CONTACT_EMAIL;
}

/**
 * Verifica si está en modo debug
 * 
 * @return bool True si está en modo debug
 */
function isDebugMode(): bool
{
    return defined('APP_DEBUG') && APP_DEBUG === 'true';
}

/**
 * Verifica si está en ambiente de producción
 * 
 * @return bool True si está en producción
 */
function isProduction(): bool
{
    return defined('APP_ENV') && APP_ENV === 'production';
}

/**
 * Verifica si está en ambiente de desarrollo
 * 
 * @return bool True si está en desarrollo
 */
function isDevelopment(): bool
{
    return defined('APP_ENV') && APP_ENV === 'development';
}

/**
 * Retorna el nombre legal de la empresa
 * 
 * @return string Nombre legal
 */
function getCompanyLegalName(): string
{
    return defined('APP_LEGAL_NAME') ? APP_LEGAL_NAME : 'PROQUIM';
}

/**
 * Retorna la dirección completa de la empresa
 * 
 * @return string Dirección completa
 */
function getCompanyAddress(): string
{
    return defined('ADDRESS_FULL') ? ADDRESS_FULL : '';
}

/**
 * Etiqueta del distrito para el mapa (sin dirección exacta)
 */
function getMapDistrictLabel(): string
{
    $parts = array_filter([
        defined('ADDRESS_DISTRICT') ? ADDRESS_DISTRICT : null,
        defined('ADDRESS_CITY') ? ADDRESS_CITY : null,
        defined('ADDRESS_COUNTRY') ? ADDRESS_COUNTRY : null,
    ]);
    return implode(', ', $parts);
}

/**
 * URL del iframe clásico de Google Maps (sin API key).
 * Usa MAP_EMBED_URL del .env o genera la URL desde el distrito.
 */
function getMapClassicEmbedURL(): ?string
{
    if (defined('MAP_EMBED_URL')) {
        $customUrl = trim(MAP_EMBED_URL);
        if ($customUrl !== '') {
            return $customUrl;
        }
    }

    $query = getMapDistrictLabel();
    if ($query === '') {
        return null;
    }

    $zoom = defined('MAP_ZOOM') ? MAP_ZOOM : '13';

    return 'https://maps.google.com/maps?q=' . urlencode($query)
        . '&hl=es&z=' . urlencode($zoom)
        . '&output=embed';
}

/**
 * URL de Google Maps con la dirección exacta (para enlace "Ver en mapa")
 */
function getGoogleMapsPlaceURL(): string
{
    $query = getCompanyAddress();
    if ($query === '') {
        return 'https://www.google.com/maps';
    }

    return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($query);
}
