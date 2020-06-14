<?php
include 'admin.php';
$id=intval($_GET['id']);
$db->exec('delete from content where id='.$id);
$ref=$_SERVER['HTTP_REFERER'];
redir($ref);