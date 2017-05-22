<?php

class Route
{

    private function __construct()
    {
        //
    }
    
    public function get($url, Closure $func)
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
        
        if ($match == "$") {
            $match = "^$";
        }
        $_REQUEST['url'] = $_SERVER['REQUEST_URI'];
        //$z = 'jmancuso';
        if (preg_match("/^res/", $_GET['url'])) {
            echo $_SERVER['DOCUMENT_ROOT'];
            echo $_GET['url'];
            $ex = explode("/", $_GET['url'], 2);
            echo $file = $ex[1];
            
            
            echo "<h1>";
            $location = str_replace("\Routes", "", __dir__)."/app/big/".$file;
            echo $location;
            
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
    
    public function post($url, Closure $func)
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
        
        if ($match == "$") {
            $match = "^$";
        }
        $_REQUEST['url'] = $_SERVER['REQUEST_URI'];
        //$z = 'jmancuso';
        
        if (preg_match("/".$match."/", $_GET['url']) && $_SERVER['REQUEST_METHOD'] == "POST") {
            require_once 'extendsFrom.php';
        } elseif (preg_match("/^res/", $_GET['url'])) {
            echo $_SERVER['DOCUMENT_ROOT'];
            echo $_GET['url'];
            $ex = explode("/", $_GET['url'], 2);
            echo $file = $ex[1];
            
            
            echo "<h1>";
            $location = str_replace("\Routes", "", __dir__)."/app/big/".$file;
            echo $location;
            
            echo "</h1>";
            if (is_file($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'])) {
                header('Content-Type:');
                readfile($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
                exit;
            }
        }
    }
}

class Render
{
    
    private function __construct()
    {
        //
    }
    
    public function view($template, $_ = "")
    {
        $app_name = explode('.', $template);
        
        if (count($app_name)) {
            $name = $app_name[0];
            if (file_exists("../app/$name/config.php")) {
                $config = include "../app/$name/config.php";
            } else {
                $config = require '../../config/config.php';
            }
        } else {
            $config = require '../../config/config.php';
        }
        
        
        if ($config['header']) {
            $header = explode('.', $config['header']);
            
            if (count($header) > 1) {
                $app = $header[0];
                $app_template = $header[1];
                include_once "../app/$app/templates/$app_template.php";
            } else {
                // no template
                $app_template = $header[0];
                
                include_once "../templates/$app_template.php";
            }
        }
        
        $template = explode(".", $template);
        
        if (count($template) > 1) {
            $app = $template[0];
            $app_template = $template[1];
            include "../app/$app/templates/$app_template.php";
        } else {
            // no template
            $app_template = $template[0];
            
            include "../templates/$app_template.php";
        }
        
        //include("../templates/$template.php");
        
        if (count($app_name)) {
            $name = $app_name[0];
            if (file_exists("../app/$name/config.php")) {
                $config = include "../app/$name/config.php";
            } else {
                $config = require '../../config/config.php';
            }
        } else {
            $config = require '../../config/config.php';
        }
        
        if ($config['footer']) {
            $footer = explode('.', $config['footer']);
            
            if (count($footer) > 1) {
                $app = $footer[0];
                $app_template = $footer[1];
                include_once "../app/$app/templates/$app_template.php";
            } else {
                // no template
                $app_template = $footer[0];
                
                include_once "../templates/$app_template.php";
            }
        }
    }
    
    private function getHeader()
    {
        //
    }
    
    private function getFooter()
    {
        //
    }
}
