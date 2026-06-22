<?php

declare(strict_types=1);

/**
 * Clase Database - Singleton para manejo de SQLite
 * Maneja conexión PDO y queries para el catálogo de productos
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;
    private string $dbPath;

    private function __construct(?string $dbPath = null)
    {
        // Cargar configuración si existe
        if (file_exists(__DIR__ . '/../config/config.php')) {
            require_once __DIR__ . '/../config/config.php';
        }
        
        // Usar dbPath del .env si está definido
        $this->dbPath = $dbPath ?? (
            defined('DB_PATH') 
                ? __DIR__ . '/../' . DB_PATH 
                : __DIR__ . '/catalogo.db'
        );
        $this->connect();
    }

    public static function getInstance(?string $dbPath = null): Database
    {
        if (self::$instance === null) {
            self::$instance = new self($dbPath);
        }
        return self::$instance;
    }

    private function connect(): void
    {
        try {
            $this->pdo = new PDO('sqlite:' . $this->dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Habilitar foreign keys
            $this->pdo->exec('PRAGMA foreign_keys = ON');
            // Habilitar WAL mode para mejor concurrencia
            $this->pdo->exec('PRAGMA journal_mode = WAL');
        } catch (PDOException $e) {
            throw new RuntimeException('Error conectando a la base de datos: ' . $e->getMessage());
        }
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    /**
     * Obtiene productos con filtros y paginación
     * 
     * @param array $filters Filtros: ['categorias' => [], 'fabricantes' => [], 'busqueda' => '', 'ordenar' => '']
     * @param array $pagination Paginación: ['pagina' => 1, 'por_pagina' => 9]
     * @return array ['productos' => [], 'total' => int]
     */
    public function getProductos(array $filters = [], array $pagination = []): array
    {
        $categorias = $filters['categorias'] ?? [];
        $fabricantes = $filters['fabricantes'] ?? [];
        $busqueda = $filters['busqueda'] ?? '';
        $ordenar = $filters['ordenar'] ?? '';
        
        $pagina = $pagination['pagina'] ?? 1;
        $porPagina = $pagination['por_pagina'] ?? 9;
        $offset = ($pagina - 1) * $porPagina;

        // Query base - primero solo IDs para contar
        $sqlBase = "
            SELECT p.id
            FROM productos p
            WHERE p.activo = 1
        ";

        $params = [];

        // Filtro por categorías
        if (!empty($categorias)) {
            $placeholders = str_repeat('?,', count($categorias) - 1) . '?';
            $sqlBase .= " AND EXISTS (
                SELECT 1 FROM producto_categoria pc2
                JOIN categorias c2 ON pc2.categoria_id = c2.id
                WHERE pc2.producto_id = p.id AND c2.nombre IN ($placeholders)
            )";
            $params = array_merge($params, $categorias);
        }

        // Filtro por fabricantes
        if (!empty($fabricantes)) {
            $placeholders = str_repeat('?,', count($fabricantes) - 1) . '?';
            $sqlBase .= " AND p.fabricante IN ($placeholders)";
            $params = array_merge($params, $fabricantes);
        }

        // Filtro por búsqueda
        if (!empty($busqueda)) {
            $sqlBase .= " AND (p.nombre LIKE ? OR p.descripcion LIKE ?)";
            $searchTerm = '%' . $busqueda . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Contar total
        $countSql = "SELECT COUNT(*) as total FROM (" . $sqlBase . ")";
        $countStmt = $this->pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetch()['total'];

        // Ordenamiento
        switch ($ordenar) {
            case 'az':
                $sqlBase .= " ORDER BY p.nombre ASC";
                break;
            case 'za':
                $sqlBase .= " ORDER BY p.nombre DESC";
                break;
            default:
                $sqlBase .= " ORDER BY p.orden DESC, p.id DESC";
                break;
        }

        // Aplicar paginación
        $sqlBase .= " LIMIT ? OFFSET ?";
        $params[] = $porPagina;
        $params[] = $offset;

        // Obtener IDs de productos
        $stmt = $this->pdo->prepare($sqlBase);
        $stmt->execute($params);
        $productIds = array_column($stmt->fetchAll(), 'id');

        if (empty($productIds)) {
            return [
                'productos' => [],
                'total' => 0
            ];
        }

        // Ahora obtener datos completos de esos productos
        $placeholders = str_repeat('?,', count($productIds) - 1) . '?';
        $sqlProductos = "
            SELECT 
                p.id,
                p.nombre,
                p.slug,
                p.descripcion,
                p.fabricante,
                p.imagen,
                p.badge,
                p.badge_color,
                p.color_gradient,
                p.orden,
                GROUP_CONCAT(c.nombre, '|||') as categorias
            FROM productos p
            LEFT JOIN producto_categoria pc ON p.id = pc.producto_id
            LEFT JOIN categorias c ON pc.categoria_id = c.id
            WHERE p.id IN ($placeholders)
            GROUP BY p.id
        ";

        // Mantener el orden original
        switch ($ordenar) {
            case 'az':
                $sqlProductos .= " ORDER BY p.nombre ASC";
                break;
            case 'za':
                $sqlProductos .= " ORDER BY p.nombre DESC";
                break;
            default:
                $sqlProductos .= " ORDER BY p.orden DESC, p.id DESC";
                break;
        }

        $stmt = $this->pdo->prepare($sqlProductos);
        $stmt->execute($productIds);
        $productos = $stmt->fetchAll();

        // Obtener características para cada producto
        foreach ($productos as &$producto) {
            $producto['features'] = $this->getCaracteristicasProducto((int)$producto['id']);
            // Convertir categorias de string separado por ||| a array
            if ($producto['categorias']) {
                $producto['categorias'] = explode('|||', $producto['categorias']);
                $producto['categoria'] = $producto['categorias'][0]; // Primera categoría para compatibilidad
            } else {
                $producto['categorias'] = [];
                $producto['categoria'] = '';
            }
        }

        return [
            'productos' => $productos,
            'total' => $total
        ];
    }

    /**
     * Obtiene todas las categorías con jerarquía
     * 
     * @param int|null $parentId ID del padre (null para raíz)
     * @return array
     */
    public function getCategorias(?int $parentId = null): array
    {
        $sql = "
            SELECT id, nombre, slug, parent_id, nivel, orden
            FROM categorias
            WHERE activo = 1 AND " . ($parentId === null ? "parent_id IS NULL" : "parent_id = ?") . "
            ORDER BY orden ASC, nombre ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        if ($parentId !== null) {
            $stmt->execute([$parentId]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    /**
     * Obtiene todas las categorías de nivel 1 (raíz)
     * 
     * @return array
     */
    public function getCategoriasRaiz(): array
    {
        return $this->getCategorias(null);
    }

    /**
     * Obtiene una lista plana de todas las categorías con conteo de productos
     * 
     * @return array
     */
    public function getCategoriasConConteo(): array
    {
        $sql = "
            SELECT 
                c.id,
                c.nombre,
                c.slug,
                c.parent_id,
                c.nivel,
                COUNT(DISTINCT pc.producto_id) as total_productos
            FROM categorias c
            LEFT JOIN producto_categoria pc ON c.id = pc.categoria_id
            LEFT JOIN productos p ON pc.producto_id = p.id AND p.activo = 1
            WHERE c.activo = 1
            GROUP BY c.id
            ORDER BY c.nivel ASC, c.orden ASC, c.nombre ASC
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Productos activos únicos por categoría, incluyendo todas sus subcategorías.
     *
     * @return array<int, int> category_id => total
     */
    public function getConteosArbolCategorias(): array
    {
        $categorias = $this->pdo->query("SELECT id, parent_id FROM categorias WHERE activo = 1")->fetchAll();
        $childrenByParent = [];
        foreach ($categorias as $cat) {
            if (!empty($cat['parent_id'])) {
                $childrenByParent[(int)$cat['parent_id']][] = (int)$cat['id'];
            }
        }

        $pairs = $this->pdo->query("
            SELECT pc.categoria_id, pc.producto_id
            FROM producto_categoria pc
            INNER JOIN productos p ON p.id = pc.producto_id AND p.activo = 1
        ")->fetchAll(PDO::FETCH_ASSOC);

        $productosPorCategoria = [];
        foreach ($pairs as $pair) {
            $productosPorCategoria[(int)$pair['categoria_id']][(int)$pair['producto_id']] = true;
        }

        $collectDescendants = function (int $id) use (&$collectDescendants, $childrenByParent): array {
            $ids = [$id];
            foreach ($childrenByParent[$id] ?? [] as $childId) {
                $ids = array_merge($ids, $collectDescendants($childId));
            }
            return $ids;
        };

        $counts = [];
        foreach ($categorias as $cat) {
            $id = (int)$cat['id'];
            $unique = [];
            foreach ($collectDescendants($id) as $categoryId) {
                foreach ($productosPorCategoria[$categoryId] ?? [] as $productId => $_) {
                    $unique[$productId] = true;
                }
            }
            $counts[$id] = count($unique);
        }

        return $counts;
    }

    /**
     * Obtiene un producto por ID con toda su información
     * 
     * @param int $id
     * @return array|null
     */
    public function getProductoById(int $id): ?array
    {
        $sql = "
            SELECT 
                p.id,
                p.nombre,
                p.slug,
                p.descripcion,
                p.fabricante,
                p.imagen,
                p.badge,
                p.badge_color,
                p.color_gradient,
                p.orden,
                GROUP_CONCAT(DISTINCT c.nombre, '|||') as categorias
            FROM productos p
            LEFT JOIN producto_categoria pc ON p.id = pc.producto_id
            LEFT JOIN categorias c ON pc.categoria_id = c.id
            WHERE p.id = ? AND p.activo = 1
            GROUP BY p.id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $producto = $stmt->fetch();

        if (!$producto) {
            return null;
        }

        // Obtener características
        $producto['features'] = $this->getCaracteristicasProducto($id);
        
        // Convertir categorias
        if ($producto['categorias']) {
            $producto['categorias'] = explode('|||', $producto['categorias']);
            $producto['categoria'] = $producto['categorias'][0];
        } else {
            $producto['categorias'] = [];
            $producto['categoria'] = '';
        }

        return $producto;
    }

    /**
     * Obtiene las características de un producto
     * 
     * @param int $productoId
     * @return array
     */
    public function getCaracteristicasProducto(int $productoId): array
    {
        $sql = "
            SELECT caracteristica
            FROM producto_caracteristicas
            WHERE producto_id = ?
            ORDER BY orden ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$productoId]);
        
        return array_column($stmt->fetchAll(), 'caracteristica');
    }

    /**
     * Obtiene la lista de fabricantes únicos
     * 
     * @return array
     */
    public function getFabricantes(): array
    {
        $sql = "
            SELECT DISTINCT fabricante
            FROM productos
            WHERE activo = 1 AND fabricante IS NOT NULL AND fabricante != ''
            ORDER BY fabricante ASC
        ";

        $stmt = $this->pdo->query($sql);
        return array_column($stmt->fetchAll(), 'fabricante');
    }

    /**
     * Busca productos por término
     * 
     * @param string $query
     * @param int $limit
     * @return array
     */
    public function searchProductos(string $query, int $limit = 20): array
    {
        $sql = "
            SELECT 
                p.id,
                p.nombre,
                p.descripcion,
                p.imagen
            FROM productos p
            WHERE p.activo = 1 
            AND (p.nombre LIKE ? OR p.descripcion LIKE ?)
            ORDER BY 
                CASE 
                    WHEN p.nombre LIKE ? THEN 1
                    ELSE 2
                END,
                p.nombre ASC
            LIMIT ?
        ";

        $searchTerm = '%' . $query . '%';
        $exactStart = $query . '%';
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $exactStart, $limit]);
        
        return $stmt->fetchAll();
    }

    /**
     * Ejecuta una transacción
     * 
     * @param callable $callback
     * @return mixed
     * @throws Exception
     */
    public function transaction(callable $callback)
    {
        $this->pdo->beginTransaction();
        try {
            $result = $callback($this->pdo);
            $this->pdo->commit();
            return $result;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Cierra la conexión
     */
    public function close(): void
    {
        $this->pdo = null;
    }

    // Evitar clonación
    private function __clone() {}
    
    // Evitar deserialización
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
