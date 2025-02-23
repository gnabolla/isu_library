<?php

use Core\Auth;
use Core\Middleware;
use Core\AuditLog;

Middleware::requireGuest();

$config = require('config.php');
$db = new Database($config['database']);
$auth = new Auth($db);

$auditLog = new AuditLog($db);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $result = $auth->login($email, $password);
    
    if ($result['success']) {
        $userId = $_SESSION['user_id'];
        $auditLog->log($userId, 'login', 'User logged in successfully');
        header('Location: ' . BASE_PATH . '/');
        exit();
    }
    
    $errors = $result['errors'];
}

$title = 'Login';
require "views/login.view.php";
