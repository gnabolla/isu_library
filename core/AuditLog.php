<?php

namespace Core;

class AuditLog
{
    private $db;

    public function __construct(\Database $db)
    {
        $this->db = $db;
    }

    public function log($userId, $action, $description)
    {
        $sql = "INSERT INTO audit_logs (user_id, action, description) 
                VALUES (:user_id, :action, :description)";
        $this->db->query($sql, [
            'user_id'    => $userId,
            'action'     => $action,
            'description'=> $description
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT a.*, u.name AS user_name
                FROM audit_logs a
                LEFT JOIN users u ON a.user_id = u.id
                ORDER BY a.id DESC";
        return $this->db->query($sql)->fetchAll();
    }
}
