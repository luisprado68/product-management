<?php
namespace Luis\Challenge\Controllers;

use Luis\Challenge\Models\ProductModel;

class ProductController {

    private function sendResponse(int $code, bool $success, $data = null, string $message = null) {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        http_response_code($code);

        echo json_encode([
            'success' => $success,
            'data'    => $data,
            'message' => $message
        ]);
        exit;
    }
    public function index() {
        $model = new ProductModel();
        $products = $model->all();

        $rate = (float) (getenv('PRECIO_USD') ?: 1000);

        $data = array_map(function($product) use ($rate) {
            $product['precio_usd'] = round($product['precio'] / $rate, 2);
            return $product;
        }, $products);

        // 1. LIMPIA CUALQUIER SALIDA PREVIA (por si acaso)
        if (ob_get_length()) ob_clean();

        // 2. SETEA EL HEADER PRIMERO
        header('Content-Type: application/json');

        // 3. IMPRIME EL JSON
        echo json_encode($data);

        // 4. DETÉN LA EJECUCIÓN (Fundamental en APIs)
        exit;
    }

    public function detail($id) {
        try {
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if (!$id) {
                return $this->sendResponse(400, false, null, 'ID inválido');
            }

            $model = new ProductModel();
            $product = $model->getProduct($id);

            if (!$product) {
                return $this->sendResponse(404, false, null, 'Producto no encontrado');
            }

            // Lógica de precio
            $rate = (float) (getenv('PRECIO_USD') ?: 1000);
            $product['precio_usd'] = round($product['precio'] / $rate, 2);

            return $this->sendResponse(200, true, $product);

        } catch (\Exception $e) {
            // Lanzamos la excepción para que el manejador global la capture
            throw $e;
        }
    }

    public function store() {
        // 1. Leer el contenido crudo de la petición
        $jsonInput = file_get_contents('php://input');
        $data = json_decode($jsonInput, true);

        // validaciones
        if (!$data || !isset($data['nombre'], $data['precio'])) {
            return $this->sendResponse(400, false, null, 'Datos incompletos o formato JSON inválido');
        }
        if (!filter_var($data['precio'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            return $this->sendResponse(400, false, null, 'Precio debe ser numero y positivo');
        }

        // 3. Persistencia
        $model = new ProductModel();
        if ($model->create($data)) {
            return $this->sendResponse(201, true, ['message' => 'Producto creado con éxito']);
        } else {
            return $this->sendResponse(500, false, null, 'Error al guardar en base de datos');
        }
    }

    public function edit($id) {

        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return $this->sendResponse(400, false, null, 'ID inválido');
        }
        // 1. Leer el contenido crudo de la petición
        $jsonInput = file_get_contents('php://input');
        $data = json_decode($jsonInput, true);
        $data['id'] = $id;
        // validaciones
        if (filter_var($data['precio'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            return $this->sendResponse(400, false, null, 'Precio debe ser numero y positivo');
        }

        // 3. Persistencia
        $model = new ProductModel();
        if ($model->update($data)) {
            return $this->sendResponse(201, true, ['message' => 'Producto actualizado con éxito']);
        } else {
            return $this->sendResponse(500, false, null, 'Error al guardar en base de datos');
        }
    }

    public function delete($id) {

        $id = filter_var($id, FILTER_VALIDATE_INT);
        // validaciones
        if (!$id) {
            return $this->sendResponse(400, false, null, 'ID inválido');
        }
        // 3. Persistencia
        $model = new ProductModel();
        if ($model->delete($id)) {
            return $this->sendResponse(201, true, ['message' => 'Producto actualizado con éxito']);
        } else {
            return $this->sendResponse(500, false, null, 'Error al guardar en base de datos');
        }
    }
}
