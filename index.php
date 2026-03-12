<?php
session_start();

require_once 'config/database.php';
require_once 'config/cloudinary.php';

$controller = $_GET['controller'] ?? 'tienda';
$action = $_GET['action'] ?? 'home';

$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = 'controladores/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    die('El controlador no existe.');
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    die('La clase del controlador no existe.');
}

$controllerInstance = new $controllerName();

if (!method_exists($controllerInstance, $action)) {
    die('La acción no existe.');
}

$controllerInstance->$action();

