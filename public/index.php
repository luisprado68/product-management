<?php
// Cargo el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Bramus\Router\Router;

$router = new Router();

// --- CONFIGURACIÓN DE MANEJADOR GLOBAL ---
set_exception_handler(function (Throwable $e) {
    // Limpio cualquier output previo
    if (ob_get_length()) ob_clean();

    // Defino el código de error
    http_response_code(500);

    // respondo JSON estándar
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage(),
    ]);
    exit;
});

$router->get('/', function() {
    $app_url = getenv('APP_URL') ?: 'http://localhost:8000';
    require_once '../views/main.php';
});

// Rutas de la API
    $router->get('api/productos', '\Luis\Challenge\Controllers\ProductController@index');
    $router->get('api/productos/{id}', '\Luis\Challenge\Controllers\ProductController@detail');
    $router->post('api/productos', '\Luis\Challenge\Controllers\ProductController@store');
    $router->put('api/productos/{id}', '\Luis\Challenge\Controllers\ProductController@edit');
    $router->delete('api/productos/{id}', '\Luis\Challenge\Controllers\ProductController@delete');
$router->run();

