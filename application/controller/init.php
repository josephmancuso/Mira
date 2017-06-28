<?php

class controller
{
    public static function __callStatic($method, $value)
    {
        return $value[0]();
    }
}