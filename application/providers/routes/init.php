<?php

namespace Mira;

class Route
{
    private function __construct()
    {
        //
    }
    
    public static function get($url, \Closure $func)
    {
        $startDelimiter = "{";
        $endDelimiter = "}";
        
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($url, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($url, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($url, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }
        
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
        
        $get_url = $_GET['url'];
        
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
        
        // add query string parameters to the global GET variable
        $u = explode("?", $_SERVER['REQUEST_URI']);
        $u = explode("&", $u[1]);
        foreach ($u as $param) {
            $param = explode("=", $param);
            $param[1] = str_replace('%20', ' ', $param[1]);
            $_GET[$param[0]] = $param[1];
        }

        if ($match == "$") {
            $match = "^$";
        }
        $_REQUEST['url'] = $_SERVER['REQUEST_URI'];

        if (preg_match("/^res/", $_GET['url'])) {
            echo $_SERVER['DOCUMENT_ROOT'];
            echo $_GET['url'];
            $ex = explode("/", $_GET['url'], 2);
            echo $file = $ex[1];
            
            echo "<h1>";
            echo "</h1>";
            if (is_file($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'])) {
                header('Content-Type:');
                readfile($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
                exit;
            }
        } elseif (preg_match("/".$match."/", $_GET['url']) && $_SERVER['REQUEST_METHOD'] == "GET") {
            require_once 'extendsFrom.php';
        }
    }
    
    public static function post($url, \Closure $func)
    {
        $startDelimiter = "{";
        $endDelimiter = "}";
        
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($url, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($url, $endDelimiter, $contentStart);
            
            if (false === $contentEnd) {
                break;
            }

            $contents[] = substr($url, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }
        
        $get_url_segments = explode("/", $url);
        
        $match = "";
        //print_r($get_url_segments);
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
        
        $get_url = $_GET['url'];
        
        $params = explode("/", $_GET['url']);
        
        //echo "<h1>".$match."</h1>";
        
        
        $i = 0;
        $pos = 0;
        foreach ($get_url_segments as $segment) {
            if (strpos($segment, "{") !== false) {
                $p[$pos] .= $params[$i];
                $pos++;
            }
            $i++;
        }

        // add query string parameters to the global GET variable
        $u = explode("?", $_SERVER['REQUEST_URI']);
        $u = explode("&", $u[1]);
        foreach ($u as $param) {
            $param = explode("=", $param);
            $_POST[$param[0]] = $param[1];
        }
        
        if ($match == "$") {
            $match = "^$";
        }
        $_REQUEST['url'] = $_SERVER['REQUEST_URI'];
        //$z = 'jmancuso';
        
        if (preg_match("/^".$match."/", $_GET['url']) && $_SERVER['REQUEST_METHOD'] == "POST") {
            require_once 'extendsFrom.php';
        } elseif (preg_match("/^res/", $_GET['url'])) {
            echo $_SERVER['DOCUMENT_ROOT'];
            echo $_GET['url'];
            $ex = explode("/", $_GET['url'], 2);
            echo $file = $ex[1];
            
            if (is_file($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'])) {
                header('Content-Type:');
                readfile($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
                exit;
            }
        }
    }
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php')) {
    include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
}

$config = require_once $_SERVER['DOCUMENT_ROOT']."/config/config.php";
$providers_config = require_once $_SERVER['DOCUMENT_ROOT']."/config/providers.php";

if ($providers_config['Providers']) {
    foreach ($providers_config['Providers'] as $provider) {
        $provider = strtolower(str_replace("\\", "/", $provider));
        require_once $_SERVER['DOCUMENT_ROOT']."/$provider/autoload.php";
    }
}

if ($config['middleware']) {
    foreach ($config['middleware'] as $template) {
        if (file_exists("../../app/$template/middleware/middleware.php")) {
            require_once("../../app/$template/middleware/middleware.php");
        } elseif (file_exists("../../middleware/$template/autoload.php")) {
            require_once("../../middleware/$template/autoload.php");
        }
    }
}

if ($config['templates']) {
    $url = $_SERVER['HTTP_HOST'];
    $host = explode('.', $url);
    $subdomain = $host[0];
    if (count($host) >= 3 && $subdomain != 'www') {
        $multi_check = true;
    } else {
        $multi_check = false;
    }

    if ($config['multi-tenancy'] && $multi_check) {
        if (file_exists("../../app/$subdomain/controller/controller.php")) {
            require_once("../controller/init.php");
            require_once("../../app/$subdomain/controller/controller.php");
        }
        include_once("../../app/$subdomain/routes/routes.php");
    } else {
        foreach ($config['templates'] as $template) {
            if (file_exists("../../app/$template/controller/controller.php")) {
                require_once("../../app/$template/controller/controller.php");
            }
            include_once("../../app/$template/routes/routes.php");
        }
    }
}
