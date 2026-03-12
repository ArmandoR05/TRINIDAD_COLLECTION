<?php

define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

function abrirConexion()
{
    $host     = getenv('MYSQLHOST')     ?: '127.0.0.1';
    $user     = getenv('MYSQLUSER')     ?: 'root';
    $password = getenv('MYSQLPASSWORD') ?: 'Root1234!';
    $db       = getenv('MYSQLDATABASE') ?: 'TRINIDAD_STUDIOS_DB';
    $port     = (int)(getenv('MYSQLPORT') ?: 3307);

    $mysqli = new mysqli($host, $user, $password, $db, $port);

    if ($mysqli->connect_errno) {
        throw new Exception("Error de conexión a la base de datos: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8mb4");

    return $mysqli;
}

function cerrarConexion($mysqli)
{
    if ($mysqli instanceof mysqli) {
        $mysqli->close();
    }
}
