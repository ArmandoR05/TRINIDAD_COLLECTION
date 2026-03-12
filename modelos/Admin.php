<?php
require_once 'config/database.php';

class Admin
{
    public function buscarPorUsuario($usuario)
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM admins WHERE usuario = ? AND estado = 1 LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $admin = $resultado->fetch_assoc();

        cerrarConexion($conexion);

        return $admin;
    }
}