Please read full documentation:

[Documentation Wiki](https://github.com/josephmancuso/DollarScore/wiki)

## About

DollarScore is a PHP MVC motivated by the Python Django MVC Framework. The goal is to create an MVC that supports self contained apps, has an admin panel, and eventually a full CMS with plugins and themes.

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
    "footer" => "appname/footer"
];
```

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
