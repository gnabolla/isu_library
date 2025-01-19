<?php

namespace Core;

class Student
{
    private $db;

    public function __construct(\Database $db)
    {
        $this->db = $db;
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO students 
                (firstname, middlename, lastname, year, course, section, department, rfid, image, sex)
                VALUES 
                (:firstname, :middlename, :lastname, :year, :course, :section, :department, :rfid, :image, :sex)";
        $this->db->query($sql, $data);
    }

    public function update(int $id, array $data)
    {
        $sql = "UPDATE students 
                   SET firstname   = :firstname,
                       middlename  = :middlename,
                       lastname    = :lastname,
                       year        = :year,
                       course      = :course,
                       section     = :section,
                       department  = :department,
                       rfid        = :rfid,
                       image       = :image,
                       sex         = :sex
                 WHERE id = :id";
        $data['id'] = $id;
        $this->db->query($sql, $data);
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM students WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM students WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }

    public function getByRFID(string $rfid)
    {
        $sql = "SELECT * FROM students WHERE rfid = :rfid";
        $stmt = $this->db->query($sql, ['rfid' => $rfid]);
        return $stmt->fetch();
    }

    public function getAll(array $filters = [], string $search = '')
    {
        $sql = "SELECT * FROM students WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (firstname LIKE :search 
                       OR middlename LIKE :search
                       OR lastname LIKE :search 
                       OR rfid LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        if (!empty($filters['year'])) {
            $sql .= " AND year = :year";
            $params['year'] = $filters['year'];
        }

        if (!empty($filters['course'])) {
            $sql .= " AND course LIKE :course";
            $params['course'] = "%".$filters['course']."%";
        }

        if (!empty($filters['section'])) {
            $sql .= " AND section LIKE :section";
            $params['section'] = "%".$filters['section']."%";
        }

        if (!empty($filters['department'])) {
            $sql .= " AND department LIKE :department";
            $params['department'] = "%".$filters['department']."%";
        }

        if (!empty($filters['sex'])) {
            $sql .= " AND sex = :sex";
            $params['sex'] = $filters['sex'];
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
}
