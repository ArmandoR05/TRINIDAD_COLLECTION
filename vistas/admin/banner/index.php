<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Banners</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Gestión de Banners</h1>
                <p class="text-muted mb-0">Administra los textos del banner</p>
            </div>


            <div class="d-flex gap-2">
                <a href="index.php?controller=dashboard&action=index" class="btn btn-outline-secondary">
                    Volver al dashboard
                </a>
                <a href="index.php?controller=banner&action=crear" class="btn btn-dark">
                    Nuevo banner
                </a>
            </div>
        </div>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <?php if (!empty($banners)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>Texto del banner</th>
                                    <th style="width: 120px;">Estado</th>
                                    <th style="width: 260px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $banner): ?>
                                    <tr>
                                        <td><?php echo (int)$banner['id']; ?></td>
                                        <td><?php echo htmlspecialchars($banner['BannerTxt']); ?></td>
                                        <td>
                                            <?php if ((int)$banner['estado'] === 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="index.php?controller=banner&action=editar&id=<?php echo (int)$banner['id']; ?>"
                                                   class="btn btn-sm btn-primary">
                                                    Editar
                                                </a>

                                                <?php if ((int)$banner['estado'] === 1): ?>
                                                    <a href="index.php?controller=banner&action=deshabilitar&id=<?php echo (int)$banner['id']; ?>"
                                                       class="btn btn-sm btn-warning"
                                                       onclick="return confirm('¿Desea deshabilitar este banner?');">
                                                        Deshabilitar
                                                    </a>
                                                <?php else: ?>
                                                    <a href="index.php?controller=banner&action=habilitar&id=<?php echo (int)$banner['id']; ?>"
                                                       class="btn btn-sm btn-success"
                                                       onclick="return confirm('¿Desea habilitar este banner?');">
                                                        Habilitar
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        No hay banners registrados todavía.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>