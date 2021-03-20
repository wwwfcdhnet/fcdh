<?php
	date_default_timezone_set("Asia/Shanghai");
	define("ROOT",__DIR__);
	try{
	  $db = new PDO("sqlite:".ROOT."/00TjxuCUJhRo1g3tPJ.db");
	}catch (Exception $e) {
	  echo $e->getMessage();
	  exit;
	}
	$db->exec("set names utf8");
	$_KEY="p!2Ax28MR#7yjM)o";
	?>