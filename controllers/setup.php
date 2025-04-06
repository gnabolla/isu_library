<?php
// controllers/setup.php

use Core\Middleware;

Middleware::requireAuth();

$title = "Setup";
$view = "views/setup/index.view.php";
require "views/layout.view.php";
