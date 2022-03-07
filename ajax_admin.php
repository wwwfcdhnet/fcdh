<?php
include 'mysql_mydb.php';
include 'functionOpen.php';
define('ADMIN',__DIR__);
$resply=array(true,true,'','');
session_start();
if(!isset($_SESSION['loginvip']) || $_SESSION['loginvip']!='OKVIP'){
	$replay['0']=false;
	$replay['2']='请登录会员';
}


if(isset($_SESSION['loginuid'])){
	$uid=intval($_SESSION['loginuid']);
	$uname=$_SESSION['loginuser'];
	$sql="SELECT uid,vrank,fondtime,logdate,exptime,imgnum,actived,uname,email,ip FROM vipuser WHERE uid='$uid'";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($uname != $row['uname']){
		$replay['0']=false;
		$replay['2']='请登录会员';
	}
	if(isset($_POST['pindex']) && !empty($_POST['pindex'])){ // 加载图片
		$pindex	= intval($_POST['pindex']);
		$pshow	= intval($_POST['pshow']);
		$year	= intval($_POST['year']);
		$start	= intval($_POST['start']);
		$limit='';
		if(!empty($start)){
			$limit=' limit '.$start.','.($pshow+1);
		}
		$photo=$db->query("SELECT pmid,psize,pwidth,pheight,psuf,pdesc FROM photoimg WHERE pindex=$pindex$limit")->fetchAll(); 
		$i=0;
		$hdesc=$size='';
		foreach($photo as $img){
			$size=formatBytes($img['psize']);
			if($i++ < $pshow){
				$hdesc .= '<p><img src="'.$_IMGDIR.'thumb/'.$year.'/'.$pindex.'-'.$img['pmid'].'-m-'.$img['psuf'].'">'.$img['pdesc'].'<a href="downpic.php?id='.$pindex.'-'.$img['pmid'].'-'.$year.'-'.$img['psuf'].'"target="_blank">〖查看原图〗</a>原图像素：'.$img['pwidth'].'*'.$img['pheight'].'，原图大小：'.$size.'</p>';
			}
		}
		if($i<($pshow+1)){// 表示没有新的图片了
			$resply[1]='over';
		}else{
			$resply[1]=$start+$pshow;
		}
		$resply[0]=true;
		$resply[2]=$hdesc;
	}else{
		$resply[0]=false;
	}
}else{
	$replay['0']=false;
	$replay['2']='请登录会员';
}

	echo json_encode($resply);
?>