<?php
use Core\Middleware;
use Core\Course;
use Core\AuditLog;

require_once __DIR__ . '/../core/Course.php';
require_once __DIR__ . '/../core/AuditLog.php';

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$courseModel = new Course($db);
$auditLog = new AuditLog($db);

$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$errors = [];

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);

            if (empty($name)) {
                $errors[] = 'Course name is required';
            } else {
                try {
                    $courseModel->create(['name' => $name]);
                    // Log
                    $auditLog->log($_SESSION['user_id'], 'create_course', 'Created course: '.$name);
                    header('Location: ' . BASE_PATH . '/courses');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Error creating course: '.$e->getMessage();
                }
            }
        }
        $title = 'Create Course';
        $view = 'views/courses/create.view.php';
        require 'views/layout.view.php';
        break;

    case 'edit':
        if (!$id) {
            abort(404);
        }
        $course = $courseModel->getById($id);
        if (!$course) {
            abort(404);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            if (empty($name)) {
                $errors[] = 'Course name is required';
            } else {
                try {
                    $courseModel->update($id, ['name' => $name]);
                    // Log
                    $auditLog->log($_SESSION['user_id'], 'update_course', 'Updated course ID '.$id);
                    header('Location: ' . BASE_PATH . '/courses');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Error updating course: '.$e->getMessage();
                }
            }
        }

        $title = 'Edit Course';
        $view = 'views/courses/edit.view.php';
        require 'views/layout.view.php';
        break;

    case 'delete':
        if (!$id) {
            abort(404);
        }
        $course = $courseModel->getById($id);
        if (!$course) {
            abort(404);
        }
        try {
            $courseModel->delete($id);
            // Log
            $auditLog->log($_SESSION['user_id'], 'delete_course', 'Deleted course ID '.$id);
            header('Location: ' . BASE_PATH . '/courses');
            exit();
        } catch (Exception $e) {
            $errors[] = 'Error deleting course: '.$e->getMessage();
            $title = 'Error';
            $view = 'views/404.php';
            require 'views/layout.view.php';
        }
        break;

    default:
        // Index
        $courses = $courseModel->getAll();
        $title = 'Courses';
        $view = 'views/courses/index.view.php';
        require 'views/layout.view.php';
        break;
}
