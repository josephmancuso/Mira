<?php
/**
 * The Render class is used to render a view (template).
 * Render also handles template tags and the templating engine.
 *
 * To extend Render capabilities, just make a new Service Provider
 * copy over the view(), templateEngine(), and templateTags() methods.
 * Then add that new Service Provider in the config file.
 *
 * @package Mira Core
 * @author Mira Framework
 **/

namespace Mira;

class Render
{
    private function __construct()
    {
        //
    }

    public function multiTenancy()
    {
        // Multi-tenancy
        $subdomain = self::getSubdomain();

        $multi_tenancy = Project::config('multi-tenancy');
        if (count($host) >= 3 && $subdomain != 'www') {
            return true;
        }
        return false;
    }

    public static function getConfig($template)
    {
        $app_name = explode('.', $template);
        
        if (count($app_name)) {
            $name = $app_name[0];
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/application/app/$name/config.php")) {
                return $config = require $_SERVER['DOCUMENT_ROOT']."/application/app/$name/config.php";
            } else {
                return $config = require $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
            }
        } else {
            return $config = require $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
        }
    }

    public static function getSubdomain()
    {
        // Multi-tenancy
        $url = $_SERVER['HTTP_HOST'];
        $host = explode('.', $url);
        return $subdomain = $host[0];
    }

    public static function register($pattern, $replace, $output)
    {
        return $output = preg_replace($pattern, $replace, $output);
    }
    
    public static function view($template, $variables = [])
    {
        extract($variables);
        $config = self::getConfig($template);

        self::multiTenancy();
        
        self::getHeader($config);

        // Template Engine Logic
        $template = explode(".", $template);
        if (count($template) > 1) {
            self::templateEngine($template, $variables);
        } else {
            // no template
            echo "no template";
        }

        self::getFooter($config);
        die();
    }

    public static function redirect($url)
    {
        Header("Location: $url");
    }

    public static function getTemplate($app, $app_template, $variables)
    {
        extract($variables);
        include $_SERVER['DOCUMENT_ROOT']."/application/app/$app/templates/$app_template.php";
    }

    public static function templateEngine($template, $variables)
    {
        extract($variables);
        if (self::multiTenancy()) {
                $app = self::getSubdomain();
            } else {
                $app = $template[0];
            }
            $app_template = $template[1];
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/application/app/$app/templates/$app_template.engine.php")) {
                $output = file_get_contents($_SERVER['DOCUMENT_ROOT']."/application/app/$app/templates/$app_template.engine.php");
                
                $output = self::templateTags($output);
                
                echo eval(' ?>'.$output. ' ');
            } else {
                //include $_SERVER['DOCUMENT_ROOT']."/application/app/$app/templates/$app_template.php";
                self::getTemplate($app, $app_template, $variables);
            }
    }

    public static function templateTags($output)
    {
        // register template tags
        $output = self::register("/{{/", '<?=', $output);
        $output = self::register("/}}/", '?>', $output);                
        
        $output = self::register(self::matcher("(if|elseif|foreach|for|while)"), '$1<?php $2$3: ?>', $output);

        $output = self::register("/(\s*)@(else)(\s*)/", '$1<?php $2: ?>$3', $output);

        $output = self::register('/(\s*)@(endif|endforeach|endfor|endwhile)(\s*)/', '$1<?php $2; ?>$3', $output);

        $output = self::register("/(\s*)@(comment)/", '$1<?php if (0): ?>', $output);

        $output = self::register("/(\s*)@(endcomment)/", "<?php endif; ?>", $output);

        $output = self::register('/(\s*)@unless(\s*\(.*\))/', "$1<?php if ( ! ($2)): ?>", $output);

        $output = self::register('/(\s*)@(endunless)(\s*)/', '<?php endif; ?>', $output);

        $output = self::register("/(\s*)@(use)(\s.*)/", "<?php use $3; ?>", $output);


        $output = self::register(self::matcher('iteration'), "1", $output);

        return $output = self::register(self::matcher('extends'), '$1<?php Mira\\Render::templateExtends($2) ?>', $output);
    }

    public static function matcher($function)
    {
        return '/(\s*)@'.$function.'(\s*\(.*\))/';
    }
    
    public function getHeader($config)
    {
        if ($config['header']) {
            $header = explode('.', $config['header']);

            if (count($header) > 1) {

                if (self::multiTenancy()) {
                    $app = self::getSubdomain();
                } else {
                    $app = $header[0];
                }
                
                $app_template = $header[1];

                include_once $_SERVER['DOCUMENT_ROOT']."/application/app/$app/templates/$app_template.php";
            } else {
                // no template
                echo "nothing";
            }
        }
    }
    
    public function getFooter($config)
    {
        if ($config['footer']) {
            $footer = explode('.', $config['footer']);
            
            if (count($footer) > 1) {
                if ($multi_tenancy && $multi_check) {
                    $app = $subdomain;
                } else {
                    $app = $footer[0];
                }
                $app_template = $footer[1];
                include_once $_SERVER['DOCUMENT_ROOT']."/application/app/$app/templates/$app_template.php";
            } else {
                // no template
                echo "no template";
            }
        }
    }

    public static function templateExtends($template)
    {
        $template = explode('.', $template);
        $app = $template[0];
        $app_template = $template[1];
        include $_SERVER['DOCUMENT_ROOT']."/application/app/$app/templates/$app_template.php";
    }
}
