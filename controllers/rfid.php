<?php
// controllers/rfid.php

use Core\Middleware;
use Core\Student;
use Core\Log;

// Include necessary classes
require_once __DIR__ . '/../core/Student.php';
require_once __DIR__ . '/../core/Log.php';

// Ensure the user is authenticated (optional: if you want to restrict access)
Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$studentModel = new Student($db);
$logModel = new Log($db);

// Check the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve RFID from POST data
    $rfid = trim($_POST['rfid'] ?? '');

    // Basic validation
    if (empty($rfid)) {
        $response = ['status' => 'error', 'message' => 'RFID cannot be empty.'];
    } else {
        // Find the student by RFID
        $student = $studentModel->getByRFID($rfid);

        if ($student) {
            // Create a new log entry
            $success = $logModel->create($student['id']);

            if ($success) {
                $response = ['status' => 'success', 'message' => 'RFID scanned successfully.', 'student' => $student];
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to log the scan. Please try again.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'No student found with the provided RFID.'];
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// If GET request, display the RFID scanning interface
$title = 'RFID Scan Interface';
$view = 'views/rfid/scan.view.php';
require 'views/layout.view.php';
?>
