<?php
include '../function.php';
session_start();
unset($_SESSION['login']);
redir('login.php');