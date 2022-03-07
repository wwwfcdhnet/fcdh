<?php
include '../functionOpen.php';
$ref='';
if(isset($_GET['ref']))$ref=$_GET['ref'];
session_start();
unset($_SESSION['loginrank']);
unset($_SESSION['loginuser']);
unset($_SESSION['loginuid']);
redir('login.php?ref='.$ref);