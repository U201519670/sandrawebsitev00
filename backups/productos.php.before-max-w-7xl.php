<?php declare(strict_types=1);

/* ══════════════════════════════════════════════════════════════
   CATÁLOGO DE PRODUCTOS — Base de Datos SQLite
══════════════════════════════════════════════════════════════ */

require_once __DIR__ . '/database/Database.php';

$db = Database::getInstance();

/* ── Filtro GET ──────────────────────────────────────────── */
$cat_filter = array_filter((array)($_GET['cat'] ?? []), fn($v) => is_string($v) && !empty($v));
$fab_filter = array_filter((array)($_GET['fab'] ?? []), fn($v) => is_string($v) && !empty($v));
$q          = trim(strip_tags($_GET['q'] ?? ''));
$ordenar    = $_GET['sort'] ?? '';

/* ── Obtener categorías y fabricantes para filtros ──────── */
$categorias = [];
$categoriasConConteo = $db->getCategoriasConConteo();
foreach ($categoriasConConteo as $cat) {
    $categorias[$cat['nombre']] = (int)$cat['total_productos'];
}
$categorias = array_keys($categorias);

$fabricantes = $db->getFabricantes();

/* ── Paginación ──────────────────────────────────────────── */
$per_page = 9;
$page     = max(1, (int)($_GET['page'] ?? 1));

/* ── Obtener productos desde la BD ─────────────────────── */
$resultado = $db->getProductos(
    [
        'categorias' => $cat_filter,
        'fabricantes' => $fab_filter,
        'busqueda' => $q,
        'ordenar' => $ordenar
    ],
    [
        'pagina' => $page,
        'por_pagina' => $per_page
    ]
);

$page_items  = $resultado['productos'];
$total       = $resultado['total'];
$total_pages = (int)ceil($total / $per_page);

/* ── Helper: URL con parámetros actuales + override ─────── */
function page_url(array $override = []): string {
    $params = array_merge($_GET, $override);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== [] && $v !== null);
    return 'productos.php' . ($params ? '?' . http_build_query($params) : '');
}

$pageTitle = 'Catálogo de Productos — PROQUIM';
$metaDescription = 'Descubre nuestra amplia gama de productos químicos especiales para tu industria.';
$bodyClass = 'min-h-screen bg-[#f4f8fb] font-sans text-navy antialiased';

require __DIR__ . '/includes/layout/head-site.php';
require __DIR__ . '/includes/layout/header-site.php';
?>


<!-- ══════════════════════════════════════
     HERO
══════════════════════════════════════ -->
<section class="relative overflow-hidden bg-gradient-to-r from-teal to-blue py-14">
  <div class="pointer-events-none absolute inset-0 opacity-10"
       style="background-image:radial-gradient(circle at 20% 50%, white 1px, transparent 1px),radial-gradient(circle at 80% 20%, white 1px, transparent 1px);background-size:40px 40px;"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Catálogo de Productos</h1>
    <p class="mt-3 max-w-xl text-base text-white/80">
      Descubre nuestra amplia gama de productos químicos especiales para tu industria.
    </p>
  </div>
</section>


<!-- ══════════════════════════════════════
     LAYOUT: SIDEBAR + CATÁLOGO
══════════════════════════════════════ -->
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
  <form method="get" action="productos.php" id="filter-form">
  <div class="flex flex-col gap-6 lg:flex-row lg:items-start">

    <!-- ── SIDEBAR FILTROS ─────────────────────────── -->
    <aside class="w-full shrink-0 lg:w-56 xl:w-60" aria-label="Filtros">
      <div class="rounded-2xl border border-[#DDE8F0] bg-white p-5 shadow-sm">

        <!-- Cabecera sidebar -->
        <div class="mb-4 flex items-center gap-2">
          <svg class="h-4 w-4 text-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
          </svg>
          <h2 class="text-sm font-semibold text-navy">Filtros</h2>
        </div>

        <!-- Búsqueda -->
        <div class="mb-5">
          <label for="q" class="mb-1.5 block text-xs font-medium text-navy/60">Buscar producto</label>
          <div class="relative">
            <svg class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input
              id="q" name="q" type="search"
              value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>"
              placeholder="Nombre del producto…"
              class="w-full rounded-md border border-[#DDE8F0] bg-[#f4f8fb] py-2 pl-8 pr-3 text-xs text-navy placeholder-navy/30 focus:border-blue focus:outline-none focus:ring-1 focus:ring-blue"
            />
          </div>
        </div>

        <!-- Categorías -->
        <div class="mb-5">
          <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-navy/40">Categorías</p>
          <div class="space-y-2">
            <?php
            // Organizar categorías por jerarquía
            $categoriasPorNivel = [];
            foreach ($categoriasConConteo as $cat) {
              $categoriasPorNivel[$cat['nivel']][] = $cat;
            }
            
            // Crear estructura jerárquica
            $nivel1Cats = $categoriasPorNivel[1] ?? [];
            $nivel2Cats = $categoriasPorNivel[2] ?? [];
            $nivel3Cats = $categoriasPorNivel[3] ?? [];
            
            // Agrupar nivel 2 por parent_id
            $nivel2PorParent = [];
            foreach ($nivel2Cats as $cat2) {
              $nivel2PorParent[$cat2['parent_id']][] = $cat2;
            }
            
            // Agrupar nivel 3 por parent_id
            $nivel3PorParent = [];
            foreach ($nivel3Cats as $cat3) {
              $nivel3PorParent[$cat3['parent_id']][] = $cat3;
            }
            
            // Renderizar categorías de nivel 1
            foreach ($nivel1Cats as $cat1):
              $cat1Id = $cat1['id'];
              $subCats = $nivel2PorParent[$cat1Id] ?? [];
              $hasChildren = !empty($subCats);
              $isExpanded = false; // Por defecto colapsado
              
              // Verificar si alguna subcategoría está seleccionada para auto-expandir
              foreach ($subCats as $subCat) {
                if (in_array($subCat['nombre'], $cat_filter, true)) {
                  $isExpanded = true;
                  break;
                }
                // Verificar nivel 3
                $subSubCats = $nivel3PorParent[$subCat['id']] ?? [];
                foreach ($subSubCats as $subSubCat) {
                  if (in_array($subSubCat['nombre'], $cat_filter, true)) {
                    $isExpanded = true;
                    break 2;
                  }
                }
              }
            ?>
              <div class="categoria-nivel-1">
                <!-- Categoría Nivel 1 - Cabecera con checkbox y expandir -->
                <div class="flex items-center gap-1">
                  <!-- Checkbox para filtrar por esta categoría -->
                  <label class="flex flex-1 cursor-pointer items-center gap-2 rounded-lg px-2 py-2 text-xs font-semibold text-navy transition hover:bg-[#f4f8fb]">
                    <input
                      type="checkbox" name="cat[]"
                      value="<?= htmlspecialchars($cat1['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                      class="filter-checkbox h-3.5 w-3.5"
                      <?= in_array($cat1['nombre'], $cat_filter, true) ? 'checked' : '' ?>
                    />
                    <span class="flex-1"><?= htmlspecialchars($cat1['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="rounded-full bg-blue/10 px-2 py-0.5 text-[10px] font-semibold text-blue"><?= (int)$cat1['total_productos'] ?></span>
                  </label>
                  
                  <!-- Botón para expandir/colapsar (solo si tiene hijos) -->
                  <?php if ($hasChildren): ?>
                    <button
                      type="button"
                      class="categoria-toggle flex h-8 w-8 items-center justify-center rounded-lg text-navy/40 transition hover:bg-[#f4f8fb] hover:text-navy"
                      data-target="cat-<?= $cat1Id ?>"
                      <?= $isExpanded ? 'data-expanded="true"' : '' ?>
                      aria-label="Expandir subcategorías"
                    >
                      <svg class="categoria-icon h-4 w-4 transition-transform <?= $isExpanded ? 'rotate-90' : '' ?>" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                      </svg>
                    </button>
                  <?php endif; ?>
                </div>
                
                <!-- Subcategorías (Nivel 2 y 3) -->
                <?php if ($hasChildren): ?>
                  <div id="cat-<?= $cat1Id ?>" class="categoria-content ml-4 mt-1 space-y-1 <?= $isExpanded ? '' : 'hidden' ?>">
                    <?php foreach ($subCats as $cat2):
                      $checked2 = in_array($cat2['nombre'], $cat_filter, true);
                      $count2 = (int)$cat2['total_productos'];
                      $subSubCats = $nivel3PorParent[$cat2['id']] ?? [];
                      $hasSubChildren = !empty($subSubCats);
                    ?>
                      <div class="categoria-nivel-2">
                        <!-- Categoría Nivel 2 -->
                        <div class="flex items-start gap-2 py-1">
                          <label class="flex flex-1 cursor-pointer items-center gap-2 text-[11px] text-navy/80 hover:text-navy">
                            <input
                              type="checkbox" name="cat[]"
                              value="<?= htmlspecialchars($cat2['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                              class="filter-checkbox mt-0.5 h-3 w-3"
                              <?= $checked2 ? 'checked' : '' ?>
                            />
                            <span class="text-navy/40">├─</span>
                            <span class="flex-1"><?= htmlspecialchars($cat2['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                          </label>
                          <span class="rounded-full bg-[#f4f8fb] px-1.5 py-0.5 text-[9px] font-medium text-navy/40"><?= $count2 ?></span>
                        </div>
                        
                        <!-- Subcategorías Nivel 3 -->
                        <?php if ($hasSubChildren): ?>
                          <div class="ml-4 mt-0.5 space-y-1">
                            <?php foreach ($subSubCats as $cat3):
                              $checked3 = in_array($cat3['nombre'], $cat_filter, true);
                              $count3 = (int)$cat3['total_productos'];
                            ?>
                              <div class="flex items-center gap-2 py-0.5">
                                <label class="flex flex-1 cursor-pointer items-center gap-2 text-[10px] text-navy/70 hover:text-navy">
                                  <input
                                    type="checkbox" name="cat[]"
                                    value="<?= htmlspecialchars($cat3['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="filter-checkbox h-2.5 w-2.5"
                                    <?= $checked3 ? 'checked' : '' ?>
                                  />
                                  <span class="text-navy/30">└─</span>
                                  <span class="flex-1"><?= htmlspecialchars($cat3['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                                </label>
                                <span class="rounded-full bg-[#f4f8fb] px-1.5 py-0.5 text-[9px] font-medium text-navy/40"><?= $count3 ?></span>
                              </div>
                            <?php endforeach; ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Fabricantes -->
        <div class="mb-6">
          <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-navy/40">Fabricante</p>
          <ul class="space-y-2">
            <?php foreach ($fabricantes as $fab):
              $checked = in_array($fab, $fab_filter, true);
            ?>
              <li>
                <label class="flex cursor-pointer items-center gap-2 text-xs text-navy/80 hover:text-navy">
                  <input
                    type="checkbox" name="fab[]"
                    value="<?= htmlspecialchars($fab, ENT_QUOTES, 'UTF-8') ?>"
                    class="filter-checkbox"
                    <?= $checked ? 'checked' : '' ?>
                  />
                  <?= htmlspecialchars($fab, ENT_QUOTES, 'UTF-8') ?>
                </label>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Botones aplicar / limpiar -->
        <div class="space-y-2">
          <button type="submit" class="btn-filter">Aplicar Filtros</button>
          <a href="productos.php" class="btn-clear block text-center">Limpiar Todo</a>
        </div>

      </div>
    </aside>


    <!-- ── ÁREA PRINCIPAL ──────────────────────────── -->
    <main class="min-w-0 flex-1" aria-label="Catálogo de productos">

      <!-- Barra superior resultado -->
      <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-navy/60">
          Mostrando <span class="font-semibold text-navy"><?= count($page_items) ?></span>
          de <span class="font-semibold text-navy"><?= $total ?></span> productos
        </p>
        <div class="flex items-center gap-2">
          <!-- Vista grid / lista -->
          <button type="button" id="btn-grid" aria-label="Vista cuadrícula"
                  class="rounded-md border border-blue bg-blue p-1.5 text-white transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
          </button>
          <button type="button" id="btn-list" aria-label="Vista lista"
                  class="rounded-md border border-[#DDE8F0] bg-white p-1.5 text-navy/40 transition hover:border-blue hover:text-blue">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <!-- Ordenar -->
          <select name="sort" id="sort-select"
                  class="rounded-md border border-[#DDE8F0] bg-white py-1.5 pl-3 pr-8 text-xs text-navy focus:border-blue focus:outline-none focus:ring-1 focus:ring-blue">
            <option value="">Más recientes</option>
            <option value="az" <?= ($_GET['sort'] ?? '') === 'az' ? 'selected' : '' ?>>A → Z</option>
            <option value="za" <?= ($_GET['sort'] ?? '') === 'za' ? 'selected' : '' ?>>Z → A</option>
          </select>
        </div>
      </div>

      <!-- Tags de filtros activos -->
      <?php if ($cat_filter || $fab_filter || $q): ?>
        <div class="mb-4 flex flex-wrap gap-2">
          <?php foreach ($cat_filter as $f): ?>
            <span class="inline-flex items-center gap-1 rounded-full bg-blue/10 px-3 py-1 text-xs font-medium text-blue">
              <?= htmlspecialchars($f, ENT_QUOTES, 'UTF-8') ?>
              <a href="<?= htmlspecialchars(page_url(['cat' => array_values(array_filter($cat_filter, fn($v) => $v !== $f)), 'page' => 1]), ENT_QUOTES, 'UTF-8') ?>"
                 class="ml-1 text-blue/60 hover:text-blue" aria-label="Quitar filtro">×</a>
            </span>
          <?php endforeach; ?>
          <?php foreach ($fab_filter as $f): ?>
            <span class="inline-flex items-center gap-1 rounded-full bg-blue/10 px-3 py-1 text-xs font-medium text-blue">
              <?= htmlspecialchars($f, ENT_QUOTES, 'UTF-8') ?>
              <a href="<?= htmlspecialchars(page_url(['fab' => array_values(array_filter($fab_filter, fn($v) => $v !== $f)), 'page' => 1]), ENT_QUOTES, 'UTF-8') ?>"
                 class="ml-1 text-blue/60 hover:text-blue" aria-label="Quitar filtro">×</a>
            </span>
          <?php endforeach; ?>
          <?php if ($q): ?>
            <span class="inline-flex items-center gap-1 rounded-full bg-navy/10 px-3 py-1 text-xs font-medium text-navy">
              "<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>"
              <a href="<?= htmlspecialchars(page_url(['q' => '', 'page' => 1]), ENT_QUOTES, 'UTF-8') ?>"
                 class="ml-1 text-navy/60 hover:text-navy" aria-label="Quitar búsqueda">×</a>
            </span>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- GRID DE PRODUCTOS -->
      <?php if (empty($page_items)): ?>
        <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-[#DDE8F0] bg-white py-20 text-center">
          <svg class="mb-3 h-10 w-10 text-navy/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <p class="text-sm font-medium text-navy/40">No se encontraron productos con estos filtros.</p>
          <a href="productos.php" class="mt-4 btn-primary text-xs">Limpiar filtros</a>
        </div>
      <?php else: ?>
        <div id="product-grid" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
          <?php foreach ($page_items as $p): ?>
            <article
              class="product-card"
              data-categoria="<?= htmlspecialchars($p['categoria'], ENT_QUOTES, 'UTF-8') ?>"
              data-fabricante="<?= htmlspecialchars($p['fabricante'], ENT_QUOTES, 'UTF-8') ?>"
            >
              <!-- Imagen representativa + tinte de marca -->
              <div class="relative h-44 w-full shrink-0 overflow-hidden">
                <img
                  src="<?= htmlspecialchars($p['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                  alt="<?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                  class="h-full w-full object-cover"
                  loading="lazy"
                  decoding="async"
                />
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-br <?= $p['color_gradient'] ?> opacity-35" aria-hidden="true"></div>
                <?php if ($p['badge']): ?>
                  <?php $bc = $p['badge_color'] ? 'bg-' . $p['badge_color'] : 'bg-blue'; ?>
                  <span class="absolute right-3 top-3 z-10 rounded-full <?= $bc ?> px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white shadow">
                    <?= htmlspecialchars($p['badge'], ENT_QUOTES, 'UTF-8') ?>
                  </span>
                <?php endif; ?>
              </div>

              <!-- Cuerpo card -->
              <div class="flex flex-1 flex-col gap-3 p-5">
                <!-- Categoría badge -->
                <span class="inline-block self-start rounded-full bg-blue/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-blue">
                  <?= htmlspecialchars($p['categoria'], ENT_QUOTES, 'UTF-8') ?>
                </span>

                <h3 class="font-semibold leading-snug text-navy">
                  <?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?>
                </h3>

                <p class="text-xs leading-relaxed text-navy/60">
                  <?= htmlspecialchars($p['descripcion'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <!-- Features -->
                <ul class="space-y-1">
                  <?php foreach ($p['features'] as $feat): ?>
                    <li class="flex items-center gap-1.5 text-xs text-navy/70">
                      <svg class="h-3 w-3 shrink-0 text-blue" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <?= htmlspecialchars($feat, ENT_QUOTES, 'UTF-8') ?>
                    </li>
                  <?php endforeach; ?>
                </ul>

                <!-- Acciones -->
                <div class="mt-auto flex flex-col gap-2 pt-3">
                  <a href="contacto.php?producto=<?= urlencode($p['nombre']) ?>" class="btn-primary w-full text-xs">
                    Solicitar Cotización
                  </a>
                  <button
                    type="button"
                    class="btn-ghost js-open-product-modal w-full justify-center text-xs"
                    data-id="<?= (int)$p['id'] ?>"
                    data-nombre="<?= htmlspecialchars($p['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    data-categoria="<?= htmlspecialchars($p['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    data-imagen="<?= htmlspecialchars($p['imagen'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    data-fabricante="<?= htmlspecialchars($p['fabricante'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    data-badge="<?= htmlspecialchars($p['badge'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    data-desc="<?= htmlspecialchars($p['descripcion'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    data-features="<?= htmlspecialchars(json_encode($p['features'] ?? [], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
                  >
                    Ver Detalles
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                  </button>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>


      <!-- ── PAGINACIÓN ──────────────────────────────── -->
      <?php if ($total_pages > 1): ?>
        <nav aria-label="Paginación de productos" class="mt-10 flex items-center justify-center gap-1">

          <!-- Anterior -->
          <?php if ($page > 1): ?>
            <a href="<?= htmlspecialchars(page_url(['page' => $page - 1]), ENT_QUOTES, 'UTF-8') ?>"
               class="page-btn" aria-label="Página anterior">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
          <?php else: ?>
            <span class="page-btn cursor-not-allowed opacity-40" aria-disabled="true">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </span>
          <?php endif; ?>

          <!-- Páginas -->
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i === $page): ?>
              <span class="page-btn active" aria-current="page"><?= $i ?></span>
            <?php else: ?>
              <a href="<?= htmlspecialchars(page_url(['page' => $i]), ENT_QUOTES, 'UTF-8') ?>"
                 class="page-btn"><?= $i ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <!-- Siguiente -->
          <?php if ($page < $total_pages): ?>
            <a href="<?= htmlspecialchars(page_url(['page' => $page + 1]), ENT_QUOTES, 'UTF-8') ?>"
               class="page-btn" aria-label="Página siguiente">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
          <?php else: ?>
            <span class="page-btn cursor-not-allowed opacity-40" aria-disabled="true">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
          <?php endif; ?>

        </nav>
      <?php endif; ?>

    </main>
  </div>
  </form>
</div>

<!-- ══════════════════════════════════════
     MODAL — detalle de producto
══════════════════════════════════════ -->
<div
  id="product-modal"
  class="pointer-events-none fixed inset-0 z-50 flex items-center justify-center bg-navy/70 p-4 opacity-0 transition duration-200"
  aria-hidden="true"
>
  <div id="product-modal-overlay" class="absolute inset-0 backdrop-blur-sm" aria-hidden="true"></div>
  <div
    id="product-modal-content"
    class="relative z-10 max-h-[92vh] w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-2xl transition duration-200"
    role="dialog"
    aria-modal="true"
    aria-labelledby="product-modal-title"
  >
    <button
      id="product-modal-close"
      type="button"
      class="absolute right-3 top-3 z-20 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-navy/70 shadow hover:text-navy"
      aria-label="Cerrar modal"
    >
      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <div class="grid max-h-[92vh] grid-cols-1 overflow-y-auto lg:grid-cols-2">
      <div class="relative h-64 lg:h-full lg:min-h-[420px]">
        <img
          id="product-modal-image"
          src=""
          alt=""
          class="h-full w-full object-cover"
        />
      </div>
      <div class="flex flex-col gap-4 p-6 lg:p-8">
        <div class="flex items-start justify-between gap-3">
          <span id="product-modal-category" class="inline-block rounded-full bg-blue/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-blue"></span>
          <span id="product-modal-badge" class="hidden rounded-full bg-blue px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white shadow"></span>
        </div>

        <h3 id="product-modal-title" class="text-xl font-bold leading-snug text-navy"></h3>

        <p class="text-xs font-semibold uppercase tracking-wider text-navy/40">
          Fabricante: <span id="product-modal-fabricante" class="text-navy/70"></span>
        </p>

        <p id="product-modal-desc" class="text-sm leading-relaxed text-navy/70"></p>

        <ul id="product-modal-features" class="space-y-1"></ul>

        <div class="mt-auto pt-3">
          <a id="product-modal-quote-link" href="contacto.php" class="btn-primary w-full text-center text-xs">
            Solicitar Cotización
          </a>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- ══════════════════════════════════════
     CTA "¿No encuentras lo que buscas?"
══════════════════════════════════════ -->
<section class="py-12">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-teal to-blue p-8 md:p-12">
      <div class="pointer-events-none absolute inset-0 opacity-10"
           style="background-image:radial-gradient(circle at 80% 50%,white 1px,transparent 1px);background-size:30px 30px;"></div>

      <div class="relative flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
        <div>
          <h2 class="text-2xl font-bold text-white md:text-3xl">¿No encuentras lo que buscas?</h2>
          <p class="mt-2 max-w-xl text-sm text-white/80">
            Cuéntanos tu necesidad y nuestro equipo técnico te ayudará a encontrar la solución química correcta para tu proceso industrial.
          </p>
        </div>

        <!-- Ícono decorativo -->
        <div class="hidden shrink-0 opacity-30 lg:block">
          <svg viewBox="0 0 120 120" class="h-28 w-28 text-white" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
            <circle cx="60" cy="60" r="55" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M40 80 L35 55 Q30 38 42 28 L78 28 Q90 38 85 55 L80 80Z"/>
            <ellipse cx="60" cy="28" rx="18" ry="7" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M37 65 Q60 74 83 65"/>
            <circle cx="60" cy="50" r="8" stroke-width="2"/>
          </svg>
        </div>

        <div class="shrink-0">
          <a href="contacto.php" class="inline-flex items-center gap-2 rounded-md bg-green px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#6aaa37]">
            Hablar con un Experto
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ -->
<?php require __DIR__ . '/includes/layout/footer-site.php'; ?>

<?php require __DIR__ . '/includes/layout/scripts-site.php'; ?>

<!-- ══════════════════════════════════════
     JAVASCRIPT — catálogo
══════════════════════════════════════ -->
<script>
(function () {
  'use strict';

  /* ── Acordeón de Categorías ─────────────────────────── */
  const categoryToggles = document.querySelectorAll('.categoria-toggle');
  
  categoryToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const content = document.getElementById(targetId);
      const icon = this.querySelector('.categoria-icon');
      const isExpanded = this.getAttribute('data-expanded') === 'true';
      
      if (isExpanded) {
        // Colapsar
        content.classList.add('hidden');
        icon.classList.remove('rotate-90');
        this.setAttribute('data-expanded', 'false');
      } else {
        // Expandir
        content.classList.remove('hidden');
        icon.classList.add('rotate-90');
        this.setAttribute('data-expanded', 'true');
      }
    });
  });

  /* ── Vista grid / lista ─────────────────────────────── */
  const grid    = document.getElementById('product-grid');
  const btnGrid = document.getElementById('btn-grid');
  const btnList = document.getElementById('btn-list');

  if (grid && btnGrid && btnList) {
    function setGrid() {
      grid.classList.remove('grid-cols-1');
      grid.classList.add('sm:grid-cols-2', 'xl:grid-cols-3');
      // Restablecer cards al modo grid
      grid.querySelectorAll('article').forEach(card => {
        card.classList.remove('flex-row', 'h-32');
        card.classList.add('flex-col');
        const thumb = card.querySelector('div:first-child');
        thumb.classList.remove('w-40', 'h-full', 'min-h-32');
        thumb.classList.add('h-44', 'shrink-0');
      });
      btnGrid.classList.replace('border-[#DDE8F0]', 'border-blue');
      btnGrid.classList.replace('bg-white', 'bg-blue');
      btnGrid.classList.replace('text-navy/40', 'text-white');
      btnList.classList.replace('border-blue', 'border-[#DDE8F0]');
      btnList.classList.replace('bg-blue', 'bg-white');
      btnList.classList.replace('text-white', 'text-navy/40');
    }

    function setList() {
      grid.classList.remove('sm:grid-cols-2', 'xl:grid-cols-3');
      grid.classList.add('grid-cols-1');
      // Ajustar cards al modo lista
      grid.querySelectorAll('article').forEach(card => {
        card.classList.remove('flex-col');
        card.classList.add('flex-row');
        const img = card.querySelector('div:first-child');
        img.classList.add('w-40', 'shrink-0', 'h-full', 'min-h-32');
        img.classList.remove('h-44');
      });
      btnList.classList.replace('border-[#DDE8F0]', 'border-blue');
      btnList.classList.replace('bg-white', 'bg-blue');
      btnList.classList.replace('text-navy/40', 'text-white');
      btnGrid.classList.replace('border-blue', 'border-[#DDE8F0]');
      btnGrid.classList.replace('bg-blue', 'bg-white');
      btnGrid.classList.replace('text-white', 'text-navy/40');
    }

    btnGrid.addEventListener('click', setGrid);
    btnList.addEventListener('click', setList);
  }

  /* ── Ordenar (cliente): envía el formulario al cambiar ── */
  const sortSelect = document.getElementById('sort-select');
  if (sortSelect) {
    sortSelect.addEventListener('change', () => {
      document.getElementById('filter-form').submit();
    });
  }

  /* ── Búsqueda en tiempo real (filtra las cards visibles) ── */
  const qInput = document.getElementById('q');
  if (qInput && grid) {
    qInput.addEventListener('input', function () {
      const term = this.value.trim().toLowerCase();
      grid.querySelectorAll('article').forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = (!term || text.includes(term)) ? '' : 'none';
      });
    });
  }

  /* ── Modal de detalle de producto ─────────────────────── */
  const productModal = document.getElementById('product-modal');
  const modalContent = document.getElementById('product-modal-content');
  const modalOverlay = document.getElementById('product-modal-overlay');
  const closeModalBtn = document.getElementById('product-modal-close');
  const openModalButtons = document.querySelectorAll('.js-open-product-modal');

  const modalImage = document.getElementById('product-modal-image');
  const modalTitle = document.getElementById('product-modal-title');
  const modalCategory = document.getElementById('product-modal-category');
  const modalBadge = document.getElementById('product-modal-badge');
  const modalFabricante = document.getElementById('product-modal-fabricante');
  const modalDesc = document.getElementById('product-modal-desc');
  const modalFeatures = document.getElementById('product-modal-features');
  const modalQuoteLink = document.getElementById('product-modal-quote-link');

  function escHtml(value) {
    return String(value)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#39;');
  }

  function closeProductModal() {
    if (!productModal) return;
    productModal.classList.add('pointer-events-none', 'opacity-0');
    productModal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');
  }

  function openProductModal(product) {
    if (!productModal || !modalContent || !product) return;

    modalImage.src = product.imagen || '';
    modalImage.alt = product.nombre || 'Producto';
    modalTitle.textContent = product.nombre || '';
    modalCategory.textContent = product.categoria || '';
    modalFabricante.textContent = product.fabricante || '';
    modalDesc.textContent = product.desc || '';
    modalQuoteLink.href = `contacto.php?producto=${encodeURIComponent(product.nombre || '')}`;

    if (product.badge) {
      modalBadge.textContent = product.badge;
      modalBadge.classList.remove('hidden');
    } else {
      modalBadge.textContent = '';
      modalBadge.classList.add('hidden');
    }

    let features = [];
    try {
      features = JSON.parse(product.features || '[]');
    } catch (e) {
      features = [];
    }

    modalFeatures.innerHTML = features.map(feat => (
      `<li class="flex items-center gap-1.5 text-sm text-navy/70">
        <svg class="h-3.5 w-3.5 shrink-0 text-blue" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
        </svg>
        ${escHtml(feat)}
      </li>`
    )).join('');

    productModal.classList.remove('pointer-events-none', 'opacity-0');
    productModal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
    closeModalBtn?.focus();
  }

  openModalButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      openProductModal({
        id: this.dataset.id,
        nombre: this.dataset.nombre,
        categoria: this.dataset.categoria,
        imagen: this.dataset.imagen,
        fabricante: this.dataset.fabricante,
        badge: this.dataset.badge,
        desc: this.dataset.desc,
        features: this.dataset.features
      });
    });
  });

  closeModalBtn?.addEventListener('click', closeProductModal);
  modalOverlay?.addEventListener('click', closeProductModal);
  modalContent?.addEventListener('click', function (event) {
    event.stopPropagation();
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && productModal?.getAttribute('aria-hidden') === 'false') {
      closeProductModal();
    }
  });

})();
</script>

</body>
</html>
