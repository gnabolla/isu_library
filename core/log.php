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

    // Retrieve filtered logs (updated to include s.sex)
    public function getFilteredLogs($filters) {
        $sql = "SELECT l.*, 
                       s.firstname, 
                       s.lastname, 
                       s.course as program, 
                       s.department, 
                       s.rfid,
                       s.sex
                FROM logs l 
                JOIN students s ON l.student_id = s.id 
                WHERE DATE(l.timestamp) BETWEEN :date_from AND :date_to";
        
        $params = [
            'date_from' => $filters['date_from'],
            'date_to'   => $filters['date_to']
        ];

        if (!empty($filters['program'])) {
            $sql .= " AND s.course = :program";
            $params['program'] = $filters['program'];
        }
        if (!empty($filters['department'])) {
            $sql .= " AND s.department = :department";
            $params['department'] = $filters['department'];
        }

        $sql .= " ORDER BY l.timestamp DESC";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    // Get unique programs
    public function getUniquePrograms() {
        $sql = "SELECT DISTINCT course as program FROM students ORDER BY course";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Get unique departments
    public function getUniqueDepartments() {
        $sql = "SELECT DISTINCT department FROM students ORDER BY department";
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
