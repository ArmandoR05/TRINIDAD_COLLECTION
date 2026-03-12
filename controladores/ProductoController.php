<?php
require_once 'modelos/Producto.php';

class ProductoController
{
    private $modelo;
    private $directorioImagenes = 'assets/img/productos/';

    public function __construct()
    {
        $this->validarSesion();
        $this->modelo = new Producto();
    }

    private function validarSesion()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    private function generarSlug($texto)
    {
        $texto = trim($texto);
        $texto = mb_strtolower($texto, 'UTF-8');
        $texto = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'n'],
            $texto
        );
        $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
        $texto = trim($texto, '-');

        return $texto;
    }

    private function asegurarDirectorioImagenes()
    {
        if (!is_dir($this->directorioImagenes)) {
            mkdir($this->directorioImagenes, 0777, true);
        }
    }

    private function esExtensionPermitida($extension)
    {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        return in_array($extension, $extensionesPermitidas, true);
    }

    private function subirImagen($archivo)
    {
        if (!isset($archivo) || !isset($archivo['error'])) {
            return null;
        }

        if ($archivo['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $extension = strtolower(pathinfo(basename($archivo['name']), PATHINFO_EXTENSION));

        if (!$this->esExtensionPermitida($extension)) {
            return false;
        }

        $resultado = subirImagenCloudinary($archivo['tmp_name'], 'productos');

        if (!$resultado['success']) {
            return false;
        }

        return $resultado['url'];
    }

    private function subirMultiplesImagenes($archivos)
    {
        $imagenesGuardadas = [];

        if (
            !isset($archivos['name']) ||
            !is_array($archivos['name']) ||
            count($archivos['name']) === 0
        ) {
            return $imagenesGuardadas;
        }

        $total = count($archivos['name']);

        for ($i = 0; $i < $total; $i++) {
            if (!isset($archivos['error'][$i])) {
                continue;
            }

            if ($archivos['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($archivos['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $extension = strtolower(pathinfo(basename($archivos['name'][$i]), PATHINFO_EXTENSION));

            if (!$this->esExtensionPermitida($extension)) {
                continue;
            }

            $resultado = subirImagenCloudinary($archivos['tmp_name'][$i], 'productos');

            if ($resultado['success']) {
                $imagenesGuardadas[] = $resultado['url'];
            }
        }

        return $imagenesGuardadas;
    }

    private function construirDatosDesdePost($imagenPrincipal)
    {
        return [
            'categoria_id'     => isset($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : 0,
            'nombre'           => trim($_POST['nombre'] ?? ''),
            'slug'             => $this->generarSlug(trim($_POST['nombre'] ?? '')),
            'descripcion'      => trim($_POST['descripcion'] ?? ''),
            'precio'           => (isset($_POST['precio']) && $_POST['precio'] !== '') ? (float) $_POST['precio'] : 0,
            'imagen_principal' => $imagenPrincipal,
            'color'            => trim($_POST['color'] ?? ''),
            'tallas'           => trim($_POST['tallas'] ?? ''),
            'disponible'       => isset($_POST['disponible']) ? 1 : 0,
            'es_nuevo'         => isset($_POST['es_nuevo']) ? 1 : 0,
            'destacado'        => isset($_POST['destacado']) ? 1 : 0,
            'estado'           => isset($_POST['estado']) ? (int) $_POST['estado'] : 1
        ];
    }

    private function validarDatosBasicos($datos)
    {
        return $datos['categoria_id'] > 0 && $datos['nombre'] !== '';
    }

    public function index()
    {
        $productos = $this->modelo->obtenerTodos();
        require_once 'vistas/admin/productos/index.php';
    }

    public function create()
    {
        $producto = null;
        $accion = 'store';
        $categorias = $this->modelo->obtenerCategoriasActivas();
        $imagenesSecundarias = [];

        require_once 'vistas/admin/productos/form.php';
    }

    public function store()
    {
        $imagenPrincipal = $this->subirImagen($_FILES['imagen_principal'] ?? null);

        if ($imagenPrincipal === false) {
            $_SESSION['error'] = 'La imagen principal debe ser JPG, JPEG, PNG o WEBP.';
            header('Location: index.php?controller=producto&action=create');
            exit;
        }

        if ($imagenPrincipal === null) {
            $_SESSION['error'] = 'Debe seleccionar una imagen principal.';
            header('Location: index.php?controller=producto&action=create');
            exit;
        }

        $datos = $this->construirDatosDesdePost($imagenPrincipal);

        if (!$this->validarDatosBasicos($datos)) {
            $_SESSION['error'] = 'Debe seleccionar una categoría y escribir el nombre del producto.';
            header('Location: index.php?controller=producto&action=create');
            exit;
        }

        $productoId = $this->modelo->insertar($datos);

        if (!$productoId) {
            $_SESSION['error'] = 'No se pudo guardar el producto.';
            header('Location: index.php?controller=producto&action=create');
            exit;
        }

        $imagenesSecundarias = $this->subirMultiplesImagenes($_FILES['imagenes_secundarias'] ?? []);

        $orden = 1;
        foreach ($imagenesSecundarias as $rutaImagen) {
            $this->modelo->insertarImagenSecundaria($productoId, $rutaImagen, $orden);
            $orden++;
        }

        $_SESSION['success'] = 'Producto creado correctamente.';
        header('Location: index.php?controller=producto&action=index');
        exit;
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $producto = $this->modelo->obtenerPorId($id);

        if (!$producto) {
            $_SESSION['error'] = 'El producto no existe.';
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        $accion = 'update&id=' . $id;
        $categorias = $this->modelo->obtenerCategoriasActivas();
        $imagenesSecundarias = $this->modelo->obtenerImagenesSecundarias($id);

        require_once 'vistas/admin/productos/form.php';
    }

    public function update()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $productoActual = $this->modelo->obtenerPorId($id);

        if (!$productoActual) {
            $_SESSION['error'] = 'El producto no existe.';
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        $nuevaImagenPrincipal = $this->subirImagen($_FILES['imagen_principal'] ?? null);

        if ($nuevaImagenPrincipal === false) {
            $_SESSION['error'] = 'La imagen principal debe ser JPG, JPEG, PNG o WEBP.';
            header('Location: index.php?controller=producto&action=edit&id=' . $id);
            exit;
        }

        $imagenFinal = $productoActual['imagen_principal'];

        if ($nuevaImagenPrincipal !== null) {
            $imagenFinal = $nuevaImagenPrincipal;

            if (!empty($productoActual['imagen_principal'])) {
                $publicId = cloudinaryPublicId($productoActual['imagen_principal']);
                if ($publicId !== '') {
                    cloudinary_eliminar($publicId);
                }
            }
        }

        $datos = $this->construirDatosDesdePost($imagenFinal);

        if (!$this->validarDatosBasicos($datos)) {
            $_SESSION['error'] = 'Datos inválidos para actualizar el producto.';
            header('Location: index.php?controller=producto&action=edit&id=' . $id);
            exit;
        }

        $ok = $this->modelo->actualizar($id, $datos);

        if (!$ok) {
            $_SESSION['error'] = 'No se pudo actualizar el producto.';
            header('Location: index.php?controller=producto&action=edit&id=' . $id);
            exit;
        }

        $imagenesSecundarias = $this->subirMultiplesImagenes($_FILES['imagenes_secundarias'] ?? []);
        $imagenesExistentes = $this->modelo->obtenerImagenesSecundarias($id);
        $orden = count($imagenesExistentes) + 1;

        foreach ($imagenesSecundarias as $rutaImagen) {
            $this->modelo->insertarImagenSecundaria($id, $rutaImagen, $orden);
            $orden++;
        }

        $_SESSION['success'] = 'Producto actualizado correctamente.';
        header('Location: index.php?controller=producto&action=index');
        exit;
    }

    public function toggleStatus()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $estadoActual = isset($_GET['estado']) ? (int) $_GET['estado'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        $nuevoEstado = ($estadoActual === 1) ? 0 : 1;
        $this->modelo->cambiarEstado($id, $nuevoEstado);

        $_SESSION['success'] = 'Estado del producto actualizado correctamente.';
        header('Location: index.php?controller=producto&action=index');
        exit;
    }

    public function deleteImage()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $productoId = isset($_GET['producto_id']) ? (int) $_GET['producto_id'] : 0;

        if ($id <= 0 || $productoId <= 0) {
            $_SESSION['error'] = 'Datos inválidos.';
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        $ok = $this->modelo->eliminarImagenSecundaria($id);

        if (!$ok) {
            $_SESSION['error'] = 'No se pudo eliminar la imagen.';
            header('Location: index.php?controller=producto&action=edit&id=' . $productoId);
            exit;
        }

        $_SESSION['success'] = 'Imagen eliminada correctamente.';
        header('Location: index.php?controller=producto&action=edit&id=' . $productoId);
        exit;
    }
}