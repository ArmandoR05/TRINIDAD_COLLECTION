<?php
require_once 'config/database.php';

class Banner
{



    public function obtenerTodos()
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM Banner ORDER BY id DESC";
        $resultado = $conexion->query($sql);

        $banners = [];

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $banners[] = $fila;
            }
        }

        cerrarConexion($conexion);
        return $banners;
    }


    public function obtenerActivos()
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM Banner WHERE estado = 1 ORDER BY id DESC";
        $resultado = $conexion->query($sql);

        $banners = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $banners[] = $fila;
            }
        }

        cerrarConexion($conexion);
        return $banners;
    }

    public function obtenerPorId($id)
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM Banner WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $banner = $resultado->fetch_assoc();

        cerrarConexion($conexion);
        return $banner;
    }

    public function insertar($bannerTxt, $estado = 1)
    {
        $conexion = abrirConexion();

        $sql = "INSERT INTO Banner (BannerTxt, estado) VALUES (?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $bannerTxt, $estado);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function actualizar($id, $bannerTxt, $estado)
    {
        $conexion = abrirConexion();

        $sql = "UPDATE Banner 
                SET BannerTxt = ?, estado = ?
                WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sii", $bannerTxt, $estado, $id);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function deshabilitar($id)
    {
        $conexion = abrirConexion();

        $sql = "UPDATE Banner SET estado = 0 WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function habilitar($id)
    {
        $conexion = abrirConexion();

        $sql = "UPDATE Banner SET estado = 1 WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }
}