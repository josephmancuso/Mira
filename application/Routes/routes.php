<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
require_once 'init.php';
require_once '../models/models.php';

if ($config['templates']) {
    foreach ($config['templates'] as $template) {
        include_once("../app/$template/routes/routes.php");
    }
}

// routes go here
