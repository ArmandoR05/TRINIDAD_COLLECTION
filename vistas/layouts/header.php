<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRINIDAD COLLECTION</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/tienda.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header class="site-header">

    <?php if (!empty($bannersActivos)): ?>
        <div class="top-banner-marquee" id="topBannerMarquee">
            <div class="top-banner-track" id="topBannerTrack">
                <?php foreach ($bannersActivos as $banner): ?>
                    <span class="top-banner-item">
                        <?php echo htmlspecialchars($banner['BannerTxt']); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="header-bar">
        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú">☰</button>

        <a href="<?php echo BASE_URL; ?>/" class="logo">
            <span class="logo-text">TRINIDAD COLLECTION®</span>
            <img src="<?php echo BASE_URL; ?>/assets/img/general/logo_black.png" alt="Trinidad Collection" class="logo-img">
        </a>

        <div class="header-right"></div>
    </div>

    <nav class="top-categories">
        <?php if (!empty($menuCategorias)): ?>
            <?php foreach ($menuCategorias as $genero): ?>
                <?php if (!empty($genero['categorias'])): ?>
                    <?php foreach ($genero['categorias'] as $categoria): ?>
                        <a href="<?php echo BASE_URL; ?>/catalogo/<?php echo urlencode($genero['slug']); ?>/<?php echo urlencode($categoria['slug']); ?>">
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </nav>
</header>

<div class="menu-overlay" id="menuOverlay"></div>

<nav class="menu-panel" id="menuPanel">
    <div class="menu-panel-content">
        <?php if (!empty($menuCategorias)): ?>
            <?php foreach ($menuCategorias as $genero): ?>
                <div class="menu-column">
                    <a class="menu-title" href="<?php echo BASE_URL; ?>/catalogo/<?php echo urlencode($genero['slug']); ?>">
                        <?php echo htmlspecialchars($genero['nombre']); ?>
                    </a>

                    <?php if (!empty($genero['categorias'])): ?>
                        <ul>
                            <?php foreach ($genero['categorias'] as $categoria): ?>
                                <li>
                                    <a href="<?php echo BASE_URL; ?>/catalogo/<?php echo urlencode($genero['slug']); ?>/<?php echo urlencode($categoria['slug']); ?>">
                                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</nav>

