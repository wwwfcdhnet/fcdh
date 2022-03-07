<?php
include 'sqlite_db.php';
$pass='admin';
$pass=md5($_KEY.$pass.$_KEY);
echo $pass;
?>