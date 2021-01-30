<?php
include 'admin.php';
$act=@$_GET['act'];
$ref=@$_SERVER['HTTP_REFERER'];
$msg='';
$tid=$tidson=$rank=$hid=-1;
if(isset($_GET['hid']) || isset($_GET['whid']) || isset($_GET['bhid']) || isset($_GET['blog']) || isset($_GET['url'])){
	if(isset($_GET['blog'])){
		$tn='blog';
		$tid=$tidson=intval(@$_GET['blog']);
		$hid=intval(@$_GET['bid']);
	}elseif(isset($_GET['url'])){
		$tn='url';
		$tid=$tidson=intval(@$_GET['url']);
		$hid=intval(@$_GET['hid']);
	}else{
		$tn='href';
		$hid=intval(@$_GET['hid']);
		if(isset($_GET['whid'])){
			$tn='whid';
			$hid=intval(@$_GET['tidson']);
		}elseif(isset($_GET['bhid'])){
			$hid=intval(@$_GET['bid']);
		}
	}
	$rank=$hid%100;
}else{
	if(isset($_GET['cate'])){
		$tn='cate';
		$tidson=intval(@$_GET['cate']);
		$hid=intval(@$_GET['tidson']);
	}else{
		$tn='tag';
		$tidson=intval(@$_GET['tidson']);
		$tid=intval(@$_GET['tid']);
	}
	$rank=$tidson%100;
}

$hadd=date('d')%10+2;
if($hadd>10)$hadd=1;
if($tn=='cate'){//  直接增加分类
	$eof=$db->exec("insert into pagehref(tid,hid,rank) values($tidson,$hid,$rank)");
	if($eof){
		$db->exec("update tag set tadd=$hadd where tid=$hid");
	}
}elseif($tn=='href' || $tn=='blog' || $tn=='url' || $tn=='whid'){// 增加链接
	if($tn=='href' || $tn=='whid'){
		if(isset($_GET['rowid']) || isset($_GET['whid']) || isset($_GET['bhid'])){
			$rowid=intval(@$_GET['rowid']);
			if(isset($_GET['whid'])){
				$rowid=intval(@$_GET['whid']);
			}elseif(isset($_GET['bhid'])){
				$rowid=intval(@$_GET['bhid']);
			}

			$res=$db->query("select tid,tidson from pagetag where rowid=$rowid")->fetch();
			$tid=intval($res['0']);
			$tidson=intval($res['1']);
			$eof=$db->exec("insert into pagehref(tid,hid,rank) values($tidson,$hid,$rank)");
		}else{
			$tid=intval(@$_GET['tid']);
			$eof=$db->exec("insert into pagehref(tid,hid,rank) values($tid,$hid,$rank)");
		}
	}else{
		$eof=$db->exec("insert into pagehref(tid,hid,rank) values($tidson,$hid,$rank)");
	}
	if($eof){
		if(isset($_GET['bhid']) || isset($_GET['blog'])){ // 博客
			$tagarr=$db->query("select btag,badd from blog where bid=$hid")->fetch();
		}elseif($tn=='whid'){
			$tagarr=$db->query("select tid,tadd from tag where tid=$hid")->fetch();
		}else{ // 链接
			$tagarr=$db->query("select htag,hadd from href where hid=$hid")->fetch();
		}
		if($tn!='whid'){
			$tagstr=trim($tagarr['0']);
			$res=explode(",",$tagstr);
			if(!in_array($tid, $res)){
				$tagstr=$tagstr.','.$tid;
				$tagstr=trim($tagstr,',');
				if(isset($_GET['bhid']) || isset($_GET['blog'])){ // 博客
					$db->exec("update blog set btag='$tagstr',badd=$hadd where bid=$hid");
				}else{
					$db->exec("update href set htag='$tagstr',hadd=$hadd where hid=$hid");
				}
			}else{
				if(isset($_GET['bhid']) || isset($_GET['blog'])){ // 博客
					$db->exec("update blog set badd=$hadd where bid=$hid");
					echo"update blog set badd=$hadd where bid=$hid";
				}elseif(isset($_GET['whid'])){
					$db->exec("update tag set tadd=$hadd where tid=$tid");
				}else{
					$db->exec("update href set hadd=$hadd where hid=$hid");
				}
			}
		}else{
			$db->exec("update tag set tadd=$hadd where tid=$hid");
		}
		//$db->exec("update href set hstate=1 where hid=$hid");
	}
}else{ //增加分类
	//if($tidfather<2)$tidfather=intval(@$_POST['fatherstate']);
	$tidfather=0;
	if(isset($_GET['rowid'])){
		$rowid=intval(@$_GET['rowid']);
		$res=$db->query("select tid,tidson from pagetag where rowid=$rowid")->fetch();
		//echo"select tid,tidson from pagetag where rowid=$rowid";
		$tid=intval($res['0']);
		$tidfather=intval($res['1']);
	}
	$tidson=intval(@$_GET['tidson']);
	$eof=$db->exec("insert into pagetag(tid,tidfather,tidson,rank) values($tid,$tidfather,$tidson,$rank)");
	$db->exec("update tag set tadd=$hadd where tid=$tidson");
//	echo"update tag set tadd=$hadd where tid=$tidson";
	if(!$eof){
		exit($eof);
	}
	//echo"insert into pagetag(tid,tidfather,tidson,rank) values($tid,0,$tidson,$rank)";
}
	redir($ref);
?>