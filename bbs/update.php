<?php
include 'function.php';
echo '正在备份数据库<br>';
$file='book-'.time().'.db';
echo '备份文件名：'.$file."<br>";
copy('book.db',$file);
$db->exec("ALTER TABLE `content` ADD  `reply` text");
$db->exec("ALTER TABLE `content` ADD  `verify` integer");
$db->exec("ALTER TABLE `content` ADD  `top` integer");
echo '数据库升级成功<br>';