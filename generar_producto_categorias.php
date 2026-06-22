<?php
declare(strict_types=1);

// Leer productos
$productos = [];
$fp = fopen(__DIR__ . '/productos.csv', 'r');
fgetcsv($fp, 0, ',', '"', '\\'); // Saltar encabezado
while (($row = fgetcsv($fp, 0, ',', '"', '\\')) !== false) {
    $productos[] = $row[0]; // nombre
}
fclose($fp);

// Leer categorías
$categorias = [];
$fp = fopen(__DIR__ . '/categorias.csv', 'r');
fgetcsv($fp, 0, ',', '"', '\\'); // Saltar encabezado
while (($row = fgetcsv($fp, 0, ',', '"', '\\')) !== false) {
    $categorias[] = $row[0]; // nombre
}
fclose($fp);

// Generar relaciones basadas en palabras clave
$relaciones = [];

foreach ($productos as $producto) {
    $nombreLower = strtolower($producto);
    $categoriasAsignadas = [];
    
    // Categoría principal por defecto
    $categoriasAsignadas[] = 'Productos Quimicos';
    
    // DESINFECCIÓN
    if (preg_match('/(cloro|hipoclorito|dioxido|desinfectante|bactericida|sanitizante|saniclean|dioxichlor)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'desinfeccion';
        $categoriasAsignadas[] = 'quimicos para piscinas y spa';
        if (preg_match('/(planta|cisterna|embotelladora)/i', $nombreLower)) {
            $categoriasAsignadas[] = 'desinfección para plantas cisternas y equipos';
        }
    }
    
    // DETERGENTES Y LIMPIEZA
    if (preg_match('/(detergente|deterflash|limpiador|desengrasante|abrillantador|brilt)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'detergentes y desinfectantes';
        $categoriasAsignadas[] = 'detergentes y desengrasantes';
        $categoriasAsignadas[] = 'limpieza';
        $categoriasAsignadas[] = 'quimicos para embotelladoras';
    }
    
    // CONTROL DE PH
    if (preg_match('/(ph|buffer|alka|acidificante)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'regulador de ph';
        $categoriasAsignadas[] = 'quimicos para piscinas y spa';
    }
    
    // OSMOSIS INVERSA
    if (preg_match('/(osmosis|osmo|membrana|portamembrana|antiincrustante)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'osmosis inversa y purificacion';
        $categoriasAsignadas[] = 'limpieza de membranas de osmosis inversa';
        $categoriasAsignadas[] = 'quimicos para pre y tratamiento de agua';
        $categoriasAsignadas[] = 'Equipos de Tratamiento de Agua';
    }
    
    // FILTROS Y SISTEMAS DE FILTRACIÓN
    if (preg_match('/(filtro|filtracion|cartucho|multimedia|carbon activado|arena|zeolita|antracita|grava)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'Equipos de Tratamiento de Agua';
        $categoriasAsignadas[] = 'Accesorios para Tratamiento de Agua Industrial';
    }
    
    // VÁLVULAS Y ACCESORIOS
    if (preg_match('/(valvula|tobera|distribuidor|conector|base|soporte|portacartucho)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'Accesorios para Tratamiento de Agua Industrial';
    }
    
    // BOMBAS
    if (preg_match('/(bomba)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'Equipos de Tratamiento de Agua';
        $categoriasAsignadas[] = 'Accesorios para Tratamiento de Agua Industrial';
    }
    
    // TANQUES
    if (preg_match('/(tanque|almacenamiento)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'Accesorios para Tratamiento de Agua Industrial';
    }
    
    // ABLANDADORES
    if (preg_match('/(ablandador|salmuera|resina)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'Equipos de Tratamiento de Agua';
        $categoriasAsignadas[] = 'quimicos tratamiento interno de agua de caldero';
    }
    
    // INSTRUMENTOS DE MEDICIÓN Y ANÁLISIS
    if (preg_match('/(medidor|kit|prueba|test|comparador|instrumento|reactivo|pastilla|tira|papel indicador|solucion|calibracion)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'Instrumentos de Laboratorio y Reactivos Quimicos';
    }
    
    // CLARIFICACIÓN Y FLOCULANTES
    if (preg_match('/(sulfato|coagulante|floculante|clarifier|polifloc|chemflox)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'clarificacion';
        $categoriasAsignadas[] = 'quimicos para pre y tratamiento de agua';
    }
    
    // CONTROL DE ALGAS
    if (preg_match('/(algisint|algas)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'control de hongos y algas';
        $categoriasAsignadas[] = 'quimicos para piscinas y spa';
    }
    
    // EQUIPOS UV Y OZONO
    if (preg_match('/(ultravioleta|uv|ozono|generador)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'Equipos de Tratamiento de Agua';
        $categoriasAsignadas[] = 'quimicos para pre y tratamiento de agua';
    }
    
    // PLANTAS EMBOTELLADORAS
    if (preg_match('/(planta|embotelladora|botellon)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'quimicos para embotelladoras';
        $categoriasAsignadas[] = 'tratamiento de botellones de agua de mesa';
    }
    
    // PERÓXIDOS
    if (preg_match('/(peroxi|proxitane|peroxidial)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'desinfeccion';
        $categoriasAsignadas[] = 'quimicos para embotelladoras';
    }
    
    // DESINCRUSTANTES
    if (preg_match('/(desincrustante|incrustante)/i', $nombreLower)) {
        $categoriasAsignadas[] = 'quimicos tratamiento interno de agua de caldero';
        $categoriasAsignadas[] = 'limpieza de membranas de osmosis inversa';
    }
    
    // Eliminar duplicados
    $categoriasAsignadas = array_unique($categoriasAsignadas);
    
    // Filtrar categorías que existen
    $categoriasAsignadas = array_filter($categoriasAsignadas, function($cat) use ($categorias) {
        return in_array($cat, $categorias);
    });
    
    // Si no se asignó ninguna categoría, usar la principal
    if (empty($categoriasAsignadas)) {
        $categoriasAsignadas = ['Productos Quimicos'];
    }
    
    // Agregar relaciones
    foreach ($categoriasAsignadas as $categoria) {
        $relaciones[] = [$producto, $categoria];
    }
}

// Escribir archivo CSV
$fp = fopen(__DIR__ . '/producto_categorias.csv', 'w');
fprintf($fp, "\xEF\xBB\xBF"); // UTF-8 BOM
fputcsv($fp, ['producto_nombre', 'categoria_nombre']);
foreach ($relaciones as $rel) {
    fputcsv($fp, $rel);
}
fclose($fp);

echo "✓ Archivo producto_categorias.csv generado\n";
echo "✓ Total de relaciones: " . count($relaciones) . "\n";
echo "✓ Productos procesados: " . count($productos) . "\n";
echo "\nArchivo listo en: " . __DIR__ . "/producto_categorias.csv\n";
