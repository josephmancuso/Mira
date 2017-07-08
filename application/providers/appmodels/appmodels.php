<?php

$config = require $_SERVER['DOCUMENT_ROOT']."/config/config.php";

if ($config['models']) {
    foreach ($config['models'] as $app) {
        require_once("../../app/$app/models/models.php");
    }
}

// models
