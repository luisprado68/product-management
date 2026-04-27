<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Bramus\Router\Router;

$router = new Router();

// --- Manejador global ---
set_exception_handler(function (Throwable $e) {
   //Limpio cualquier buffer de salida
    if (ob_get_length()) ob_clean();

    http_response_code(500);
    header('Content-Type: application/json');

    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage(),
    ]);
    exit;
});


$router->get('/', function() {
    require_once '../views/main.php';
});

// Rutas de la API
    $router->get('api/productos', '\Luis\Challenge\Controllers\ProductController@index');
    $router->get('api/productos/{id}', '\Luis\Challenge\Controllers\ProductController@detail');
    $router->post('api/productos', '\Luis\Challenge\Controllers\ProductController@store');
    $router->put('api/productos/{id}', '\Luis\Challenge\Controllers\ProductController@edit');
    $router->delete('api/productos/{id}', '\Luis\Challenge\Controllers\ProductController@delete');
    $router->set404(function() {
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['success' => false,'message' => 'Ruta no encontrada']);
        exit;
    });
$router->run();

