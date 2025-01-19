<?php
use Core\Middleware;
use Core\AuditLog;

require_once __DIR__ . '/../core/AuditLog.php';

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$auditLogModel = new AuditLog($db);

$auditLogs = $auditLogModel->getAll();

$title = 'Audit Logs';
$view = 'views/audit/index.view.php';
require 'views/layout.view.php';
