<?php 
declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers.php';

$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF'] ?? '', '.php');
?>
<header class="sticky top-0 z-50">

  <!-- Franja blanca — Logo + Contacto rápido -->
  <div class="border-b border-[#DDE8F0] bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2 sm:px-6">

      <a href="index.php">
        <img src="assets/img/logo-proquim.png" alt="PROQUIM Cleaning &amp; Chemical Products" class="h-12 w-auto" />
      </a>

      <div class="hidden items-center gap-5 sm:flex">
        <a href="<?= getWhatsAppURL() ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-sm font-medium text-navy/60 transition hover:text-[#25D366]">
          <svg class="h-4 w-4 shrink-0 text-[#25D366]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
          </svg>
          <?= CONTACT_MOBILE ?>
        </a>
        <span class="h-4 w-px bg-[#DDE8F0]"></span>
        <a href="mailto:<?= CONTACT_EMAIL ?>" class="flex items-center gap-2 text-sm font-medium text-navy/60 transition hover:text-blue">
          <svg class="h-4 w-4 shrink-0 text-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
          <?= CONTACT_EMAIL ?>
        </a>
      </div>

    </div>
  </div>

  <!-- Franja navy — Navegación -->
  <div class="border-b border-white/10 bg-navy/95 backdrop-blur supports-[backdrop-filter]:bg-navy/80">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-2 sm:px-6">

      <nav class="hidden items-center gap-1 md:flex" aria-label="Principal">
        <a href="index.php" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">Inicio</a>
        <a href="productos.php" class="nav-link <?= $currentPage === 'productos' ? 'active' : '' ?>">Productos</a>
        <a href="nosotros.php" class="nav-link <?= $currentPage === 'nosotros' ? 'active' : '' ?>">Nosotros</a>
        <a href="contacto.php" class="nav-link <?= $currentPage === 'contacto' ? 'active' : '' ?>">Contacto</a>
      </nav>

      <div class="flex items-center gap-3">
        <a href="contacto.php" class="hidden rounded-md bg-green px-4 py-2 text-xs font-bold text-white transition hover:bg-[#6aaa37] sm:inline-flex">
          Solicitar cotización
        </a>
        <button id="menu-btn" type="button" class="rounded-md p-2 text-white/70 hover:bg-white/10 md:hidden" aria-expanded="false" aria-controls="mobile-menu">
          <span class="sr-only">Abrir menú</span>
          <svg id="icon-open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          <svg id="icon-close" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>

    <div id="mobile-menu" class="hidden border-t border-white/10 md:hidden">
      <nav class="flex flex-col gap-1 px-4 py-3" aria-label="Móvil">
        <a href="index.php" class="rounded-md px-3 py-2 text-sm font-medium <?= $currentPage === 'index' ? 'text-white bg-white/10' : 'text-white/80 hover:bg-white/10' ?>">Inicio</a>
        <a href="productos.php" class="rounded-md px-3 py-2 text-sm font-medium <?= $currentPage === 'productos' ? 'text-white bg-white/10' : 'text-white/80 hover:bg-white/10' ?>">Productos</a>
        <a href="nosotros.php" class="rounded-md px-3 py-2 text-sm font-medium <?= $currentPage === 'nosotros' ? 'text-white bg-white/10' : 'text-white/80 hover:bg-white/10' ?>">Nosotros</a>
        <a href="contacto.php" class="rounded-md px-3 py-2 text-sm font-medium <?= $currentPage === 'contacto' ? 'text-white bg-white/10' : 'text-white/80 hover:bg-white/10' ?>">Contacto</a>
        <a href="contacto.php" class="mt-2 rounded-md bg-green px-3 py-2 text-center text-sm font-bold text-white">Solicitar cotización</a>
      </nav>
    </div>
  </div>
</header>
