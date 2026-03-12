<?php
require_once 'modelos/Banner.php';

class BannerController
{
    private $modelo;

    public function __construct()
    {
        $this->validarSesion();
        $this->modelo = new Banner();
    }

    private function validarSesion()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    public function index()
    {
        $banners = $this->modelo->obtenerTodos();
        require_once 'vistas/admin/banner/index.php';
    }

    public function crear()
    {
        require_once 'vistas/admin/banner/form.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bannerTxt = trim($_POST['BannerTxt'] ?? '');
            $estado = isset($_POST['estado']) ? (int) $_POST['estado'] : 1;

            if ($bannerTxt === '') {
                $_SESSION['error'] = 'El texto del banner es obligatorio.';
                header('Location: index.php?controller=banner&action=crear');
                exit;
            }

            $ok = $this->modelo->insertar($bannerTxt, $estado);

            if ($ok) {
                $_SESSION['success'] = 'Banner registrado correctamente.';
            } else {
                $_SESSION['error'] = 'No se pudo registrar el banner.';
            }

            header('Location: index.php?controller=banner&action=index');
            exit;
        }
    }

    public function editar()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            header('Location: index.php?controller=banner&action=index');
            exit;
        }

        $banner = $this->modelo->obtenerPorId($id);

        if (!$banner) {
            $_SESSION['error'] = 'Banner no encontrado.';
            header('Location: index.php?controller=banner&action=index');
            exit;
        }

        require_once 'vistas/admin/banner/form.php';
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
            $bannerTxt = trim($_POST['BannerTxt'] ?? '');
            $estado = isset($_POST['estado']) ? (int) $_POST['estado'] : 1;

            if ($id <= 0) {
                $_SESSION['error'] = 'ID inválido.';
                header('Location: index.php?controller=banner&action=index');
                exit;
            }

            if ($bannerTxt === '') {
                $_SESSION['error'] = 'El texto del banner es obligatorio.';
                header('Location: index.php?controller=banner&action=editar&id=' . $id);
                exit;
            }

            $ok = $this->modelo->actualizar($id, $bannerTxt, $estado);

            if ($ok) {
                $_SESSION['success'] = 'Banner actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'No se pudo actualizar el banner.';
            }

            header('Location: index.php?controller=banner&action=index');
            exit;
        }
    }

    public function deshabilitar()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            header('Location: index.php?controller=banner&action=index');
            exit;
        }

        $ok = $this->modelo->deshabilitar($id);

        if ($ok) {
            $_SESSION['success'] = 'Banner deshabilitado correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo deshabilitar el banner.';
        }

        header('Location: index.php?controller=banner&action=index');
        exit;
    }

    public function habilitar()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            header('Location: index.php?controller=banner&action=index');
            exit;
        }

        $ok = $this->modelo->habilitar($id);

        if ($ok) {
            $_SESSION['success'] = 'Banner habilitado correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo habilitar el banner.';
        }

        header('Location: index.php?controller=banner&action=index');
        exit;
    }
}