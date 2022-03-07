<?php
include 'mysql_mydb.php';
include 'functionOpen.php';
define('ADMIN',__DIR__);
session_start();
$isvip=1;
if(!isset($_SESSION['loginuid']) || intval($_SESSION['loginuid'])<1 || !isset($_SESSION['loginuser'])){
	$isvip=0;
}else{ // 会员用户
	$uid=intval($_SESSION['loginuid']);
	$uname=$_SESSION['loginuser'];
	$sql="SELECT uname FROM vipuser WHERE uid=$uid LIMIT 1";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($uname != $row['uname'] || $uid<1 || empty($uname)){$isvip=0;}
}
if($isvip){
	$id=@$_GET['id'];
	$img=explode("-", $id);
	$pindex=intval($img['0']);
	$pmid=intval($img['1']);
	$year=intval($img['2']);
	$psuf=$img['3'];
	$phash=substr(md5($pindex.$_KEY.$pmid.$psuf),0, 8); //截取7个字符
	$img= $_IMGDIR.'photo/'.$year.'/'.$pindex.'-'.$pmid.'-'.$phash.'-'.$psuf;// 文件的真实地址（支持url,不过不建议用url）
//	$img = $_IMGDIR.'thumb/'.$year.'/'.$pindex.'-'.$pmid.'-m-'.$psuf;// 文件的真实地址（支持url,不过不建议用url）
	header("Content-Type:image/jpeg");
	readfile($img);//输出图片文件
	ob_get_contents();//得到浏览器输出
}
?>