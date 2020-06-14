<?php
include '../function.php';
define('ADMIN',__DIR__);
session_start();
//var_dump($_SESSION['login']);
if($_SESSION['login']!='OK') redir('login.php');

