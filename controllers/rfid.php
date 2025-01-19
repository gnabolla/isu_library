<?php
// controllers/rfid.php

use Core\Student;
use Core\Log;

require_once __DIR__ . '/../core/Student.php';
require_once __DIR__ . '/../core/Log.php';

// REMOVED: Middleware::requireAuth(); to allow access without login

$config = require('config.php');
$db = new Database($config['database']);
$studentModel = new Student($db);
$logModel = new Log($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rfid = trim($_POST['rfid'] ?? '');

    if (empty($rfid)) {
        $response = ['status' => 'error', 'message' => 'RFID cannot be empty.'];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $student = $studentModel->getByRFID($rfid);

    if (!$student) {
        $response = ['status' => 'error', 'message' => 'No student found with the provided RFID.'];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // 10-second buffer
    if ($logModel->withinBuffer((int)$student['id'], 10)) {
        $response = [
            'status'  => 'error',
            'message' => 'Please wait 10 seconds before scanning again.'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Check the most recent log for today
    $lastLogToday = $logModel->getMostRecentLogToday((int)$student['id']);

    // If no log yet today or last log was 'out', then time in
    if (!$lastLogToday || $lastLogToday['type'] === 'out') {
        $success = $logModel->createTimeLog((int)$student['id'], 'in');
        if ($success) {
            $response = [
                'status'    => 'success',
                'message'   => 'Time In recorded!',
                'student'   => $student,
                'log_type'  => 'in',
                'date_time' => date('Y-m-d H:i:s')
            ];
        } else {
            $response = [
                'status'  => 'error',
                'message' => 'Failed to record time in.'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        // Last log was 'in', so time out
        $success = $logModel->createTimeLog((int)$student['id'], 'out');
        if ($success) {
            $response = [
                'status'    => 'success',
                'message'   => 'Time Out recorded!',
                'student'   => $student,
                'log_type'  => 'out',
                'date_time' => date('Y-m-d H:i:s')
            ];
        } else {
            $response = [
                'status'  => 'error',
                'message' => 'Failed to record time out.'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

$title = 'RFID Scan Interface';
require 'views/rfid/scan.view.php';
