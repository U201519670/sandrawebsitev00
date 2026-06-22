<?php
declare(strict_types=1);

require_once __DIR__ . '/database/Database.php';

echo "Exportando base de datos a CSV...\n\n";

$db = Database::getInstance();
$pdo = $db->getPDO();

// ========================================
// 1. CATEGORÍAS
// ========================================
echo "Exportando categorías...\n";
$categorias = $pdo->query("
    SELECT 
        c1.nombre,
        c1.slug,
        c2.nombre as parent_nombre,
        c1.nivel,
        c1.orden,
        c1.activo
    FROM categorias c1
    LEFT JOIN categorias c2 ON c1.parent_id = c2.id
    ORDER BY c1.nivel, c1.orden, c1.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$fp = fopen(__DIR__ . '/categorias.csv', 'w');
fprintf($fp, "\xEF\xBB\xBF"); // UTF-8 BOM para Excel
fputcsv($fp, ['nombre', 'slug', 'parent_nombre', 'nivel', 'orden', 'activo']);
foreach ($categorias as $cat) {
    fputcsv($fp, [
        $cat['nombre'],
        $cat['slug'],
        $cat['parent_nombre'] ?? '',
        $cat['nivel'],
        $cat['orden'],
        $cat['activo']
    ]);
}
fclose($fp);
echo "✓ categorias.csv creado (" . count($categorias) . " categorías)\n";

// ========================================
// 2. PRODUCTOS
// ========================================
echo "Exportando productos...\n";
$productos = $pdo->query("
    SELECT 
        nombre,
        slug,
        descripcion,
        fabricante,
        imagen,
        badge,
        badge_color,
        color_gradient,
        activo,
        orden
    FROM productos
    ORDER BY orden DESC, nombre
")->fetchAll(PDO::FETCH_ASSOC);

$fp = fopen(__DIR__ . '/productos.csv', 'w');
fprintf($fp, "\xEF\xBB\xBF"); // UTF-8 BOM
fputcsv($fp, ['nombre', 'slug', 'descripcion', 'fabricante', 'imagen', 'badge', 'badge_color', 'color_gradient', 'activo', 'orden']);
foreach ($productos as $prod) {
    fputcsv($fp, [
        $prod['nombre'],
        $prod['slug'],
        $prod['descripcion'],
        $prod['fabricante'],
        $prod['imagen'],
        $prod['badge'] ?? '',
        $prod['badge_color'] ?? '',
        $prod['color_gradient'] ?? '',
        $prod['activo'],
        $prod['orden']
    ]);
}
fclose($fp);
echo "✓ productos.csv creado (" . count($productos) . " productos)\n";

// ========================================
// 3. PRODUCTO-CATEGORÍAS
// ========================================
echo "Exportando relaciones producto-categoría...\n";
$prodCats = $pdo->query("
    SELECT 
        p.nombre as producto_nombre,
        c.nombre as categoria_nombre
    FROM producto_categoria pc
    JOIN productos p ON pc.producto_id = p.id
    JOIN categorias c ON pc.categoria_id = c.id
    ORDER BY p.nombre, c.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$fp = fopen(__DIR__ . '/producto_categorias.csv', 'w');
fprintf($fp, "\xEF\xBB\xBF"); // UTF-8 BOM
fputcsv($fp, ['producto_nombre', 'categoria_nombre']);
foreach ($prodCats as $pc) {
    fputcsv($fp, [$pc['producto_nombre'], $pc['categoria_nombre']]);
}
fclose($fp);
echo "✓ producto_categorias.csv creado (" . count($prodCats) . " relaciones)\n";

// ========================================
// 4. PRODUCTO-CARACTERÍSTICAS
// ========================================
echo "Exportando características de productos...\n";
$prodCaract = $pdo->query("
    SELECT 
        p.nombre as producto_nombre,
        pc.caracteristica,
        pc.orden
    FROM producto_caracteristicas pc
    JOIN productos p ON pc.producto_id = p.id
    ORDER BY p.nombre, pc.orden
")->fetchAll(PDO::FETCH_ASSOC);

$fp = fopen(__DIR__ . '/producto_caracteristicas.csv', 'w');
fprintf($fp, "\xEF\xBB\xBF"); // UTF-8 BOM
fputcsv($fp, ['producto_nombre', 'caracteristica', 'orden']);
foreach ($prodCaract as $pc) {
    fputcsv($fp, [$pc['producto_nombre'], $pc['caracteristica'], $pc['orden']]);
}
fclose($fp);
echo "✓ producto_caracteristicas.csv creado (" . count($prodCaract) . " características)\n";

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Exportación completada exitosamente\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "📂 Archivos creados en: " . __DIR__ . "\n\n";
echo "  1️⃣  categorias.csv (" . count($categorias) . " registros)\n";
echo "  2️⃣  productos.csv (" . count($productos) . " registros)\n";
echo "  3️⃣  producto_categorias.csv (" . count($prodCats) . " registros)\n";
echo "  4️⃣  producto_caracteristicas.csv (" . count($prodCaract) . " registros)\n\n";
echo "📝 Siguiente paso:\n";
echo "  1. Abre los archivos CSV en Excel\n";
echo "  2. Edita los datos con información real\n";
echo "  3. Guarda los archivos\n";
echo "  4. Devuélveme los archivos editados\n";
echo "  5. Ejecutaré import_from_csv.php para actualizar la BD\n\n";
