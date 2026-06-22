<?php 
declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers.php';

$pageTitle = $pageTitle ?? 'PROQUIM — Cleaning & Chemical Products';
$metaDescription = $metaDescription ?? 'Soluciones químicas de vanguardia para la industria global.';
$includeLucide = $includeLucide ?? false;
$includeLordicon = $includeLordicon ?? false;
$bodyClass = $bodyClass ?? 'min-h-screen bg-white font-sans text-navy antialiased';
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>" />

  <script src="https://cdn.tailwindcss.com"></script>
  
  <?php if (isProduction()): ?>
  <!-- Google Ads Global Site Tag -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= GOOGLE_ADS_ID ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= GOOGLE_ADS_ID ?>');
  </script>
  <?php else: ?>
  <!-- Modo desarrollo: Mock de gtag -->
  <script>
    window.dataLayer = [];
    function gtag(){
      console.log('[DEBUG gtag]', ...arguments);
    }
  </script>
  <?php endif; ?>
  
  <?php if ($includeLucide): ?>
  <script src="https://unpkg.com/lucide@latest"></script>
  <?php endif; ?>
  <?php if ($includeLordicon): ?>
  <script src="https://cdn.lordicon.com/lordicon.js"></script>
  <?php endif; ?>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy:  '#1A2F4B',
            blue:  '#1F8FD6',
            green: '#7DC242',
            brown: '#6B3E26',
            ice:   '#EAF3FB',
            teal:  '#00BCD4',
          },
          fontFamily: {
            sans: ['Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
          },
        },
      },
    }
  </script>
  <style type="text/tailwindcss">
    @layer base {
      html { scroll-behavior: smooth; }
    }
    @layer components {
      /* Botones — sitio + catálogo */
      .btn-primary {
        @apply inline-flex items-center justify-center gap-2 rounded-md bg-blue px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1a7abf] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue;
      }
      .btn-outline {
        @apply inline-flex items-center justify-center gap-2 rounded-md border border-white/60 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10;
      }
      .btn-outline-surface {
        @apply inline-flex items-center justify-center gap-2 rounded-md border border-[#DDE8F0] bg-white px-4 py-2 text-sm font-medium text-navy transition hover:border-blue hover:text-blue;
      }
      .btn-green {
        @apply inline-flex items-center justify-center gap-2 rounded-md bg-green px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#6aaa37];
      }
      .btn-teal {
        @apply inline-flex items-center justify-center gap-2 rounded-md bg-blue px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1a7abf];
      }
      .btn-ghost {
        @apply inline-flex items-center justify-center gap-1 text-sm font-medium text-blue transition hover:underline;
      }
      .btn-filter {
        @apply w-full rounded-md bg-blue px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1a7abf];
      }
      .btn-clear {
        @apply w-full rounded-md border border-[#DDE8F0] bg-white px-4 py-2 text-sm font-medium text-navy/60 transition hover:border-navy hover:text-navy;
      }
      /* Navegación */
      .nav-link {
        @apply relative px-3 py-1.5 text-sm font-medium text-white/80 transition hover:text-white after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-green after:transition-all hover:after:w-full;
      }
      .nav-link.active {
        @apply text-white after:w-full;
      }
      /* Bloques de contenido */
      .stat-card {
        @apply flex flex-col items-center gap-1 rounded-xl bg-white/70 px-6 py-4 shadow-sm backdrop-blur;
      }
      .category-card {
        @apply group relative overflow-hidden rounded-2xl bg-white shadow transition hover:-translate-y-1 hover:shadow-lg;
      }
      .valor-card {
        @apply flex flex-col items-center rounded-2xl border border-[#DDE8F0] bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md;
      }
      .form-input {
        @apply w-full rounded-md border border-[#DDE8F0] bg-white px-3 py-2.5 text-sm text-navy placeholder-navy/40 focus:border-blue focus:outline-none focus:ring-1 focus:ring-blue transition;
      }
      .info-card {
        @apply flex items-start gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-[#DDE8F0];
      }
      /* Catálogo */
      .filter-checkbox {
        @apply h-4 w-4 rounded border-[#DDE8F0] text-blue accent-blue;
      }
      .product-card {
        @apply flex flex-col overflow-hidden rounded-2xl border border-[#DDE8F0] bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md;
      }
      #product-grid.product-grid--list {
        @apply grid-cols-1 gap-3;
      }
      #product-grid.product-grid--list .product-card {
        @apply flex-row items-stretch hover:translate-y-0;
      }
      #product-grid.product-grid--list .product-card-media {
        @apply h-24 w-24 shrink-0 sm:h-28 sm:w-28;
      }
      #product-grid.product-grid--list .product-card-media span.absolute {
        @apply right-1 top-1 px-1.5 py-0 text-[8px];
      }
      #product-grid.product-grid--list .product-card-media img {
        @apply h-full w-full object-cover;
      }
      #product-grid.product-grid--list .product-card-body {
        @apply min-w-0 flex-1 flex-col justify-center gap-2 p-3 sm:flex-row sm:items-center sm:gap-4 sm:p-4;
      }
      #product-grid.product-grid--list .product-card-info {
        @apply min-w-0 flex-1;
      }
      #product-grid.product-grid--list .product-card-info h3 {
        @apply text-sm sm:text-base;
      }
      #product-grid.product-grid--list .product-card-features {
        @apply hidden shrink-0 flex-wrap gap-x-3 gap-y-1 space-y-0 md:flex;
      }
      #product-grid.product-grid--list .product-card-features li {
        @apply whitespace-nowrap text-[11px];
      }
      #product-grid.product-grid--list .product-card-features li:nth-child(n+3) {
        @apply hidden xl:flex;
      }
      #product-grid.product-grid--list .product-card-actions {
        @apply mt-0 w-full shrink-0 flex-row gap-2 pt-0 sm:w-auto sm:flex-col sm:gap-1.5;
      }
      #product-grid.product-grid--list .product-card-actions .btn-primary,
      #product-grid.product-grid--list .product-card-actions .btn-ghost {
        @apply w-auto whitespace-nowrap px-3 py-2 text-[11px] sm:w-full;
      }
      .page-btn {
        @apply flex h-8 w-8 items-center justify-center rounded-md border border-[#DDE8F0] text-sm font-medium text-navy transition hover:border-blue hover:text-blue;
      }
      .page-btn.active {
        @apply border-blue bg-blue text-white;
      }
    }
  </style>
</head>

<body class="<?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">
