<?php
namespace Luis\Challenge\Models;

use Luis\Challenge\Core\Database;

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function all() {
        return $this->db->query("SELECT * FROM productos")->fetchAll();
    }

    public function getProduct($id) {
        // 1. Preparamos el query con un "placeholder" (:id)
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id");

        // 2. Ejecutamos pasando el valor de forma segura
        $stmt->execute(['id' => $id]);

        // 3. Usamos fetch() en lugar de fetchAll()
        // Ya que solo esperamos un resultado, fetch() es más limpio
        return $stmt->fetch();
    }

    public function create(array $data) {
        $sql = "INSERT INTO productos (nombre, precio, descripcion) VALUES (:nombre, :precio, :descripcion)";
        $stmt = $this->db->prepare($sql);

        // La ejecución retorna true si tiene éxito
        return $stmt->execute([
            'nombre'      => $data['nombre'],
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion']
        ]);
    }

    public function update($data) {
        // 1. Preparamos el query con un "placeholder" (:id)
        $stmt = $this->db->prepare("UPDATE productos SET nombre = :nombre, precio = :precio, descripcion = :descripcion WHERE id = :id");

        // 2. Ejecutamos pasando el valor de forma segura
        return $stmt->execute([
            'id'      => $data['id'],
            'nombre'      => $data['nombre'],
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion']
        ]);

        // 3. Usamos fetch() en lugar de fetchAll()
        // Ya que solo esperamos un resultado, fetch() es más limpio
        return $stmt->fetch();
    }

    public function delete($id) {
        // 1. Preparamos el query con un "placeholder" (:id)
        $stmt = $this->db->prepare("DELETE FROM productos  WHERE id = :id");

        // 2. Ejecutamos pasando el valor de forma segura
        return $stmt->execute([
            'id'      => $id
        ]);

        // 3. Usamos fetch() en lugar de fetchAll()
        // Ya que solo esperamos un resultado, fetch() es más limpio
        return $stmt->fetch();
    }
    // Aquí agregarías delete() y update()...
}



