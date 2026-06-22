<?php

declare(strict_types=1);

/**
 * Script para poblar la base de datos con datos de ejemplo
 * Usa las imágenes existentes en assets/img/catalogo/
 */

require_once __DIR__ . '/Database.php';

$db = Database::getInstance(__DIR__ . '/catalogo.db');
$pdo = $db->getPDO();

echo "Poblando base de datos con datos de ejemplo...\n\n";

try {
    $db->transaction(function($pdo) {
        
        // ============================================
        // CATEGORÍAS - Nivel 1 (Raíz)
        // ============================================
        echo "Insertando categorías de nivel 1...\n";
        
        $categoriasNivel1 = [
            ['nombre' => 'Tratamiento de Agua', 'slug' => 'tratamiento-de-agua', 'orden' => 1],
            ['nombre' => 'Químicos de Limpieza', 'slug' => 'quimicos-de-limpieza', 'orden' => 2],
            ['nombre' => 'Equipos y Accesorios', 'slug' => 'equipos-y-accesorios', 'orden' => 3],
            ['nombre' => 'Medición y Control', 'slug' => 'medicion-y-control', 'orden' => 4],
            ['nombre' => 'Especialidades', 'slug' => 'especialidades', 'orden' => 5],
        ];
        
        $stmt = $pdo->prepare("INSERT INTO categorias (nombre, slug, nivel, orden) VALUES (?, ?, 1, ?)");
        foreach ($categoriasNivel1 as $cat) {
            $stmt->execute([$cat['nombre'], $cat['slug'], $cat['orden']]);
        }
        echo "✓ " . count($categoriasNivel1) . " categorías de nivel 1 insertadas\n";
        
        // ============================================
        // CATEGORÍAS - Nivel 2
        // ============================================
        echo "Insertando categorías de nivel 2...\n";
        
        $categoriasNivel2 = [
            // Tratamiento de Agua (id: 1)
            ['nombre' => 'Coagulantes y Floculantes', 'slug' => 'coagulantes-y-floculantes', 'parent_id' => 1, 'orden' => 1],
            ['nombre' => 'Desinfección', 'slug' => 'desinfeccion', 'parent_id' => 1, 'orden' => 2],
            ['nombre' => 'Control de pH', 'slug' => 'control-de-ph', 'parent_id' => 1, 'orden' => 3],
            ['nombre' => 'Antiincrustantes', 'slug' => 'antiincrustantes', 'parent_id' => 1, 'orden' => 4],
            ['nombre' => 'Inhibidores de Corrosión', 'slug' => 'inhibidores-corrosion', 'parent_id' => 1, 'orden' => 5],
            
            // Químicos de Limpieza (id: 2)
            ['nombre' => 'Detergentes Industriales', 'slug' => 'detergentes-industriales', 'parent_id' => 2, 'orden' => 1],
            ['nombre' => 'Desengrasantes', 'slug' => 'desengrasantes', 'parent_id' => 2, 'orden' => 2],
            ['nombre' => 'Sanitizantes y Desinfectantes', 'slug' => 'sanitizantes-desinfectantes', 'parent_id' => 2, 'orden' => 3],
            ['nombre' => 'Abrillantadores', 'slug' => 'abrillantadores', 'parent_id' => 2, 'orden' => 4],
            
            // Equipos y Accesorios (id: 3)
            ['nombre' => 'Sistemas de Filtración', 'slug' => 'sistemas-filtracion', 'parent_id' => 3, 'orden' => 1],
            ['nombre' => 'Sistemas de Ósmosis Inversa', 'slug' => 'sistemas-osmosis-inversa', 'parent_id' => 3, 'orden' => 2],
            ['nombre' => 'Bombas y Dosificación', 'slug' => 'bombas-dosificacion', 'parent_id' => 3, 'orden' => 3],
            ['nombre' => 'Tanques y Almacenamiento', 'slug' => 'tanques-almacenamiento', 'parent_id' => 3, 'orden' => 4],
            ['nombre' => 'Válvulas y Accesorios', 'slug' => 'valvulas-accesorios', 'parent_id' => 3, 'orden' => 5],
            ['nombre' => 'Ablandadores de Agua', 'slug' => 'ablandadores-agua', 'parent_id' => 3, 'orden' => 6],
            
            // Medición y Control (id: 4)
            ['nombre' => 'Equipos de Medición', 'slug' => 'equipos-medicion', 'parent_id' => 4, 'orden' => 1],
            ['nombre' => 'Kits de Análisis', 'slug' => 'kits-analisis', 'parent_id' => 4, 'orden' => 2],
            ['nombre' => 'Reactivos y Calibración', 'slug' => 'reactivos-calibracion', 'parent_id' => 4, 'orden' => 3],
            
            // Especialidades (id: 5)
            ['nombre' => 'Dispersantes', 'slug' => 'dispersantes', 'parent_id' => 5, 'orden' => 1],
            ['nombre' => 'Biocidas', 'slug' => 'biocidas', 'parent_id' => 5, 'orden' => 2],
            ['nombre' => 'Resinas de Intercambio Iónico', 'slug' => 'resinas-intercambio-ionico', 'parent_id' => 5, 'orden' => 3],
            ['nombre' => 'Medios Filtrantes', 'slug' => 'medios-filtrantes', 'parent_id' => 5, 'orden' => 4],
        ];
        
        $stmt = $pdo->prepare("INSERT INTO categorias (nombre, slug, parent_id, nivel, orden) VALUES (?, ?, ?, 2, ?)");
        foreach ($categoriasNivel2 as $cat) {
            $stmt->execute([$cat['nombre'], $cat['slug'], $cat['parent_id'], $cat['orden']]);
        }
        echo "✓ " . count($categoriasNivel2) . " categorías de nivel 2 insertadas\n";
        
        // ============================================
        // CATEGORÍAS - Nivel 3
        // ============================================
        echo "Insertando categorías de nivel 3...\n";
        
        $categoriasNivel3 = [
            // Coagulantes y Floculantes (id: 6)
            ['nombre' => 'Coagulantes Inorgánicos', 'slug' => 'coagulantes-inorganicos', 'parent_id' => 6, 'orden' => 1],
            ['nombre' => 'Floculantes Aniónicos', 'slug' => 'floculantes-anionicos', 'parent_id' => 6, 'orden' => 2],
            ['nombre' => 'Floculantes Catiónicos', 'slug' => 'floculantes-cationicos', 'parent_id' => 6, 'orden' => 3],
            
            // Desinfección (id: 7)
            ['nombre' => 'Cloro y Derivados', 'slug' => 'cloro-derivados', 'parent_id' => 7, 'orden' => 1],
            ['nombre' => 'Dióxido de Cloro', 'slug' => 'dioxido-cloro', 'parent_id' => 7, 'orden' => 2],
            ['nombre' => 'Peróxidos', 'slug' => 'peroxidos', 'parent_id' => 7, 'orden' => 3],
            ['nombre' => 'UV y Ozono', 'slug' => 'uv-ozono', 'parent_id' => 7, 'orden' => 4],
            
            // Sistemas de Filtración (id: 10)
            ['nombre' => 'Filtros de Sedimento', 'slug' => 'filtros-sedimento', 'parent_id' => 15, 'orden' => 1],
            ['nombre' => 'Filtros de Carbón Activado', 'slug' => 'filtros-carbon-activado', 'parent_id' => 15, 'orden' => 2],
            ['nombre' => 'Filtros Multimedia', 'slug' => 'filtros-multimedia', 'parent_id' => 15, 'orden' => 3],
            ['nombre' => 'Filtros Plisados', 'slug' => 'filtros-plisados', 'parent_id' => 15, 'orden' => 4],
            
            // Sistemas de Ósmosis Inversa (id: 11)
            ['nombre' => 'Membranas RO', 'slug' => 'membranas-ro', 'parent_id' => 16, 'orden' => 1],
            ['nombre' => 'Portamembranas', 'slug' => 'portamembranas', 'parent_id' => 16, 'orden' => 2],
            ['nombre' => 'Bombas de Refuerzo', 'slug' => 'bombas-refuerzo', 'parent_id' => 16, 'orden' => 3],
            
            // Equipos de Medición (id: 21)
            ['nombre' => 'Medidores de pH', 'slug' => 'medidores-ph', 'parent_id' => 21, 'orden' => 1],
            ['nombre' => 'Medidores de Cloro', 'slug' => 'medidores-cloro', 'parent_id' => 21, 'orden' => 2],
            ['nombre' => 'Conductímetros', 'slug' => 'conductimetros', 'parent_id' => 21, 'orden' => 3],
        ];
        
        $stmt = $pdo->prepare("INSERT INTO categorias (nombre, slug, parent_id, nivel, orden) VALUES (?, ?, ?, 3, ?)");
        foreach ($categoriasNivel3 as $cat) {
            $stmt->execute([$cat['nombre'], $cat['slug'], $cat['parent_id'], $cat['orden']]);
        }
        echo "✓ " . count($categoriasNivel3) . " categorías de nivel 3 insertadas\n\n";
        
        // ============================================
        // PRODUCTOS desde imágenes
        // ============================================
        echo "Insertando productos desde imágenes...\n";
        
        $imagenDir = __DIR__ . '/../assets/img/catalogo/';
        $imagenes = glob($imagenDir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        
        if (empty($imagenes)) {
            echo "⚠ No se encontraron imágenes en $imagenDir\n";
            return;
        }
        
        $fabricantes = ['Nacional', 'Nacional', 'Nacional', 'Importado', 'Importado', 'Certificado ISO'];
        $badges = ['', '', '', '', '', '', '', 'Destacado', 'Nuevo'];
        $badgeColors = ['teal', 'blue', 'green', 'purple'];
        
        $coloresGradient = [
            'from-[#1A3A2F] to-[#2d6a4f]',
            'from-[#1a2a4a] to-[#2d4a7a]',
            'from-[#4a3800] to-[#7a5c00]',
            'from-[#0d2137] to-[#1a3a5c]',
            'from-[#1a2a1a] to-[#2d4a2d]',
            'from-[#2a1a2a] to-[#4a2a4a]',
            'from-[#0a1a0a] to-[#1a3a1a]',
            'from-[#1a1a0a] to-[#3a3a1a]',
            'from-[#1a0a0a] to-[#3a1a0a]',
            'from-[#0a1a1a] to-[#1a3a3a]',
        ];
        
        $caracteristicasPool = [
            'Alta efectividad',
            'Bajo residuo',
            'Económico',
            'Acción rápida',
            'Multi-superficie',
            'Certificado',
            'Sin residuos',
            'Fácil dosificación',
            'Rápida sedimentación',
            'Previene calcificación',
            'Larga duración',
            'Fácil aplicación',
            'Concentrado',
            'Biodegradable',
            'Alta espuma',
            'Amplio espectro',
            'No tóxico',
            'Rápida acción prolongada',
            'Pasivante',
            'Para metales ferrosos',
            'Alta eficiencia',
            'Multimetal',
            'Bajo dosis',
            'Alta pureza',
            'Resistente',
            'Instalación fácil',
            'Mantenimiento mínimo',
            'Durabilidad garantizada',
        ];
        
        $stmtProducto = $pdo->prepare("
            INSERT INTO productos (nombre, slug, descripcion, fabricante, imagen, badge, badge_color, color_gradient, orden)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmtCategoria = $pdo->prepare("INSERT INTO producto_categoria (producto_id, categoria_id) VALUES (?, ?)");
        $stmtCaract = $pdo->prepare("INSERT INTO producto_caracteristicas (producto_id, caracteristica, orden) VALUES (?, ?, ?)");
        
        $orden = 0;
        foreach ($imagenes as $imagenPath) {
            $nombreArchivo = basename($imagenPath);
            
            // Saltar el logo
            if (strpos($nombreArchivo, 'logo') !== false) {
                continue;
            }
            
            // Convertir nombre de archivo a título
            $nombre = str_replace(['-', '_'], ' ', pathinfo($nombreArchivo, PATHINFO_FILENAME));
            $nombre = ucwords($nombre);
            
            // Crear slug
            $slug = strtolower(str_replace(' ', '-', $nombre));
            $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
            
            // Descripción genérica
            $descripcion = "Producto químico de alta calidad para tratamiento de agua e industria. " . $nombre . " ofrece resultados consistentes y confiables en aplicaciones industriales y comerciales.";
            
            // Seleccionar atributos aleatorios
            $fabricante = $fabricantes[array_rand($fabricantes)];
            $badge = $badges[array_rand($badges)];
            $badgeColor = $badge ? $badgeColors[array_rand($badgeColors)] : '';
            $colorGradient = $coloresGradient[array_rand($coloresGradient)];
            
            // Ruta relativa de la imagen
            $imagenRelativa = 'assets/img/catalogo/' . $nombreArchivo;
            
            // Insertar producto
            $stmtProducto->execute([
                $nombre,
                $slug,
                $descripcion,
                $fabricante,
                $imagenRelativa,
                $badge,
                $badgeColor,
                $colorGradient,
                $orden++
            ]);
            
            $productoId = (int)$pdo->lastInsertId();
            
            // Asignar 1-3 categorías aleatorias (pero lógicas basadas en el nombre)
            $categoriasIds = [];
            
            // Mapeo inteligente basado en palabras clave
            $nombreLower = strtolower($nombre);
            
            if (strpos($nombreLower, 'cloro') !== false || strpos($nombreLower, 'hipoclorito') !== false || strpos($nombreLower, 'dioxido') !== false) {
                $categoriasIds[] = 7; // Desinfección
            }
            if (strpos($nombreLower, 'ph') !== false || strpos($nombreLower, 'alka') !== false || strpos($nombreLower, 'buffer') !== false) {
                $categoriasIds[] = 8; // Control de pH
            }
            if (strpos($nombreLower, 'detergente') !== false || strpos($nombreLower, 'limpiador') !== false || strpos($nombreLower, 'clean') !== false) {
                $categoriasIds[] = 11; // Detergentes Industriales
            }
            if (strpos($nombreLower, 'filtro') !== false || strpos($nombreLower, 'cartucho') !== false) {
                $categoriasIds[] = 15; // Sistemas de Filtración
            }
            if (strpos($nombreLower, 'membrana') !== false || strpos($nombreLower, 'osmosis') !== false || strpos($nombreLower, 'osmo') !== false || strpos($nombreLower, 'portamembrana') !== false) {
                $categoriasIds[] = 16; // Sistemas de Ósmosis Inversa
            }
            if (strpos($nombreLower, 'bomba') !== false) {
                $categoriasIds[] = 17; // Bombas y Dosificación
            }
            if (strpos($nombreLower, 'tanque') !== false) {
                $categoriasIds[] = 18; // Tanques y Almacenamiento
            }
            if (strpos($nombreLower, 'valvula') !== false || strpos($nombreLower, 'conector') !== false || strpos($nombreLower, 'tobera') !== false || strpos($nombreLower, 'base') !== false || strpos($nombreLower, 'soporte') !== false) {
                $categoriasIds[] = 19; // Válvulas y Accesorios
            }
            if (strpos($nombreLower, 'medidor') !== false || strpos($nombreLower, 'comparador') !== false || strpos($nombreLower, 'instrumento') !== false) {
                $categoriasIds[] = 21; // Equipos de Medición
            }
            if (strpos($nombreLower, 'kit') !== false || strpos($nombreLower, 'test') !== false || strpos($nombreLower, 'analisis') !== false) {
                $categoriasIds[] = 22; // Kits de Análisis
            }
            if (strpos($nombreLower, 'reactivo') !== false || strpos($nombreLower, 'solucion') !== false || strpos($nombreLower, 'pastilla') !== false || strpos($nombreLower, 'tira') !== false || strpos($nombreLower, 'papel') !== false) {
                $categoriasIds[] = 23; // Reactivos y Calibración
            }
            if (strpos($nombreLower, 'carbon') !== false || strpos($nombreLower, 'arena') !== false || strpos($nombreLower, 'antracita') !== false || strpos($nombreLower, 'zeolita') !== false || strpos($nombreLower, 'grava') !== false) {
                $categoriasIds[] = 27; // Medios Filtrantes
            }
            if (strpos($nombreLower, 'resina') !== false) {
                $categoriasIds[] = 26; // Resinas de Intercambio Iónico
            }
            if (strpos($nombreLower, 'ablandador') !== false) {
                $categoriasIds[] = 20; // Ablandadores de Agua
            }
            if (strpos($nombreLower, 'ultravioleta') !== false || strpos($nombreLower, 'ozono') !== false) {
                $categoriasIds[] = 36; // UV y Ozono
            }
            if (strpos($nombreLower, 'antiincrustante') !== false || strpos($nombreLower, 'incrustante') !== false) {
                $categoriasIds[] = 9; // Antiincrustantes
            }
            if (strpos($nombreLower, 'planta') !== false || strpos($nombreLower, 'embotelladora') !== false) {
                $categoriasIds[] = 3; // Equipos y Accesorios (general)
            }
            if (strpos($nombreLower, 'floculante') !== false || strpos($nombreLower, 'coagulante') !== false || strpos($nombreLower, 'sulfato') !== false) {
                $categoriasIds[] = 6; // Coagulantes y Floculantes
            }
            if (strpos($nombreLower, 'abrillantador') !== false || strpos($nombreLower, 'bril') !== false) {
                $categoriasIds[] = 13; // Abrillantadores
            }
            if (strpos($nombreLower, 'peróxido') !== false || strpos($nombreLower, 'peroxido') !== false || strpos($nombreLower, 'bioperoxi') !== false || strpos($nombreLower, 'proxitane') !== false) {
                $categoriasIds[] = 35; // Peróxidos
            }
            
            // Si no se encontró ninguna categoría específica, asignar a categoría general
            if (empty($categoriasIds)) {
                $categoriasIds[] = 1; // Tratamiento de Agua (general)
            }
            
            // Asignar categorías
            $categoriasIds = array_unique($categoriasIds);
            foreach ($categoriasIds as $catId) {
                $stmtCategoria->execute([$productoId, $catId]);
            }
            
            // Asignar 3-5 características aleatorias
            $numCaract = rand(3, 5);
            $caractSeleccionadas = array_rand(array_flip($caracteristicasPool), $numCaract);
            if (!is_array($caractSeleccionadas)) {
                $caractSeleccionadas = [$caractSeleccionadas];
            }
            
            foreach ($caractSeleccionadas as $idx => $caract) {
                $stmtCaract->execute([$productoId, $caract, $idx]);
            }
        }
        
        echo "✓ " . ($orden) . " productos insertados\n";
        
        // ============================================
        // Estadísticas finales
        // ============================================
        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Datos insertados correctamente\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        $stats = [
            'Categorías' => $pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn(),
            'Productos' => $pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn(),
            'Relaciones Producto-Categoría' => $pdo->query("SELECT COUNT(*) FROM producto_categoria")->fetchColumn(),
            'Características' => $pdo->query("SELECT COUNT(*) FROM producto_caracteristicas")->fetchColumn(),
        ];
        
        foreach ($stats as $label => $count) {
            echo sprintf("%-30s: %d\n", $label, $count);
        }
        
        echo "\n✓ Base de datos lista para usar\n";
    });
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
