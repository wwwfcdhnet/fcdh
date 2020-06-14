<?php
/*

$_DBHOSTname="hdm12507729.my3w.com:3306";               //主机
$_DBuser="hdm12507729";                    //用户
$_DBpass="5729134068Gif99";                      //密码
$_DBname="hdm12507729_db";                    //数据库
$_KEY="@(`9Jyo4YrdZw5^{Y})@";                         //密钥
$_URLNUM=15;						// 非认证用户默认收藏记录
*/

$_DBHOSTname="localhost";               //主机
$_DBuser="root";                    //用户
$_DBpass="root";                      //密码
$_DBname="neihi";                    //数据库
$_KEY="@(`9Jyo4YrdZw5^{Y})@";                         //密钥
$_URLNUM=15;						// 非认证用户默认收藏记录
$db=mysqli_connect($_DBHOSTname,$_DBuser,$_DBpass,$_DBname);
if (!$db)
{
    die("连接错误: " . mysqli_connect_error());
}
mysqli_set_charset($db,"utf8");
?>