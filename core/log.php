<?php
// core/Log.php

namespace Core;

class Log {
    private $db;

    public function __construct(\Database $db) {
        $this->db = $db;
    }

    // Create a new log entry
    public function create(int $student_id): bool {
        $sql = 'INSERT INTO logs (student_id) VALUES (:student_id)';
        $stmt = $this->db->query($sql, ['student_id' => $student_id]);
        return $stmt->rowCount() > 0;
    }

    // Optional: Retrieve logs (e.g., for admin purposes)
    public function getAll(array $filters = [], string $search = '', int $limit = 100, int $offset = 0): array {
        $sql = 'SELECT logs.*, students.firstname, students.lastname FROM logs
                JOIN students ON logs.student_id = students.id WHERE 1';
        $params = [];

        // Apply search
        if (!empty($search)) {
            $sql .= ' AND (students.firstname LIKE :search OR students.lastname LIKE :search OR students.rfid LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        // Apply filters if any (e.g., date range)

        // Pagination
        $sql .= " ORDER BY logs.timestamp DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => &$value) {
            if ($key === 'limit' || $key === 'offset') {
                $stmt->bindParam(":$key", $value, \PDO::PARAM_INT);
            } else {
                $stmt->bindParam(":$key", $value);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
