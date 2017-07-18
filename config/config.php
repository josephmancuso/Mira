<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Set the database variables username and password to match the username
    | and password of your MySQL database. You'll be able to use the framework
    | without this setting until you start adding models.
    |
    */

    'database' => [
        'host' => 'localhost',
        'username' => 'database_name',
        'password' => 'database_password',
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    | Add any apps to this configuration in order for mira to search through
    | and find the correct route files. If the app is not inside this setting
    | then it will simply not match the correct URL's. 
    |
    */

    'templates' => [
        'mira',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Any apps you have will load all the models to be available in any of
    | the route files. Put the app name in the setting below so Mira
    | can recognize the models.
    |
    */

    'models' => [],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Add the folder inside your middleware folder inside your application.
    | Middlware can be found at application > middleware > middleware_name.
    | Inside this setting, just put the middleware_name and it will 
    | connect to the autoload.php file. inside the app.
    |
    */

    'middleware' => [],
];
