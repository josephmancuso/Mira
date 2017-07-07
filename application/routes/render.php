<?php

namespace Mira;

class Render
{
    
    private function __construct()
    {
        //
    }
    
    public static function view($template, $_ = [])
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

        // Multi-tenancy
        $url = $_SERVER['HTTP_HOST'];
        $host = explode('.', $url);
        $subdomain = $host[0];

        $project_config = require '../../config/config.php';

        $multi_tenancy = $project_config['multi-tenancy'];
        if (count($host) >= 3 && $subdomain != 'www') {
            $multi_check = true;
        } else {
            $multi_check = false;
        }
        
        if ($config['header']) {
            $header = explode('.', $config['header']);
            
            if (count($header) > 1) {
                if ($multi_tenancy && $multi_check) {
                    $app = $subdomain;
                } else {
                    $app = $header[0];
                }
                
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
            if ($multi_tenancy && $multi_check) {
                $app = $subdomain;
            } else {
                $app = $template[0];
            }
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
                if ($multi_tenancy && $multi_check) {
                    $app = $subdomain;
                } else {
                    $app = $footer[0];
                }
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
