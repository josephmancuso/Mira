<?php

//spl_autoload_register('miraAutoloader');
/*
function miraAutoloader($className)
{
    if (preg_match('/^Events/', $className)) {
        $path = $_SERVER['DOCUMENT_ROOT'].'/application/Providers/';
        include $path.$className.'.php';
    } elseif (preg_match('/^Application/', $className)) {

        $path = $_SERVER['DOCUMENT_ROOT'].'/';
        include $path.$className.'.php';
    } elseif (preg_match('/^App/', $className)) {
        $pos = strrpos($className, "\\");

        if ($pos !== false) {
            $className = substr_replace($className, '\Models\\', $pos, strlen("\\"));
        }
        $path = $_SERVER['DOCUMENT_ROOT'].'/application/';
        include $path.$className.'.php';
    }
}
*/
require_once 'init.php';
require_once 'extendsFrom.php';
require_once 'Routes.php';
