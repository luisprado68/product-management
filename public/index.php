<?php
// 1. Cargar el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Luis\Challenge\Core\Database;
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

// Rutas de la API
$router->get('/productos', '\Luis\Challenge\Controllers\ProductController@index');
$router->get('/productos/{id}', '\Luis\Challenge\Controllers\ProductController@detail');

$router->post('/productos', '\Luis\Challenge\Controllers\ProductController@store');
$router->put('/productos/{id}', '\Luis\Challenge\Controllers\ProductController@edit');
$router->delete('/productos/{id}', '\Luis\Challenge\Controllers\ProductController@delete');

$router->run();

