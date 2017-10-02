<img src="mira-master.png" alt="PHP Version" width="300px">

<img src="https://img.shields.io/badge/PHP-7%2B-brightgreen.svg" alt="PHP Version"> [![DUB](https://img.shields.io/dub/l/vibe-d.svg)]()
<img src="https://img.shields.io/badge/stable-v2.0.1-blue.svg" alt="PHP Version">

Please read full documentation:

[Documentation Wiki](https://github.com/josephmancuso/Mira/wiki)  
[Example of a self contained app](https://github.com/taloncode/mustache)

## About

Mira is a PHP MVC motivated by self contained applications. The goal is to create an MVC that supports self contained apps and service providers to simply and easily add functionality between Mira framework installs.

## Installation

Download the latest release from this repo

Unzip the download and put all the folder contents inside your root directory or folder you wish to install Mira into.

Then run:

    $ composer install

Open up a browser and navigation to your localhost. You should see the Mira default App.

### Install a self-contained app

Inside the root of the project (where `mira` is located)

```shell
$ php mira new:install taloncode/mustache
```
This will install an app from GitHub into your project 

Then add mustache to the main config file inside a templates array ABOVE mira so the routes are added to your main routes file like so:

(inside `config/config.php`).

```php
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
        'Mustache',
        'Mira',
    ],

    'Debug' => true,
];
```

Open up your project using your normal webserver (like typing localhost into your browser). You should see the Mustache App website

More information inside the wiki

## Apps

Apps are designed to be complete containers. In other words, they are being designed to be drag and dropped into a project

### Creating an app

To create an app, go to the command line and go to the root of your project.

    $ php mira new:app Appname

This will create a new app in the app folder and put all needed files inside it.

### Installing Apps

Apps can be installed from GitHub. Find the app you want on GitHub and then in the command line do:

In the root directory, (the directory that contains the `mira` file)

```shell
    $ php mira new:install githubaccount/reponame
```

To try this out for a real app, do: 

```shell
    $ php mira new:install taloncode/mustache
```

this will install a pre made app into your `app` directory

remember to put the name of the app in the Apps array in your config file.

Read the documentation for more info