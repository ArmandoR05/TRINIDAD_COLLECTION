<?php require_once 'vistas/layouts/header.php'; ?>

<main class="home-page">

    <section class="hero-section container-fluid px-0">

        <!-- MÓVIL: carrusel -->
        <div id="heroCarousel" class="carousel slide d-md-none" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="hero-card">
                        <img src="<?php echo BASE_URL; ?>/assets/img/general/img0.png" alt="Colección hombre" class="hero-img">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-card">
                        <img src="<?php echo BASE_URL; ?>/assets/img/general/img1.png" alt="Colección mujer" class="hero-img">
                    </div>
                </div>
            </div>
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            </div>
        </div>

        <!-- DESKTOP: dos columnas -->
        <div class="row g-0 m-0 d-none d-md-flex">
            <div class="col-md-6 p-0">
                <div class="hero-card">
                    <img src="<?php echo BASE_URL; ?>/assets/img/general/img0.png" alt="Colección hombre" class="hero-img">
                </div>
            </div>
            <div class="col-md-6 p-0">
                <div class="hero-card">
                    <img src="<?php echo BASE_URL; ?>/assets/img/general/img1.png" alt="Colección mujer" class="hero-img">
                </div>
            </div>
        </div>

    </section>

    <section class="products-section container-fluid px-2 px-md-3">
        <div class="section-header mb-3">
            <h2>NEW ARRIVALS</h2>
        </div>

        <?php if (!empty($nuevos)): ?>
            <div class="row g-2 g-md-3 product-row">
                <?php foreach ($nuevos as $item): ?>
                    <?php
                    $tallas = [];
                    if (!empty($item['tallas'])) {
                        $tallas = array_map('trim', explode(',', $item['tallas']));
                    }

                    /* Imagen principal por defecto */
                    $imagenPrincipal = !empty($item['imagen_principal']) ? $item['imagen_principal'] : '';

                    /* Imagen secundaria por defecto */
                    $imagenSecundaria = '';

                    /* Si el producto trae varias imágenes */
                    if (!empty($item['imagenes']) && is_array($item['imagenes'])) {
                        if (!empty($item['imagenes'][0]['imagen'])) {
                            $imagenPrincipal = $item['imagenes'][0]['imagen'];
                        }

                        if (!empty($item['imagenes'][1]['imagen'])) {
                            $imagenSecundaria = $item['imagenes'][1]['imagen'];
                        }
                    } elseif (!empty($item['imagen_secundaria'])) {
                        /* Si no hay arreglo de imágenes, usa el campo clásico */
                        $imagenSecundaria = $item['imagen_secundaria'];
                    }

                    $tieneSecundaria = !empty($imagenSecundaria);
                    ?>

                    <div class="col-6 col-md-4 col-lg-3 d-flex">
                        <article class="catalog-card <?php echo $tieneSecundaria ? 'has-secondary' : ''; ?> w-100">
                            <a class="catalog-link"
                                href="<?php echo BASE_URL; ?>/producto/<?php echo urlencode($item['slug']); ?>">

                                <div class="catalog-image catalog-image-tall">
                                    <img class="catalog-img img-primary" src="<?php echo htmlspecialchars(imgUrl($imagenPrincipal)); ?>"
                                        alt="<?php echo htmlspecialchars($item['nombre']); ?>">

                                    <?php if ($tieneSecundaria): ?>
                                        <img class="catalog-img img-secondary"
                                            src="<?php echo htmlspecialchars(imgUrl($imagenSecundaria)); ?>"
                                            alt="<?php echo htmlspecialchars($item['nombre']); ?> secundaria"
                                            onerror="this.style.display='none'; this.closest('.catalog-card').classList.remove('has-secondary');">
                                    <?php endif; ?>
                                </div>

                                <div class="catalog-info-wrap">
                                    <div class="catalog-info default-info">
                                        <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                                        <p class="catalog-price">₡<?php echo number_format($item['precio'], 2); ?></p>
                                    </div>

                                    <div class="catalog-info hover-info">

                                        <div class="sizes-list">
                                            <?php if (!empty($tallas)): ?>
                                                <?php foreach ($tallas as $talla): ?>
                                                    <span class="size-pill"><?php echo htmlspecialchars($talla); ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="size-pill">Única</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-message">No hay productos nuevos disponibles.</p>
        <?php endif; ?>
    </section>

    <section class="products-section container-fluid px-2 px-md-3">
        <div class="section-header mb-3">
            <h2>FEATURED</h2>
        </div>

        <?php if (!empty($destacados)): ?>
            <div class="row g-2 g-md-3 product-row">
                <?php foreach ($destacados as $item): ?>
                    <?php
                    $tieneSecundaria = !empty($item['imagen_secundaria']);
                    $tallas = [];

                    if (!empty($item['tallas'])) {
                        $tallas = array_map('trim', explode(',', $item['tallas']));
                    }
                    ?>
                    <div class="col-6 col-md-4 col-lg-3 d-flex">
                        <article class="catalog-card <?php echo $tieneSecundaria ? 'has-secondary' : ''; ?> w-100">
                            <a class="catalog-link"
                                href="<?php echo BASE_URL; ?>/producto/<?php echo urlencode($item['slug']); ?>">

                                <div class="catalog-image catalog-image-tall">
                                    <img class="catalog-img img-primary"
                                        src="<?php echo htmlspecialchars(imgUrl($item['imagen_principal'])); ?>"
                                        alt="<?php echo htmlspecialchars($item['nombre']); ?>">

                                    <?php if ($tieneSecundaria): ?>
                                        <img class="catalog-img img-secondary"
                                            src="<?php echo htmlspecialchars(imgUrl($item['imagen_secundaria'])); ?>"
                                            alt="<?php echo htmlspecialchars($item['nombre']); ?> secundaria"
                                            onerror="this.style.display='none'; this.closest('.catalog-card').classList.remove('has-secondary');">
                                    <?php endif; ?>
                                </div>

                                <div class="catalog-info-wrap">
                                    <div class="catalog-info default-info">
                                        <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                                        <p class="catalog-price">₡<?php echo number_format($item['precio'], 2); ?></p>
                                    </div>

                                    <div class="catalog-info hover-info">

                                        <div class="sizes-list">
                                            <?php if (!empty($tallas)): ?>
                                                <?php foreach ($tallas as $talla): ?>
                                                    <span class="size-pill"><?php echo htmlspecialchars($talla); ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="size-pill">Única</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-message"></p>
        <?php endif; ?>
    </section>

</main>

<?php require_once 'vistas/layouts/footer.php'; ?>