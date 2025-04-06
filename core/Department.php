<?php
namespace Core;

class Department {
    private $db;

    public function __construct(\Database $db) {
        $this->db = $db;
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM departments ORDER BY name")->fetchAll();
    }

    public function getById(int $id) {
        $stmt = $this->db->query("SELECT * FROM departments WHERE id = :id", ['id' => $id]);
        return $stmt->fetch() ?? abort(404);
    }

    public function create(array $data) {
        $this->db->query("INSERT INTO departments (name) VALUES (:name)", $data);
    }

    public function update(int $id, array $data) {
        $data['id'] = $id;
        $this->db->query("UPDATE departments SET name = :name WHERE id = :id", $data);
    }

    public function delete(int $id) {
        $this->db->query("DELETE FROM departments WHERE id = :id", ['id' => $id]);
    }
}