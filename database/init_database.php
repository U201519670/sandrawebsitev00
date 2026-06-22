<?php

declare(strict_types=1);

/**
 * Script de inicialización de base de datos
 * Crea todas las tablas e índices necesarios para el catálogo de productos
 */

$dbPath = __DIR__ . '/catalogo.db';

// Eliminar BD existente si se pasa el parámetro force
if (isset($argv[1]) && $argv[1] === '--force' && file_exists($dbPath)) {
    unlink($dbPath);
    echo "✓ Base de datos existente eliminada\n";
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Inicializando base de datos...\n\n";

    // Habilitar foreign keys
    $pdo->exec('PRAGMA foreign_keys = ON');
    
    // Habilitar WAL mode para mejor concurrencia
    $pdo->exec('PRAGMA journal_mode = WAL');
    echo "✓ Configuración de SQLite aplicada\n";

    // ============================================
    // TABLA: categorias
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categorias (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            parent_id INTEGER NULL,
            nivel INTEGER DEFAULT 1 CHECK(nivel IN (1, 2, 3)),
            orden INTEGER DEFAULT 0,
            activo INTEGER DEFAULT 1 CHECK(activo IN (0, 1)),
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (parent_id) REFERENCES categorias(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Tabla 'categorias' creada\n";

    // Índices para categorias
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_categorias_parent ON categorias(parent_id)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_categorias_slug ON categorias(slug)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_categorias_nivel ON categorias(nivel)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_categorias_activo ON categorias(activo)");
    echo "✓ Índices de 'categorias' creados\n";

    // ============================================
    // TABLA: productos
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS productos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            descripcion TEXT,
            fabricante TEXT,
            imagen TEXT,
            badge TEXT NULL,
            badge_color TEXT NULL,
            color_gradient TEXT,
            activo INTEGER DEFAULT 1 CHECK(activo IN (0, 1)),
            orden INTEGER DEFAULT 0,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Tabla 'productos' creada\n";

    // Índices para productos
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_productos_slug ON productos(slug)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_productos_fabricante ON productos(fabricante)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_productos_activo ON productos(activo)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_productos_nombre ON productos(nombre)");
    echo "✓ Índices de 'productos' creados\n";

    // ============================================
    // TABLA: producto_categoria (relación muchos a muchos)
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS producto_categoria (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            producto_id INTEGER NOT NULL,
            categoria_id INTEGER NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
            FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE,
            UNIQUE(producto_id, categoria_id)
        )
    ");
    echo "✓ Tabla 'producto_categoria' creada\n";

    // Índices para producto_categoria
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_producto_categoria_producto ON producto_categoria(producto_id)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_producto_categoria_categoria ON producto_categoria(categoria_id)");
    echo "✓ Índices de 'producto_categoria' creados\n";

    // ============================================
    // TABLA: producto_caracteristicas
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS producto_caracteristicas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            producto_id INTEGER NOT NULL,
            caracteristica TEXT NOT NULL,
            orden INTEGER DEFAULT 0,
            FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Tabla 'producto_caracteristicas' creada\n";

    // Índices para producto_caracteristicas
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_producto_caracteristicas_producto ON producto_caracteristicas(producto_id)");
    echo "✓ Índices de 'producto_caracteristicas' creados\n";

    // ============================================
    // Trigger para actualizar updated_at en productos
    // ============================================
    $pdo->exec("
        CREATE TRIGGER IF NOT EXISTS update_productos_timestamp 
        AFTER UPDATE ON productos
        FOR EACH ROW
        BEGIN
            UPDATE productos SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END
    ");
    echo "✓ Trigger de actualización creado\n";

    // ============================================
    // Verificar integridad
    // ============================================
    $result = $pdo->query("PRAGMA integrity_check")->fetch();
    if ($result[0] === 'ok') {
        echo "✓ Verificación de integridad: OK\n";
    } else {
        echo "⚠ Advertencia en verificación de integridad: " . $result[0] . "\n";
    }

    // ============================================
    // Estadísticas finales
    // ============================================
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);
    echo "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Base de datos inicializada correctamente\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Ubicación: $dbPath\n";
    echo "Tablas creadas: " . count($tables) . "\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    echo "\n";
    echo "Siguiente paso: Ejecutar seed_data.php para poblar la base de datos\n";
    echo "Comando: php database/seed_data.php\n";

} catch (PDOException $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
