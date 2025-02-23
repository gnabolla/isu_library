<?php
// controllers/index.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_PATH . '/login');
    exit();
}

$config = require('config.php');
$db = new Database($config['database']);

// Existing stats
$totalStudents = $db->query('SELECT COUNT(*) as count FROM students')->fetch();
$totalLogs = $db->query('SELECT COUNT(*) as count FROM logs')->fetch();
$totalDepartments = $db->query('SELECT COUNT(DISTINCT department_id) as count FROM students')->fetch();
$latestStudent = $db->query('SELECT firstname, lastname, created_at FROM students ORDER BY created_at DESC LIMIT 1')->fetch();

// Count total male/female
$totalMaleStudents = $db->query("SELECT COUNT(*) as count FROM students WHERE sex='Male'")->fetch();
$totalFemaleStudents = $db->query("SELECT COUNT(*) as count FROM students WHERE sex='Female'")->fetch();

// Existing charts
$departments = $db->query('SELECT department_id as department, COUNT(*) as count FROM students GROUP BY department_id')->fetchAll();
$genders = $db->query('SELECT sex, COUNT(*) as count FROM students GROUP BY sex')->fetchAll();
$logs = $db->query("
    SELECT DATE_FORMAT(timestamp, '%Y-%m') as log_month, COUNT(*) as count 
    FROM logs 
    WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(timestamp, '%Y-%m')
    ORDER BY DATE_FORMAT(timestamp, '%Y-%m')
")->fetchAll();
$topStudents = $db->query("
    SELECT s.firstname, s.lastname, COUNT(l.id) as log_count
    FROM logs l
    JOIN students s ON l.student_id = s.id
    GROUP BY l.student_id
    ORDER BY log_count DESC
    LIMIT 5
")->fetchAll();

// Library attendance (male/female) day/week/month
$range = $_GET['range'] ?? 'day';
switch ($range) {
    case 'day':
        $attendanceSql = "
            SELECT s.sex, COUNT(*) as count
            FROM logs l
            JOIN students s ON s.id = l.student_id
            WHERE l.type='in' AND DATE(l.timestamp) = CURDATE()
            GROUP BY s.sex
        ";
        break;
    case 'week':
        $attendanceSql = "
            SELECT s.sex, COUNT(*) as count
            FROM logs l
            JOIN students s ON s.id = l.student_id
            WHERE l.type='in' AND l.timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY s.sex
        ";
        break;
    case 'month':
        $attendanceSql = "
            SELECT s.sex, COUNT(*) as count
            FROM logs l
            JOIN students s ON s.id = l.student_id
            WHERE l.type='in' AND l.timestamp >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            GROUP BY s.sex
        ";
        break;
    default:
        $range = 'day';
        $attendanceSql = "
            SELECT s.sex, COUNT(*) as count
            FROM logs l
            JOIN students s ON s.id = l.student_id
            WHERE l.type='in' AND DATE(l.timestamp) = CURDATE()
            GROUP BY s.sex
        ";
        break;
}
$attendanceResults = $db->query($attendanceSql)->fetchAll();
$maleAttendees = 0;
$femaleAttendees = 0;
$totalAttendees = 0;
foreach ($attendanceResults as $row) {
    if ($row['sex'] === 'Male') {
        $maleAttendees = $row['count'];
    }
    if ($row['sex'] === 'Female') {
        $femaleAttendees = $row['count'];
    }
    $totalAttendees += $row['count'];
}

// Usage by hour (past 7 days)
$usageByHour = $db->query("
    SELECT 
        HOUR(timestamp) AS hour, 
        COUNT(*) AS count
    FROM logs
    WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY HOUR(timestamp)
    ORDER BY hour
")->fetchAll();
$peakHour = null;
$peakHourCount = 0;
foreach ($usageByHour as $row) {
    if ($row['count'] > $peakHourCount) {
        $peakHourCount = $row['count'];
        $peakHour      = $row['hour'];
    }
}

// Usage by day of week (past month)
$usageByDayOfWeek = $db->query("
    SELECT 
        DAYNAME(timestamp) AS weekday,
        COUNT(*) AS count
    FROM logs
    WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    GROUP BY DAYOFWEEK(timestamp)
    ORDER BY DAYOFWEEK(timestamp)
")->fetchAll();
$peakDay = null;
$peakDayCount = 0;
foreach ($usageByDayOfWeek as $row) {
    if ($row['count'] > $peakDayCount) {
        $peakDayCount = $row['count'];
        $peakDay      = $row['weekday'];
    }
}

// Latest 5 logs
$latestLogs = $db->query("
    SELECT s.firstname, s.lastname, l.timestamp, l.type
    FROM logs l
    JOIN students s ON s.id = l.student_id
    ORDER BY l.timestamp DESC
    LIMIT 5
")->fetchAll();

// Prepare data
$data = [
    'totalStudents' => $totalStudents['count'],
    'totalLogs' => $totalLogs['count'],
    'totalDepartments' => $totalDepartments['count'],
    'totalMaleStudents' => $totalMaleStudents['count'],
    'totalFemaleStudents' => $totalFemaleStudents['count'],
    'latestStudent' => $latestStudent,
    'departments' => $departments,
    'genders' => $genders,
    'logs' => $logs,
    'topStudents' => $topStudents,
    'attendees' => [
        'male' => $maleAttendees,
        'female' => $femaleAttendees,
        'total' => $totalAttendees,
        'range' => $range
    ],
    'usageByHour'      => $usageByHour,
    'usageByDayOfWeek' => $usageByDayOfWeek,
    'peakHour'         => $peakHour,
    'peakHourCount'    => $peakHourCount,
    'peakDay'          => $peakDay,
    'peakDayCount'     => $peakDayCount,
    'latestLogs'       => $latestLogs
];

$title = "Dashboard";
$view = "views/index.view.php";
require "views/layout.view.php";
