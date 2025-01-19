<?php
// controllers/students.php

use Core\Middleware;
use Core\Student;
use Core\Log;
use Core\AuditLog;

require_once __DIR__ . '/../core/Student.php';
require_once __DIR__ . '/../core/Log.php';

require_once __DIR__ . '/../core/AuditLog.php';

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$studentModel = new Student($db);
$logModel = new Log($db);
$auditLog = new AuditLog($db);

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$studentModel = new Student($db);

// Create a Log model instance (if needed for logs retrieval)
$logModel = new Log($db);

// Instantiate AuditLog
$auditLog = new AuditLog($db);

$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'firstname'  => trim($_POST['firstname']),
                'middlename' => trim($_POST['middlename']),
                'lastname'   => trim($_POST['lastname']),
                'year'       => (int)$_POST['year'],
                'course'     => trim($_POST['course']),
                'section'    => trim($_POST['section']),
                'department' => trim($_POST['department']),
                'rfid'       => trim($_POST['rfid']),
                'sex'        => $_POST['sex'],
                'image'      => '' // Will handle image upload below
            ];

            // Handle Image Upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $data['image'] = $targetFile;
                }
            }

            // Validate data (basic validation)
            $errors = [];
            foreach ($data as $key => $value) {
                if (in_array($key, ['firstname', 'middlename', 'lastname', 'course', 'section', 'department', 'rfid', 'sex']) && empty($value)) {
                    $errors[] = ucfirst($key) . ' is required.';
                }
            }

            if (empty($errors)) {
                try {
                    $studentModel->create($data);
                    $newStudentId = $db->getConnection()->lastInsertId();

                    // Log audit
                    $userId = $_SESSION['user_id'] ?? null;
                    $auditLog->log($userId, 'create_student', 'Created student ID ' . $newStudentId);

                    header('Location: ' . BASE_PATH . '/students');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Failed to create student: ' . $e->getMessage();
                }
            }

            $title = 'Add Student';
            $view = 'views/students/create.view.php';
            require 'views/layout.view.php';
        } else {
            $title = 'Add Student';
            $view = 'views/students/create.view.php';
            require 'views/layout.view.php';
        }
        break;

    case 'edit':
        if (!$id) {
            abort(404);
        }

        $student = $studentModel->getById($id);
        if (!$student) {
            abort(404);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'firstname'  => trim($_POST['firstname']),
                'middlename' => trim($_POST['middlename']),
                'lastname'   => trim($_POST['lastname']),
                'year'       => (int)$_POST['year'],
                'course'     => trim($_POST['course']),
                'section'    => trim($_POST['section']),
                'department' => trim($_POST['department']),
                'rfid'       => trim($_POST['rfid']),
                'sex'        => $_POST['sex'],
                'image'      => $student['image']
            ];

            // Handle Image Upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    // Optionally, delete the old image
                    if ($student['image'] && file_exists($student['image'])) {
                        unlink($student['image']);
                    }
                    $data['image'] = $targetFile;
                }
            }

            // Validate data (basic validation)
            $errors = [];
            foreach ($data as $key => $value) {
                if (in_array($key, ['firstname', 'middlename', 'lastname', 'course', 'section', 'department', 'rfid', 'sex']) && empty($value)) {
                    $errors[] = ucfirst($key) . ' is required.';
                }
            }

            if (empty($errors)) {
                try {
                    $studentModel->update($id, $data);

                    // Log audit
                    $userId = $_SESSION['user_id'] ?? null;
                    $auditLog->log($userId, 'update_student', 'Updated student ID ' . $id);

                    header('Location: ' . BASE_PATH . '/students');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Failed to update student: ' . $e->getMessage();
                }
            }

            $title = 'Edit Student';
            $view = 'views/students/edit.view.php';
            require 'views/layout.view.php';
        } else {
            $title = 'Edit Student';
            $view = 'views/students/edit.view.php';
            require 'views/layout.view.php';
        }
        break;

    case 'delete':
        if (!$id) {
            abort(404);
        }

        $student = $studentModel->getById($id);
        if (!$student) {
            abort(404);
        }

        try {
            $studentModel->delete($id);
            // Optionally, delete the image
            if ($student['image'] && file_exists($student['image'])) {
                unlink($student['image']);
            }

            // Log audit
            $userId = $_SESSION['user_id'] ?? null;
            $auditLog->log($userId, 'delete_student', 'Deleted student ID ' . $id);

            header('Location: ' . BASE_PATH . '/students');
            exit();
        } catch (Exception $e) {
            $errors[] = 'Failed to delete student: ' . $e->getMessage();
            $title = 'Error';
            $view = 'views/404.php';
            require 'views/layout.view.php';
        }
        break;

    case 'view':
        if (!$id) {
            abort(404);
        }

        $student = $studentModel->getById($id);
        if (!$student) {
            abort(404);
        }

        // Retrieve this student's logs
        $studentLogs = $logModel->getLogsByStudentId($id);

        $title = 'View Student';
        $view = 'views/students/view.view.php';
        require 'views/layout.view.php';
        break;

    case 'index':
    default:
        // Handle search and filters
        $search = $_GET['search'] ?? '';
        $filters = [
            'year'       => $_GET['year'] ?? '',
            'course'     => $_GET['course'] ?? '',
            'section'    => $_GET['section'] ?? '',
            'department' => $_GET['department'] ?? '',
            'sex'        => $_GET['sex'] ?? ''
        ];
        $filters = array_filter($filters);

        $students = $studentModel->getAll($filters, $search);

        $title = 'Students';
        $view = 'views/students/index.view.php';
        require 'views/layout.view.php';
        break;
}
