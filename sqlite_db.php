<?php
date_default_timezone_set('Asia/Shanghai');
define('ROOT',__DIR__);
try{
  $db = new PDO('sqlite:'.ROOT.'/fcdh.db');
}catch (Exception $e) {
  echo $e->getMessage();
  exit;
}
$db->exec('set names utf8');
?>