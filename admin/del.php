<?php
include 'admin.php';
$table='';
$id=0;
if(isset($_GET['id']) && !empty($_GET['id'])){
	$id=intval($_GET['id']);
	$table='content';
}elseif(isset($_GET['hid']) && !empty($_GET['hid'])){
	$id=intval($_GET['hid']);
	$table='contenthref';
}
$db->exec("delete from $table where id=".$id);
$ref=$_SERVER['HTTP_REFERER'];
redir($ref);