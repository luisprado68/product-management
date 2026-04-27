<?php
namespace Luis\Challenge\Models;

use Luis\Challenge\Core\Database;
use PDOException;

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

        // Ejecutamos la consulta
        $success = $stmt->execute([
            'nombre'      => $data['nombre'],
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion']
        ]);

        if ($success) {
            $id = $this->db->lastInsertId();
            return ['id' => (int)$id] + $data;
        }

        // Si falla, devolvemos false (o podrías lanzar una excepción)
        return false;
    }

    public function update($data) {
        try {
            $stmt = $this->db->prepare("UPDATE productos SET nombre = :nombre, precio = :precio, descripcion = :descripcion WHERE id = :id");

            $success = $stmt->execute([
                'id'      => $data['id'],
                'nombre'      => $data['nombre'],
                'precio'      => $data['precio'],
                'descripcion' => $data['descripcion']
            ]);
            if ($success) {
                return ['id' => (int)$data['id']] + $data;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error en DB al actualizar: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM productos  WHERE id = :id");

        return $stmt->execute([
            'id'      => $id
        ]);

        return $stmt->fetch();
    }
}



