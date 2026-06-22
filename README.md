# Sitio Sandra (PHP + Tailwind)

## Desarrollo (en tu PC)

1. Instalar dependencias: `npm install`
2. Generar CSS: `npm run build:css`
3. Opcional mientras maquetas: `npm run watch:css` (recompila al guardar)

El hosting **no necesita Node.js**. Solo sube el resultado del build.

## Despliegue en public_html (cPanel)

Sube al servidor:

- Todos los `.php` de la raíz y la carpeta `includes/`
- `assets/` completa (**incluye** `assets/css/app.css` ya generado)
- `.htaccess`

**No subas** (opcional, solo para desarrollo): `node_modules/`, `src/`, `package.json`, `tailwind.config.js`, `postcss.config.js`.

Si cambias estilos o clases Tailwind en PHP, vuelve a ejecutar `npm run build:css` antes de subir `assets/css/app.css`.

## Subcarpeta en el dominio

Si el sitio no está en la raíz, edita `includes/bootstrap.php` y define `BASE_URL` (por ejemplo `'/micarpeta'`) **antes** del `if (!defined('BASE_URL'))` o ajusta la constante según tu caso.
