<img src="Mira.png" alt="PHP Version" width="300px">

<img src="https://img.shields.io/badge/PHP-5.6%2B-brightgreen.svg" alt="PHP Version"> [![DUB](https://img.shields.io/dub/l/vibe-d.svg)]()
<img src="https://img.shields.io/badge/Coverage-None-lightgrey.svg" alt="PHP Version">
<img src="https://img.shields.io/badge/stable-v0.1.0-blue.svg" alt="PHP Version">

Please read full documentation:

[Documentation Wiki](https://github.com/josephmancuso/Mira/wiki)  
[Example of a self contained app](https://github.com/taloncode/mustache)

## About

Mira is a PHP MVC motivated by the Python Django MVC Framework. The goal is to create an MVC that supports self contained apps, has an admin panel, and eventually a full CMS with plugins and themes.

## Installation

```shell
$ git clone https://github.com/josephmancuso/Mira.git
```

Take the contents of this repo you just cloned and put it into the root directory

Then inside the root of the project (where `cli.php` is located)

```shell
$ php cli.php --install taloncode/mustache
```
This will install an app from GitHub into your project 

Then add mustache to the main config file inside a templates array so the routes are added to your main routes file like so:

(inside `config/config.php`).

```php
return [
    'database' => [
        'username' => 'user',
        'password' => 'password',
    ],
    'templates' => [
        'mustache',
    ],
    'header' => 'dollarscore.base',
    'footer' => 'dollarscore.footer',
];
```

Open up your project using your normal webserver (like typing localhost into your browser)

More information inside the wiki

## Examples

## Routing

```php
// application/Routes.php

Route::get("path/to/url", function(){
     Render::view("appname.template", 
     [
          "variable" => "value",
          "another_variable" => "value"
     ]
     );
});
```

You can also get certain values from the URL and pass them to your view or use them in your Closure

```php
// application/Routes.php

// URL: website.com/baseball/player/123/
Route::get("baseball/player/{id}/", function($id){
     // $id is now accessible : $id = 123
     $model = new model("database");
     $player = $model->filter(" id = '$id' ");

     Render::view("appname.template", 
     [
          "player" => $player
     ]
     );
});
```

## Models

```php
// models/models.php
class table extends Model{}
```

You may specify the database in 1 of 2 ways.

Inside the class:

```php
// models.php

class table extends Model{
    protected $db = "database_name";
}
```

or during instantiation:

```php
// routes.php

$model = new table("database_name");
```

## Apps

Apps are designed to be complete containers. In other words, they are being designed to be drag and dropped into a project

### Creating an app

To create an app, create a folder with the app name into the `application/app/` directory

Inside that directory, create a `templates` directory as well as a `config.php` file.

### Config file

The config file is meant to create app specific header and footer templates

```php
// application/app/appname/config.php file

return [
    "header" => "appname.base",
    "footer" => "appname.footer"
];
```

### Installing Apps

Apps can be installed from GitHub. Find the app you want on GitHub and then in the command line do:

In the root directory, (the directory that contains the `cli.php` file)

```shell
    $ php cli.php --install githubaccount/reponame
```

To try this out for a real app, do: 

```shell
    $ php cli.php --install taloncode/mustache
```

this will install an pre made app into your `app` directory

### Linking the Router

Each app can have its own router added to the main `application/Routes/routes.php` file. 

Open up your main `config/config.php` file and add a `templates` array to the file like so:

```php
return [
    'database' => [
        'username' => 'user',
        'password' => 'password',
    ],
    'templates' => [
        'dollarscore',
        'mustache',
    ],
    'header' => 'dollarscore.base',
    'footer' => 'dollarscore.footer',
];
```

This will add the apps routers in order they are in the templates array

## Templates

Templates can be used in the `app/app_name/templates/` directory.

A route and template example might look like this:

```php
// inside Routes/route.pgp
Route::get("baseball/player/{id}/$", function(){
    $player = new player();
    $players = $player->filter(" id = '$id' ");

    Render::view("baseball.player-roster", [
        "players" => $player,
    ]);
});
```

then inside your template:

```php
// inside application/app/baseball/templates/player-roster.php

foreach ($_['players'] as $player) {
    echo $player['id'];
}
```
