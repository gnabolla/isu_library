<?php
// controllers/logs.php
use Core\Middleware;
use Core\Log;

require_once __DIR__ . '/../core/Log.php';

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$logModel = new Log($db);

// Get filters from request
$filters = [
    'date_from' => $_GET['date_from'] ?? date('Y-m-d'),
    'date_to' => $_GET['date_to'] ?? date('Y-m-d'),
    'program' => $_GET['program'] ?? '',
    'department' => $_GET['department'] ?? ''
];

// Get logs with filters
$logs = $logModel->getFilteredLogs($filters);
$count = count($logs);

// Get unique programs and departments for filter dropdowns
$programs = $logModel->getUniquePrograms();
$departments = $logModel->getUniqueDepartments();

$title = 'RFID Logs';
$view = 'views/logs/index.view.php';
require 'views/layout.view.php';