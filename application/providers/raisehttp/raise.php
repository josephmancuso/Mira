<?php

namespace Mira;

class Http
{
    public static function raise404()
    {
        require '404.html';
        die();
    }

    public static function raise500()
    {
        require '500.html';
        die();
    }

    public static function raise403()
    {
        require '403.html';
        die();
    }
}
