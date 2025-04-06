<?php
// controllers/import_students.php

use Core\Middleware;

require 'vendor/autoload.php';

Middleware::requireAuth();

// Check for successful import or error messages passed from specialized importers
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error = isset($_GET['error']) ? $_GET['error'] : null;
$added = isset($_GET['added']) ? (int)$_GET['added'] : 0;
$skipped = isset($_GET['skipped']) ? (int)$_GET['skipped'] : 0;

// Set view variables
$title = "Import Students";
$view = "views/import/students.view.php";
require "views/layout.view.php";