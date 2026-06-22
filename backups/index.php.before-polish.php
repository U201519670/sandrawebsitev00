<?php
declare(strict_types=1);

$pageTitle = 'PROQUIM — Cleaning & Chemical Products';
$metaDescription = 'Soluciones químicas de vanguardia para la industria global. Más de 25 años ofreciendo productos especializados.';
$includeLucide = true;

require __DIR__ . '/includes/layout/head-site.php';
require __DIR__ . '/includes/layout/header-site.php';
?>


<!-- ═══════════════════════════════════════
     HERO — Split layout (texto contenido + media edge-to-edge)
════════════════════════════════════════ -->
<section class="relative overflow-hidden bg-ice">
  <!-- Fondo degradado decorativo (lado izquierdo) -->
  <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-ice via-white/60 to-[#d4ebf8] lg:w-1/2"></div>

  <div class="relative grid min-h-0 lg:grid-cols-2 lg:min-h-[520px] xl:min-h-[580px]">

    <!-- Columna izquierda — Texto -->
    <div class="relative z-10 flex items-center px-4 py-14 sm:px-6 sm:py-16 lg:px-10 lg:py-20 xl:px-16">
      <div class="mx-auto w-full max-w-xl text-center md:max-w-2xl lg:mx-0 lg:max-w-lg lg:text-left xl:max-w-xl">
        <span class="mb-4 inline-block rounded-full bg-blue/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-blue">
          TRATAMIENTO DE AGUA · PERÚ
        </span>
        <h1 class="text-4xl font-extrabold leading-tight tracking-tight text-navy sm:text-5xl lg:text-5xl xl:text-6xl">
          Soluciones Quimicas<br />
          <span class="text-blue">e Industriales de</span><br />
          <span class="text-green">Alta Calidad</span>
        </h1>
        <p class="mt-5 text-base leading-relaxed text-navy/70 sm:text-lg">
          Innovación en productos no tóxicos para el tratamiento de agua y mantenimiento industrial. Garantizamos eficiencia, calidad y respeto por el medio ambiente en todo el país.
        </p>
        <div class="mt-8 flex flex-wrap justify-center gap-3 lg:justify-start">
          <a href="productos.php" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            Ver Productos
          </a>
          <a href="nosotros.php" class="btn-outline !text-navy !border-navy/30 hover:!bg-navy/5">
            Saber más
          </a>
        </div>
      </div>
    </div>

    <!-- Columna derecha — Video a ancho completo del panel -->
    <div class="relative min-h-[260px] sm:min-h-[320px] lg:min-h-full">
      <!-- Degradado de transición hacia el texto (solo desktop) -->
      <div class="pointer-events-none absolute inset-y-0 left-0 z-10 hidden w-24 bg-gradient-to-r from-ice/90 to-transparent lg:block"></div>

      <video
        src="assets/img/sections/Inicio/slider-home/Hero_20260426_VIDEO.mp4"
        autoplay
        muted
        loop
        playsinline
        class="absolute inset-0 h-full w-full object-cover lg:rounded-none"
        style="display: block;"
      ></video>
    </div>

  </div>
</section>


<!-- ═══════════════════════════════════════
     STATS
════════════════════════════════════════ -->
<section class="bg-white py-12">
  <div class="mx-auto max-w-7xl px-4 sm:px-6">
    <dl class="grid grid-cols-2 gap-4 sm:grid-cols-4">
      <?php
      $stats = [
        ['value' => '25+',   'label' => 'Años de experiencia',    'icon' => 'trophy'],
        ['value' => '500+',  'label' => 'Clientes satisfechos',   'icon' => 'handshake'],
        ['value' => '1000+', 'label' => 'Productos activos',      'icon' => 'flask-conical'],
        ['value' => '98%',   'label' => 'Tasa de satisfacción',   'icon' => 'star'],
      ];
      foreach ($stats as $s): ?>
        <div class="group flex flex-col items-center gap-3 rounded-2xl border border-[#DDE8F0] bg-gradient-to-br from-ice/80 to-white px-4 py-8 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue/50 hover:shadow-lg">
          <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-blue/10 to-green/10 transition-all duration-300 group-hover:scale-110 group-hover:from-blue/20 group-hover:to-green/20">
            <i data-lucide="<?= $s['icon'] ?>" class="h-7 w-7 text-blue"></i>
          </div>
          <dt class="text-3xl font-extrabold text-blue transition-all duration-300 group-hover:scale-105"><?= $s['value'] ?></dt>
          <dd class="text-xs font-medium text-navy/60 transition-colors group-hover:text-navy/80"><?= $s['label'] ?></dd>
        </div>
      <?php endforeach; ?>
    </dl>
  </div>
</section>


<!-- ═══════════════════════════════════════
     CATEGORÍAS DESTACADAS
════════════════════════════════════════ -->
<section class="bg-[#f8fbff] py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6">
    <div class="mb-10 text-center">
      <h2 class="text-3xl font-bold tracking-tight text-navy">Suministros Químicos Especializados</h2>
      <p class="mt-3 text-navy/60">Una línea completa para solucionar los problemas de limpieza e higiene industrial</p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php
      $categories = [
        [
          'title' => 'Instrumentos de medición y reactivos químicos',
          'desc'  => 'Equipos de precisión y reactivos para análisis, control de calidad y procesos industriales.',
          'image' => 'assets\img\sections\Inicio\instrumentos-y-reactivos.png',
          'icon'  => 'microscope',
          'badge' => 'Precision',
        ],
        [
          'title' => 'Materiales y accesorios para tratamiento de agua',
          'desc'  => 'Soluciones completas para purificación, filtración y tratamiento de aguas residuales e industriales.',
          'image' => 'assets\img\sections\Inicio\materiales-y-tratamiento.png',
          'icon'  => 'droplets',
          'badge' => 'Industrial',
        ],
        [
          'title' => 'Productos químicos biodegradables',
          'desc'  => 'Línea eco-friendly sin comprometer el efecto de limpieza. Uso seguro y responsable con el medioambiente.',
          'image' => 'assets\img\sections\Inicio\productos-biodegradables.png',
          'icon'  => 'leaf',
          'badge' => 'Eco-Friendly',
        ],
      ];
      foreach ($categories as $cat): ?>
        <article class="group relative flex flex-col overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
          <div class="relative h-64 overflow-hidden">
            <img
              src="<?= htmlspecialchars($cat['image'], ENT_QUOTES, 'UTF-8') ?>"
              alt="<?= htmlspecialchars($cat['title'], ENT_QUOTES, 'UTF-8') ?>"
              class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
              loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-br from-navy/80 via-navy/60 to-blue/40 transition-opacity duration-500 group-hover:opacity-90"></div>
            <div class="absolute left-4 top-4 rounded-full bg-green/90 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white backdrop-blur-sm">
              <?= htmlspecialchars($cat['badge'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="absolute right-4 top-4 flex h-14 w-14 items-center justify-center rounded-full bg-white/95 shadow-lg transition-all duration-500 backdrop-blur-sm group-hover:scale-110 group-hover:shadow-xl">
              <i data-lucide="<?= htmlspecialchars($cat['icon'], ENT_QUOTES, 'UTF-8') ?>" class="h-7 w-7 text-blue"></i>
            </div>
          </div>
          <div class="flex flex-1 flex-col gap-3 bg-white p-6">
            <h3 class="text-lg font-bold leading-snug text-navy transition-colors duration-300 group-hover:text-blue">
              <?= htmlspecialchars($cat['title'], ENT_QUOTES, 'UTF-8') ?>
            </h3>
            <p class="flex-1 text-sm leading-relaxed text-navy/70"><?= htmlspecialchars($cat['desc'], ENT_QUOTES, 'UTF-8') ?></p>
            <div class="flex items-center justify-between border-t border-[#DDE8F0] pt-4">
              <a href="productos.php" class="inline-flex items-center gap-2 text-sm font-bold text-blue transition-all hover:gap-3">
                Ver catálogo
                <svg class="h-4 w-4 transition-transform hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="mt-10 text-center">
      <a href="productos.php" class="btn-primary">
        Explorar Productos Completo
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════
     MARCAS / PARTNERS — Infinite Marquee
════════════════════════════════════════ -->
<?php
$marcas = [
  ['nombre' => 'Calgon Carbon',      'img' => 'assets/img/sections/Inicio/marcas/calgon-carbon.jpg'],
  ['nombre' => 'Hach',               'img' => 'assets/img/sections/Inicio/marcas/hach.jpg'],
  ['nombre' => 'Hanna Instruments',  'img' => 'assets/img/sections/Inicio/marcas/hanna-instruments.jpg'],
  ['nombre' => 'Hidrotek',           'img' => 'assets/img/sections/Inicio/marcas/hidrotek.jpg'],
  ['nombre' => 'Hydronix',           'img' => 'assets/img/sections/Inicio/marcas/hydronix.jpg'],
  ['nombre' => 'La Motte',           'img' => 'assets/img/sections/Inicio/marcas/la-motte.jpg'],
  ['nombre' => 'Lewatit',            'img' => 'assets/img/sections/Inicio/marcas/lewatit.jpg'],
  ['nombre' => 'Pentair',            'img' => 'assets/img/sections/Inicio/marcas/pentair.jpg'],
  ['nombre' => 'Toray',              'img' => 'assets/img/sections/Inicio/marcas/toray.jpg'],
  ['nombre' => 'Tru Water',          'img' => 'assets/img/sections/Inicio/marcas/tru-water.jpg'],
  ['nombre' => 'Viqua',              'img' => 'assets/img/sections/Inicio/marcas/viqua.jpg'],
];
/* Duplicamos para lograr el loop infinito visual */
$marcas_loop = array_merge($marcas, $marcas);
?>

<section class="overflow-hidden border-y border-[#DDE8F0] bg-white py-12">

  <!-- Título -->
  <div class="mb-8 text-center">
    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-navy/30">Marcas con las que trabajamos</p>
  </div>

  <!-- Máscaras de desvanecimiento lateral (CSS) -->
  <style>
    .marquee-track {
      display: flex;
      width: max-content;
      animation: marquee-left 28s linear infinite;
    }
    .marquee-track.reverse {
      animation: marquee-right 28s linear infinite;
    }
    .marquee-wrapper:hover .marquee-track { animation-play-state: paused; }

    @keyframes marquee-left {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    @keyframes marquee-right {
      0%   { transform: translateX(-50%); }
      100% { transform: translateX(0); }
    }

    /* Fade lateral */
    .marquee-fade {
      -webkit-mask-image: linear-gradient(
        to right,
        transparent 0%,
        black 10%,
        black 90%,
        transparent 100%
      );
      mask-image: linear-gradient(
        to right,
        transparent 0%,
        black 10%,
        black 90%,
        transparent 100%
      );
    }
  </style>

  <!-- Fila 1 — izquierda -->
  <div class="marquee-wrapper marquee-fade mb-4 overflow-hidden">
    <div class="marquee-track">
      <?php foreach ($marcas_loop as $m): ?>
        <div class="mx-4 flex h-20 w-44 shrink-0 items-center justify-center rounded-xl border border-[#DDE8F0] bg-white px-5 shadow-sm transition duration-300 hover:border-[#00BCD4] hover:shadow-md">
          <img
            src="<?= htmlspecialchars($m['img'], ENT_QUOTES, 'UTF-8') ?>"
            alt="<?= htmlspecialchars($m['nombre'], ENT_QUOTES, 'UTF-8') ?>"
            class="max-h-12 w-auto max-w-[120px] object-contain grayscale transition duration-300 hover:grayscale-0"
            loading="lazy"
          />
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Fila 2 — derecha (sentido inverso) -->
  <div class="marquee-wrapper marquee-fade overflow-hidden">
    <div class="marquee-track reverse">
      <?php
      /* Segunda fila: rotamos el array para que no queden alineados -->*/
      $marcas_loop2 = array_merge(
        array_slice($marcas, 4),
        array_slice($marcas, 0, 4),
        array_slice($marcas, 4),
        array_slice($marcas, 0, 4)
      );
      foreach ($marcas_loop2 as $m): ?>
        <div class="mx-4 flex h-20 w-44 shrink-0 items-center justify-center rounded-xl border border-[#DDE8F0] bg-white px-5 shadow-sm transition duration-300 hover:border-[#1F8FD6] hover:shadow-md">
          <img
            src="<?= htmlspecialchars($m['img'], ENT_QUOTES, 'UTF-8') ?>"
            alt="<?= htmlspecialchars($m['nombre'], ENT_QUOTES, 'UTF-8') ?>"
            class="max-h-12 w-auto max-w-[120px] object-contain grayscale transition duration-300 hover:grayscale-0"
            loading="lazy"
          />
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</section>


<!-- ═══════════════════════════════════════
     CTA OSCURO
════════════════════════════════════════ -->
<section class="bg-navy py-20">
  <div class="mx-auto flex max-w-7xl flex-col items-center gap-10 px-4 sm:px-6 lg:flex-row lg:gap-16">

    <!-- Texto CTA -->
    <div class="flex-1 text-center lg:text-left">
      <h2 class="text-3xl font-bold text-white sm:text-4xl">
        ¿Listo para optimizar sus<br class="hidden sm:block" /> procesos industriales?
      </h2>
      <ul class="mt-6 space-y-3 text-sm text-white/70">
        <li class="flex items-start gap-2">
          <svg class="mt-0.5 h-4 w-4 shrink-0 text-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          Asesores especializados sin compromiso
        </li>
        <li class="flex items-start gap-2">
          <svg class="mt-0.5 h-4 w-4 shrink-0 text-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          Productos certificados con garantía de origen
        </li>
        <li class="flex items-start gap-2">
          <svg class="mt-0.5 h-4 w-4 shrink-0 text-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          Despacho nacional con entrega rápida
        </li>
        <li class="flex items-start gap-2">
          <svg class="mt-0.5 h-4 w-4 shrink-0 text-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          Soporte técnico 24/7 para emergencias
        </li>
      </ul>
      <div class="mt-8 flex flex-wrap justify-center gap-3 lg:justify-start">
        <a href="contacto.php" class="btn-green">Solicitar Cotización</a>
        <a href="productos.php" class="btn-outline">Ver Productos</a>
      </div>
    </div>

    <!-- Formulario contacto rápido -->
    <div class="w-full max-w-md rounded-2xl bg-white/10 p-6 ring-1 ring-white/20 backdrop-blur lg:flex-shrink-0">
      <h3 class="mb-4 text-lg font-semibold text-white">Inicie su Consulta</h3>
      <form action="contacto.php" method="post" class="space-y-3">
        <input
          type="text" name="nombre" placeholder="Nombre completo" required
          class="w-full rounded-md border border-white/20 bg-white/10 px-3 py-2.5 text-sm text-white placeholder-white/40 focus:border-blue focus:outline-none focus:ring-1 focus:ring-blue"
        />
        <input
          type="email" name="email" placeholder="Correo electrónico" required
          class="w-full rounded-md border border-white/20 bg-white/10 px-3 py-2.5 text-sm text-white placeholder-white/40 focus:border-blue focus:outline-none focus:ring-1 focus:ring-blue"
        />
        <input
          type="text" name="empresa" placeholder="Empresa (opcional)"
          class="w-full rounded-md border border-white/20 bg-white/10 px-3 py-2.5 text-sm text-white placeholder-white/40 focus:border-blue focus:outline-none focus:ring-1 focus:ring-blue"
        />
        <textarea
          name="mensaje" rows="3" placeholder="¿En qué podemos ayudarte?" required
          class="w-full resize-none rounded-md border border-white/20 bg-white/10 px-3 py-2.5 text-sm text-white placeholder-white/40 focus:border-blue focus:outline-none focus:ring-1 focus:ring-blue"
        ></textarea>
        <button type="submit" class="btn-green w-full justify-center">
          Enviar Solicitud
        </button>
      </form>
    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════
     FOOTER
════════════════════════════════════════ -->
<?php require __DIR__ . '/includes/layout/footer-site.php'; ?>


<!-- ═══════════════════════════════════════
     SCRIPTS
════════════════════════════════════════ -->
<?php require __DIR__ . '/includes/layout/scripts-site.php'; ?>

</body>
</html>
