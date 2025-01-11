<?php
// index.php (Core)
// This is the entry point for your application.

// 1. Set the default timezone first
date_default_timezone_set('Asia/Manila');

// 2. If needed, ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Require the necessary files
require "core/Auth.php";
require "core/Middleware.php";
require "functions.php";
require "Database.php";
require "router.php";
