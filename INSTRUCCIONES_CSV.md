# 📋 Guía de Actualización de Base de Datos con CSV

## ✅ Archivos Exportados

Se han creado 4 archivos CSV con todos los datos actuales de tu base de datos:

1. **categorias.csv** (44 categorías)
2. **productos.csv** (79 productos)
3. **producto_categorias.csv** (105 relaciones)
4. **producto_caracteristicas.csv** (307 características)

---

## 📝 Cómo Editar en Excel

### 1. Abrir los archivos

Los archivos tienen codificación UTF-8 con BOM para que Excel los abra correctamente:

- Doble clic en cada archivo CSV
- Se abrirán en Excel automáticamente
- Si no se ven correctamente:
  - Abre Excel → Datos → Obtener datos externos → Desde texto
  - Selecciona el archivo CSV
  - Codificación: UTF-8

### 2. Estructura de cada archivo

#### 📂 categorias.csv

| Columna | Descripción | Ejemplo | Notas |
|---------|-------------|---------|-------|
| nombre | Nombre de la categoría | "Tratamiento de Agua" | Requerido |
| slug | URL-friendly | "tratamiento-de-agua" | Único |
| parent_nombre | Categoría padre | "Tratamiento de Agua" | Vacío si nivel 1 |
| nivel | Nivel jerárquico | 1, 2 o 3 | Requerido |
| orden | Orden de aparición | 0, 1, 2... | Para ordenar |
| activo | Activo/Inactivo | 1 o 0 | 1 = activo |

**Importante:**
- Las categorías de nivel 1 NO tienen `parent_nombre`
- Las categorías de nivel 2 y 3 SÍ necesitan `parent_nombre`
- No se permiten más de 3 niveles

#### 📦 productos.csv

| Columna | Descripción | Ejemplo | Notas |
|---------|-------------|---------|-------|
| nombre | Nombre del producto | "Coagulante PAC-10" | Requerido |
| slug | URL-friendly | "coagulante-pac-10" | Único |
| descripcion | Descripción completa | "Solución de..." | Requerido |
| fabricante | Fabricante | Nacional/Importado/Certificado ISO | Requerido |
| imagen | Ruta a imagen | assets/img/catalogo/pac10.webp | Requerido |
| badge | Etiqueta | Destacado/Nuevo/vacío | Opcional |
| badge_color | Color del badge | blue/teal/green/purple/vacío | Opcional |
| color_gradient | Gradiente Tailwind | from-[#1A3A2F] to-[#2d6a4f] | Opcional |
| activo | Activo/Inactivo | 1 o 0 | 1 = activo |
| orden | Orden de aparición | 0, 1, 2... | Para ordenar |

**Valores permitidos:**

**fabricante:**
- Nacional
- Importado
- Certificado ISO

**badge_color:**
- blue
- teal
- green
- purple
- (vacío = sin badge)

**color_gradient** (ejemplos):
```
from-[#1A3A2F] to-[#2d6a4f]   (verde)
from-[#1a2a4a] to-[#2d4a7a]   (azul)
from-[#4a3800] to-[#7a5c00]   (café)
from-[#0d2137] to-[#1a3a5c]   (azul oscuro)
```

#### 🔗 producto_categorias.csv

| Columna | Descripción | Ejemplo |
|---------|-------------|---------|
| producto_nombre | Nombre del producto | "Coagulante PAC-10" |
| categoria_nombre | Nombre de la categoría | "Tratamiento de Agua" |

**Importante:**
- Un producto puede tener múltiples categorías
- Agrega una fila por cada relación producto-categoría

#### ⭐ producto_caracteristicas.csv

| Columna | Descripción | Ejemplo |
|---------|-------------|---------|
| producto_nombre | Nombre del producto | "Coagulante PAC-10" |
| caracteristica | Texto de la característica | "Alta efectividad" |
| orden | Orden de aparición | 0, 1, 2... |

**Importante:**
- Un producto puede tener 3-5 características
- El `orden` determina en qué posición aparece (0 = primera)

---

## 🔄 Proceso de Actualización

### Paso 1: Editar en Excel

1. Abre cada archivo CSV en Excel
2. Edita los datos:
   - ✅ Actualiza nombres reales de productos
   - ✅ Actualiza descripciones
   - ✅ Verifica que las imágenes existan en `assets/img/catalogo/`
   - ✅ Asigna categorías correctas
   - ✅ Agrega características relevantes
3. **NO cambies los encabezados de las columnas**
4. Guarda los archivos (mantén formato CSV)

### Paso 2: Importar a la Base de Datos

Una vez que hayas terminado de editar:

```bash
php import_from_csv.php
```

Este script:
1. ✅ Limpia la BD actual
2. ✅ Importa categorías (respetando jerarquía)
3. ✅ Importa productos
4. ✅ Crea relaciones producto-categoría
5. ✅ Importa características
6. ✅ Muestra estadísticas finales

### Paso 3: Verificar

Abre el catálogo en tu navegador:
```
http://localhost/productos.php
```

---

## ⚠️ Consejos Importantes

### ✅ Hacer

- ✅ Mantén los nombres de productos únicos
- ✅ Mantén los slugs únicos (sin espacios, minúsculas, guiones)
- ✅ Verifica que las rutas de imágenes sean correctas
- ✅ Usa fabricantes válidos: Nacional, Importado, Certificado ISO
- ✅ Respeta la jerarquía de categorías (nivel 1, 2, 3)
- ✅ Haz backup de los archivos CSV antes de importar

### ❌ Evitar

- ❌ NO cambies los encabezados de las columnas
- ❌ NO uses nombres duplicados de productos
- ❌ NO uses slugs duplicados
- ❌ NO pongas categorías de nivel 4 o más
- ❌ NO uses caracteres especiales en slugs
- ❌ NO olvides guardar en formato CSV (no Excel .xlsx)

---

## 🔧 Solución de Problemas

### Problema: Excel no abre correctamente los acentos

**Solución:**
1. Abre Excel
2. Ve a Datos → Obtener datos → Desde texto/CSV
3. Selecciona el archivo
4. En "Origen del archivo" selecciona "65001: Unicode (UTF-8)"
5. Click en "Cargar"

### Problema: Error al importar "categoría padre no encontrada"

**Solución:**
- Asegúrate de que el `parent_nombre` coincida exactamente con el `nombre` de una categoría existente
- Las categorías se importan en orden: primero nivel 1, luego 2, luego 3

### Problema: Error "slug duplicado"

**Solución:**
- Revisa que no haya dos productos o categorías con el mismo slug
- Los slugs deben ser únicos en todo el sistema

---

## 📊 Ejemplo Completo

### categorias.csv
```csv
nombre,slug,parent_nombre,nivel,orden,activo
"Tratamiento de Agua","tratamiento-de-agua",,1,1,1
"Coagulantes","coagulantes","Tratamiento de Agua",2,1,1
"Sulfato de Aluminio","sulfato-aluminio","Coagulantes",3,1,1
```

### productos.csv
```csv
nombre,slug,descripcion,fabricante,imagen,badge,badge_color,color_gradient,activo,orden
"Sulfato de Aluminio Tipo A","sulfato-aluminio-a","Coagulante de alta pureza...","Nacional","assets/img/catalogo/sulfato.webp","Destacado","teal","from-[#1A3A2F] to-[#2d6a4f]",1,1
```

### producto_categorias.csv
```csv
producto_nombre,categoria_nombre
"Sulfato de Aluminio Tipo A","Tratamiento de Agua"
"Sulfato de Aluminio Tipo A","Coagulantes"
"Sulfato de Aluminio Tipo A","Sulfato de Aluminio"
```

### producto_caracteristicas.csv
```csv
producto_nombre,caracteristica,orden
"Sulfato de Aluminio Tipo A","Alta pureza",0
"Sulfato de Aluminio Tipo A","Soluble en agua",1
"Sulfato de Aluminio Tipo A","Económico",2
```

---

## 🎯 Siguiente Paso

Una vez que termines de editar los archivos:

1. Guárdalos en formato CSV
2. Ejecuta: `php import_from_csv.php`
3. Verifica en: `http://localhost/productos.php`

¿Necesitas ayuda con algo? ¡Pregúntame!
