<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $genero ? 'Editar Género' : 'Nuevo Género'; ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">
                            <?php echo $genero ? 'Editar Género' : 'Nuevo Género'; ?>
                        </h1>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <a href="index.php?controller=genero&action=index" class="btn btn-outline-secondary btn-sm">
                                Volver al listado
                            </a>
                        </div>

                        <?php if (!empty($_SESSION['error'])) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['error']; ?>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form action="index.php?controller=genero&action=<?php echo $accion; ?>" method="POST">

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input 
                                    type="text" 
                                    name="nombre" 
                                    id="nombre" 
                                    class="form-control"
                                    required
                                    value="<?php echo $genero ? htmlspecialchars($genero['nombre']) : ''; ?>"
                                >
                            </div>

                            <div class="mb-4">
                                <label for="estado" class="form-label">Estado</label>
                                <select name="estado" id="estado" class="form-select">
                                    <option value="1" <?php echo ($genero && $genero['estado'] == 1) || !$genero ? 'selected' : ''; ?>>
                                        Activo
                                    </option>
                                    <option value="0" <?php echo ($genero && $genero['estado'] == 0) ? 'selected' : ''; ?>>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>