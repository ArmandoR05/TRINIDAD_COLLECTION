<?php
require_once 'modelos/Genero.php';

class GeneroController
{
    private $modelo;

    public function __construct()
    {
        $this->validarSesion();
        $this->modelo = new Genero();
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
        $generos = $this->modelo->obtenerTodos();
        require_once 'vistas/admin/generos/index.php';
    }

    public function create()
    {
        $genero = null;
        $accion = 'store';
        require_once 'vistas/admin/generos/form.php';
    }

    public function store()
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 1;
        $slug = $this->generarSlug($nombre);

        if ($nombre === '') {
            $_SESSION['error'] = 'El nombre del género es obligatorio.';
            header('Location: index.php?controller=genero&action=create');
            exit;
        }

        $this->modelo->insertar($nombre, $slug, $estado);

        $_SESSION['success'] = 'Género creado correctamente.';
        header('Location: index.php?controller=genero&action=index');
        exit;
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $genero = $this->modelo->obtenerPorId($id);

        if (!$genero) {
            $_SESSION['error'] = 'El género no existe.';
            header('Location: index.php?controller=genero&action=index');
            exit;
        }

        $accion = 'update&id=' . $id;
        require_once 'vistas/admin/generos/form.php';
    }

    public function update()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 1;
        $slug = $this->generarSlug($nombre);

        if ($id <= 0 || $nombre === '') {
            $_SESSION['error'] = 'Datos inválidos para actualizar el género.';
            header('Location: index.php?controller=genero&action=index');
            exit;
        }

        $this->modelo->actualizar($id, $nombre, $slug, $estado);

        $_SESSION['success'] = 'Género actualizado correctamente.';
        header('Location: index.php?controller=genero&action=index');
        exit;
    }

    public function toggleStatus()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $estadoActual = isset($_GET['estado']) ? (int)$_GET['estado'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            header('Location: index.php?controller=genero&action=index');
            exit;
        }

        $nuevoEstado = $estadoActual === 1 ? 0 : 1;
        $this->modelo->cambiarEstado($id, $nuevoEstado);

        $_SESSION['success'] = 'Estado del género actualizado correctamente.';
        header('Location: index.php?controller=genero&action=index');
        exit;
    }
}