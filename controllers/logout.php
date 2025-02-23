<?php

use Core\Auth;
use Core\Middleware;
use Core\AuditLog;

// Removed manual require as autoloader handles AuditLog
// require_once __DIR__ . '/../core/AuditLog.php';

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$auditLog = new AuditLog($db);

$userId = $_SESSION['user_id'] ?? null;

Auth::logout();

if ($userId) {
    $auditLog->log($userId, 'logout', 'User logged out');
}

header('Location: ' . BASE_PATH . '/login');
exit();
