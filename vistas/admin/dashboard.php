<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Trinidad Studios</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h1 class="h3 mb-0">Panel de Administración</h1>
                    </div>

                    <div class="card-body">
                        <p class="fs-5">
                            Bienvenido,
                            <strong><?php echo $_SESSION['admin_nombre'] ?? 'Administrador'; ?></strong>
                        </p>

                        <div class="list-group">
                            <a href="index.php?controller=genero&action=index" class="list-group-item list-group-item-action">
                                Gestionar géneros
                            </a>

                            <a href="index.php?controller=categoria&action=index" class="list-group-item list-group-item-action">
                                Gestionar categorías
                            </a>

                            <a href="index.php?controller=producto&action=index" class="list-group-item list-group-item-action">
                                Gestionar productos
                            </a>

                            <a href="index.php?controller=banner&action=index" class="list-group-item list-group-item-action">
                                Gestionar banners
                            </a>

                            <a href="index.php?controller=auth&action=logout" class="list-group-item list-group-item-action text-danger">
                                Cerrar sesión
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>