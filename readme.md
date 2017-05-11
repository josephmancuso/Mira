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
