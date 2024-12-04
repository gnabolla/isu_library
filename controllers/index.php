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

// Fetch data for informative cards

// 1. Total Students
$totalStudents = $db->query('SELECT COUNT(*) as count FROM students')->fetch();

// 2. Total Access Logs
$totalLogs = $db->query('SELECT COUNT(*) as count FROM logs')->fetch();

// 3. Total Departments
$totalDepartments = $db->query('SELECT COUNT(DISTINCT department) as count FROM students')->fetch();

// 4. Latest Student Added
$latestStudent = $db->query('SELECT firstname, lastname, created_at FROM students ORDER BY created_at DESC LIMIT 1')->fetch();

// Fetch data for charts

// 1. Students per Department
$departments = $db->query('SELECT department, COUNT(*) as count FROM students GROUP BY department')->fetchAll();

// 2. Gender Distribution
$genders = $db->query('SELECT sex, COUNT(*) as count FROM students GROUP BY sex')->fetchAll();

// 3. Monthly Access Logs (Last 12 Months)
$logs = $db->query("
    SELECT DATE_FORMAT(timestamp, '%Y-%m') as log_month, COUNT(*) as count 
    FROM logs 
    WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(timestamp, '%Y-%m')
    ORDER BY DATE_FORMAT(timestamp, '%Y-%m')
")->fetchAll();

// 4. Top 5 Active Students
$topStudents = $db->query("
    SELECT s.firstname, s.lastname, COUNT(l.id) as log_count
    FROM logs l
    JOIN students s ON l.student_id = s.id
    GROUP BY l.student_id
    ORDER BY log_count DESC
    LIMIT 5
")->fetchAll();

// Prepare data for the view
$data = [
    'totalStudents' => $totalStudents['count'],
    'totalLogs' => $totalLogs['count'],
    'totalDepartments' => $totalDepartments['count'],
    'latestStudent' => $latestStudent,
    'departments' => $departments,
    'genders' => $genders,
    'logs' => $logs,
    'topStudents' => $topStudents,
];

$title = "Dashboard";
$view = "views/index.view.php";
require "views/layout.view.php";
?>
