<?php

use Core\Auth;
use Core\Middleware;

// ADD THIS LINE:
require_once __DIR__ . '/../core/AuditLog.php';

use Core\AuditLog;

Middleware::requireGuest();

$config = require('config.php');
$db = new Database($config['database']);
$auth = new Auth($db);

$auditLog = new AuditLog($db);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->signup([
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'confirm' => isset($_POST['confirm'])
    ]);
    
    if ($result['success']) {
        $newUserId = $db->getConnection()->lastInsertId();
        $auditLog->log($newUserId, 'signup', 'New user registered');
        header('Location: ' . BASE_PATH . '/login');
        exit();
    }
    
    $errors = $result['errors'];
}

$title = 'Sign Up';
require "views/signup.view.php";
