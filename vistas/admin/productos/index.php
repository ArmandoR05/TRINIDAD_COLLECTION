<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h1 class="mb-2">Gestión de Productos</h1>
                <a href="index.php?controller=dashboard&action=index" class="btn btn-outline-secondary btn-sm me-2">
                    Volver al dashboard
                </a>
                <a href="index.php?controller=producto&action=create" class="btn btn-primary btn-sm">
                    Nuevo producto
                </a>
            </div>
        </div>

        <?php if (!empty($_SESSION['success'])) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Género</th>
                                <th>Categoría</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Nuevo</th>
                                <th>Destacado</th>
                                <th>Disponible</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($productos)) : ?>
                                <?php foreach ($productos as $producto) : ?>
                                    <tr>
                                        <td><?php echo $producto['id']; ?></td>
                                        <td>
                                            <?php if (!empty($producto['imagen_principal'])) : ?>
                                                <img 
                                                    src="<?php echo htmlspecialchars($producto['imagen_principal']); ?>" 
                                                    alt="Producto"
                                                    class="img-thumbnail"
                                                    style="width: 70px; height: 70px; object-fit: cover;"
                                                >
                                            <?php else : ?>
                                                <span class="text-muted">Sin imagen</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($producto['genero_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                        <td>₡<?php echo number_format((float)$producto['precio'], 2); ?></td>
                                        <td>
                                            <?php if ($producto['es_nuevo'] == 1) : ?>
                                                <span class="badge bg-info text-dark">Sí</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($producto['destacado'] == 1) : ?>
                                                <span class="badge bg-warning text-dark">Sí</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($producto['disponible'] == 1) : ?>
                                                <span class="badge bg-success">Sí</span>
                                            <?php else : ?>
                                                <span class="badge bg-danger">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($producto['estado'] == 1) : ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?controller=producto&action=edit&id=<?php echo $producto['id']; ?>" class="btn btn-warning btn-sm me-1">
                                                Editar
                                            </a>
                                            <a href="index.php?controller=producto&action=toggleStatus&id=<?php echo $producto['id']; ?>&estado=<?php echo $producto['estado']; ?>" class="btn btn-sm <?php echo $producto['estado'] == 1 ? 'btn-danger' : 'btn-success'; ?>">
                                                <?php echo $producto['estado'] == 1 ? 'Inactivar' : 'Activar'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        No hay productos registrados.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>