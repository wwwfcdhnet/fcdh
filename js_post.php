<?php
include_once('sqlite_db.php');
include_once('function.php');
session_start();
$content=@$_POST['content'];
$content=mb_substr(strip_tags(filterText($content)),0,256);
$content=nl2br($content);
$scode=@strtoupper($_POST['scode']);

if(isset($_POST['tname'])){ //增加网站提交信息	
	$ttype=intval($_POST['ttype']);
	$url=mb_substr(trim(filterTitle($_POST['url'])),0,64);
	$tname=mb_substr(trim(filterTitle($_POST['tname'])),0,16);
	$title=mb_substr(trim(filterTitle($_POST['title'])),0,32);
	$keyword=mb_substr(trim(filterTitle($_POST['keyword'])),0,64);
	if(load_config('scode')=='1'){
		if($scode!=$_SESSION['scode']){
			echo 'scode';
			exit;
		}
	}

	$search ='/(http|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\d\-_#\/]+)/i';
	preg_match_all($search, $url.'/' ,$r);
	$urlarr = @explode(".", $r['2']['0']);
	$arr = array('/' => '', '-' => '', "_" => '', ":" => '', "#" => ''); 
	$lastdir=strtr($r['3']['0'], $arr); 
	$res['index']='';
	for($len=$i=count($urlarr);$i>0;$i--){
		if($i==1){
			if($len<3){
				$res['index']=$urlarr[$i-1].$res['index'];
			}else{
				$res['index'].=$urlarr[$i-1];
			}
		}else{
			$res['index']=$urlarr[$i-1].$res['index'];
		}
	}
	$hindex=strtolower($res['index'].$lastdir);


	$addtime=time();
	$ip=get_ip();

	if(load_config('verify')==1){
		$verify=0;
	}
	else{
		$verify=1;
	}

	$eof=$db->query("insert into contenthref(cate,hindex,url,tname,title,keyword,content,addtime,ip,verify,top) values($ttype,'$hindex','$url','$tname','$title','$keyword','$content',$addtime,'$ip',$verify,0)");
	if(!$eof){
		echo 'chong';
	}else{
		echo 'success';
	}

}else{// 留言板
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
}