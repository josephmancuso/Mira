<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
require_once 'init.php';
require_once '../models/models.php';
foreach ($config['templates'] as $template) {
    include_once("../app/$template/routes/routes.php");
}

