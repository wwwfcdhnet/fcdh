<?php
include 'admin.php';
$id=intval($_GET['id']);
$cancel=@$_GET['cancel'];
if($cancel){
    $db->exec("UPDATE `content` SET `verify`=0 where id=$id");
}
else{
    $db->exec("UPDATE `content` SET `verify`=1 where id=$id");
}

$ref=$_SERVER['HTTP_REFERER'];
redir($ref);