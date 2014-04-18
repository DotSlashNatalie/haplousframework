<?php

class HF_Core
{
	private $class;
	private $method;
	private $classname;
	private $args = array();
	
	public function __construct()
	{
		$this->findController();
	}
	
	private function findController()
	{
		$request = $_SERVER["REQUEST_URI"];
		if ($request == "" || $request == "/")
		{
			require("../../application/controllers/main.php");
			$this->$class = new main();
			$this->$method = "index";
			$this->$classname = "main";
			return;
		}
		$arr = explode("/", $request);
		$path = "../../application/controllers/";
		for($i = 0; $i < count($arr); $i++)
		{
			if ($is_file($path . $arr[$i] . ".php")) // found the controller
			{
				include($path . $arr[$i] . ".php");
				$this->$class = new $arr[$i];
				
				if ($i + 1 != count($arr)) // if there is a define after the controller name - this would be the method name
				{
					$this->$method = $arr[$i+1];
					$this->$args = array_slice ($arr, 2);
					$this->$classname = $arr[$i];
				} else { // call index
					$this->$method = "index";
					$this->$classname = $arr[$i];
				}
				return;
			}
			
			if (is_dir($path . $arr[$i])) // controller is hidden deeper
			{
				$path = $path . $arr[$i] . "/";
				continue;
			}
			
			// throw exception controller not found
		}
		
	}
	
	public function run()
	{
		$call = new ReflectionFunction($this->$classname, $this->$method);
		$call->invokeArgs($this->$class, $this->$args);
	}
}