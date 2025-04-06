<?php

namespace Core;

class Course
{
    private $db;

    public function __construct(\Database $db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM courses ORDER BY name")->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->db->query("SELECT * FROM courses WHERE id = :id", ['id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $this->db->query("INSERT INTO courses (name) VALUES (:name)", [
            'name' => $data['name']
        ]);
    }

    public function update(int $id, array $data)
    {
        $data['id'] = $id;
        $this->db->query("UPDATE courses SET name = :name WHERE id = :id", $data);
    }

    public function delete(int $id)
    {
        $this->db->query("DELETE FROM courses WHERE id = :id", ['id' => $id]);
    }
}
