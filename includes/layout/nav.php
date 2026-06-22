<?php
declare(strict_types=1);
$currentNav = $currentNav ?? '';

$items = [
    'inicio' => ['label' => 'Inicio', 'href' => 'index.php'],
    'productos' => ['label' => 'Productos', 'href' => 'productos.php'],
    'nosotros' => ['label' => 'Nosotros', 'href' => 'nosotros.php'],
    'contacto' => ['label' => 'Contacto', 'href' => 'contacto.php'],
];

$linkBase = 'inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors';
$linkIdle = 'text-muted-foreground hover:bg-accent hover:text-accent-foreground';
$linkActive = 'bg-accent text-accent-foreground';
?>
<nav class="border-b border-border bg-card/80 backdrop-blur supports-[backdrop-filter]:bg-card/60" aria-label="Principal">
    <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6">
        <a href="<?= htmlspecialchars(asset_url('index.php'), ENT_QUOTES, 'UTF-8') ?>" class="text-lg font-semibold tracking-tight text-foreground">
            Sandra
        </a>

        <button
            type="button"
            class="inline-flex items-center justify-center rounded-md border border-input bg-background p-2 text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground md:hidden"
            aria-expanded="false"
            aria-controls="main-menu"
            data-nav-toggle
        >
            <span class="sr-only">Abrir menú</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <div id="main-menu" class="flex w-full flex-col md:w-auto md:flex-row md:items-center max-md:hidden" data-nav-panel>
            <ul class="flex flex-col gap-1 md:flex-row md:gap-1">
                <?php foreach ($items as $key => $item) :
                    $isActive = $currentNav === $key;
                    $classes = $linkBase . ' ' . ($isActive ? $linkActive : $linkIdle);
                    $href = asset_url($item['href']);
                    ?>
                    <li>
                        <a
                            href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
                            class="<?= htmlspecialchars($classes, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $isActive ? 'aria-current="page"' : '' ?>
                        ><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
