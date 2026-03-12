<?php

class DashboardController
{
    public function index()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: index.php');
            exit;
        }

        require_once 'vistas/admin/dashboard.php';
    }
}