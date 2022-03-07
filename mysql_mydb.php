<?php
		$_DBHOSTname="localhost";               
		$_DBuser="root";                    
		$_DBpass="root";                    
		$_DBname="daohang";                   
		$_KEY="|mGbPaU4ZJACD,9{";     
		$_URLNUM=100;						

		$mydb=mysqli_connect($_DBHOSTname,$_DBuser,$_DBpass,$_DBname);
		if (!$mydb)
		{
			die("err link : " . mysqli_connect_error());
		}
		mysqli_set_charset($mydb,"utf8");
	?>