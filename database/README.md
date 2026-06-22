# Base de Datos SQLite - Catálogo de Productos PROQUIM

## Implementación Completada

### Archivos Creados

1. **database/Database.php** - Clase Singleton para manejo de SQLite
   - Conexión PDO con manejo de errores
   - Métodos para consultas de productos con filtros y paginación
   - Métodos para categorías jerárquicas
   - Método para búsqueda full-text
   - Soporte para transacciones

2. **database/init_database.php** - Script de inicialización
   - Crea 4 tablas: categorias, productos, producto_categoria, producto_caracteristicas
   - Crea índices optimizados
   - Habilita WAL mode para mejor concurrencia
   - Verifica integridad de la BD

3. **database/seed_data.php** - Script para poblar datos
   - 44 categorías en jerarquía de 3 niveles
   - 79 productos basados en imágenes del directorio
   - 105 relaciones producto-categoría
   - 307 características de productos
   - Asignación inteligente de categorías basada en nombres

4. **database/catalogo.db** - Archivo SQLite (122 KB)
   - Base de datos poblada y lista para usar

### Estructura de Base de Datos

#### Tabla: categorias
- Jerarquía de hasta 3 niveles
- 44 categorías totales (5 nivel 1, 22 nivel 2, 17 nivel 3)

#### Tabla: productos
- 79 productos con información completa
- Imágenes mapeadas desde assets/img/catalogo/
- Fabricantes: Nacional, Importado, Certificado ISO
- Badges: Destacado, Nuevo

#### Tabla: producto_categoria
- Relación muchos a muchos
- Permite productos en múltiples categorías

#### Tabla: producto_caracteristicas
- Características/features de cada producto
- 3-5 características por producto

### Cambios en productos.php

- Eliminado array hardcodeado de productos
- Integrada clase Database para consultas
- Mantenida toda la funcionalidad existente:
  - Filtros por categoría y fabricante
  - Búsqueda por texto
  - Paginación (9 items por página)
  - Ordenamiento (A-Z, Z-A, recientes)
  - Vista grid/lista
  - Modal de detalle

### Configuración de PHP

- Habilitadas extensiones: pdo_sqlite, sqlite3
- Archivo php.ini creado en C:\php\

### Tests Realizados

✓ Conexión a base de datos
✓ Consulta de productos (79 totales)
✓ Filtros por categoría (funcional)
✓ Filtros por fabricante (funcional)
✓ Búsqueda por texto (funcional)
✓ Paginación (funcional)
✓ Sintaxis PHP correcta

## Próximos Pasos (Opcional)

1. Panel de administración para gestionar productos y categorías
2. Optimización de imágenes (actualmente apuntan a archivos locales)
3. Sistema de caché para queries frecuentes
4. API REST para consumo externo
5. Backup automático de la base de datos

## Uso

### Agregar productos manualmente

```php
require_once 'database/Database.php';
$db = Database::getInstance();
$pdo = $db->getPDO();

$pdo->exec("INSERT INTO productos (nombre, slug, descripcion, fabricante, imagen, color_gradient) 
            VALUES ('Producto Nuevo', 'producto-nuevo', 'Descripción...', 'Nacional', 'path/img.jpg', 'from-blue-500 to-blue-700')");
```

### Consultar productos

```php
$resultado = $db->getProductos(
    ['categorias' => ['Desinfección'], 'busqueda' => 'cloro'],
    ['pagina' => 1, 'por_pagina' => 10]
);

echo "Total: " . $resultado['total'];
foreach ($resultado['productos'] as $producto) {
    echo $producto['nombre'];
}
```

## Mantenimiento

- **Backup**: Copiar archivo database/catalogo.db
- **Reset**: Ejecutar `php database/init_database.php --force` (borra y recrea)
- **Re-poblar**: Ejecutar `php database/seed_data.php`
