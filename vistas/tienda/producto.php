<?php require_once 'vistas/layouts/header.php'; ?>

<?php
$telefonoWhatsapp = '50662786173';

$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$urlActual = $protocolo . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$nombreProducto = !empty($producto['nombre']) ? $producto['nombre'] : 'Producto';
$precioProducto = isset($producto['precio']) ? '₡' . number_format($producto['precio'], 2) : 'Consultar precio';
$colorProducto = !empty($producto['color']) ? $producto['color'] : 'No especificado';
$tallasProducto = !empty($producto['tallas']) ? $producto['tallas'] : 'No especificadas';

$mensaje = "Hola, me interesa este producto:\n";
$mensaje .= "Producto: " . $nombreProducto . "\n";
$mensaje .= "Precio: " . $precioProducto . "\n";
$mensaje .= "Color: " . $colorProducto . "\n";
$mensaje .= "Tallas: " . $tallasProducto . "\n";
$mensaje .= "Link: " . $urlActual;

$linkWhatsapp = "https://wa.me/" . $telefonoWhatsapp . "?text=" . urlencode($mensaje);
?>

<main class="product-page">
    <section class="product-detail">

        <div class="product-gallery-stack">
            <?php if (!empty($producto['imagen_principal'])): ?>
                <div class="product-image-block">
                    <img
                        src="<?php echo htmlspecialchars(imgUrl($producto['imagen_principal'])); ?>"
                        alt="<?php echo htmlspecialchars($nombreProducto); ?>">
                </div>
            <?php endif; ?>

            <?php if (!empty($imagenes) && is_array($imagenes)): ?>
                <?php foreach ($imagenes as $img): ?>
                    <?php if (!empty($img['imagen'])): ?>
                        <div class="product-image-block">
                            <img
                                src="<?php echo htmlspecialchars(imgUrl($img['imagen'])); ?>"
                                alt="<?php echo htmlspecialchars($nombreProducto); ?>">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="product-content">
            <p class="breadcrumb">
                <?php echo htmlspecialchars($producto['genero_nombre'] ?? ''); ?>
                <?php if (!empty($producto['genero_nombre']) && !empty($producto['categoria_nombre'])): ?>
                    /
                <?php endif; ?>
                <?php echo htmlspecialchars($producto['categoria_nombre'] ?? ''); ?>
            </p>

            <h1><?php echo htmlspecialchars($nombreProducto); ?></h1>

            <p class="product-price">
                <?php echo $precioProducto; ?>
            </p>

            <div class="product-meta">
                <?php if (!empty($producto['color'])): ?>
                    <p><strong>Color:</strong> <?php echo htmlspecialchars($producto['color']); ?></p>
                <?php endif; ?>

                <?php if (!empty($producto['tallas'])): ?>
                    <p><strong>Tallas:</strong> <?php echo htmlspecialchars($producto['tallas']); ?></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($producto['descripcion'])): ?>
                <div class="product-description">
                    <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
                </div>
            <?php endif; ?>

            <div class="product-actions">
                <a href="<?php echo htmlspecialchars($linkWhatsapp); ?>" target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
                    <span>COMPRAR POR</span>
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>

            <div class="product-extra-info">
                <details class="product-accordion">
                    <summary>ENTREGAS Y ENVÍO <span>+</span></summary>
                    <div class="product-accordion-content">
                        <p>
                            Realizamos envíos dentro de Costa Rica. El tiempo de entrega puede variar según la zona.
                            Una vez confirmada tu compra, te compartiremos por WhatsApp el detalle del envío y seguimiento.
                        </p>
                    </div>
                </details>

                <details class="product-accordion">
                    <summary>CUIDADOS DE LAVADO <span>+</span></summary>
                    <div class="product-accordion-content">
                        <p>
                            Lavar por el reverso con agua fría. No usar cloro. No retorcer. Secar a la sombra.
                            Si se plancha, hacerlo por el reverso para cuidar mejor el estampado.
                        </p>
                    </div>
                </details>
            </div>
        </div>

    </section>

    <?php if (!empty($relacionados) && is_array($relacionados)): ?>
    <section class="related-products-section container-fluid px-2 px-md-3">
        <div class="section-header mb-3">
            <h2>TAMBIÉN TE PUEDE INTERESAR</h2>
        </div>

        <div class="row g-2 g-md-3 product-row">
            <?php foreach ($relacionados as $item): ?>
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
    </section>
<?php endif; ?>
</main>

<?php require_once 'vistas/layouts/footer.php'; ?>