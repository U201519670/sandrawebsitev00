# Sistema de Configuración con .env

## Implementación Completada ✓

Se ha implementado exitosamente un sistema centralizado de configuración usando archivos `.env` para gestionar todos los datos variables del sitio web.

---

## Archivos Creados

### 1. **config/config.php**
Cargador de configuración que lee el archivo `.env` y crea constantes PHP globales.

### 2. **.env**
Archivo principal de configuración con todas las variables del sistema:
- Información de la empresa
- Datos de contacto (teléfono, email, dirección)
- WhatsApp y mensajes predefinidos
- Redes sociales
- Google Ads/Analytics
- Configuración de base de datos
- SMTP para emails
- Modo de desarrollo/producción

### 3. **.env.example**
Plantilla pública (se sube a Git) con la estructura del `.env` pero sin datos sensibles.

### 4. **includes/helpers.php**
Funciones auxiliares para uso común:
- `getWhatsAppURL()` - Genera URLs de WhatsApp
- `getWhatsAppQuoteURL()` - URL para cotizar productos
- `isDebugMode()` - Verifica modo debug
- `isProduction()` - Verifica ambiente de producción
- Y más...

### 5. **.gitignore**
Actualizado para ignorar archivos sensibles (.env, logs, backups, etc.)

---

## Archivos Modificados

✅ **includes/layout/header-site.php** - Usa variables para teléfono y email  
✅ **includes/layout/footer-site.php** - Usa variables para contacto, redes sociales, y dirección  
✅ **contacto.php** - Usa variables para todos los datos de contacto  
✅ **database/Database.php** - Usa `DB_PATH` del .env  

---

## Cómo Usar

### Para Desarrollo Local

1. **El archivo `.env` ya está configurado** con datos genéricos de ejemplo
2. **Abre tu navegador** en `http://localhost:8080`
3. Todo debería funcionar automáticamente

### Para Actualizar con Datos Reales

Edita el archivo `.env` y cambia los valores:

```ini
# Ejemplo: Actualizar teléfono
CONTACT_PHONE="+51 (01) 555-1234"  # ← Cambiar este valor

# Ejemplo: Actualizar email
CONTACT_EMAIL="contacto@tuempresa.com"  # ← Cambiar este valor
```

**Guarda el archivo** y recarga la página. Los cambios aparecerán automáticamente.

---

## Variables Disponibles

### Información de la Empresa
```
APP_NAME                - Nombre de la empresa
APP_LEGAL_NAME         - Nombre legal completo
APP_TAGLINE           - Eslogan o descripción corta
APP_URL               - URL del sitio web
```

### Contacto
```
CONTACT_PHONE         - Teléfono principal
CONTACT_MOBILE        - Teléfono móvil
CONTACT_EMAIL         - Email principal
CONTACT_EMAIL_SALES   - Email de ventas
CONTACT_EMAIL_SUPPORT - Email de soporte
```

### WhatsApp
```
WHATSAPP_NUMBER            - Número sin + ni espacios (51987654321)
WHATSAPP_MESSAGE_DEFAULT   - Mensaje por defecto
WHATSAPP_MESSAGE_PRODUCT   - Mensaje para productos
WHATSAPP_MESSAGE_QUOTE     - Mensaje para cotizaciones
```

### Dirección
```
ADDRESS_STREET     - Calle y número
ADDRESS_DISTRICT   - Distrito
ADDRESS_CITY       - Ciudad
ADDRESS_COUNTRY    - País
ADDRESS_FULL       - Dirección completa
```

### Horarios
```
SCHEDULE_WEEKDAYS  - Horario de lunes a viernes
SCHEDULE_SATURDAY  - Horario de sábado
SCHEDULE_SUNDAY    - Horario de domingo
```

### Redes Sociales
```
SOCIAL_FACEBOOK    - URL de Facebook
SOCIAL_INSTAGRAM   - URL de Instagram
SOCIAL_LINKEDIN    - URL de LinkedIn
SOCIAL_WHATSAPP    - URL de WhatsApp Web
```

### Google Ads & Analytics
```
GOOGLE_ADS_ID                - ID de cuenta de Google Ads
GOOGLE_ADS_CONVERSION_LABEL  - Label de conversión
GOOGLE_ANALYTICS_ID          - ID de Google Analytics
```

### Base de Datos
```
DB_PATH            - Ruta a la base de datos SQLite
DB_BACKUP_PATH     - Ruta para backups
```

### SMTP (Email)
```
SMTP_HOST          - Servidor SMTP
SMTP_PORT          - Puerto SMTP
SMTP_USERNAME      - Usuario SMTP
SMTP_PASSWORD      - Contraseña SMTP
SMTP_FROM_NAME     - Nombre del remitente
SMTP_FROM_EMAIL    - Email del remitente
```

### Desarrollo
```
APP_DEBUG  - "true" o "false"
APP_ENV    - "development" o "production"
```

---

## Usar Variables en PHP

### En cualquier archivo PHP:

```php
<?php
// 1. Cargar configuración
require_once __DIR__ . '/config/config.php';

// 2. Usar constantes
echo CONTACT_PHONE;      // +51 (01) 234-5678
echo CONTACT_EMAIL;      // info@proquim.com.pe
echo APP_NAME;           // PROQUIM Cleaning & Chemical Products

// 3. Usar funciones helper
require_once __DIR__ . '/includes/helpers.php';

$whatsappURL = getWhatsAppURL('Hola, necesito información');
echo $whatsappURL;
```

### En archivos HTML/PHP mixtos:

```php
<h1><?= APP_NAME ?></h1>
<p>Llámanos al <?= CONTACT_PHONE ?></p>
<a href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a>
```

---

## Seguridad

### ⚠️ IMPORTANTE

1. **NUNCA subas `.env` a Git** - Ya está en `.gitignore`
2. **SÍ sube `.env.example`** - Es la plantilla sin datos sensibles
3. **Cambia las contraseñas** en producción
4. **Configura `APP_ENV="production"`** en el servidor real

### Cambiar a Producción

En el servidor de producción, edita `.env`:

```ini
# Modo de Desarrollo
APP_DEBUG="false"
APP_ENV="production"
```

Esto deshabilitará mensajes de error detallados y habilitará optimizaciones.

---

## Estructura de Archivos

```
Sandra_v0/
├── .env                          ← Configuración (NO subir a Git)
├── .env.example                  ← Plantilla (SÍ subir a Git)
├── .gitignore                    ← Ignora .env y archivos sensibles
├── config/
│   └── config.php                ← Cargador de variables
├── includes/
│   ├── helpers.php               ← Funciones auxiliares
│   └── layout/
│       ├── header-site.php       ← Actualizado con variables
│       └── footer-site.php       ← Actualizado con variables
├── database/
│   ├── Database.php              ← Actualizado con DB_PATH
│   └── catalogo.db
├── contacto.php                  ← Actualizado con variables
└── [otros archivos...]
```

---

## Pruebas Realizadas

✅ Sintaxis PHP verificada en todos los archivos  
✅ Configuración carga correctamente  
✅ Variables disponibles como constantes  
✅ Database conecta usando `DB_PATH`  
✅ Funciones helper funcionan  
✅ Sin errores de linter  

---

## Próximos Pasos Recomendados

1. **Actualizar `.env` con datos reales** de PROQUIM
2. **Probar en navegador** todas las páginas del sitio
3. **Verificar emails y teléfonos** en header y footer
4. **Configurar Google Ads** con IDs reales
5. **Configurar SMTP** para formulario de contacto
6. **Antes de deployar a producción**, cambiar `APP_ENV` a `"production"`

---

## Soporte

Si tienes dudas sobre alguna variable o necesitas agregar nuevas configuraciones:

1. Edita `.env` y `.env.example`
2. Opcionalmente, agrega una función helper en `includes/helpers.php`
3. Usa la variable en tus archivos PHP con la constante definida

---

**Implementación completada: Todos los TODOs del plan fueron ejecutados exitosamente.**

Fecha de implementación: Mayo 24, 2026
