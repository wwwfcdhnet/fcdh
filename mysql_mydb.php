<?php
$_DBHOSTname="localhost";               //主机
$_DBuser="root";                    //用户
$_DBpass="root";                      //密码
$_DBname="neihi";                    //数据库
$_KEY="@(`9Jyo4YrdZw5^{Y})@";                         //密钥
$_URLNUM=100;						// 非认证用户默认收藏记录

$mysql=mysqli_connect($_DBHOSTname,$_DBuser,$_DBpass,$_DBname);
if (!$mysql)
{
    die("连接错误: " . mysqli_connect_error());
}
mysqli_set_charset($mysql,"utf8");
?>