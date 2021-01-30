<?php
include '../sqlite_db.php';
include '../function.php';
define('ADMIN',__DIR__);
session_start();
//var_dump($_SESSION['login']);
if($_SESSION['login']!='OK') redir('login.php');
/*
七牛的www.qiniu.com 	AccessKey/SecretKey
如果没有可以在官网注册一个
*/
$accessKey = '';
$secretKey = '';
