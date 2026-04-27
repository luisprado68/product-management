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

        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id");

        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    public function create(array $data) {
        $sql = "INSERT INTO productos (nombre, precio, descripcion) VALUES (:nombre, :precio, :descripcion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre'      => $data['nombre'],
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion']
        ]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE productos SET nombre = :nombre, precio = :precio, descripcion = :descripcion WHERE id = :id");

        return $stmt->execute([
            'id'      => $data['id'],
            'nombre'      => $data['nombre'],
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion']
        ]);
        return $stmt->fetch();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM productos  WHERE id = :id");

        return $stmt->execute([
            'id'      => $id
        ]);

        return $stmt->fetch();
    }
}



