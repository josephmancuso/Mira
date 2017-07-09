<img src="Mira.png" alt="PHP Version" width="300px">

<img src="https://img.shields.io/badge/PHP-5.6%2B-brightgreen.svg" alt="PHP Version"> [![DUB](https://img.shields.io/dub/l/vibe-d.svg)]()
<img src="https://img.shields.io/badge/stable-v0.8.0-blue.svg" alt="PHP Version">

Please read full documentation:

[Documentation Wiki](https://github.com/josephmancuso/Mira/wiki)  
[Example of a self contained app](https://github.com/taloncode/mustache)

## About

Mira is a PHP MVC motivated by self contained applications. The goal is to create an MVC that supports self contained apps and service providers to simply and easily add functionality between Mira framework installs.

## Installation

Download the latest release from this repo

Unzip the download and put all the folder contents inside your root directory or folder you wish to install Mira into.

### Install a self-contained app

inside the root of the project (where `cli.php` is located)

```shell
$ php cli.php --install taloncode/mustache
```
This will install an app from GitHub into your project 

Then add mustache to the main config file inside a templates array ABOVE mira so the routes are added to your main routes file like so:

(inside `config/config.php`).

```php
return [
    'database' => [
        'username' => 'user',
        'password' => 'password',
    ],
    'templates' => [
        'mustache',
        'mira',
    ]
];
```

Open up your project using your normal webserver (like typing localhost into your browser)

More information inside the wiki

## Examples

## Routing

```php
// application/Routes.php

use Mira\Route;
use Mira\Render;

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

use Mira\Route;
use Mira\Render;

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

use Mira\Model;

class table extends Model{}
```

You may specify the database in 1 of 2 ways.

Inside the class:

```php
// models.php

use Mira\Model;

class table extends Model{
    protected $db = "database_name";
}
```

or during instantiation:

```php
// routes.php

$model = new table("database_name");
```

## Controllers

Controller take on a new meaning in Mira

Controllers can get really messy and the best way to create enterprise level software is to compartmentalize your software.

In Mira, controllers aren't conventional classes, but they are closures that compartmentalize your Routes.

This is an example of a controller in Mira:

```php
// the method for controller:: can be any name you want, it is more of a category name

use Mira\Route;
use Mira\Render;

controller::name(function(){

    // controller level variables
    $posts = new posts();
    $authors = new authors();

    Routes::get('url/path/$', function() use ($authors){

        Render::view('app.template', [
            'authors' => $authors
        ]);

    });

    Routes::get('url/path/posts/$', function() use ($posts, $authors){

        Render::view('app.template2', [
            'authors' => $authors,
            'posts' => $posts
        ]);

    });


});
``` 

Controllers in Mira just abstracts some of the logic for `Routes` class. Controllers just take multiple routers and match like normal Routes

## Apps

Apps are designed to be complete containers. In other words, they are being designed to be drag and dropped into a project

### Creating an app

To create an app, go to the command line and go to the root of your project.

    $ php cli.php --new appname

This will create a new app in the app folder and put all needed files inside it.

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

Each app can have its own router added to the main Routes file. 

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

use Mira\Route;
use Mira\Render;

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

foreach ($players as $player) {
    echo $player['id'];
}
```

if you end your template in `.engine.php` then you can also do:

```php
@foreach ($players as $player)
    {{ $player }}
@endforeach
```
