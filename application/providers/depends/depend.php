<?php
namespace Mira;

class Depends
{
    public static function provider($provider)
    {
        require_once $_SERVER['DOCUMENT_ROOT'].'/application/providers/'.$provider.'/autoload.php';
    }
}
