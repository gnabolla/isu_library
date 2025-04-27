<?php

use Core\Middleware;
use Core\Student;
use Core\Log;
use Core\AuditLog;
use Core\Course;
use Core\Department;

require_once __DIR__ . '/../core/Student.php';
require_once __DIR__ . '/../core/Log.php';
require_once __DIR__ . '/../core/AuditLog.php';
require_once __DIR__ . '/../core/Course.php';
require_once __DIR__ . '/../core/Department.php';

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);

$studentModel = new Student($db);
$logModel     = new Log($db);
$auditLog     = new AuditLog($db);
$courseModel  = new Course($db);
$deptModel    = new Department($db);

$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$errors = [];

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'firstname'     => trim($_POST['firstname']),
                'middlename'    => trim($_POST['middlename']),
                'lastname'      => trim($_POST['lastname']),
                'year'          => (int)$_POST['year'],
                'course_id'     => (int)$_POST['course_id'],
                'section'       => trim($_POST['section'] ?? ''),
                'department_id' => (int)$_POST['department_id'],
                'rfid'          => trim($_POST['rfid']),
                'sex'           => $_POST['sex'],
                'image'         => ''  // Will set below
            ];

            // Basic validation
            foreach (['firstname','middlename','lastname','rfid','sex'] as $req) {
                if (empty($data[$req])) {
                    $errors[] = ($req === 'rfid' ? 'Student ID' : ucfirst($req)) . ' is required.';
                }
            }
            if ($data['course_id'] <= 0) {
                $errors[] = 'Course is required.';
            }
            if ($data['department_id'] <= 0) {
                $errors[] = 'Department is required.';
            }

            // Handle image upload (if any)
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename   = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $data['image'] = $targetFile;
                }
            } else {
                // If no file chosen, assign default avatar
                $data['image'] = 'assets/img/default-avatar.png';
            }

            if (empty($errors)) {
                try {
                    $studentModel->create($data);
                    $newId = $db->getConnection()->lastInsertId();

                    // Audit
                    $userId = $_SESSION['user_id'] ?? null;
                    $auditLog->log($userId, 'create_student', 'Created student ID '.$newId);

                    header('Location: ' . BASE_PATH . '/students');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Failed to create student: ' . $e->getMessage();
                }
            }

            $courses     = $courseModel->getAll();
            $departments = $deptModel->getAll();
            $title = 'Add Student';
            $view  = 'views/students/create.view.php';
            require 'views/layout.view.php';
        } else {
            $courses     = $courseModel->getAll();
            $departments = $deptModel->getAll();
            $title = 'Add Student';
            $view  = 'views/students/create.view.php';
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
                'firstname'     => trim($_POST['firstname']),
                'middlename'    => trim($_POST['middlename']),
                'lastname'      => trim($_POST['lastname']),
                'year'          => (int)$_POST['year'],
                'course_id'     => (int)$_POST['course_id'],
                'section'       => trim($_POST['section'] ?? ''),
                'department_id' => (int)$_POST['department_id'],
                'rfid'          => trim($_POST['rfid']),
                'sex'           => $_POST['sex'],
                'image'         => $student['image'] // existing image by default
            ];

            foreach (['firstname','middlename','lastname','rfid','sex'] as $req) {
                if (empty($data[$req])) {
                    $errors[] = ucfirst($req).' is required.';
                }
            }
            if ($data['course_id'] <= 0) {
                $errors[] = 'Course is required.';
            }
            if ($data['department_id'] <= 0) {
                $errors[] = 'Department is required.';
            }

            // If a new file is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename   = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    // Remove old image if not default
                    if ($student['image'] 
                        && file_exists($student['image']) 
                        && $student['image'] !== 'assets/img/default-avatar.png') {
                        unlink($student['image']);
                    }
                    $data['image'] = $targetFile;
                }
            } else {
                // If old image was empty or missing, use default
                if (!$student['image'] || !file_exists($student['image'])) {
                    $data['image'] = 'assets/img/default-avatar.png';
                }
            }

            if (empty($errors)) {
                try {
                    $studentModel->update($id, $data);
                    $userId = $_SESSION['user_id'] ?? null;
                    $auditLog->log($userId, 'update_student', 'Updated student ID '.$id);

                    header('Location: ' . BASE_PATH . '/students');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Failed to update student: ' . $e->getMessage();
                }
            }

            $courses     = $courseModel->getAll();
            $departments = $deptModel->getAll();
            $title = 'Edit Student';
            $view  = 'views/students/edit.view.php';
            require 'views/layout.view.php';
        } else {
            $courses     = $courseModel->getAll();
            $departments = $deptModel->getAll();
            $title = 'Edit Student';
            $view  = 'views/students/edit.view.php';
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
            if ($student['image'] 
                && file_exists($student['image']) 
                && $student['image'] !== 'assets/img/default-avatar.png') {
                unlink($student['image']);
            }
            $userId = $_SESSION['user_id'] ?? null;
            $auditLog->log($userId, 'delete_student', 'Deleted student ID '.$id);

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

        $studentLogs = $logModel->getLogsByStudentId($id);

        $title = 'View Student';
        $view  = 'views/students/view.view.php';
        require 'views/layout.view.php';
        break;

    case 'index':
    default:
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
        $view  = 'views/students/index.view.php';
        require 'views/layout.view.php';
        break;
}
