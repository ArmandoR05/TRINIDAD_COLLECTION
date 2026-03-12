<?php

// =============================================
//  HELPER — URL de imagen (local o Cloudinary)
// =============================================

/**
 * Retorna la URL correcta de una imagen,
 * sea una ruta local (assets/...) o una URL de Cloudinary (https://...).
 *
 * Uso en vistas:  src="<?= imgUrl($producto['imagen_principal']) ?>"
 */
function imgUrl(string $ruta): string
{
    if (empty($ruta)) {
        return '';
    }

    // Si ya es una URL absoluta (Cloudinary u otro CDN), la retorna tal cual
    if (str_starts_with($ruta, 'http://') || str_starts_with($ruta, 'https://')) {
        return $ruta;
    }

    // Si es ruta local, le agrega el BASE_URL
    return BASE_URL . '/' . ltrim($ruta, '/');
}

// =============================================
//  CLOUDINARY — Almacenamiento de imágenes
//  Crear cuenta gratis en cloudinary.com
//  Dashboard → Settings → API Keys
// =============================================

define('CLOUDINARY_CLOUD_NAME', getenv('CLOUDINARY_CLOUD_NAME') ?: 'dmxryjdv5');
define('CLOUDINARY_API_KEY',    getenv('CLOUDINARY_API_KEY')    ?: '369428175478381');
define('CLOUDINARY_API_SECRET', getenv('CLOUDINARY_API_SECRET') ?: '0eGbyknaRvqUwj7e5vXcjTG5U9k');

/**
 * Sube una imagen a Cloudinary y retorna un array con el resultado.
 * Usa la API REST directamente (sin SDK).
 *
 * @param string $archivoTmp  Ruta temporal del archivo ($_FILES['campo']['tmp_name'])
 * @param string $carpeta     Carpeta destino en Cloudinary (ej: 'productos')
 * @return array              ['success' => bool, 'url' => string, 'error' => string]
 */
function subirImagenCloudinary(string $archivoTmp, string $carpeta = 'productos'): array
{
    $timestamp = time();
    $firma     = sha1("folder={$carpeta}&timestamp={$timestamp}" . CLOUDINARY_API_SECRET);

    $url = 'https://api.cloudinary.com/v1_1/' . CLOUDINARY_CLOUD_NAME . '/image/upload';

    $campos = [
        'file'      => new CURLFile($archivoTmp),
        'api_key'   => CLOUDINARY_API_KEY,
        'timestamp' => $timestamp,
        'folder'    => $carpeta,
        'signature' => $firma,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $campos);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $respuesta = curl_exec($ch);
    $error     = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['success' => false, 'url' => '', 'error' => $error];
    }

    $datos = json_decode($respuesta, true);

    if (!empty($datos['secure_url'])) {
        return ['success' => true, 'url' => $datos['secure_url'], 'error' => ''];
    }

    $mensajeError = $datos['error']['message'] ?? 'Error desconocido de Cloudinary';
    return ['success' => false, 'url' => '', 'error' => $mensajeError];
}

/**
 * Extrae el public_id de una URL de Cloudinary.
 * Ej: https://res.cloudinary.com/cloud/image/upload/v123/productos/foto.jpg → productos/foto
 */
function cloudinaryPublicId(string $url): string
{
    if (!str_contains($url, '/upload/')) {
        return '';
    }
    $partes = explode('/upload/', $url, 2);
    $path   = preg_replace('#^v\d+/#', '', $partes[1]); // quita versión
    $path   = preg_replace('/\.[^.]+$/', '', $path);     // quita extensión
    return $path;
}

/**
 * Elimina una imagen de Cloudinary por su public_id.
 *
 * @param string $publicId  El public_id de la imagen (sin extensión)
 * @return bool
 */
function cloudinary_eliminar(string $publicId): bool
{
    $timestamp = time();
    $firma     = sha1("public_id={$publicId}&timestamp={$timestamp}" . CLOUDINARY_API_SECRET);

    $url = 'https://api.cloudinary.com/v1_1/' . CLOUDINARY_CLOUD_NAME . '/image/destroy';

    $campos = [
        'public_id' => $publicId,
        'api_key'   => CLOUDINARY_API_KEY,
        'timestamp' => $timestamp,
        'signature' => $firma,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($campos));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $respuesta = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return ($respuesta['result'] ?? '') === 'ok';
}
