<?php

function extendsFrom($url)
{
    include "../templates/$url.php";
}

function loadStatic($app)
{
    return "/application/app/$app";
}
