<?php
// controllers/rfid.php

use Core\Middleware;
use Core\Student;
use Core\Log;

require_once __DIR__ . '/../core/Student.php';
require_once __DIR__ . '/../core/Log.php';

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$studentModel = new Student($db);
$logModel = new Log($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rfid = trim($_POST['rfid'] ?? '');

    if (empty($rfid)) {
        $response = ['status' => 'error', 'message' => 'RFID cannot be empty.'];
    } else {
        $student = $studentModel->getByRFID($rfid);

        if ($student) {
            $success = $logModel->create($student['id']);
            if ($success) {
                $response = [
                    'status' => 'success',
                    'message' => 'RFID scanned successfully.',
                    'student' => $student
                ];
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to log the scan. Please try again.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'No student found with the provided RFID.'];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

$title = 'RFID Scan Interface';
$view = 'views/rfid/scan.view.php';
require 'views/layout.view.php';
