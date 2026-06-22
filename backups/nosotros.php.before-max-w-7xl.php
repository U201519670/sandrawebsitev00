<?php
declare(strict_types=1);

$pageTitle = 'Nosotros — PROQUIM Cleaning & Chemical Products';
$metaDescription = 'Más de 25 años liderando la industria química en Perú con productos de calidad y servicio excepcional.';
$includeLordicon = true;
$bodyClass = 'min-h-screen bg-[#f4f8fb] font-sans text-navy antialiased';

require __DIR__ . '/includes/layout/head-site.php';
require __DIR__ . '/includes/layout/header-site.php';
?>


<!-- ══════════════════════════════════════
     HERO — "Sobre Nosotros"
══════════════════════════════════════ -->
<section class="relative overflow-hidden bg-gradient-to-r from-teal to-blue py-14">
  <div class="pointer-events-none absolute inset-0 opacity-10"
       style="background-image:radial-gradient(circle at 20% 50%, white 1px, transparent 1px),radial-gradient(circle at 80% 20%, white 1px, transparent 1px);background-size:40px 40px;"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Sobre Nosotros</h1>
    <p class="mt-3 max-w-xl text-base text-white/80">
      Más de 25 años liderando la industria química en Perú con productos de calidad y servicio excepcional.
    </p>
  </div>
</section>


<!-- ══════════════════════════════════════
     NUESTRA HISTORIA + TIMELINE + GALERÍA
══════════════════════════════════════ -->
<section class="bg-white py-20">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    <div class="grid gap-14 lg:grid-cols-2 lg:gap-16">

      <!-- Texto + timeline -->
      <div>
        <h2 class="mb-5 text-3xl font-bold text-navy">Nuestra Historia</h2>

        <p class="mb-4 text-sm leading-relaxed text-navy/70">
          Somos una empresa privada, dedicada a la venta y distribución de materiales para los sistemas de tratamiento de agua de uso industrial, institucional y residencial.
        </p>
        <p class="mb-4 text-sm leading-relaxed text-navy/70">
          Asimismo, contamos con soluciones reactivas para análisis, así como investigación y desarrollo de productos químicos biodegradables y no tóxicos para el tratamiento de agua, limpieza, desinfección y mantenimiento de toda planta industrial.
        </p>
        <p class="mb-8 text-sm leading-relaxed text-navy/70">
          La permanente comunicación que mantenemos con nuestros clientes, nos permite proporcionarles información técnica oportuna y asesoramiento constante.
        </p>

        <!-- Timeline -->
        <div class="space-y-5">
          <?php
          $timeline = [
            ['year' => '1999', 'title' => 'Fundación',         'desc' => 'Inicio de operaciones en Lima, Perú.'],
            ['year' => '2005', 'title' => 'Expansión',          'desc' => 'Apertura de planta de producción.'],
            ['year' => '2015', 'title' => 'Certificación ISO',  'desc' => 'Obtención de certificaciones internacionales.'],
            ['year' => '2020', 'title' => 'Líder Nacional',     'desc' => 'Más de 500 clientes en todo el país.'],
          ];
          foreach ($timeline as $t): ?>
            <div class="flex items-start gap-4">
              <!-- Badge año -->
              <div class="flex h-12 w-16 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-teal to-blue text-xs font-extrabold text-white shadow-sm">
                <?= $t['year'] ?>
              </div>
              <!-- Contenido -->
              <div class="pt-1">
                <p class="text-sm font-semibold text-navy"><?= htmlspecialchars($t['title'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="text-xs text-navy/60"><?= htmlspecialchars($t['desc'], ENT_QUOTES, 'UTF-8') ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Galería 3 imágenes -->
      <div class="flex flex-col gap-4">

        <!-- Imagen principal grande: equipo -->
        <div class="group overflow-hidden rounded-2xl shadow-lg ring-1 ring-navy/10 transition-all duration-500 hover:shadow-2xl hover:ring-navy/20">
          <img
            src="assets\img\sections\Nosotros\nosotros-main.png"
            alt="Equipo PROQUIM en reunión de planificación estratégica"
            class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-105"
            loading="eager"
          />
        </div>

        <!-- Dos imágenes pequeñas en fila -->
        <div class="grid grid-cols-2 gap-4">

          <!-- Planta industrial -->
          <div class="group overflow-hidden rounded-xl shadow-md ring-1 ring-navy/10 transition-all duration-500 hover:shadow-xl hover:ring-navy/20">
            <img
              src="assets\img\sections\Nosotros\nosotros-team.png"
              alt="Planta de producción PROQUIM"
              class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-105"
              loading="lazy"
            />
          </div>

          <!-- Laboratorio / reactivos -->
          <div class="group overflow-hidden rounded-xl shadow-md ring-1 ring-navy/10 transition-all duration-500 hover:shadow-xl hover:ring-navy/20">
            <img
              src="assets\img\sections\Nosotros\nosotros-warehouse.png"
              alt="Laboratorio de control de calidad PROQUIM"
              class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-105"
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     MISIÓN Y VISIÓN
══════════════════════════════════════ -->
<section class="bg-[#f4f8fb] py-16">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    <div class="grid gap-6 md:grid-cols-2">

      <!-- Misión -->
      <div class="group rounded-2xl border border-[#DDE8F0] bg-white p-8 shadow-sm ring-1 ring-[#DDE8F0] transition-all duration-300 hover:shadow-md">
        <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-teal/10 to-blue/10 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg">
          <lord-icon
            src="https://cdn.lordicon.com/xfftupfv.json"
            trigger="hover"
            colors="primary:#00BCD4,secondary:#1F8FD6"
            style="width:48px;height:48px">
          </lord-icon>
        </div>
        <h2 class="mb-3 text-2xl font-bold text-navy">Misión</h2>
        <p class="text-sm leading-relaxed text-navy/70">
          Ser empresa líder en el mercado, en la prestación de bienes en un entorno de crecimiento rentable y sostenido. Ser socio tecnológico de nuestros clientes en y para encontrar soluciones en tratamiento de agua industrial. Ser una empresa en continuo crecimiento en el mercado, abarcando todo el Perú.
        </p>
      </div>

      <!-- Visión -->
      <div class="group rounded-2xl border border-green/30 bg-white p-8 shadow-sm ring-1 ring-green/20 transition-all duration-300 hover:border-green/50 hover:shadow-md">
        <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-green/10 to-teal/10 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg">
          <lord-icon
            src="https://cdn.lordicon.com/slduhdil.json"
            trigger="hover"
            colors="primary:#7DC242,secondary:#00BCD4"
            style="width:48px;height:48px">
          </lord-icon>
        </div>
        <h2 class="mb-3 text-2xl font-bold text-navy">Visión</h2>
        <p class="text-sm leading-relaxed text-navy/70">
          Garantizar soluciones tecnológicas integrales en tratamiento de agua, suministrando productos y materiales de calidad garantizada y en forma oportuna. Brindar soporte técnico a nuestros clientes, proporcionándoles con nuestros productos, la mejor solución responsable, honesta, rápida, constante y efectiva. Encontrar en conjunto con el cliente la mejor alternativa para sus necesidades, considerando tecnología, calidad, eficiencia y economía.
        </p>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     NUESTROS VALORES
══════════════════════════════════════ -->
<section class="bg-white py-16">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    <div class="mb-10 text-center">
      <h2 class="text-3xl font-bold text-navy">Nuestros Valores</h2>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <?php
      $valores = [
        [
          'nombre' => 'Calidad',
          'desc'   => 'Productos y servicios que cumplen los más altos estándares internacionales de calidad.',
          'icon'   => '<circle cx="12" cy="12" r="10" stroke-width="1.5"/><circle cx="12" cy="12" r="6" stroke-width="1.5"/><circle cx="12" cy="12" r="2" stroke-width="1.5"/>',
          'color'  => 'from-teal/10 to-blue/10 text-teal',
        ],
        [
          'nombre' => 'Compromiso',
          'desc'   => 'Dedicación total a la satisfacción de nuestros clientes y el éxito de sus proyectos.',
          'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
          'color'  => 'from-blue/10 to-teal/10 text-blue',
        ],
        [
          'nombre' => 'Sostenibilidad',
          'desc'   => 'Responsabilidad ambiental en todos nuestros procesos y soluciones químicas.',
          'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
          'color'  => 'from-green/10 to-teal/10 text-green',
        ],
        [
          'nombre' => 'Innovación',
          'desc'   => 'Búsqueda constante de nuevas tecnologías y mejoras en nuestros productos.',
          'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
          'color'  => 'from-teal/10 to-navy/10 text-teal',
        ],
        [
          'nombre' => 'Excelencia',
          'desc'   => 'Superación continua en cada aspecto de nuestro servicio y operación.',
          'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4"/>',
          'color'  => 'from-blue/10 to-navy/10 text-blue',
        ],
        [
          'nombre' => 'Integridad',
          'desc'   => 'Honestidad y transparencia en todas nuestras relaciones comerciales.',
          'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
          'color'  => 'from-green/10 to-blue/10 text-green',
        ],
      ];
      foreach ($valores as $v): ?>
        <div class="valor-card">
          <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br <?= $v['color'] ?>">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <?= $v['icon'] ?>
            </svg>
          </div>
          <h3 class="mb-2 font-semibold text-navy"><?= htmlspecialchars($v['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
          <p class="text-sm text-navy/60"><?= htmlspecialchars($v['desc'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     CTA FINAL
══════════════════════════════════════ -->
<section class="bg-gradient-to-r from-navy to-[#243f60] py-16 text-center">
  <div class="mx-auto max-w-2xl px-4 sm:px-6">
    <h2 class="text-3xl font-bold text-white">¿Quieres ser parte de nuestra historia?</h2>
    <p class="mt-3 text-sm text-white/70">
      Contáctanos hoy y descubre cómo nuestras soluciones pueden transformar tus procesos industriales.
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <a href="contacto.php" class="btn-green">Contáctanos</a>
      <a href="productos.php" class="btn-outline">Ver Productos</a>
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

</body>
</html>
