<?php
// controllers/logs_summary.php

use Core\Middleware;
use Core\Log;

Middleware::requireAuth();

$config = require('config.php');
$db = new Database($config['database']);

// Fetch your logs summary data
$sql = "
    SELECT 
        s.department AS college,
        SUM(CASE WHEN s.sex = 'Male' THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN s.sex = 'Female' THEN 1 ELSE 0 END) AS female,
        COUNT(l.id) AS total
    FROM logs l
    JOIN students s ON l.student_id = s.id
    GROUP BY s.department
    ORDER BY s.department
";
$logSummary = $db->query($sql)->fetchAll();

// Pass data to the normal summary page
$title = 'Logs Summary';
$view = 'views/logs/summary.view.php';
require 'views/layout.view.php';
