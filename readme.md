# Installation

Copy files into your working directory

Go into the config/database.php file and insert your username and password for your database

### Routing

All routing is done in `application/Routes/route.php`

Routing can be done like so:

```php
Route::get('url', function(){
    //logic goes here
});

Route::post('url', function(){
    //logic goes here
});
```
### Templates

All templates go inside the `application/templates` folder

```php
Route::get('url', function(){
    // template for discover.php
    
    Render::view("discover", ['variable' => 'value']);
    
});
```

#### Example of Real Route

```php
Route::get('url', function(){
    $players = new player("database_name");
    $teams = new teams("database_name");
    
    Render::view("discover", 
    [
    
        'players' => $players->all(),
	'teams' => $teams->all(),
	
    ]);
});
```

#### Inside the Template

```php
<?php
// player.php template
foreach($_['players'] as $player){

    echo "Player: ".$player['name'];
    
}

foreach($_['teams'] as $team){

    echo "Team: ".$team['name'];
    
}
```


# PHP Models

PHP Models is a project to create an Active Record type ORM system.

## Installation
Download the models folder and insert it into your project.

Include the models.php file in the file you want to retrieve your models.

Put your username and password in the top of the models.php file.

## Usage

Open models.php and put your tables inside the models php file.
### Existing Tables
```php
// models.php

// this syntax is used for already established tables
class table_name extends Model{}
```

### Maintaining Tables
```php
// this syntax is used for maintaining tables
class table_name extends Model{
    protected $create = true;
    
    protected $id = "id";
    protected $firstname = "varchar";
    protected $lastname = "varchar";
```
When `$create = true` you may start adding variables by `protected $column_name = 'data_type'`

When you add new variables, the table will be updated accordingly when the class is initialized.

So doing:
```php
class table_name1 extends Model{
    protected $create = true;
    
    protected $id = "id";
    protected $firstname = "varchar";
    protected $phonenumber = "int"; // changed from lastname = 'varchar'
```
Will rename the `lastname` column in the table to `phonenumber` and change the datatype from a `varchar` to an `int` 

Adding new variables will create new columns.

**NOTE: Currently there is not a way to delete columns by simply deleting variables, _yet_. That will be to come in future versions. Also that the only supported datatypes are int and varchar**

## CRUD
Inside your working files:

### Create
```php
$table = new table_name("database_name");

$table->setFirstname("value");
$table->setLastname("value");
$table->save();
```

### Read

```php
$table = new table_name("database_name");

// get all records
$table->all();

// get one record
$table->get();

// get filtered records
$table->filter(" firstname = 'value' ");

// display the records
foreach($table->all() as $table){
    echo $table['column_name'];
}
```

### Update
```php
$table = new table_name("database_name");

$table->update_Firstname("john");
$table->update(" firstname = 'mike' "); // a where clause
```

### Delete
```php
$table = new table_name("database_name");

$table->delete(" firstname = 'mike' "); // where clause, deletes 1 record
$table->confirm(); // safety feature

$table->deleteAll(" firstname = 'mike' "); // where clause, deletes all record
$table->confirmAll(); // safety feature
```
