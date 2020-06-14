<?php
include_once('function.php');
session_start();
$content=@$_POST['content'];
$content=strip_tags(filterText($content));
$content=nl2br($content);
$scode=@strtoupper($_POST['scode']);

if(!empty($_POST['email']) && !_CheckInput($_POST['email'],'email')){
	echo 'email';
	exit;
}

$email=trim(filterTitle($_POST['email']));

if(load_config('scode')=='1'){
if($scode!=$_SESSION['scode']){
	echo 'scode';
	exit;
}
}

$addtime=time();
$ip=get_ip();

if(load_config('verify')==1){
	$verify=0;
}
else{
	$verify=1;
}

$db->query("insert into content(content,addtime,ip,email,verify,top) values('$content',$addtime,'$ip','$email',$verify,0)");

echo 'success';