<?php
declare(strict_types=1);

require_once __DIR__ . '/database/Database.php';

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Importando datos desde archivos CSV\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$db = Database::getInstance();
$pdo = $db->getPDO();

// Verificar que existan los archivos
$archivos = ['categorias.csv', 'productos.csv', 'producto_categorias.csv', 'producto_caracteristicas.csv'];
foreach ($archivos as $archivo) {
    if (!file_exists(__DIR__ . '/' . $archivo)) {
        die("❌ ERROR: No se encontró el archivo $archivo\n");
    }
}

$pdo->beginTransaction();

try {
    // ========================================
    // 1. LIMPIAR TABLAS EXISTENTES
    // ========================================
    echo "🗑️  Limpiando tablas existentes...\n";
    $pdo->exec("DELETE FROM producto_caracteristicas");
    $pdo->exec("DELETE FROM producto_categoria");
    $pdo->exec("DELETE FROM productos");
    $pdo->exec("DELETE FROM categorias");
    echo "✓ Tablas limpiadas\n\n";

    // ========================================
    // 2. IMPORTAR CATEGORÍAS
    // ========================================
    echo "📂 Importando categorías...\n";
    $fp = fopen(__DIR__ . '/categorias.csv', 'r');
    $header = fgetcsv($fp); // Saltar encabezado
    
    $categoriasMap = []; // nombre => id
    $categoriasPendientes = [];
    
    // Primera pasada: crear categorías de nivel 1
    while (($row = fgetcsv($fp)) !== false) {
        $nombre = $row[0];
        $slug = $row[1];
        $parentNombre = $row[2] ?? '';
        $nivel = (int)$row[3];
        $orden = (int)$row[4];
        $activo = (int)$row[5];
        
        if ($nivel === 1 && empty($parentNombre)) {
            $stmt = $pdo->prepare("
                INSERT INTO categorias (nombre, slug, parent_id, nivel, orden, activo)
                VALUES (?, ?, NULL, ?, ?, ?)
            ");
            $stmt->execute([$nombre, $slug, $nivel, $orden, $activo]);
            $categoriasMap[$nombre] = (int)$pdo->lastInsertId();
        } else {
            $categoriasPendientes[] = [$nombre, $slug, $parentNombre, $nivel, $orden, $activo];
        }
    }
    fclose($fp);
    
    // Pasadas adicionales para niveles 2 y 3
    $maxIterations = 10;
    $iteration = 0;
    
    while (!empty($categoriasPendientes) && $iteration < $maxIterations) {
        $iteration++;
        $remaining = [];
        
        foreach ($categoriasPendientes as $data) {
            [$nombre, $slug, $parentNombre, $nivel, $orden, $activo] = $data;
            
            if (isset($categoriasMap[$parentNombre])) {
                $parentId = $categoriasMap[$parentNombre];
                $stmt = $pdo->prepare("
                    INSERT INTO categorias (nombre, slug, parent_id, nivel, orden, activo)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$nombre, $slug, $parentId, $nivel, $orden, $activo]);
                $categoriasMap[$nombre] = (int)$pdo->lastInsertId();
            } else {
                $remaining[] = $data;
            }
        }
        
        $categoriasPendientes = $remaining;
    }
    
    echo "✓ " . count($categoriasMap) . " categorías importadas\n\n";

    // ========================================
    // 3. IMPORTAR PRODUCTOS
    // ========================================
    echo "📦 Importando productos...\n";
    $fp = fopen(__DIR__ . '/productos.csv', 'r');
    $header = fgetcsv($fp);
    
    $productosMap = []; // nombre => id
    $count = 0;
    
    while (($row = fgetcsv($fp)) !== false) {
        $nombre = $row[0];
        $slug = $row[1];
        $descripcion = $row[2];
        $fabricante = $row[3];
        $imagen = $row[4];
        $badge = $row[5] ?? '';
        $badgeColor = $row[6] ?? '';
        $colorGradient = $row[7] ?? '';
        $activo = (int)($row[8] ?? 1);
        $orden = (int)($row[9] ?? 0);
        
        $stmt = $pdo->prepare("
            INSERT INTO productos (nombre, slug, descripcion, fabricante, imagen, badge, badge_color, color_gradient, activo, orden)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $nombre, $slug, $descripcion, $fabricante, $imagen,
            $badge ?: null, $badgeColor ?: null, $colorGradient ?: null,
            $activo, $orden
        ]);
        
        $productosMap[$nombre] = (int)$pdo->lastInsertId();
        $count++;
    }
    fclose($fp);
    
    echo "✓ $count productos importados\n\n";

    // ========================================
    // 4. IMPORTAR PRODUCTO-CATEGORÍAS
    // ========================================
    echo "🔗 Importando relaciones producto-categoría...\n";
    $fp = fopen(__DIR__ . '/producto_categorias.csv', 'r');
    $header = fgetcsv($fp);
    
    $count = 0;
    while (($row = fgetcsv($fp)) !== false) {
        $productoNombre = $row[0];
        $categoriaNombre = $row[1];
        
        if (isset($productosMap[$productoNombre]) && isset($categoriasMap[$categoriaNombre])) {
            $stmt = $pdo->prepare("
                INSERT INTO producto_categoria (producto_id, categoria_id)
                VALUES (?, ?)
            ");
            $stmt->execute([$productosMap[$productoNombre], $categoriasMap[$categoriaNombre]]);
            $count++;
        }
    }
    fclose($fp);
    
    echo "✓ $count relaciones importadas\n\n";

    // ========================================
    // 5. IMPORTAR CARACTERÍSTICAS
    // ========================================
    echo "⭐ Importando características de productos...\n";
    $fp = fopen(__DIR__ . '/producto_caracteristicas.csv', 'r');
    $header = fgetcsv($fp);
    
    $count = 0;
    while (($row = fgetcsv($fp)) !== false) {
        $productoNombre = $row[0];
        $caracteristica = $row[1];
        $orden = (int)$row[2];
        
        if (isset($productosMap[$productoNombre])) {
            $stmt = $pdo->prepare("
                INSERT INTO producto_caracteristicas (producto_id, caracteristica, orden)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$productosMap[$productoNombre], $caracteristica, $orden]);
            $count++;
        }
    }
    fclose($fp);
    
    echo "✓ $count características importadas\n\n";

    // ========================================
    // COMMIT
    // ========================================
    $pdo->commit();
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ Importación completada exitosamente\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Estadísticas finales
    $stats = [
        'Categorías' => $pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn(),
        'Productos' => $pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn(),
        'Relaciones Producto-Categoría' => $pdo->query("SELECT COUNT(*) FROM producto_categoria")->fetchColumn(),
        'Características' => $pdo->query("SELECT COUNT(*) FROM producto_caracteristicas")->fetchColumn(),
    ];
    
    foreach ($stats as $label => $valor) {
        echo sprintf("%-35s: %d\n", $label, $valor);
    }
    
    echo "\n✓ Base de datos actualizada con datos reales\n";
    echo "✓ Verifica en: http://localhost/productos.php\n\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
