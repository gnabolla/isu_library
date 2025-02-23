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
                (firstname, middlename, lastname, year, course_id, section, department_id, rfid, image, sex)
                VALUES 
                (:firstname, :middlename, :lastname, :year, :course_id, :section, :department_id, :rfid, :image, :sex)";
        $this->db->query($sql, $data);
    }

    public function update(int $id, array $data)
    {
        $sql = "UPDATE students 
                   SET firstname     = :firstname,
                       middlename    = :middlename,
                       lastname      = :lastname,
                       year          = :year,
                       course_id     = :course_id,
                       section       = :section,
                       department_id = :department_id,
                       rfid          = :rfid,
                       image         = :image,
                       sex           = :sex
                 WHERE id = :id";
        $data['id'] = $id;
        $this->db->query($sql, $data);
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM students WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

    // Pull in department/course names via LEFT JOIN for display
    public function getById(int $id)
    {
        $sql = "SELECT s.*, 
                       c.name AS course_name,
                       d.name AS department_name
                  FROM students s
             LEFT JOIN courses c ON s.course_id = c.id
             LEFT JOIN departments d ON s.department_id = d.id
                 WHERE s.id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }

    // Added getByRFID method to fetch student by RFID with JOINs
    public function getByRFID(string $rfid)
    {
        $sql = "SELECT s.*, 
                       c.name AS course_name,
                       d.name AS department_name
                  FROM students s
             LEFT JOIN courses c ON s.course_id = c.id
             LEFT JOIN departments d ON s.department_id = d.id
                 WHERE s.rfid = :rfid";
        $stmt = $this->db->query($sql, ['rfid' => $rfid]);
        return $stmt->fetch();
    }

    // Use JOIN to show the actual course & department names
    public function getAll(array $filters = [], string $search = '')
    {
        $sql = "SELECT s.*,
                       c.name AS course_name,
                       d.name AS department_name
                  FROM students s
             LEFT JOIN courses c ON s.course_id = c.id
             LEFT JOIN departments d ON s.department_id = d.id
                 WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (
                        s.firstname LIKE :search OR
                        s.middlename LIKE :search OR
                        s.lastname LIKE :search OR
                        s.rfid LIKE :search
                      )";
            $params['search'] = "%{$search}%";
        }

        if (!empty($filters['year'])) {
            $sql .= " AND s.year = :year";
            $params['year'] = $filters['year'];
        }
        if (!empty($filters['course'])) {
            $sql .= " AND c.name LIKE :course";
            $params['course'] = "%".$filters['course']."%";
        }
        if (!empty($filters['section'])) {
            $sql .= " AND s.section LIKE :section";
            $params['section'] = "%".$filters['section']."%";
        }
        if (!empty($filters['department'])) {
            $sql .= " AND d.name LIKE :department";
            $params['department'] = "%".$filters['department']."%";
        }
        if (!empty($filters['sex'])) {
            $sql .= " AND s.sex = :sex";
            $params['sex'] = $filters['sex'];
        }

        $sql .= " ORDER BY s.id DESC";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
}
