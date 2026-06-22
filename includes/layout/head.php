<?php
declare(strict_types=1);
$pageTitle = $pageTitle ?? 'Sitio';
$metaDescription = $metaDescription ?? '';
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if ($metaDescription !== '') : ?>
        <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('assets/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body class="min-h-screen bg-background font-sans text-foreground antialiased">
