<?php
// router.php (Core)

require_once "core/Auth.php";
require_once "core/Middleware.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = rtrim(dirname($scriptName), '/\\');

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
if ($uri === '' || $uri === false) {
    $uri = '/';
}

// Define protected routes (require authentication)
$protected_routes = [
    "/"                 => "controllers/index.php",
    "/students"         => "controllers/students.php",
    "/logs"             => "controllers/logs.php",
    "/logs/summary"     => "controllers/logs_summary.php",
    "/logs/summary-print"=> "controllers/logs_summary_print.php",
    "/logs/print"       => "controllers/logs_print.php",
    "/audit-logs"       => "controllers/audit_logs.php",
    "/departments"      => "controllers/departments.php",
    "/courses"          => "controllers/courses.php",
    "/import-students"  => "controllers/import_students.php",
    "/import-students/sacarias" => "controllers/import_sacarias.php",
    "/import-students/sarias"   => "controllers/import_sarias.php",
    "/import-students/debug"    => "controllers/import_sacarias_debug.php"
];

// Define public routes (no auth required)
$public_routes = [
    "/rfid"   => "controllers/rfid.php",
    "/login"  => "controllers/login.php",
    "/signup" => "controllers/signup.php",
    "/logout" => "controllers/logout.php",
    "/setup"  => "controllers/setup.php"
];

$routes = array_merge($protected_routes, $public_routes);

define('BASE_PATH', $basePath);

function abort($code = 404) {
    http_response_code($code);
    $title = "{$code} Error";
    require "views/{$code}.php";
    exit();
}

function routeToController($uri, $routes, $protected_routes) {
    if (array_key_exists($uri, $routes)) {
        if (array_key_exists($uri, $protected_routes)) {
            \Core\Middleware::requireAuth();
        }
        $controller = $routes[$uri];
        if (file_exists($controller)) {
            require $controller;
            return;
        }
    }
    abort(404);
}

routeToController($uri, $routes, $protected_routes);