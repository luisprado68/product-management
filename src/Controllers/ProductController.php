<?php
namespace Luis\Challenge\Controllers;

use Luis\Challenge\Models\ProductModel;

class ProductController {

    /**
     * Envía una respuesta JSON
     * @param int $code
     * @param bool $success
     * @param $data
     * @param string|null $message
     * @return void
     */
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

    /**
     * Obtiene todos los productos
     * @return void
     */
    public function index() {
        $model = new ProductModel();
        $products = $model->all();

        $rate = (float) (getenv('PRECIO_USD') ?: 1000);

        $data = array_map(function($product) use ($rate) {
            $product['precio_usd'] = round($product['precio'] / $rate, 2);
            return $product;
        }, $products);

        return $this->sendResponse(200, true, $data,null);
    }

    /**
     * Obtiene un producto por ID
     * @param $id
     * @return null
     * @throws \Exception
     */
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

            $rate = (float) (getenv('PRECIO_USD') ?: 1000);
            $product['precio_usd'] = round($product['precio'] / $rate, 2);

            return $this->sendResponse(200, true, $product);

        } catch (\Exception $e) {
            // Lanzamos la excepción para que el manejador global la capture
            throw $e;
        }
    }

    /**
     * Crea un nuevo producto
     * @return null
     */
    public function store() {
        // 1. Leer el contenido crudo de la petición
        $jsonInput = file_get_contents('php://input');
        $data = json_decode($jsonInput, true);

        // validaciones
        if (!$data || !isset($data['nombre']) || !isset($data['precio'])) {
            return $this->sendResponse(400, false, null, 'Datos incompletos o formato JSON inválido');
        }

        $precio_limpio = str_replace(',', '.', $data['precio']);
        if ($data['precio'] === null || !is_numeric($precio_limpio) || floatval($precio_limpio) <= 0) {

            return $this->sendResponse(400, false, null, 'Precio debe ser numero y positivo');
        }

        // 3. Persistencia
        $model = new ProductModel();
        $nuevoProducto = $model->create($data);

        if ($nuevoProducto) {
            // Pasamos $nuevoProducto en lugar de null
            return $this->sendResponse(201, true, $nuevoProducto, 'Producto creado con éxito');
        } else {
            return $this->sendResponse(500, false, null, 'Error al guardar en base de datos');
        }
    }

    /**
     * Edita un producto por ID
     * @param $id
     * @return null
     */
    public function edit($id) {

        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return $this->sendResponse(400, false, null, 'ID inválido');
        }
        // 1. Leer el contenido crudo de la petición
        $jsonInput = file_get_contents('php://input');

        error_log("CONTENIDO CRUDO RECIBIDO: " . $jsonInput);

        $data = json_decode($jsonInput, true);


        $data['id'] = $id;
        // validaciones
        if (!filter_var($data['precio'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            return $this->sendResponse(400, false, null, 'Precio debe ser numero y positivo');
        }

        $model = new ProductModel();
        $productoEditado = $model->update($data);
        if ($productoEditado) {
            return $this->sendResponse(201, true, $productoEditado,'Producto actualizado con éxito');
        } else {
            return $this->sendResponse(500, false, null, 'Error al guardar en base de datos');
        }
    }

    /**
     * Elimina un producto por ID
     * @param $id
     * @return null
     */
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
