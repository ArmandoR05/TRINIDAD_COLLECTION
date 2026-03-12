<?php
require_once 'config/database.php';

class Categoria
{
    public function obtenerTodos()
    {
        $conexion = abrirConexion();

        $sql = "SELECT c.*, g.nombre AS genero_nombre
                FROM categorias c
                INNER JOIN generos g ON c.genero_id = g.id
                ORDER BY c.id DESC";

        $resultado = $conexion->query($sql);

        $categorias = [];
        while ($fila = $resultado->fetch_assoc()) {
            $categorias[] = $fila;
        }

        cerrarConexion($conexion);
        return $categorias;
    }

    public function obtenerPorId($id)
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM categorias WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $categoria = $resultado->fetch_assoc();

        cerrarConexion($conexion);
        return $categoria;
    }

    public function insertar($generoId, $nombre, $slug, $estado)
    {
        $conexion = abrirConexion();

        $sql = "INSERT INTO categorias (genero_id, nombre, slug, estado)
                VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("issi", $generoId, $nombre, $slug, $estado);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function actualizar($id, $generoId, $nombre, $slug, $estado)
    {
        $conexion = abrirConexion();

        $sql = "UPDATE categorias
                SET genero_id = ?, nombre = ?, slug = ?, estado = ?
                WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("issii", $generoId, $nombre, $slug, $estado, $id);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function cambiarEstado($id, $estado)
    {
        $conexion = abrirConexion();

        $sql = "UPDATE categorias SET estado = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $estado, $id);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function obtenerGenerosActivos()
    {
        $conexion = abrirConexion();

        $sql = "SELECT id, nombre FROM generos WHERE estado = 1 ORDER BY nombre ASC";
        $resultado = $conexion->query($sql);

        $generos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $generos[] = $fila;
        }

        cerrarConexion($conexion);
        return $generos;
    }

    public function obtenerActivasPorGeneroSlug($slug = null)
    {
        $conexion = abrirConexion();

        if (!empty($slug)) {
            $stmt = $conexion->prepare("
            SELECT c.*
            FROM categorias c
            INNER JOIN generos g ON c.genero_id = g.id
            WHERE c.estado = 1 AND g.estado = 1 AND g.slug = ?
            ORDER BY c.nombre ASC
        ");
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $resultado = $stmt->get_result();
        } else {
            $resultado = $conexion->query("
            SELECT *
            FROM categorias
            WHERE estado = 1
            ORDER BY nombre ASC
        ");
        }

        $datos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }

        cerrarConexion($conexion);
        return $datos;
    }
    public function obtenerActivasAgrupadasPorGenero()
    {
        $conexion = abrirConexion();

        $sql = "
        SELECT 
            g.id AS genero_id,
            g.nombre AS genero_nombre,
            g.slug AS genero_slug,
            c.id AS categoria_id,
            c.nombre AS categoria_nombre,
            c.slug AS categoria_slug
        FROM generos g
        LEFT JOIN categorias c 
            ON c.genero_id = g.id 
            AND c.estado = 1
        WHERE g.estado = 1
        ORDER BY g.nombre ASC, c.nombre ASC
    ";

        $resultado = $conexion->query($sql);

        $menu = [];

        while ($fila = $resultado->fetch_assoc()) {
            $generoId = $fila['genero_id'];

            if (!isset($menu[$generoId])) {
                $menu[$generoId] = [
                    'id' => $fila['genero_id'],
                    'nombre' => $fila['genero_nombre'],
                    'slug' => $fila['genero_slug'],
                    'categorias' => []
                ];
            }

            if (!empty($fila['categoria_id'])) {
                $menu[$generoId]['categorias'][] = [
                    'id' => $fila['categoria_id'],
                    'nombre' => $fila['categoria_nombre'],
                    'slug' => $fila['categoria_slug']
                ];
            }
        }

        cerrarConexion($conexion);

        return array_values($menu);
    }
    
}