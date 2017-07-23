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
        'username' => 'database_username',
        'password' => 'database_password',
    ],

    /*
    |--------------------------------------------------------------------------
    | Apps
    |--------------------------------------------------------------------------
    |
    | Any apps you want Mira to register, put here. Putting apps here will
    | auto register both the controllers and routes.
    |
    */

    'Apps' => [
        'Mira',
    ],
];
