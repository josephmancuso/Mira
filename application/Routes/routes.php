<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once 'init.php';
require_once '../models/models.php';

require_once '../forms/forms.php';

if ($config['middleware']) {
    foreach ($config['middleware'] as $template) {
        require_once("../app/$template/middleware/middleware.php");
    }
}

if ($config['templates']) {
    foreach ($config['templates'] as $template) {
        include_once("../app/$template/routes/routes.php");
    }
}

// routes go here
