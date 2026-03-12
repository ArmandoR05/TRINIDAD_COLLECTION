<?php
require_once 'config/database.php';

class Genero
{
    public function obtenerTodos()
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM generos ORDER BY id DESC";
        $resultado = $conexion->query($sql);

        $generos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $generos[] = $fila;
        }

        cerrarConexion($conexion);
        return $generos;
    }

    public function obtenerActivos()
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM generos WHERE estado = 1 ORDER BY nombre ASC";
        $resultado = $conexion->query($sql);

        $datos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }

        cerrarConexion($conexion);
        return $datos;
    }

    public function obtenerPorId($id)
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM generos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $genero = $resultado->fetch_assoc();

        cerrarConexion($conexion);
        return $genero;
    }

    public function insertar($nombre, $slug, $estado)
    {
        $conexion = abrirConexion();

        $sql = "INSERT INTO generos (nombre, slug, estado) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $slug, $estado);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function actualizar($id, $nombre, $slug, $estado)
    {
        $conexion = abrirConexion();

        $sql = "UPDATE generos SET nombre = ?, slug = ?, estado = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssii", $nombre, $slug, $estado, $id);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function cambiarEstado($id, $estado)
    {
        $conexion = abrirConexion();

        $sql = "UPDATE generos SET estado = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $estado, $id);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    
}