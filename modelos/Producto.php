<?php
require_once 'config/database.php';

class Producto
{
    public function obtenerTodos()
    {
        $conexion = abrirConexion();

        $sql = "SELECT p.*, c.nombre AS categoria_nombre, g.nombre AS genero_nombre
                FROM productos p
                INNER JOIN categorias c ON p.categoria_id = c.id
                INNER JOIN generos g ON c.genero_id = g.id
                ORDER BY p.id DESC";

        $resultado = $conexion->query($sql);

        $productos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }

        cerrarConexion($conexion);
        return $productos;
    }

    public function obtenerPorId($id)
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM productos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();

        cerrarConexion($conexion);
        return $producto;
    }

    public function insertar($datos)
    {
        $conexion = abrirConexion();

        $sql = "INSERT INTO productos
                (categoria_id, nombre, slug, descripcion, precio, imagen_principal, color, tallas, disponible, es_nuevo, destacado, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            "isssdsssiiii",
            $datos['categoria_id'],
            $datos['nombre'],
            $datos['slug'],
            $datos['descripcion'],
            $datos['precio'],
            $datos['imagen_principal'],
            $datos['color'],
            $datos['tallas'],
            $datos['disponible'],
            $datos['es_nuevo'],
            $datos['destacado'],
            $datos['estado']
        );

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function actualizar($id, $datos)
    {
        $conexion = abrirConexion();

        $sql = "UPDATE productos
                SET categoria_id = ?, nombre = ?, slug = ?, descripcion = ?, precio = ?, imagen_principal = ?, color = ?, tallas = ?, disponible = ?, es_nuevo = ?, destacado = ?, estado = ?
                WHERE id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            "isssdsssiiiii",
            $datos['categoria_id'],
            $datos['nombre'],
            $datos['slug'],
            $datos['descripcion'],
            $datos['precio'],
            $datos['imagen_principal'],
            $datos['color'],
            $datos['tallas'],
            $datos['disponible'],
            $datos['es_nuevo'],
            $datos['destacado'],
            $datos['estado'],
            $id
        );

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function cambiarEstado($id, $estado)
    {
        $conexion = abrirConexion();

        $sql = "UPDATE productos SET estado = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $estado, $id);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function obtenerCategoriasActivas()
    {
        $conexion = abrirConexion();

        $sql = "SELECT c.id, c.nombre, g.nombre AS genero_nombre
                FROM categorias c
                INNER JOIN generos g ON c.genero_id = g.id
                WHERE c.estado = 1 AND g.estado = 1
                ORDER BY g.nombre ASC, c.nombre ASC";

        $resultado = $conexion->query($sql);

        $categorias = [];
        while ($fila = $resultado->fetch_assoc()) {
            $categorias[] = $fila;
        }

        cerrarConexion($conexion);
        return $categorias;
    }

    public function insertarImagenSecundaria($productoId, $imagen, $orden = 1)
    {
        $conexion = abrirConexion();

        $sql = "INSERT INTO producto_imagenes (producto_id, imagen, orden) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isi", $productoId, $imagen, $orden);

        $ok = $stmt->execute();

        cerrarConexion($conexion);
        return $ok;
    }

    public function obtenerImagenesSecundarias($productoId)
    {
        $conexion = abrirConexion();

        $sql = "SELECT * FROM producto_imagenes WHERE producto_id = ? ORDER BY orden ASC, id ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $productoId);
        $stmt->execute();

        $resultado = $stmt->get_result();

        $imagenes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $imagenes[] = $fila;
        }

        cerrarConexion($conexion);
        return $imagenes;
    }

    public function eliminarImagenSecundaria($id)
    {
        $conexion = abrirConexion();

        $sql = "SELECT imagen FROM producto_imagenes WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $imagen = $resultado->fetch_assoc();

        if ($imagen && !empty($imagen['imagen'])) {
            $publicId = cloudinaryPublicId($imagen['imagen']);
            if ($publicId !== '') {
                cloudinary_eliminar($publicId);
            }
        }

        $sqlDelete = "DELETE FROM producto_imagenes WHERE id = ?";
        $stmtDelete = $conexion->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $id);
        $ok = $stmtDelete->execute();

        cerrarConexion($conexion);
        return $ok;
    }


    public function obtenerNuevos($limite = 8)
    {
        $conexion = abrirConexion();

        $stmt = $conexion->prepare("
        SELECT 
            p.*, 
            c.nombre AS categoria_nombre, 
            c.slug AS categoria_slug,
            g.nombre AS genero_nombre, 
            g.slug AS genero_slug,
            pi.imagen AS imagen_secundaria
        FROM productos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        INNER JOIN generos g ON c.genero_id = g.id
        LEFT JOIN producto_imagenes pi 
            ON pi.producto_id = p.id 
            AND pi.orden = 1
        WHERE p.estado = 1
          AND p.disponible = 1
          AND p.es_nuevo = 1
          AND c.estado = 1
          AND g.estado = 1
        ORDER BY p.created_at DESC
        LIMIT ?
    ");

        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $datos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }

        cerrarConexion($conexion);
        return $datos;
    }

    public function obtenerDestacados($limite = 8)
    {
        $conexion = abrirConexion();

        $stmt = $conexion->prepare("
        SELECT 
            p.*, 
            c.nombre AS categoria_nombre, 
            c.slug AS categoria_slug,
            g.nombre AS genero_nombre, 
            g.slug AS genero_slug,
            pi.imagen AS imagen_secundaria
        FROM productos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        INNER JOIN generos g ON c.genero_id = g.id
        LEFT JOIN producto_imagenes pi 
            ON pi.producto_id = p.id 
            AND pi.orden = 1
        WHERE p.estado = 1
          AND p.disponible = 1
          AND p.destacado = 1
          AND c.estado = 1
          AND g.estado = 1
        ORDER BY p.created_at DESC
        LIMIT ?
    ");

        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $datos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }

        cerrarConexion($conexion);
        return $datos;
    }

    public function obtenerCatalogo($generoSlug = null, $categoriaSlug = null)
    {
        $conexion = abrirConexion();

        $sql = "
        SELECT 
            p.*, 
            c.nombre AS categoria_nombre, 
            c.slug AS categoria_slug,
            g.nombre AS genero_nombre, 
            g.slug AS genero_slug,
            pi.imagen AS imagen_secundaria
        FROM productos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        INNER JOIN generos g ON c.genero_id = g.id
        LEFT JOIN producto_imagenes pi 
            ON pi.producto_id = p.id 
            AND pi.orden = 1
        WHERE p.estado = 1
          AND p.disponible = 1
          AND c.estado = 1
          AND g.estado = 1
    ";

        if (!empty($generoSlug)) {
            $generoSlug = $conexion->real_escape_string($generoSlug);
            $sql .= " AND g.slug = '{$generoSlug}'";
        }

        if (!empty($categoriaSlug)) {
            $categoriaSlug = $conexion->real_escape_string($categoriaSlug);
            $sql .= " AND c.slug = '{$categoriaSlug}'";
        }

        $sql .= " ORDER BY p.created_at DESC";

        $resultado = $conexion->query($sql);

        $datos = [];

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $datos[] = $fila;
            }
        }

        cerrarConexion($conexion);
        return $datos;
    }

    public function obtenerPorSlugPublico($slug)
    {
        $conexion = abrirConexion();

        $stmt = $conexion->prepare("
        SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug,
               g.nombre AS genero_nombre, g.slug AS genero_slug
        FROM productos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        INNER JOIN generos g ON c.genero_id = g.id
        WHERE p.slug = ?
          AND p.estado = 1
          AND c.estado = 1
          AND g.estado = 1
        LIMIT 1
    ");

        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $dato = $resultado->fetch_assoc();

        cerrarConexion($conexion);
        return $dato;
    }


    public function obtenerRelacionadosPorCategoria($categoriaId, $excluirId = null, $limite = 4)
    {
        $conexion = abrirConexion();

        $sql = "
        SELECT 
            p.*, 
            c.nombre AS categoria_nombre, 
            c.slug AS categoria_slug,
            g.nombre AS genero_nombre, 
            g.slug AS genero_slug,
            pi.imagen AS imagen_secundaria
        FROM productos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        INNER JOIN generos g ON c.genero_id = g.id
        LEFT JOIN producto_imagenes pi 
            ON pi.producto_id = p.id 
            AND pi.orden = 1
        WHERE p.estado = 1
          AND p.disponible = 1
          AND c.estado = 1
          AND g.estado = 1
          AND p.categoria_id = ?
    ";

        if ($excluirId !== null) {
            $sql .= " AND p.id <> ?";
        }

        $sql .= " ORDER BY p.created_at DESC LIMIT ?";

        $stmt = $conexion->prepare($sql);

        if ($excluirId !== null) {
            $stmt->bind_param("iii", $categoriaId, $excluirId, $limite);
        } else {
            $stmt->bind_param("ii", $categoriaId, $limite);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        $productos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }

        $stmt->close();
        cerrarConexion($conexion);

        return $productos;
    }

    



}

