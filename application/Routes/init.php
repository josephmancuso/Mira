<?php

class Route{
	function __construct(){
		
	}
	
	public function get($url, Closure $func){
		//echo $url." Compared to: ".$_POST['url'];
		
		if ($url == $_GET['url'] && $_SERVER['REQUEST_METHOD'] == "GET"){
			
			function extendsFrom($url){
				include "../templates/$url.php";
				
			}
			return $func();
		}
	}
	
	public function post($url, Closure $func){
		if ($url == $_GET['url'] && $_SERVER['REQUEST_METHOD'] == "POST"){			
			return $func();
		}
	}
	
}

class Render{
	
	function __construct(){}
	
	function view($template, $_ = ""){
		
		
		
		include("../templates/$template.php");
		
	}
}
?>