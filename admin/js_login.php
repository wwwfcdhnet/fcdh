<?php
include '../sqlite_db.php';
include '../function.php';
session_start();
$name=@$_POST['name'];
$pass=@$_POST['pass'];
$scode=@strtoupper($_POST['scode']);
$pass=md5($pass.$_KEY);
$ps=$db->prepare("select * from admin where name=:name and pass=:pass");
$ps->execute(array(':name'=>$name,':pass'=>$pass));
$data=$ps->fetch();
//	echo$_POST['pass'],$pass;
if($data){
	$_SESSION['login']='OK';
	if(load_config('scode')=='1'){
		if($scode!=$_SESSION['scode']){
			echo 'scode';
			exit;
		}
	}
	echo 'success';
}