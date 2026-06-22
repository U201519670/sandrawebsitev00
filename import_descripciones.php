<?php
declare(strict_types=1);

require_once __DIR__ . '/database/Database.php';

const EXPORT_FILE = __DIR__ . '/productos-export.csv';
const PRODUCTOS_CSV = __DIR__ . '/productos.csv';
const BACKUP_DB = __DIR__ . '/database/backups/catalogo.db.before-descripciones';
const BACKUP_CSV = __DIR__ . '/backups/productos.csv.before-descripciones';

function cleanDescription(string $html): string
{
    if ($html === '') {
        return '';
    }

    $text = preg_replace('/<\/(?:div|p|li|h[1-6]|tr)>/i', "\n", $html) ?? $html;
    $text = preg_replace('/<br\s*\/?>/i', "\n", $text) ?? $text;
    $text = strip_tags($text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = str_replace("\xc2\xa0", ' ', $text);
    $text = preg_replace('/[ \t]+/', ' ', $text) ?? $text;
    $text = preg_replace('/\n{3,}/', "\n\n", $text) ?? $text;

    return trim($text);
}

function toLowerAscii(string $value): string
{
    $value = trim($value);
    $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

    return strtolower($ascii !== false ? $ascii : $value);
}

function normalizeSlug(string $slug): string
{
    $slug = toLowerAscii($slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? $slug;

    return trim($slug, '-');
}

function normalizeName(string $name): string
{
    $name = toLowerAscii($name);
    $name = preg_replace('/[^a-z0-9\s]+/', ' ', $name) ?? $name;
    $name = preg_replace('/\s+/', ' ', $name) ?? $name;

    return trim($name);
}

function slugTokens(string $slug): array
{
    $parts = array_filter(explode('-', normalizeSlug($slug)), static fn(string $t): bool => strlen($t) >= 3);

    return array_values(array_unique($parts));
}

function keywordStopwords(): array
{
    return [
        'ablandador', 'ablandadores', 'activado', 'agua', 'alcalino', 'antiincrustante', 'automaticas',
        'automaticas', 'base', 'bomba', 'buffer', 'calcio', 'calibracion', 'carbon', 'cartuchos',
        'cloro', 'concentracion', 'conectores', 'continuo', 'desinfectante', 'desincrustante',
        'detergente', 'de', 'envases', 'equipos', 'espuma', 'filtro', 'filtros', 'granulado',
        'industrial', 'instrumentos', 'intercambio', 'ionico', 'limpiador', 'manual', 'manuales',
        'materia', 'medidor', 'membranas', 'multimedia', 'organica', 'osmo', 'osmosis', 'para',
        'pastillas', 'peracetico', 'plasticos', 'plantas', 'porta', 'portamembranas', 'rapidos',
        'reactivas', 'reactivos', 'removedor', 'resinas', 'sistema', 'solucion', 'sodio', 'suave',
        'tanque', 'tanques', 'tipo', 'ultravioleta', 'uso', 'valvulas', 'vidrio',
    ];
}

function isDistinctiveToken(string $token): bool
{
    return strlen($token) >= 5 && !in_array($token, keywordStopwords(), true);
}

function createBackup(string $source, string $destination): void
{
    $dir = dirname($destination);
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException("No se pudo crear el directorio de backup: $dir");
    }

    if (!copy($source, $destination)) {
        throw new RuntimeException("No se pudo crear backup: $destination");
    }
}

function loadExport(string $path): array
{
    if (!file_exists($path)) {
        throw new RuntimeException("No se encontró $path");
    }

    $fp = fopen($path, 'r');
    if ($fp === false) {
        throw new RuntimeException("No se pudo abrir $path");
    }

    $header = fgetcsv($fp, 0, ',', '"', '\\');
    if ($header === false) {
        fclose($fp);
        throw new RuntimeException('El CSV de export está vacío');
    }

    $columns = array_map(static fn(string $col): string => trim($col, " \t\n\r\0\x0B\""), $header);
    $index = array_flip($columns);

    foreach (['nombre', 'urlamigable', 'contenido'] as $required) {
        if (!isset($index[$required])) {
            fclose($fp);
            throw new RuntimeException("Falta la columna '$required' en productos-export.csv");
        }
    }

    $rows = [];
    while (($row = fgetcsv($fp, 0, ',', '"', '\\')) !== false) {
        if (count($row) === 1 && ($row[0] === null || $row[0] === '')) {
            continue;
        }

        $rows[] = [
            'nombre' => $row[$index['nombre']] ?? '',
            'slug' => $row[$index['urlamigable']] ?? '',
            'contenido' => $row[$index['contenido']] ?? '',
        ];
    }

    fclose($fp);

    return $rows;
}

function findMatchByMethod(array $catalog, array $exportRows, array &$usedIndexes, string $method): ?array
{
    $catalogSlug = $catalog['slug'];
    $catalogNormSlug = normalizeSlug($catalogSlug);
    $catalogNormName = normalizeName($catalog['nombre']);

    if ($method === 'exact_slug') {
        foreach ($exportRows as $i => $export) {
            if (isset($usedIndexes[$i])) {
                continue;
            }

            if ($export['slug'] === $catalogSlug) {
                return [
                    'index' => $i,
                    'method' => 'exact_slug',
                    'score' => 100.0,
                    'export' => $export,
                ];
            }
        }

        return null;
    }

    if ($method === 'norm_slug') {
        foreach ($exportRows as $i => $export) {
            if (isset($usedIndexes[$i])) {
                continue;
            }

            if (normalizeSlug($export['slug']) === $catalogNormSlug) {
                return [
                    'index' => $i,
                    'method' => 'norm_slug',
                    'score' => 95.0,
                    'export' => $export,
                ];
            }
        }

        return null;
    }

    if ($method === 'fuzzy_name') {
        $bestName = null;
        foreach ($exportRows as $i => $export) {
            if (isset($usedIndexes[$i])) {
                continue;
            }

            $exportNormName = normalizeName($export['nombre']);
            similar_text($catalogNormName, $exportNormName, $pct);
            if ($pct >= 75 && ($bestName === null || $pct > $bestName['score'])) {
                $bestName = [
                    'index' => $i,
                    'method' => 'fuzzy_name',
                    'score' => round($pct, 1),
                    'export' => $export,
                ];
            }
        }

        return $bestName;
    }

    if ($method === 'fuzzy_slug') {
        $bestSlug = null;
        foreach ($exportRows as $i => $export) {
            if (isset($usedIndexes[$i])) {
                continue;
            }

            $exportNormSlug = normalizeSlug($export['slug']);
            similar_text($catalogNormSlug, $exportNormSlug, $pct);
            if ($pct >= 80 && ($bestSlug === null || $pct > $bestSlug['score'])) {
                $bestSlug = [
                    'index' => $i,
                    'method' => 'fuzzy_slug',
                    'score' => round($pct, 1),
                    'export' => $export,
                ];
            }
        }

        return $bestSlug;
    }

    if ($method !== 'keyword') {
        return null;
    }

    $catalogTokens = slugTokens($catalogSlug);
    $bestKeyword = null;
    foreach ($exportRows as $i => $export) {
        if (isset($usedIndexes[$i])) {
            continue;
        }

        $exportNormSlug = normalizeSlug($export['slug']);
        $exportTokens = slugTokens($export['slug']);

        $matched = false;
        if ($catalogNormSlug !== '' && $exportNormSlug !== '') {
            $shorter = strlen($catalogNormSlug) <= strlen($exportNormSlug) ? $catalogNormSlug : $exportNormSlug;
            $longer = $shorter === $catalogNormSlug ? $exportNormSlug : $catalogNormSlug;
            if (strlen($shorter) >= 12 && str_contains($longer, $shorter)) {
                $matched = true;
            }
        }

        if (!$matched) {
            $shared = array_intersect($catalogTokens, $exportTokens);
            $distinctive = array_filter($shared, static fn(string $t): bool => isDistinctiveToken($t));
            if ($distinctive !== []) {
                $matched = true;
            }
        }

        if (!$matched) {
            foreach ($catalogTokens as $token) {
                if (isDistinctiveToken($token) && str_contains($exportNormSlug, $token)) {
                    $matched = true;
                    break;
                }
            }
        }

        if ($matched) {
            similar_text($catalogNormSlug, $exportNormSlug, $pct);
            if ($bestKeyword === null || $pct > $bestKeyword['score']) {
                $bestKeyword = [
                    'index' => $i,
                    'method' => 'keyword',
                    'score' => round($pct, 1),
                    'export' => $export,
                ];
            }
        }
    }

    return $bestKeyword;
}

function findMatch(array $catalog, array $exportRows, array &$usedIndexes): ?array
{
    foreach (['exact_slug', 'norm_slug', 'fuzzy_name', 'fuzzy_slug', 'keyword'] as $method) {
        $match = findMatchByMethod($catalog, $exportRows, $usedIndexes, $method);
        if ($match !== null) {
            return $match;
        }
    }

    return null;
}

function resolveAllMatches(array $catalogProducts, array $exportRows): array
{
    $usedIndexes = [];
    $assignments = [];

    foreach (['exact_slug', 'norm_slug', 'fuzzy_name', 'fuzzy_slug', 'keyword'] as $method) {
        foreach ($catalogProducts as $product) {
            $productId = (int) $product['id'];
            if (isset($assignments[$productId])) {
                continue;
            }

            $match = findMatchByMethod($product, $exportRows, $usedIndexes, $method);
            if ($match === null) {
                continue;
            }

            $usedIndexes[$match['index']] = true;
            $assignments[$productId] = $match;
        }
    }

    return $assignments;
}

function syncProductosCsv(array $descriptionsBySlug): int
{
    $fp = fopen(PRODUCTOS_CSV, 'r');
    if ($fp === false) {
        throw new RuntimeException('No se pudo abrir productos.csv');
    }

    $rows = [];
    $header = fgetcsv($fp, 0, ',', '"', '\\');
    if ($header === false) {
        fclose($fp);
        throw new RuntimeException('productos.csv está vacío');
    }

    $rows[] = $header;
    $slugIndex = array_search('slug', $header, true);
    $descIndex = array_search('descripcion', $header, true);

    if ($slugIndex === false || $descIndex === false) {
        fclose($fp);
        throw new RuntimeException('productos.csv debe tener columnas slug y descripcion');
    }

    $updated = 0;
    while (($row = fgetcsv($fp, 0, ',', '"', '\\')) !== false) {
        $slug = $row[$slugIndex] ?? '';
        if ($slug !== '' && isset($descriptionsBySlug[$slug])) {
            $row[$descIndex] = $descriptionsBySlug[$slug];
            $updated++;
        }
        $rows[] = $row;
    }
    fclose($fp);

    $out = fopen(PRODUCTOS_CSV, 'w');
    if ($out === false) {
        throw new RuntimeException('No se pudo escribir productos.csv');
    }

    foreach ($rows as $row) {
        fputcsv($out, $row, ',', '"', '\\');
    }
    fclose($out);

    return $updated;
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Importar descripciones desde productos-export.csv\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

try {
    echo "💾 Creando backups...\n";
    createBackup(__DIR__ . '/database/catalogo.db', BACKUP_DB);
    createBackup(PRODUCTOS_CSV, BACKUP_CSV);
    echo "   ✓ " . BACKUP_DB . "\n";
    echo "   ✓ " . BACKUP_CSV . "\n\n";

    $exportRows = loadExport(EXPORT_FILE);
    echo '📥 Export cargado: ' . count($exportRows) . " productos\n";

    $db = Database::getInstance();
    $pdo = $db->getPDO();
    $stmtSelect = $pdo->query('SELECT id, nombre, slug, descripcion FROM productos ORDER BY orden DESC, id ASC');
    $catalogProducts = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
    echo '📦 Catálogo actual: ' . count($catalogProducts) . " productos\n\n";

    $exactMatches = [];
    $fuzzyMatches = [];
    $noMatches = [];
    $descriptionsBySlug = [];

    $stmtUpdate = $pdo->prepare('UPDATE productos SET descripcion = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');

    $assignments = resolveAllMatches($catalogProducts, $exportRows);
    $usedIndexes = [];
    foreach ($assignments as $match) {
        $usedIndexes[$match['index']] = true;
    }

    $pdo->beginTransaction();

    foreach ($catalogProducts as $product) {
        $productId = (int) $product['id'];
        $match = $assignments[$productId] ?? null;

        if ($match === null) {
            $noMatches[] = [
                'id' => (int) $product['id'],
                'nombre' => $product['nombre'],
                'slug' => $product['slug'],
            ];
            continue;
        }

        $description = cleanDescription($match['export']['contenido']);

        if ($description === '') {
            $noMatches[] = [
                'id' => (int) $product['id'],
                'nombre' => $product['nombre'],
                'slug' => $product['slug'],
                'note' => 'Match encontrado pero contenido vacío',
            ];
            continue;
        }

        $stmtUpdate->execute([$description, (int) $product['id']]);
        $descriptionsBySlug[$product['slug']] = $description;

        $entry = [
            'catalog' => $product['nombre'] . ' (' . $product['slug'] . ')',
            'export' => $match['export']['nombre'] . ' (' . $match['export']['slug'] . ')',
            'method' => $match['method'],
            'score' => $match['score'],
        ];

        if ($match['method'] === 'exact_slug') {
            $exactMatches[] = $entry;
        } else {
            $fuzzyMatches[] = $entry;
        }
    }

    $pdo->commit();

    $csvUpdated = syncProductosCsv($descriptionsBySlug);

    $unusedExport = [];
    foreach ($exportRows as $i => $export) {
        if (!isset($usedIndexes[$i])) {
            $unusedExport[] = $export['nombre'] . ' (' . $export['slug'] . ')';
        }
    }

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "REPORTE DE IMPORTACIÓN\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

    echo '✅ Slug exacto: ' . count($exactMatches) . "\n";
    foreach ($exactMatches as $item) {
        echo "   • {$item['catalog']} ← {$item['export']}\n";
    }

    echo "\n🔍 Fuzzy / keyword: " . count($fuzzyMatches) . "\n";
    foreach ($fuzzyMatches as $item) {
        echo "   • [{$item['method']} {$item['score']}%] {$item['catalog']} ← {$item['export']}\n";
    }

    echo "\n⚠️  Sin match: " . count($noMatches) . "\n";
    foreach ($noMatches as $item) {
        $note = isset($item['note']) ? ' — ' . $item['note'] : '';
        echo "   • {$item['nombre']} ({$item['slug']}){$note}\n";
    }

    echo "\n📤 Export no usados: " . count($unusedExport) . "\n";
    foreach ($unusedExport as $item) {
        echo "   • $item\n";
    }

    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo 'Descripciones actualizadas en SQLite: ' . count($descriptionsBySlug) . "\n";
    echo "Filas actualizadas en productos.csv: $csvUpdated\n";
    echo "✓ Importación completada\n";
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    fwrite(STDERR, '❌ ERROR: ' . $e->getMessage() . "\n");
    exit(1);
}
