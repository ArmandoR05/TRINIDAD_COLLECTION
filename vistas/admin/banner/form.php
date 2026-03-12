<?php
$esEdicion = isset($banner) && !empty($banner);
$titulo = $esEdicion ? 'Editar Banner' : 'Nuevo Banner';
$accion = $esEdicion
    ? 'index.php?controller=banner&action=actualizar'
    : 'index.php?controller=banner&action=guardar';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                    </div>

                    <div class="card-body">
                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo $accion; ?>" method="POST">
                            <?php if ($esEdicion): ?>
                                <input type="hidden" name="id" value="<?php echo (int)$banner['id']; ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="BannerTxt" class="form-label">Texto del banner</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="BannerTxt"
                                    name="BannerTxt"
                                    maxlength="255"
                                    required
                                    value="<?php echo $esEdicion ? htmlspecialchars($banner['BannerTxt']) : ''; ?>"
                                >
                            </div>

                            <div class="mb-4">
                                <label for="estado" class="form-label">Estado</label>
                                <select name="estado" id="estado" class="form-select">
                                    <option value="1" <?php echo ($esEdicion && (int)$banner['estado'] === 1) || !$esEdicion ? 'selected' : ''; ?>>
                                        Activo
                                    </option>
                                    <option value="0" <?php echo ($esEdicion && (int)$banner['estado'] === 0) ? 'selected' : ''; ?>>
                                        Inactivo
                                    </option>
                                </select>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark">
                                    <?php echo $esEdicion ? 'Actualizar' : 'Guardar'; ?>
                                </button>

                                <a href="index.php?controller=banner&action=index" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>
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