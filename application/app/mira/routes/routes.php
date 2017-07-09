<?php
use Mira\Route;
use Mira\Render;
use Mira\Http;

get('home/$', function () {
    $so_item = new so_item();
    view('mira.home');
});
