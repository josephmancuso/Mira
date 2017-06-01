<?php

$config = require_once "../../config/config.php";

abstract class Model
{
    
    public $database = null;
    public $arr = array();
    public $update = array();
    public $structure = array();
    
    
    public function __construct($default_database = null)
    {
        if (isset($this->db)) {
            $this->database = $this->db;
        } elseif ($default_database === null) {
            echo("DID NOT SUPPLY DATABASE INSIDE INSTATIATED CLASS");
        } else {
            $this->database = $default_database;
        }

        // check if database exists
        global $config;
        $handler = new PDO(
            'mysql:host=localhost;dbname='.
            $this->database,
            $config['database']['username'],
            $config['database']['password']
        );
        $table = static::class;
        $query = $handler->query("SHOW TABLES LIKE '$table' ");
        
        // query the database
        // get the columns and echo them
        
        if ($this->create == true && $query->rowCount()) {
            // the table exists and create is true. try and add the columns
            $query = $handler->query("SELECT * FROM $table WHERE 1");
            $get_column = $query->GetColumnMeta(1);
            
            // match the get column ['name'] to each element in the array.
            $variables = get_class_vars(static::class);
            
            
            foreach ($variables as $variable) {
                $get_column = $query->GetColumnMeta($i);
                
                if ($variable) {
                    //if ($get_column['name'] == array_keys($variables)[$i+1] ){
                    if (in_array($get_column['name'], array_keys($variables), true)) {
                        
                        
                    } else {
                        // add the columns to the table
                        $column_name = array_keys($variables)[$i+1];
                        $data_type = array_values($variables)[$i+1];
                        $old_column = $get_column['name'];
                        
                        if ($data_type == "varchar") {
                            $data_type = "VARCHAR (255)";
                        } elseif ($data_type == "int") {
                            $data_type = "INT (11)";
                        }
                        
                        // modify table
                        if ($handler->query("ALTER TABLE $table CHANGE $old_column $column_name $data_type")) {
                        } else {
                            if ($data_type == "varchar") {
                                $data_type = "VARCHAR (255)";
                            } elseif ($data_type == "int") {
                                $data_type = "INT (11)";
                            }
                            
                            
                            $handler->query("ALTER TABLE $table ADD $column_name $data_type");
                        }
                        // add column
                    }
                    $i++;
                }
            }
        }
        
        
        if (!$query->rowCount() && $this->create == true) {
            // check if database exists
            global $config;
            $handler = new PDO(
                'mysql:host=localhost;dbname='.
                $this->database,
                $config['database']['username'],
                $config['database']['password']
            );
            $table = static::class;
            $query = $handler->query("SHOW TABLES LIKE '$table' ");
            
            // Construct a query
            $testing = get_class_vars(static::class);
            $query_s = "";
            $query_s .= "CREATE TABLE $table ( ";
            foreach ($testing as $key => $value) {
                if ($value === "id") {
                    $query_s .= $key ." INT (11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, ";
                } elseif ($value === "int") {
                    $query_s .= $key ." INT (11), ";
                } elseif ($value === "varchar") {
                    $query_s .= $key ." VARCHAR (255) NOT NULL,";
                }
            }
            $query_s = rtrim($query_s, ",");
            $query_s .= ")";
            
            // table does not exist, create the table
            $handler->query($query_s);
        }

        return static::class;
    }
    
    public function setDatabase($database)
    {
        $this->$database = $database;
        return true;
    }
    
    public function __call($method, $value)
    {
        //remove set from the variable
        
        $choice = explode("_", $method);
        
        if ($choice[0] == "update") {
            
            // This is an update call
            $method = explode("update", $method);
            $method = strtolower(substr_replace($method[1], ":", 0, 0));
            $method = str_replace("_", "", $method);
            $this->update[$method] = $value[0];
        } else {
            // This is an set call to be inserted into a database.
            
            $method = explode("set", $method);
            $method = strtolower(substr_replace($method[1], ":", 0, 0));
            $method = str_replace("_", "", $method);
            
            $this->arr[$method] = $value[0];
        }
    }

    public function getColumns()
    {
        global $config;
        $handler = new PDO('mysql:host=localhost;dbname='.
            $this->database,
            $config['database']['username'],
            $config['database']['password']
        );
        
        if (strpos(static::class, "_") !== false) {
            $table = str_replace("_", "-", static::class);
        } else {
            $table = static::class;
        }

        $rs = $handler->query("SELECT * FROM $table LIMIT 1");
        for ($i = 0; $i < $rs->columnCount(); $i++) {
            $col = $rs->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }
    
    #### CREATE
    
    public function insert()
    {
        
        foreach (array_keys($this->arr) as $key) {
            $newkey .= " ".$key;
        }
        
        $cols = str_replace(":", "", trim($newkey));
        $cols = str_replace(" ", ",", $cols);
        
        $newkey = str_replace(" ", ",", trim($newkey));     
        
        $handler = new PDO('mysql:host=localhost;dbname='.$this->database,$config['database']['username'],$config['database']['password']);
        $table = static::class;
        $view_query = "INSERT INTO `$table` ($cols) VALUES($newkey)";
        return $query = $handler->prepare("INSERT INTO `$table` ($cols) VALUES($newkey)");
    }

    public function insertFromPost($post)
    {
        print_r($post);
        
        foreach ($post as $key => $value) {
            $cols .= "--".$key;
            $values .= "--'".$value."'";
        }

        $cols = str_replace("--", ",", trim($cols));
        $cols = ltrim($cols, ',');
        $values = str_replace("--", ",", trim($values));
        $values = ltrim($values, ",");

        global $config;
        $handler = new PDO('mysql:host=localhost;dbname='.
            $this->database,
            $config['database']['username'],
            $config['database']['password']
        );
        $table = static::class;
        echo $view_query = "INSERT INTO `$table` ($cols) VALUES ($values)";
        return $query = $handler->query("INSERT INTO `$table` ($cols) VALUES ($values)");
    }


    
    #### READ
    public function all()
    {
        global $config;
        $handler = new PDO('mysql:host=localhost;dbname='.$this->database,$config['database']['username'],$config['database']['password']);
        
        if (strpos(static::class, "_") !== false) {
            $table = str_replace("_", "-", static::class);
        } else {
            $table = static::class;
        }
        
        $query = $handler->query("SELECT * FROM `$table` WHERE 1");
        
        return $query->fetchAll();
    }
    
    public function get($where_clause = 1)
    {
        $handler = new PDO('mysql:host=localhost;dbname='.$this->database,$config['database']['username'],$config['database']['password']);
        
        if (strpos(static::class, "_") !== false) {
            $table = str_replace("_", "-", static::class);
        } else {
            $table = static::class;
        }
        
        $query = $handler->query('SELECT * FROM `'.$table.'` WHERE '.$where_clause.' LIMIT 1');
        
        return $query->fetchAll();
    }
    
    public function filter($where_clause = null)
    {
        global $config;
        $handler = new PDO(
            'mysql:host=localhost;dbname='.
            $this->database,
            $config['database']['username'],
            $config['database']['password']
        );

        $handler->errorInfo();
        if (strpos(static::class, "_") !== false) {
            $table = str_replace("_", "-", static::class);
        } else {
            $table = static::class;
        }
        
        $query = $handler->query("SELECT * FROM $table WHERE $where_clause");
        $this->structure = array();
        //echo $query->columnCount();

        for ($i = 0; $i < $query->columnCount(); $i++) {
            array_push($this->structure, $query->getColumnMeta($i));
        }
        
        return $query->fetchAll();
    }

    public function toJson($where_clause = 1)
    {
        global $config;
        $handler = new PDO('mysql:host=localhost;dbname='.$this->database,$config['database']['username'],$config['database']['password']);
        
        if (strpos(static::class, "_") !== false) {
            $table = str_replace("_", "-", static::class);
        } else {
            $table = static::class;
        }
        
        $query = $handler->query("SELECT * FROM $table WHERE $where_clause");

        return json_encode($query->fetchAll());
    }
    
    #### UPDATE
    
    public function update($where_clause = 1)
    {
        foreach ($this->update as $key => $value) {
            $values .= "`".$key."` = '".$value."',";
        }
        
        $val = str_replace(":", "", $values);
        $val = rtrim($val, ",");
    
        $handler = new PDO('mysql:host=localhost;dbname='.$this->database,$config['database']['username'],$config['database']['password']);
        
        if (strpos(static::class, "_") !== false) {
            $table = str_replace("_", "-", static::class);
        } else {
            $table = static::class;
        }
        
        return $query = $handler->prepare("UPDATE `$table` SET $val WHERE $where_clause LIMIT 1");
    }

    public function updateFromPost($post, $where_clause = 1)
    {
        foreach ($post as $key => $value) {
            $values .= "`".$key."` = '".$value."',";
        }
        
        $val = str_replace(":", "", $values);
        $val = rtrim($val, ",");

        global $config;
        $handler = new PDO(
            'mysql:host=localhost;dbname='.
            $this->database,
            $config['database']['username'],
            $config['database']['password']
        );
        
        if (strpos(static::class, "_") !== false) {
            $table = str_replace("_", "-", static::class);
        } else {
            $table = static::class;
        }
        
        return $query = $handler->query("UPDATE `$table` SET $val WHERE $where_clause LIMIT 1");
    }
    
    #### DELETE
    
    public function delete($where_clause = 1)
    {
        $handler = new PDO(
            'mysql:host=localhost;dbname='.
            $this->database,
            $config['database']['username'],
            $config['database']['password']
        );
        
        if (strpos(static::class, "_") !== false) {
            $table = str_replace("_", "-", static::class);
        } else {
            $table = static::class;
        }
        
        return $query = $handler->prepare("DELETE FROM `$table` WHERE $where_clause LIMIT 1");
    }
    
    public function deleteAll($where_clause = 1)
    {
        $handler = new PDO(
            'mysql:host=localhost;dbname='.
            $this->database,
            $config['database']['username'],
            $config['database']['password']
        );

        if (strpos(static::class, "_") !== false) {
            $table = str_replace("_", "-", static::class);
        } else {
            $table = static::class;
        }
        return $query = $handler->prepare("DELETE FROM `$table` WHERE $where_clause ");
    }
    
    public function confirm()
    {
        try {
            $this->delete()->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function confirmAll()
    {
        try {
            $this->deleteAll()->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    
    public function save()
    {
        try {
            if (!empty($this->arr)) {
                $this->insert()->execute($this->arr);
            }
            
            if (!empty($this->update)) {
                $this->update()->execute($this->update);
            }
            $this->arr = array();
            $this->update = array();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function works()
    {
        echo "works";
    }
    
    public function viewSave()
    {
        print_r($this->arr);
    }
    
    public function viewUpdate()
    {
        print_r($this->update);
    }
    
    public function viewQuery()
    {
        echo $this->include()->view_query;
    }
}
