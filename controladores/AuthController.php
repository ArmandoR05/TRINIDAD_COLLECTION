<?php
require_once 'modelos/Admin.php';

class AuthController
{
    public function showLogin()
    {
        require_once 'vistas/admin/login.php';
    }

    public function login()
    {
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        $adminModel = new Admin();
        $admin = $adminModel->buscarPorUsuario($usuario);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nombre'] = $admin['nombre'];

            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        $error = "Usuario o contraseña incorrectos";
        require_once 'vistas/admin/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}