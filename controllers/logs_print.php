<?php
use Core\Middleware;
use Core\Log;

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);
$logModel = new Log($db);

// Capture the same filters as in logs.php
$filters = [
    'date_from' => $_GET['date_from'] ?? date('Y-m-d'),
    'date_to'   => $_GET['date_to']   ?? date('Y-m-d'),
    'program'   => $_GET['program']   ?? '',
    'department'=> $_GET['department'] ?? '',
    // NEW: type
    'type'      => $_GET['type']      ?? ''
];

$logs  = $logModel->getFilteredLogs($filters);
$count = count($logs);

// Count male/female ONLY if type is 'in' (like we do in logs page):
$males = 0;
$females = 0;
foreach ($logs as $log) {
    if ($log['type'] === 'in') {
        if (isset($log['sex']) && $log['sex'] === 'Male') {
            $males++;
        } elseif (isset($log['sex']) && $log['sex'] === 'Female') {
            $females++;
        }
    }
}
$maleCount = $males;
$femaleCount = $females;

$title = 'Print Filtered RFID Logs';
$view  = 'views/logs/print.view.php';
require 'views/layout.view.php';
