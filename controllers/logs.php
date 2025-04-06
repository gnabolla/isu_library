<?php
// controllers/logs.php
use Core\Middleware;
use Core\Log;

// Removed manual require as autoloader loads core classes
// require_once __DIR__ . '/../core/Log.php';

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$logModel = new Log($db);

// NEW: Check for range (day, week, month)
$range = $_GET['range'] ?? '';

// Default filters
$dateFrom = date('Y-m-d');
$dateTo   = date('Y-m-d');

if ($range) {
    switch ($range) {
        case 'day':
            // Today
            $dateFrom = date('Y-m-d');
            $dateTo   = date('Y-m-d');
            break;
        case 'week':
            // Last 7 days
            $dateFrom = date('Y-m-d', strtotime('-1 week'));
            $dateTo   = date('Y-m-d');
            break;
        case 'month':
            // Last 30 days
            $dateFrom = date('Y-m-d', strtotime('-1 month'));
            $dateTo   = date('Y-m-d');
            break;
    }
} else {
    // Fallback to any custom date_from/date_to if provided
    // or keep defaults above if none given
    if (!empty($_GET['date_from'])) {
        $dateFrom = $_GET['date_from'];
    }
    if (!empty($_GET['date_to'])) {
        $dateTo = $_GET['date_to'];
    }
}

// Build filters array
$filters = [
    'date_from' => $dateFrom,
    'date_to'   => $dateTo,
    'program'   => $_GET['program']   ?? '',
    'department'=> $_GET['department'] ?? '',
    // NEW: Filter by type ('in' or 'out' or '')
    'type'      => $_GET['type']      ?? ''
];

// Get logs with filters
$logs  = $logModel->getFilteredLogs($filters);

// Count only 'in' logs for M/F summary
$males = 0;
$females = 0;
$totalIn = 0;

foreach ($logs as $log) {
    if ($log['type'] === 'in') {
        $totalIn++;
        if (isset($log['sex']) && $log['sex'] === 'Male') {
            $males++;
        } elseif (isset($log['sex']) && $log['sex'] === 'Female') {
            $females++;
        }
    }
}

// For display in the view
$maleCount   = $males;
$femaleCount = $females;
$count       = count($logs);  // total logs (both in & out)


// Get unique programs and departments for filter dropdowns
$programs    = $logModel->getUniquePrograms();
$departments = $logModel->getUniqueDepartments();

$title = 'RFID Logs';
$view = 'views/logs/index.view.php';
require 'views/layout.view.php';
