<?php
include 'mysql_mydb.php';
include 'functionOpen.php';
define('ADMIN',__DIR__);
session_start();
$isvip=1;
if(!isset($_SESSION['loginuid']) || intval($_SESSION['loginuid'])<1 || !isset($_SESSION['loginuser'])){
	$isvip=0;
}else{ // ��Ա�û�
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
	$phash=substr(md5($pindex.$_KEY.$pmid.$psuf),0, 8); //��ȡ7���ַ�
	$img= $_IMGDIR.'photo/'.$year.'/'.$pindex.'-'.$pmid.'-'.$phash.'-'.$psuf;// �ļ�����ʵ��ַ��֧��url,������������url��
//	$img = $_IMGDIR.'thumb/'.$year.'/'.$pindex.'-'.$pmid.'-m-'.$psuf;// �ļ�����ʵ��ַ��֧��url,������������url��
	header("Content-Type:image/jpeg");
	readfile($img);//���ͼƬ�ļ�
	ob_get_contents();//�õ���������
}
?>