<?php 
declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
?>
<footer class="bg-[#111f30] text-white/70">
  <div class="mx-auto max-w-6xl px-4 pt-12 pb-6 sm:px-6">

    <!-- Grid principal: logo | enlaces | productos | contacto -->
    <div class="grid grid-cols-2 gap-x-8 gap-y-10 sm:grid-cols-4">

      <!-- Columna 1 — Marca -->
      <div class="col-span-2 sm:col-span-1">
        <a href="index.php" class="mb-4 inline-block">
          <img
            src="assets/img/logo-proquim-dark.png"
            alt="PROQUIM Cleaning &amp; Chemical Products"
            class="h-12 w-auto max-w-[160px] object-contain"
          />
        </a>
        <p class="text-xs leading-relaxed text-white/50">
          <?= APP_LEGAL_NAME ?><br/>
          <?= APP_TAGLINE ?>
        </p>
        <!-- Redes sociales -->
        <div class="mt-5 flex gap-2">
          <?php
          $redes = [
            ['label' => 'Facebook',  'url' => SOCIAL_FACEBOOK,  'path' => 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
            ['label' => 'Instagram', 'url' => SOCIAL_INSTAGRAM, 'path' => 'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01M6.5 3.5h11a3 3 0 013 3v11a3 3 0 01-3 3h-11a3 3 0 01-3-3v-11a3 3 0 013-3z'],
            ['label' => 'WhatsApp',  'url' => SOCIAL_WHATSAPP,  'path' => 'M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z'],
          ];
          foreach ($redes as $r): ?>
            <a href="<?= htmlspecialchars($r['url'], ENT_QUOTES, 'UTF-8') ?>" 
               target="_blank" 
               rel="noopener noreferrer"
               aria-label="<?= htmlspecialchars($r['label'], ENT_QUOTES, 'UTF-8') ?>"
               class="flex h-8 w-8 items-center justify-center rounded-full border border-white/10 text-white/40 transition hover:border-[#00BCD4] hover:text-[#00BCD4]">
              <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="<?= $r['path'] ?>"/>
              </svg>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Columna 2 — Enlaces rápidos -->
      <div>
        <h4 class="mb-4 text-[10px] font-bold uppercase tracking-widest text-white/35">
          Enlaces Rápidos
        </h4>
        <ul class="space-y-2.5 text-sm">
          <li><a href="index.php"     class="transition-colors hover:text-[#00BCD4]">Inicio</a></li>
          <li><a href="productos.php" class="transition-colors hover:text-[#00BCD4]">Productos</a></li>
          <li><a href="nosotros.php"  class="transition-colors hover:text-[#00BCD4]">Nosotros</a></li>
          <li><a href="contacto.php"  class="transition-colors hover:text-[#00BCD4]">Contacto</a></li>
        </ul>
      </div>

      <!-- Columna 3 — Productos -->
      <div>
        <h4 class="mb-4 text-[10px] font-bold uppercase tracking-widest text-white/35">
          Productos
        </h4>
        <ul class="space-y-2.5 text-sm">
          <li><a href="productos.php?cat%5B%5D=Tratamiento+de+Agua" class="transition-colors hover:text-[#00BCD4]">Tratamiento de agua</a></li>
          <li><a href="productos.php?cat%5B%5D=Desinfección"        class="transition-colors hover:text-[#00BCD4]">Reactivos químicos</a></li>
          <li><a href="productos.php?cat%5B%5D=Especialidades"      class="transition-colors hover:text-[#00BCD4]">Biodegradables</a></li>
          <li><a href="productos.php?cat%5B%5D=Limpieza+Industrial"  class="transition-colors hover:text-[#00BCD4]">Limpieza Industrial</a></li>
          <li><a href="productos.php?cat%5B%5D=Control+de+pH"       class="transition-colors hover:text-[#00BCD4]">Control de pH</a></li>
        </ul>
      </div>

      <!-- Columna 4 — Contacto -->
      <div>
        <h4 class="mb-4 text-[10px] font-bold uppercase tracking-widest text-white/35">
          Contacto
        </h4>
        <ul class="space-y-3 text-sm">
          <li class="flex items-start gap-2.5">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#1F8FD6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            <span><?= CONTACT_PHONE ?><br/><span class="text-xs text-white/40"><?= CONTACT_MOBILE ?></span></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="h-4 w-4 shrink-0 text-[#1F8FD6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <a href="mailto:<?= CONTACT_EMAIL ?>" class="transition-colors hover:text-[#00BCD4]"><?= CONTACT_EMAIL ?></a>
          </li>
          <li class="flex items-start gap-2.5">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#1F8FD6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span><?= ADDRESS_STREET ?><br/><span class="text-xs text-white/40"><?= ADDRESS_CITY ?>, <?= ADDRESS_COUNTRY ?></span></span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Línea divisora + copyright -->
    <div class="mt-10 border-t border-white/10 pt-5 flex flex-col items-center justify-between gap-2 text-xs text-white/30 sm:flex-row">
      <p>&copy; <?= date('Y') ?> <?= APP_LEGAL_NAME ?>. Todos los derechos reservados.</p>
      <div class="flex gap-5">
        <a href="#" class="transition-colors hover:text-white/60">Política de Privacidad</a>
        <a href="#" class="transition-colors hover:text-white/60">Términos y Condiciones</a>
      </div>
    </div>

  </div>
</footer>

<?php 
// Incluir botón flotante de WhatsApp
require_once __DIR__ . '/../components/whatsapp-button.php'; 
?>
