<?php
abstract class View{
	
	public $template = null;
	
	public $text = null;
	
	function __construct()
	{
		$template = strtolower(__CLASS__);
		//include '../../'.$template.'.php';
		if ($text){
			echo $text;
		}
	}
	
	function out($value){
		echo $value;
	}
	
	function render($name){
		require 'templates/$name.php';
	}
}

class Index extends View{
	function __construct()
	{
		$template = strtolower(__CLASS__);
		$this->view->msg = "got a variable";
	}
}
