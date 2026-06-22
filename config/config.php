<?php
declare(strict_types=1);

/**
 * Cargador de Configuración desde .env
 * Carga variables de entorno y las hace disponibles como constantes
 */

class Config
{
    private static bool $loaded = false;
    private static array $vars = [];
    
    public static function load(): void
    {
        if (self::$loaded) return;
        
        $envPath = __DIR__ . '/../.env';
        
        if (!file_exists($envPath)) {
            throw new RuntimeException('.env file not found');
        }
        
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Saltar comentarios
            if (strpos(trim($line), '#') === 0) continue;
            
            // Parsear línea KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remover comillas
                $value = trim($value, '"\'');
                
                // Guardar en array y en $_ENV
                self::$vars[$key] = $value;
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
        
        self::$loaded = true;
        self::defineConstants();
    }
    
    private static function defineConstants(): void
    {
        // Definir constantes para acceso rápido
        foreach (self::$vars as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
            }
        }
    }
    
    public static function get(string $key, $default = null)
    {
        return self::$vars[$key] ?? $default;
    }
}

// Auto-cargar al incluir este archivo
Config::load();
