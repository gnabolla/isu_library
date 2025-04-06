<?php
use Core\Middleware;
use Core\Department;

// Removed manual require; autoloader now loads Department
// require_once __DIR__ . '/../core/Department.php';
Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$deptModel = new Department($db);

$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            
            if (empty($name)) {
                $errors[] = 'Department name is required';
            } else {
                try {
                    $deptModel->create(['name' => $name]);
                    header('Location: '.BASE_PATH.'/departments');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Error creating department: '.$e->getMessage();
                }
            }
            
            $title = 'Create Department';
            $view = 'views/departments/create.view.php';
            require 'views/layout.view.php';
        } else {
            $title = 'Create Department';
            $view = 'views/departments/create.view.php';
            require 'views/layout.view.php';
        }
        break;

    case 'edit':
        if (!$id) abort(404);
        $department = $deptModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            
            if (empty($name)) {
                $errors[] = 'Department name is required';
            } else {
                try {
                    $deptModel->update($id, ['name' => $name]);
                    header('Location: '.BASE_PATH.'/departments');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Error updating department: '.$e->getMessage();
                }
            }
        }
        
        $title = 'Edit Department';
        $view = 'views/departments/edit.view.php';
        require 'views/layout.view.php';
        break;

    case 'delete':
        if (!$id) abort(404);
        $deptModel->delete($id);
        header('Location: '.BASE_PATH.'/departments');
        exit();
        break;

    default:
        $departments = $deptModel->getAll();
        $title = 'Departments';
        $view = 'views/departments/index.view.php';
        require 'views/layout.view.php';
        break;
}