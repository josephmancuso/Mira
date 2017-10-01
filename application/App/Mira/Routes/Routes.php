<?php

use Mira\Route;
use Mira\Render\Render;

Route::get('home/', function () {
    Render::view('Mira.home');
});
