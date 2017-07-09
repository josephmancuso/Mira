<?php
use Mira\Route;
use Mira\Render;
use Mira\Http;

get('home/$', function () {
    view('mira.home');
});
