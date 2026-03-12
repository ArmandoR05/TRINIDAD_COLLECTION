<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $producto ? 'Editar Producto' : 'Nuevo Producto'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0"><?php echo $producto ? 'Editar Producto' : 'Nuevo Producto'; ?></h1>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <a class="btn btn-outline-secondary btn-sm" href="index.php?controller=producto&action=index">
                                Volver al listado
                            </a>
                        </div>

                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['error']; ?>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form action="index.php?controller=producto&action=<?php echo $accion; ?>" method="POST" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label for="categoria_id" class="form-label">Categoría</label>
                                <select name="categoria_id" id="categoria_id" class="form-select" required>
                                    <option value="">Seleccione una categoría</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo $categoria['id']; ?>"
                                            <?php echo ($producto && $producto['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($categoria['genero_nombre'] . ' - ' . $categoria['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input
                                    type="text"
                                    name="nombre"
                                    id="nombre"
                                    class="form-control"
                                    required
                                    value="<?php echo $producto ? htmlspecialchars($producto['nombre']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea
                                    name="descripcion"
                                    id="descripcion"
                                    rows="5"
                                    class="form-control"><?php echo $producto ? htmlspecialchars($producto['descripcion']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="precio"
                                    id="precio"
                                    class="form-control"
                                    value="<?php echo $producto ? htmlspecialchars($producto['precio']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input
                                    type="text"
                                    name="color"
                                    id="color"
                                    class="form-control"
                                    value="<?php echo $producto ? htmlspecialchars($producto['color']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="tallas" class="form-label">Tallas</label>
                                <input
                                    type="text"
                                    name="tallas"
                                    id="tallas"
                                    class="form-control"
                                    placeholder="Ej: S, M, L, XL"
                                    value="<?php echo $producto ? htmlspecialchars($producto['tallas']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="imagen_principal" class="form-label">Imagen principal</label>
                                <input
                                    type="file"
                                    name="imagen_principal"
                                    id="imagen_principal"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">
                            </div>

                            <?php if ($producto && !empty($producto['imagen_principal'])): ?>
                                <div class="mb-3">
                                    <label class="form-label">Imagen principal actual</label>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="card">
                                                <img src="<?php echo htmlspecialchars($producto['imagen_principal']); ?>" class="card-img-top img-fluid" alt="Imagen principal actual">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="imagenes_secundarias" class="form-label">Imágenes secundarias</label>
                                <input
                                    type="file"
                                    name="imagenes_secundarias[]"
                                    id="imagenes_secundarias"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    multiple>
                            </div>

                            <?php if (!empty($imagenesSecundarias)): ?>
                                <div class="mb-3">
                                    <label class="form-label">Imágenes secundarias actuales</label>
                                    <div class="row g-3">
                                        <?php foreach ($imagenesSecundarias as $img): ?>
                                            <div class="col-sm-6 col-md-4 col-lg-3">
                                                <div class="card h-100">
                                                    <img src="<?php echo htmlspecialchars($img['imagen']); ?>" class="card-img-top img-fluid" alt="Imagen secundaria">
                                                    <div class="card-body text-center">
                                                        <a
                                                            href="index.php?controller=producto&action=deleteImage&id=<?php echo $img['id']; ?>&producto_id=<?php echo $producto['id']; ?>"
                                                            class="btn btn-danger btn-sm">
                                                            Eliminar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label d-block">Opciones</label>

                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="disponible"
                                        id="disponible"
                                        <?php echo (!$producto || $producto['disponible'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="disponible">
                                        Disponible
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="es_nuevo"
                                        id="es_nuevo"
                                        <?php echo ($producto && $producto['es_nuevo'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="es_nuevo">
                                        Nuevo ingreso
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="destacado"
                                        id="destacado"
                                        <?php echo ($producto && $producto['destacado'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="destacado">
                                        Destacado
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="estado" class="form-label">Estado</label>
                                <select name="estado" id="estado" class="form-select">
                                    <option value="1" <?php echo (($producto && $producto['estado'] == 1) || !$producto) ? 'selected' : ''; ?>>
                                        Activo
                                    </option>
                                    <option value="0" <?php echo ($producto && $producto['estado'] == 0) ? 'selected' : ''; ?>>
                                        Inactivo
                                    </option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    Guardar
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>