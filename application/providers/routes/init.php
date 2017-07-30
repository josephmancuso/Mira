<?php

namespace Mira;

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

class Route
{

    public static function getPattern($url)
    {
        $get_url_segments = explode("/", $url);
        
        $match = "";
        
        foreach ($get_url_segments as $segment) {
            if (strpos($segment, "{") !== false) {
                $match .= ".*";
            } elseif ($segment == "") {
                //
            } elseif ($segment == "$") {
                $match .= "$";
            } else {
                $match .= "$segment\/";
            }
        }

        return $match;
    }

    public static function matchUrlSegments($url)
    {
        $get_url_segments = explode("/", $url);
        
        $params = explode("/", $_GET['url']);
        
        $i = 0;
        $pos = 0;
        foreach ($get_url_segments as $segment) {
            if (strpos($segment, "{") !== false) {
                $p[$pos] .= $params[$i];
                $pos++;
            }
            $i++;
        }

        return $p;
    }

    public static function addParamtersToGlobal()
    {
        // add query string parameters to the global GET variable
        $u = explode("?", $_SERVER['REQUEST_URI']);
        $u = explode("&", $u[1]);
        foreach ($u as $param) {
            $param = explode("=", $param);
            $param[1] = str_replace('%20', ' ', $param[1]);
            if ($_SERVER['REQUEST_METHOD'] == "GET") {
                $_GET[$param[0]] = $param[1];
            } else {
                $_POST[$param[0]] = $param[1];
            }
            $_GET = array_filter($_GET);
            $_POST = array_filter($_POST);
        }
        return array_filter($u);
    }
    
    public static function get($url, \Closure $func)
    {
        $match = static::getPattern($url);
        $p = static::matchUrlSegments($url);

        static::addParamtersToGlobal();

        if ($match == "$") {
            $match = "^$";
        }

        if (preg_match("/".$match."/", $_GET['url']) && $_SERVER['REQUEST_METHOD'] == "GET") {

            require_once 'extendsFrom.php';

            $func($p[0], $p[1], $p[2], $p[3], $p[4], $p[5]);
            return true;
        } else {
            return false;
        }
    }
    
    public static function post($url, \Closure $func)
    {
        $match = static::getPattern($url);
        $p = static::matchUrlSegments($url);

        static::addParamtersToGlobal();

        if ($match == "$") {
            $match = "^$";
        }

        if (preg_match("/^".$match."/", $_GET['url']) && $_SERVER['REQUEST_METHOD'] == "POST") {
            require_once 'extendsFrom.php';

            $func($p[0], $p[1], $p[2], $p[3], $p[4], $p[5]);
            return true;
        } else {
            return false;
        }
    }
}

define('__PROJECT__', $_SERVER['DOCUMENT_ROOT']);

$dotenv = new \Dotenv\Dotenv(__PROJECT__);
$dotenv->load();

if (!file_exists($_SERVER['DOCUMENT_ROOT']."/config/config.php")) {
    $_SERVER['DOCUMENT_ROOT'] = realpath('../../../');
}

$config = require_once $_SERVER['DOCUMENT_ROOT']."/config/config.php";
$providers_config = require_once $_SERVER['DOCUMENT_ROOT']."/config/providers.php";

if ($providers_config['Providers']) {
    foreach ($providers_config['Providers'] as $provider) {
        $provider = strtolower(str_replace("\\", "/", $provider));
        require_once $_SERVER['DOCUMENT_ROOT']."/$provider/autoload.php";
    }
}

if ($config['Apps']) {
    $url = $_SERVER['HTTP_HOST'];
    $host = explode('.', $url);
    $subdomain = $host[0];
    if (count($host) >= 3 && $subdomain != 'www') {
        $multi_check = true;
    } else {
        $multi_check = false;
    }

    if ($config['multi-tenancy'] && $multi_check) {
        if (file_exists($_SERVER['DOCUMENT_ROOT']."/application/app/$subdomain/controller/controller.php")) {
            require_once($_SERVER['DOCUMENT_ROOT']."/application/app/$subdomain/controller/controller.php");
        }
        include_once($_SERVER['DOCUMENT_ROOT']."/application/app/$subdomain/routes/routes.php");
    } else {
        foreach ($config['Apps'] as $template) {
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/application/app/$template/controller/controller.php")) {
                require_once($_SERVER['DOCUMENT_ROOT']."/application/app/$template/controller/controller.php");
            }
            include_once($_SERVER['DOCUMENT_ROOT']."/application/app/$template/routes/routes.php");
        }
    }
}
