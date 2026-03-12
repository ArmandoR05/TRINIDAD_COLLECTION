<?php require_once 'vistas/layouts/header.php'; ?>

<main class="catalog-page container-fluid px-2 px-md-3">
    <section class="catalog-header">
        <h1>CATÁLOGO</h1>

        <p>
            Explora los productos disponibles
            <?php if (!empty($generoSeleccionado) || !empty($categoriaSeleccionada)): ?>
                en
                <?php if (!empty($generoSeleccionado)): ?>
                    <strong><?php echo htmlspecialchars($generoSeleccionado); ?></strong>
                <?php endif; ?>

                <?php if (!empty($generoSeleccionado) && !empty($categoriaSeleccionada)): ?>
                    /
                <?php endif; ?>

                <?php if (!empty($categoriaSeleccionada)): ?>
                    <strong><?php echo htmlspecialchars($categoriaSeleccionada); ?></strong>
                <?php endif; ?>
            <?php else: ?>
                por género y categoría.
            <?php endif; ?>
        </p>
    </section>

    <section class="catalog-content">
        <p class="catalog-total">
            <strong>Total de productos:</strong> <?php echo count($productos); ?>
        </p>

        <?php if (!empty($productos)): ?>
            <div class="row g-2 g-md-3 product-row">
                <?php foreach ($productos as $item): ?>
                    <?php
                    $tieneSecundaria = !empty($item['imagen_secundaria']);
                    $tallas = [];

                    if (!empty($item['tallas'])) {
                        $tallas = array_map('trim', explode(',', $item['tallas']));
                    }
                    ?>

                    <div class="col-6 col-md-4 col-lg-3 d-flex">
                        <article class="catalog-card <?php echo $tieneSecundaria ? 'has-secondary' : ''; ?> w-100">
                            <a class="catalog-link" href="<?php echo BASE_URL; ?>/producto/<?php echo urlencode($item['slug']); ?>">

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

                                        <p class="catalog-price">
                                            ₡<?php echo number_format($item['precio'], 2); ?>
                                        </p>
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
            <p class="catalog-empty">No hay productos disponibles para este filtro.</p>
        <?php endif; ?>
    </section>
</main>

<?php require_once 'vistas/layouts/footer.php'; ?>