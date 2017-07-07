<?php

require_once 'inherit.php';

if ($config['models']) {
    foreach ($config['models'] as $app) {
        include_once("../../app/$app/models/models.php");
    }
}

// models
