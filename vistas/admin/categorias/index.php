<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h1 class="mb-2">Gestión de Categorías</h1>
                <a href="index.php?controller=dashboard&action=index" class="btn btn-outline-secondary btn-sm me-2">
                    Volver al dashboard
                </a>
                <a href="index.php?controller=categoria&action=create" class="btn btn-primary btn-sm">
                    Nueva categoría
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
                                <th>Género</th>
                                <th>Nombre</th>
                                <th>Slug</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categorias)) : ?>
                                <?php foreach ($categorias as $categoria) : ?>
                                    <tr>
                                        <td><?php echo $categoria['id']; ?></td>
                                        <td><?php echo htmlspecialchars($categoria['genero_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($categoria['slug']); ?></td>
                                        <td>
                                            <?php if ($categoria['estado'] == 1) : ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?controller=categoria&action=edit&id=<?php echo $categoria['id']; ?>" class="btn btn-warning btn-sm me-1">
                                                Editar
                                            </a>
                                            <a href="index.php?controller=categoria&action=toggleStatus&id=<?php echo $categoria['id']; ?>&estado=<?php echo $categoria['estado']; ?>" class="btn btn-sm <?php echo $categoria['estado'] == 1 ? 'btn-danger' : 'btn-success'; ?>">
                                                <?php echo $categoria['estado'] == 1 ? 'Inactivar' : 'Activar'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No hay categorías registradas.
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