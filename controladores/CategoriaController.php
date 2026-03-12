<?php
require_once 'modelos/Categoria.php';

class CategoriaController
{
    private $modelo;

    public function __construct()
    {
        $this->validarSesion();
        $this->modelo = new Categoria();
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

    public function index()
    {
        $categorias = $this->modelo->obtenerTodos();
        require_once 'vistas/admin/categorias/index.php';
    }

    public function create()
    {
        $categoria = null;
        $accion = 'store';
        $generos = $this->modelo->obtenerGenerosActivos();

        require_once 'vistas/admin/categorias/form.php';
    }

    public function store()
    {
        $generoId = isset($_POST['genero_id']) ? (int)$_POST['genero_id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 1;
        $slug = $this->generarSlug($nombre);

        if ($generoId <= 0 || $nombre === '') {
            $_SESSION['error'] = 'Debe seleccionar un género y escribir el nombre de la categoría.';
            header('Location: index.php?controller=categoria&action=create');
            exit;
        }

        $this->modelo->insertar($generoId, $nombre, $slug, $estado);

        $_SESSION['success'] = 'Categoría creada correctamente.';
        header('Location: index.php?controller=categoria&action=index');
        exit;
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $categoria = $this->modelo->obtenerPorId($id);

        if (!$categoria) {
            $_SESSION['error'] = 'La categoría no existe.';
            header('Location: index.php?controller=categoria&action=index');
            exit;
        }

        $accion = 'update&id=' . $id;
        $generos = $this->modelo->obtenerGenerosActivos();

        require_once 'vistas/admin/categorias/form.php';
    }

    public function update()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $generoId = isset($_POST['genero_id']) ? (int)$_POST['genero_id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 1;
        $slug = $this->generarSlug($nombre);

        if ($id <= 0 || $generoId <= 0 || $nombre === '') {
            $_SESSION['error'] = 'Datos inválidos para actualizar la categoría.';
            header('Location: index.php?controller=categoria&action=index');
            exit;
        }

        $this->modelo->actualizar($id, $generoId, $nombre, $slug, $estado);

        $_SESSION['success'] = 'Categoría actualizada correctamente.';
        header('Location: index.php?controller=categoria&action=index');
        exit;
    }

    public function toggleStatus()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $estadoActual = isset($_GET['estado']) ? (int)$_GET['estado'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            header('Location: index.php?controller=categoria&action=index');
            exit;
        }

        $nuevoEstado = $estadoActual === 1 ? 0 : 1;
        $this->modelo->cambiarEstado($id, $nuevoEstado);

        $_SESSION['success'] = 'Estado de la categoría actualizado correctamente.';
        header('Location: index.php?controller=categoria&action=index');
        exit;
    }
}