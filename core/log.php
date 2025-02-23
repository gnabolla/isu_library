<?php
// core/Log.php

namespace Core;

class Log {
    private $db;

    public function __construct(\Database $db) {
        $this->db = $db;
    }

    // Create a time-in or time-out entry
    public function createTimeLog(int $student_id, string $type): bool {
        $sql = 'INSERT INTO logs (student_id, type) VALUES (:student_id, :type)';
        $stmt = $this->db->query($sql, [
            'student_id' => $student_id,
            'type'       => $type
        ]);
        return ($stmt !== false);
    }

    // Get today's log (in or out) for a student
    public function getTodaysLogByType(int $student_id, string $type) {
        $sql = "
            SELECT * FROM logs
            WHERE student_id = :student_id
              AND DATE(timestamp) = CURDATE()
              AND type = :type
            ORDER BY timestamp DESC
            LIMIT 1
        ";
        $stmt = $this->db->query($sql, [
            'student_id' => $student_id,
            'type'       => $type
        ]);
        return $stmt->fetch();
    }

    // Get the most recent log (in or out) for a student (today)
    public function getMostRecentLogToday(int $student_id) {
        $sql = "
            SELECT * FROM logs
            WHERE student_id = :student_id
              AND DATE(timestamp) = CURDATE()
            ORDER BY timestamp DESC
            LIMIT 1
        ";
        $stmt = $this->db->query($sql, [
            'student_id' => $student_id
        ]);
        return $stmt->fetch();
    }

    // Buffer check in seconds
    public function withinBuffer(int $student_id, int $bufferSeconds = 10): bool {
        $sql = "
            SELECT * FROM logs
            WHERE student_id = :student_id
            ORDER BY timestamp DESC
            LIMIT 1
        ";
        $stmt = $this->db->query($sql, [
            'student_id' => $student_id
        ]);
        $lastLog = $stmt->fetch();
        if (!$lastLog) {
            return false;
        }
        $lastTime = strtotime($lastLog['timestamp']);
        $now      = time();
        $diff     = $now - $lastTime;
        return ($diff < $bufferSeconds);
    }

    // Retrieve filtered logs, properly joining courses and departments
    public function getFilteredLogs(array $filters) {
        // We'll JOIN courses (c) and departments (d) to get 'program' and 'department' names
        $sql = "SELECT l.*,
                       s.firstname,
                       s.lastname,
                       s.rfid,
                       s.sex,
                       c.name AS program,
                       d.name AS department
                FROM logs l
                JOIN students s ON l.student_id = s.id
                LEFT JOIN courses c ON s.course_id = c.id
                LEFT JOIN departments d ON s.department_id = d.id
                WHERE DATE(l.timestamp) BETWEEN :date_from AND :date_to";

        $params = [
            'date_from' => $filters['date_from'],
            'date_to'   => $filters['date_to']
        ];

        // Filter by course name if provided
        if (!empty($filters['program'])) {
            $sql .= " AND c.name = :program";
            $params['program'] = $filters['program'];
        }

        // Filter by department name if provided
        if (!empty($filters['department'])) {
            $sql .= " AND d.name = :department";
            $params['department'] = $filters['department'];
        }

        // Filter by type ('in' or 'out')
        if (!empty($filters['type'])) {
            $sql .= " AND l.type = :type";
            $params['type'] = $filters['type'];
        }

        $sql .= " ORDER BY l.timestamp DESC";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    // Get unique programs (based on courses table)
    public function getUniquePrograms() {
        $sql = "SELECT DISTINCT name as program
                FROM courses
                ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Get unique departments (based on departments table)
    public function getUniqueDepartments() {
        $sql = "SELECT DISTINCT name as department
                FROM departments
                ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Get all logs for a specific student
    public function getLogsByStudentId(int $student_id): array {
        $sql = "SELECT * FROM logs 
                WHERE student_id = :student_id
                ORDER BY timestamp DESC";
        $stmt = $this->db->query($sql, [ 'student_id' => $student_id ]);
        return $stmt->fetchAll();
    }
}
