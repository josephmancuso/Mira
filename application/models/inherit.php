<?php

require_once "../../config/database.php";

abstract class Model
{
	
	public $database = null;
	public $arr = array();
	public $update = array();
	
	
	function __construct($default_database)
	{
		if (!$default_database) {
			echo("DID NOT SUPPLY DATABASE INSIDE INSTATIATED CLASS");
		}
		
		$this->database = $default_database;

		return static::class;
	}
	
	function setDatabase($database)
	{
		$this->$database = $database;
		return true;
	}
	
	function __call($method, $value)
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
	
	#### CREATE
	
	public function insert()
	{
		
		foreach (array_keys($this->arr) as $key) {
			$newkey .= " ".$key;
		}
		
		$cols = str_replace(":", "", trim($newkey));
		$cols = str_replace(" ", ",", $cols);
		
		$newkey = str_replace(" ", ",", trim($newkey));		
		
		$handler = new PDO('mysql:host=localhost;dbname='.$this->database,USERNAME,PASSWORD);
		$table = static::class;
		$view_query = "INSERT INTO `$table` ($cols) VALUES($newkey)";
		return $query = $handler->prepare("INSERT INTO `$table` ($cols) VALUES($newkey)");
		
	}	
	
	#### READ
	function all()
	{
		$handler = new PDO('mysql:host=localhost;dbname='.$this->database,USERNAME,PASSWORD);
		
		if (strpos(static::class, "_") !== false) {
			$table = str_replace("_", "-", static::class);
		} else {
			$table = static::class;
		}
		
		$query = $handler->query("SELECT * FROM `$table` WHERE 1");
		
		return $query->fetchAll();
	}
	
	function get($where_clause = 1)
	{
		$handler = new PDO('mysql:host=localhost;dbname='.$this->database,USERNAME,PASSWORD);
		
		if (strpos(static::class, "_") !== false) {
			$table = str_replace("_", "-", static::class);
		} else {
			$table = static::class;
		}
		
		$query = $handler->query('SELECT * FROM `'.$table.'` WHERE '.$where_clause.' LIMIT 1');
		
		return $query->fetchAll();
		
	}
	
	function filter($where_clause = 1)
	{
		$handler = new PDO('mysql:host=localhost;dbname='.$this->database,USERNAME,PASSWORD);
		
		if (strpos(static::class, "_") !== false) {
			$table = str_replace("_", "-", static::class);
		} else {
			$table = static::class;
		}
		
		$query = $handler->query('SELECT * FROM `'.$table.'` WHERE '.$where_clause.'');
		
		return $query->fetchAll();
		
	}
	
	#### UPDATE
	
	function update($where_clause = 1)
	{
		foreach ($this->update as $key => $value) {
			$values .= "`".$key."` = '".$value."',";
		}
		
		$val = str_replace(":", "", $values);
		$val = rtrim($val, ",");
	
		$handler = new PDO('mysql:host=localhost;dbname='.$this->database,USERNAME,PASSWORD);
		
		if (strpos(static::class, "_") !== false) {
			$table = str_replace("_", "-", static::class);
		} else {
			$table = static::class;
		}
		
		return $query = $handler->prepare("UPDATE `$table` SET $val WHERE $where_clause LIMIT 1");
		
	}
	
	#### DELETE
	
	function delete($where_clause = 1)
	{
		$handler = new PDO('mysql:host=localhost;dbname='.$this->database,USERNAME,PASSWORD);
		
		if (strpos(static::class, "_") !== false) {
			$table = str_replace("_", "-", static::class);
		} else {
			$table = static::class;
		}
		
		return $query = $handler->prepare("DELETE FROM `$table` WHERE $where_clause LIMIT 1");
		
	}
	
	function deleteAll($where_clause = 1)
	{
		$handler = new PDO('mysql:host=localhost;dbname='.$this->database,USERNAME,PASSWORD);

		if (strpos(static::class, "_") !== false) {
			$table = str_replace("_", "-", static::class);
		} else {
			$table = static::class;
		}
		return $query = $handler->prepare("DELETE FROM `$table` WHERE $where_clause ");
		
	}
	
	function confirm()
	{
		try {
			$this->delete()->execute();
			return true;
		} catch(Exception $e) {
			return false;
		}
	}
	
	function confirmAll()
	{
		try {
			$this->deleteAll()->execute();
			return true;
		} catch(Exception $e) {
			return false;
		}
	}
	
	
	function save(){
		try {
			if (!empty($this->arr)){
				$this->insert()->execute($this->arr);
			}
			
			if (!empty($this->update)){
				$this->update()->execute($this->update);
			}
			$this->arr = array();
			$this->update = array();
			return true;
		} catch(Exception $e) {
			return false;
		}
	}
	
	function works()
	{
		echo "works";		
	}
	
	function viewSave()
	{
		print_r($this->arr);
	}
	
	function viewUpdate()
	{
		print_r($this->update);
	}
	
	function viewQuery()
	{
		echo $this->include()->view_query;
	}
}


?>