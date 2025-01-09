<?php
// controllers/logs_summary_print.php

use Core\Middleware;
use Core\Log;

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);

// Optional: Capture a date range from query parameters (same as logs filters)
$dateFrom = $_GET['date_from'] ?? '';
$dateTo   = $_GET['date_to']   ?? '';

// Build query with optional date filtering
$sql = "
    SELECT 
        s.department AS college,
        SUM(CASE WHEN s.sex = 'Male' THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN s.sex = 'Female' THEN 1 ELSE 0 END) AS female,
        COUNT(l.id) AS total
    FROM logs l
    JOIN students s ON l.student_id = s.id
";

$params = [];
if ($dateFrom && $dateTo) {
    $sql .= " WHERE DATE(l.timestamp) BETWEEN :date_from AND :date_to";
    $params['date_from'] = $dateFrom;
    $params['date_to']   = $dateTo;
}

$sql .= " GROUP BY s.department ORDER BY s.department";

$logSummary = $db->query($sql, $params)->fetchAll();

// Pass data to the print-only view
$title = 'Logs Summary Print';
$view = 'views/logs/summary_print.view.php';
require 'views/layout.view.php';
