<?php
// core/Student.php

namespace Core;

class Student
{
    private $db;

    public function __construct(\Database $db)
    {
        $this->db = $db;
    }

    // Create a new student
    public function create(array $data): bool
    {
        $sql = 'INSERT INTO students (firstname, lastname, year, course, section, department, rfid, image, sex)
                VALUES (:firstname, :lastname, :year, :course, :section, :department, :rfid, :image, :sex)';
        $stmt = $this->db->query($sql, $data);
        return $stmt->rowCount() > 0;
    }

    // Read all students with optional search and filter
    public function getAll(array $filters = [], string $search = ''): array
    {
        $sql = 'SELECT * FROM students WHERE 1';
        $params = [];

        // Apply search
        if (!empty($search)) {
            $sql .= ' AND (firstname LIKE :search OR lastname LIKE :search OR rfid LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        // Apply filters
        foreach ($filters as $key => $value) {
            if (in_array($key, ['year', 'course', 'section', 'department', 'sex'])) {
                $sql .= " AND {$key} = :{$key}";
                $params[$key] = $value;
            }
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    // Get a single student by ID
    public function getById(int $id): ?array
    {
        $stmt = $this->db->query('SELECT * FROM students WHERE id = :id', ['id' => $id]);
        $student = $stmt->fetch();
        return $student ? $student : null;
    }

    // Update a student
    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE students SET firstname = :firstname, lastname = :lastname, year = :year, course = :course, 
                section = :section, department = :department, rfid = :rfid, image = :image, sex = :sex 
                WHERE id = :id';
        $data['id'] = $id;
        $stmt = $this->db->query($sql, $data);
        return $stmt->rowCount() > 0;
    }

    // Delete a student
    public function delete(int $id): bool
    {
        $stmt = $this->db->query('DELETE FROM students WHERE id = :id', ['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Get a single student by RFID
    public function getByRFID(string $rfid): ?array
    {
        $stmt = $this->db->query('SELECT * FROM students WHERE rfid = :rfid', ['rfid' => $rfid]);
        $student = $stmt->fetch();
        return $student ? $student : null;
    }
}
