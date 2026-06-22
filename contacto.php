<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/helpers.php';

$sent = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_contacto'])) {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $tipo     = trim($_POST['tipo'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');
    if ($nombre && $email && filter_var($email, FILTER_VALIDATE_EMAIL) && $mensaje) {
        // Aquí iría mail() o integración SMTP
        $sent = true;
    } else {
        $error = 'Por favor completa todos los campos obligatorios correctamente.';
    }
}

$pageTitle = 'Contacto — PROQUIM Cleaning & Chemical Products';
$metaDescription = 'Contáctanos. Estamos aquí para ayudarte.';
$bodyClass = 'min-h-screen bg-[#f4f8fb] font-sans text-navy antialiased';

require __DIR__ . '/includes/layout/head-site.php';
require __DIR__ . '/includes/layout/header-site.php';
?>


<!-- ══════════════════════════════════════
     HERO CONTACTO
══════════════════════════════════════ -->
<section class="relative overflow-hidden bg-gradient-to-r from-[#00BCD4] to-blue py-14">
  <div class="pointer-events-none absolute inset-0 opacity-10"
       style="background-image:radial-gradient(circle at 20% 50%, white 1px, transparent 1px),radial-gradient(circle at 80% 20%, white 1px, transparent 1px);background-size:40px 40px;"></div>
  <div class="relative mx-auto max-w-7xl px-4 sm:px-6">
    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Contáctanos</h1>
    <p class="mt-3 max-w-xl text-base text-white/80">
      Estamos aquí para ayudarte. Escríbenos y te responderemos a la brevedad posible.
    </p>
  </div>
</section>


<!-- ══════════════════════════════════════
     TARJETAS DE DATOS RÁPIDOS
══════════════════════════════════════ -->
<section class="bg-white py-10 shadow-sm">
  <div class="mx-auto max-w-7xl px-4 sm:px-6">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

      <!-- Teléfono -->
      <div class="flex items-center gap-4 rounded-xl border border-[#DDE8F0] bg-white p-4 shadow-sm">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-[#00BCD4]/10 text-[#00BCD4]">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
        </div>
        <div>
          <p class="text-xs font-semibold uppercase tracking-widest text-navy/40">Teléfono</p>
          <p class="text-sm font-medium text-navy"><?= CONTACT_PHONE ?></p>
          <p class="text-xs text-navy/60"><?= CONTACT_MOBILE ?></p>
        </div>
      </div>

      <!-- Email -->
      <div class="flex items-center gap-4 rounded-xl border border-[#DDE8F0] bg-white p-4 shadow-sm">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-green/10 text-green">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div>
          <p class="text-xs font-semibold uppercase tracking-widest text-navy/40">Email</p>
          <p class="text-sm font-medium text-navy"><?= CONTACT_EMAIL ?></p>
          <p class="text-xs text-navy/60"><?= CONTACT_EMAIL_SALES ?></p>
        </div>
      </div>

      <!-- Ubicación -->
      <div class="flex items-center gap-4 rounded-xl border border-[#DDE8F0] bg-white p-4 shadow-sm">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue/10 text-blue">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
          <p class="text-xs font-semibold uppercase tracking-widest text-navy/40">Ubicación</p>
          <p class="text-sm font-medium text-navy"><?= ADDRESS_STREET ?></p>
          <p class="text-xs text-navy/60"><?= ADDRESS_DISTRICT ?>, <?= ADDRESS_CITY ?>, <?= ADDRESS_COUNTRY ?></p>
        </div>
      </div>

      <!-- Horario -->
      <div class="flex items-center gap-4 rounded-xl border border-[#DDE8F0] bg-white p-4 shadow-sm">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-navy/10 text-navy">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <p class="text-xs font-semibold uppercase tracking-widest text-navy/40">Horario</p>
          <p class="text-sm font-medium text-navy"><?= SCHEDULE_WEEKDAYS ?></p>
          <p class="text-xs text-navy/60"><?= SCHEDULE_SATURDAY ?></p>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     FORMULARIO + MAPA
══════════════════════════════════════ -->
<section class="py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6">
    <div class="grid gap-10 lg:grid-cols-2">

      <!-- Formulario -->
      <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-[#DDE8F0]">
        <h2 class="mb-1 text-2xl font-bold text-navy">Envíanos un Mensaje</h2>
        <p class="mb-6 text-sm text-navy/60">Te responderemos en un máximo de 12 horas.</p>

        <?php if ($sent): ?>
          <div class="mb-6 flex items-center gap-3 rounded-lg bg-green/10 px-4 py-3 text-sm font-medium text-green ring-1 ring-green/30">
            <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            ¡Mensaje enviado! Nos pondremos en contacto pronto.
          </div>
        <?php endif; ?>

        <?php if ($error): ?>
          <div class="mb-6 flex items-center gap-3 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-600 ring-1 ring-red-200">
            <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <form action="contacto.php" method="post" class="space-y-4" novalidate>
          <input type="hidden" name="_contacto" value="1" />

          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="nombre" class="mb-1.5 block text-xs font-semibold text-navy/70">Nombre <span class="text-red-500">*</span></label>
              <input id="nombre" name="nombre" type="text" required placeholder="Juan"
                value="<?= htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                class="form-input" />
            </div>
            <div>
              <label for="apellido" class="mb-1.5 block text-xs font-semibold text-navy/70">Apellido</label>
              <input id="apellido" name="apellido" type="text" placeholder="García"
                value="<?= htmlspecialchars($_POST['apellido'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                class="form-input" />
            </div>
          </div>

          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="email" class="mb-1.5 block text-xs font-semibold text-navy/70">Email <span class="text-red-500">*</span></label>
              <input id="email" name="email" type="email" required placeholder="correo@empresa.com"
                value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                class="form-input" />
            </div>
            <div>
              <label for="telefono" class="mb-1.5 block text-xs font-semibold text-navy/70">Teléfono</label>
              <input id="telefono" name="telefono" type="tel" placeholder="+51 987 654 321"
                value="<?= htmlspecialchars($_POST['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                class="form-input" />
            </div>
          </div>

          <div>
            <label for="tipo" class="mb-1.5 block text-xs font-semibold text-navy/70">Tipo de consulta</label>
            <select id="tipo" name="tipo" class="form-input">
              <option value="">Categoría de consulta…</option>
              <option value="cotizacion" <?= (($_POST['tipo'] ?? '') === 'cotizacion') ? 'selected' : '' ?>>Solicitud de cotización</option>
              <option value="soporte"    <?= (($_POST['tipo'] ?? '') === 'soporte')    ? 'selected' : '' ?>>Soporte técnico</option>
              <option value="informacion"<?= (($_POST['tipo'] ?? '') === 'informacion')? 'selected' : '' ?>>Información de productos</option>
              <option value="otro"       <?= (($_POST['tipo'] ?? '') === 'otro')       ? 'selected' : '' ?>>Otro</option>
            </select>
          </div>

          <div>
            <label for="mensaje" class="mb-1.5 block text-xs font-semibold text-navy/70">Mensaje <span class="text-red-500">*</span></label>
            <textarea id="mensaje" name="mensaje" rows="4" required placeholder="Cuéntanos en qué podemos ayudarte…"
              class="form-input resize-none"><?= htmlspecialchars($_POST['mensaje'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>

          <p class="text-[11px] text-navy/40">
            Puedes escribirnos en cualquier momento. Te responderemos en el horario de atención según la normativa de servicios al cliente.
          </p>

          <button type="submit" class="btn-green w-full justify-center py-3">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Enviar Mensaje
          </button>

          <div class="grid grid-cols-2 gap-2 text-xs text-navy/50">
            <div class="flex items-center gap-2">
              <svg class="h-4 w-4 text-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              ¿Prefiere llamar? <?= CONTACT_PHONE ?>
            </div>
            <div class="flex items-center gap-2">
              <svg class="h-4 w-4 text-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              Respuesta rápida por email
            </div>
          </div>
        </form>
      </div>

      <!-- Panel derecho: mapa + info oficina + horario -->
      <div class="flex flex-col gap-6">
        <h2 class="text-2xl font-bold text-navy">Encuéntranos</h2>
        <p class="text-sm text-navy/60">Visítanos en nuestras oficinas principales o en alguna de nuestras sucursales a lo largo del país.</p>

        <?php $mapEmbedUrl = getMapClassicEmbedURL(); ?>

        <!-- Mapa interactivo — vista de distrito (sin pin rojo) -->
        <div class="overflow-hidden rounded-xl bg-[#e0ecf5] ring-1 ring-[#DDE8F0]">
          <?php if ($mapEmbedUrl): ?>
            <iframe
              src="<?= htmlspecialchars($mapEmbedUrl, ENT_QUOTES, 'UTF-8') ?>"
              class="block h-64 w-full border-0 sm:h-72"
              allowfullscreen
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              title="Mapa de la zona — <?= htmlspecialchars(getMapDistrictLabel(), ENT_QUOTES, 'UTF-8') ?>"
            ></iframe>
          <?php else: ?>
            <div class="flex h-64 items-center justify-center sm:h-72">
              <div class="px-6 text-center">
                <svg class="mx-auto h-10 w-10 text-blue/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="mt-2 text-xs font-medium text-navy/50">Mapa no disponible</p>
                <p class="mt-1 text-[11px] text-navy/40">Configure la ubicación en el archivo .env</p>
              </div>
            </div>
          <?php endif; ?>
          <p class="border-t border-[#DDE8F0] bg-white/60 px-4 py-2 text-center text-[11px] text-navy/50">
            Vista general de <?= htmlspecialchars(getMapDistrictLabel(), ENT_QUOTES, 'UTF-8') ?>
          </p>
        </div>

        <!-- Oficina Principal -->
        <div class="rounded-xl border border-[#DDE8F0] bg-white p-5 shadow-sm">
          <div class="mb-3 flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue/10 text-blue">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h3 class="font-semibold text-navy">Oficina Principal</h3>
          </div>
          <address class="not-italic space-y-1 text-sm text-navy/70">
            <p><?= htmlspecialchars(ADDRESS_STREET, ENT_QUOTES, 'UTF-8') ?></p>
            <p><?= htmlspecialchars(ADDRESS_DISTRICT . ', ' . ADDRESS_CITY, ENT_QUOTES, 'UTF-8') ?></p>
            <p><?= htmlspecialchars(ADDRESS_COUNTRY, ENT_QUOTES, 'UTF-8') ?></p>
          </address>
          <a href="<?= htmlspecialchars(getGoogleMapsPlaceURL(), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer"
             class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-blue hover:underline">
            Ver en mapa
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        </div>

        <!-- Horario -->
        <div class="rounded-xl border border-[#DDE8F0] bg-white p-5 shadow-sm">
          <div class="mb-3 flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-navy/10 text-navy">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-semibold text-navy">Horario de Atención</h3>
          </div>
          <?php
          $horario = [
            ['dias' => 'Lunes – Viernes', 'horas' => '8:00 am – 6:00 pm'],
            ['dias' => 'Sábado',          'horas' => '9:00 am – 1:00 pm'],
            ['dias' => 'Domingo',         'horas' => 'Cerrado'],
          ];
          foreach ($horario as $h): ?>
            <div class="flex items-center justify-between border-b border-[#DDE8F0] py-2 last:border-0">
              <span class="text-sm text-navy/70"><?= $h['dias'] ?></span>
              <span class="text-sm font-medium <?= $h['horas'] === 'Cerrado' ? 'text-red-400' : 'text-navy' ?>">
                <?= $h['horas'] ?>
              </span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ══════════════════════════════════════
     FAQ ACORDEÓN
══════════════════════════════════════ -->
<section class="bg-white py-16">
  <div class="mx-auto max-w-3xl px-4 sm:px-6">
    <div class="mb-10 text-center">
      <h2 class="text-3xl font-bold text-navy">Preguntas Frecuentes</h2>
      <p class="mt-2 text-sm text-navy/60">Encuentra respuesta a las preguntas más comunes.</p>
    </div>

    <div class="space-y-3" id="faq-list">
      <?php
      $faqs = [
        ['q' => '¿Cuál es el tiempo de entrega de los productos?',       'a' => 'Los pedidos en Lima se entregan en 24-48 horas hábiles. Para provincias, el plazo es de 3 a 5 días hábiles dependiendo de la ubicación.'],
        ['q' => '¿Hacen envíos a provincia?',                            'a' => 'Sí, realizamos despachos a nivel nacional a través de operadores logísticos certificados. Contáctanos para conocer tarifas según destino.'],
        ['q' => '¿Cuál es el cantidad mínima de pedido?',                'a' => 'El pedido mínimo varía según el producto. En general, aceptamos pedidos desde 1 unidad, aunque para precios especiales se requieren volúmenes mayores.'],
        ['q' => '¿Realizan análisis de laboratorio?',                    'a' => 'Contamos con laboratorio propio para análisis de calidad. Podemos realizar pruebas a muestras de clientes bajo previa coordinación.'],
        ['q' => '¿Cómo puedo rastrear mi cotización?',                   'a' => 'Una vez enviada tu solicitud, recibirás un número de seguimiento por correo. También puedes contactarnos directamente indicando tu nombre y fecha de solicitud.'],
      ];
      foreach ($faqs as $i => $faq): ?>
        <div class="overflow-hidden rounded-xl border border-[#DDE8F0] bg-[#f4f8fb]" data-faq>
          <button
            type="button"
            class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left text-sm font-semibold text-navy hover:bg-[#eaf3fb] transition"
            aria-expanded="false"
            data-faq-btn
          >
            <span><?= htmlspecialchars($faq['q'], ENT_QUOTES, 'UTF-8') ?></span>
            <svg class="h-5 w-5 shrink-0 text-blue transition-transform duration-200" data-faq-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
          </button>
          <div class="hidden px-5 pb-4 text-sm text-navy/70" data-faq-body>
            <?= htmlspecialchars($faq['a'], ENT_QUOTES, 'UTF-8') ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     REDES SOCIALES
══════════════════════════════════════ -->
<section class="bg-navy py-14 text-center">
  <div class="mx-auto max-w-2xl px-4 sm:px-6">
    <h2 class="text-2xl font-bold text-white">Síguenos en Redes Sociales</h2>
    <p class="mt-2 text-sm text-white/60">Mantente al día con nuestras novedades, promociones y contenido especializado.</p>
    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <?php
      $redes = [
        ['nombre' => 'Facebook',  'href' => '#', 'icon' => 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
        ['nombre' => 'Instagram', 'href' => '#', 'icon' => 'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01M6.5 3.5h11a3 3 0 013 3v11a3 3 0 01-3 3h-11a3 3 0 01-3-3v-11a3 3 0 013-3z'],
        ['nombre' => 'WhatsApp',  'href' => '#', 'icon' => 'M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z'],
      ];
      foreach ($redes as $r): ?>
        <a href="<?= htmlspecialchars($r['href'], ENT_QUOTES, 'UTF-8') ?>"
           aria-label="<?= htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') ?>"
           class="flex h-12 w-12 items-center justify-center rounded-full border border-white/20 text-white/60 transition hover:border-[#00BCD4] hover:bg-[#00BCD4]/20 hover:text-white">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?= $r['icon'] ?>"/>
          </svg>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ -->
<?php require __DIR__ . '/includes/layout/footer-site.php'; ?>


<!-- ══════════════════════════════════════
     SCRIPTS
══════════════════════════════════════ -->
<?php require __DIR__ . '/includes/layout/scripts-site.php'; ?>

<script>
  // FAQ acordeón
  document.querySelectorAll('[data-faq-btn]').forEach(btn => {
    btn.addEventListener('click', () => {
      const faq  = btn.closest('[data-faq]');
      const body = faq.querySelector('[data-faq-body]');
      const icon = faq.querySelector('[data-faq-icon]');
      const open = btn.getAttribute('aria-expanded') === 'true';

      // Cerrar todos los demás
      document.querySelectorAll('[data-faq]').forEach(el => {
        el.querySelector('[data-faq-body]').classList.add('hidden');
        el.querySelector('[data-faq-btn]').setAttribute('aria-expanded', 'false');
        el.querySelector('[data-faq-icon]').style.transform = 'rotate(0deg)';
      });

      if (!open) {
        body.classList.remove('hidden');
        btn.setAttribute('aria-expanded', 'true');
        icon.style.transform = 'rotate(45deg)';
      }
    });
  });
</script>

</body>
</html>
