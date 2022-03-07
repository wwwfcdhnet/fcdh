<?php
include '../mysql_mydb.php';
include '../functionOpen.php';
define('ADMIN',__DIR__);
session_start();
$ref='';
if(isset($_GET['ref']))$ref='?ref='.$_GET['ref'];
if(!isset($_SESSION['loginuid']) || intval($_SESSION['loginuid'])<1) redir('login.php'.$ref);
//$_KEY="ls(d[}#I+*^(llsldkf)#!A_-=";