<?php

namespace Mira;

class Project
{
    public $providers;
    public function __construct()
    {
        //
    }

    public static function config($config_name = false)
    {
        if (!$config_name) {
            return include $_SERVER['DOCUMENT_ROOT']."/config/config.php";
        } else {
            $config = include $_SERVER['DOCUMENT_ROOT']."/config/config.php";
            return $config[$config_name];
        }
    }

    public function setProviders($providers)
    {
        $this->providers = $providers;
    }

    public function test($array)
    {
        $this->setProviders($array);
        return $this;
    }

    public function check()
    {
        echo "checked, works good";
        echo $this->providers;
        foreach ($this->providers as $provider) {
            echo $provider;
            if (in_array($provider, $this->config('providers'))) {
                echo true;
            } else {
                echo false;
            }
        }
    }
}
