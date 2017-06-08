<?php

class Route
{

    private function __construct()
    {
        //
    }
    
    public static function get($url, Closure $func)
    {
        //echo $_SERVER['REQUEST_METHOD'];
        
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
            $_GET[$param[0]] = $param[1];
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
    
    public static function post($url, Closure $func)
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
            
            
            echo "<h1>";
            
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
    
    public static function view($template, $_ = "")
    {
        extract($_);
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

            if (file_exists("../app/$app/templates/$app_template.engine.php")) {
                $output = file_get_contents("../app/$app/templates/$app_template.engine.php");
                $output = preg_replace("/{{/", '<?=', $output);
                $output = preg_replace("/}}/", '?>', $output);

                // convert foreach template tags
                $output = str_replace("@foreach", "<?php foreach(", $output);
                $output = str_replace("@start", ") : ?> ", $output);
                $output = str_replace("@endforeach", "<?php endforeach; ?>", $output);

                // convert if template tags
                $output = str_replace("@if", "<?php if(", $output);
                $output = str_replace("@elseif", "<?php elseif(", $output);
                $output = str_replace("@else", "<?php else : ?>", $output);
                $output = str_replace("@endif", "<?php endif; ?>", $output);

                //ob_end_clean();
                echo eval(' ?>'.$output. ' ');
            } else {
                include "../app/$app/templates/$app_template.php";
            }
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

    public static function redirect($url)
    {
        Header("Location: $url");
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