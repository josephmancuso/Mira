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

    /**
     * Check if multi-tenancy => true in the project config file
     *
     * @return bool
     **/

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

    /**
     * Check if the template has its own config file then include that
     * else include a project wide config file.
     *
     * @param $template - an app name
     * @return config file
     **/

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

    /**
     * Get the subdomain from the url
     *
     * @return subdomain - first section of url
     * @see multiTenancy()
     **/

    public static function getSubdomain()
    {
        // Multi-tenancy
        $url = $_SERVER['HTTP_HOST'];
        $host = explode('.', $url);
        return $subdomain = $host[0];
    }

    /**
     * undocumented function
     *
     * @param $pattern - a regex expression
     * @param $replace - a replacement for the output
     * @param $output - take input from file_get_contents() @see view()
     * @return $output - a preg_replace template tag
     * @see templateTags()
     **/

    public static function register($pattern, $replace, $output)
    {
        return $output = preg_replace($pattern, $replace, $output);
    }

    /**
     * Render a view
     *
     * @param $template - app.template string
     * @param $variables (array) - an array of variables to pass to view
     * @return null
     **/
    
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

    /**
     * undocumented function
     *
     * @param $url - /normal/url/
     * @return header
     **/
    public static function redirect($url)
    {
        Header("Location: $url");
    }

    /**
     * undocumented function
     *
     * @param $app - app name
     * @param $app_template - app template
     * @param $variables (array) - array of variables to pass to template
     * @return template file
     * @see templateEngine()
     **/

    public static function getTemplate($app, $app_template, $variables)
    {
        extract($variables);
        include $_SERVER['DOCUMENT_ROOT']."/application/app/$app/templates/$app_template.php";
    }

    /**
     * The engine to power the template syntax. Replaces template syntax
     * with valid PHP code
     *
     * 1. Gets the file_get_contents of the template
     * 2. Then runs templateTags() which preg_replaces the output
     * 3. Then returns the eval of the replaced template syntax
     *
     * @param $template - takes an exploded() template
     * @param $variables (array) - array of variables to pass to view
     * @return php eval code for Comet template syntax engine
     * @see templateTags()
     **/

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
            self::getTemplate($app, $app_template, $variables);
        }
    }

    /**
     * Function registers template tags for the Comet template engine.
     *
     * This function takes $output as a paramter which is from a
     * file_get_contents() function. This function processes the
     * $output variable and returns the last $output which will
     * preg_replace() all the template engine syntax.
     *
     * @param $output - a file_get_contents() of the template
     * @return output file
     * @see view()
     **/

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

    /**
     * Returns a pattern that matches expressions such as @tag('')
     *
     * @param $tag - the tag name to match such as extends, 
     * @return regex pattern
     * @see templateTags()
     **/

    public static function matcher($tag)
    {
        return '/(\s*)@'.$tag.'(\s*\(.*\))/';
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

    /**
     * function for @extends() template syntax
     *
     * @param $template - app.template
     * @return the template
     * @see templateTags()
     **/

    public static function templateExtends($template)
    {
        $template = explode('.', $template);
        $app = $template[0];
        $app_template = $template[1];
        include $_SERVER['DOCUMENT_ROOT']."/application/app/$app/templates/$app_template.php";
    }
}
