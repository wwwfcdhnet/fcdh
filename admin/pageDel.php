<?php
include 'admin.php';
$ref=$_SERVER['HTTP_REFERER'];
$tid=intval(@$_GET['tid']);
if(isset($_GET['hid'])){// 是对链接的删除操作
	$hid=intval(@$_GET['hid']);
	//$time=time()-86400;
	if(isset($_GET['rowid'])){
		$rowid=intval(@$_GET['rowid']);
		$res=$db->query("select tidson from pagetag where rowid=$rowid")->fetch();
		$tid=$res['0'];
	}elseif(isset($_GET['bhid'])){
		$rowid=intval(@$_GET['bhid']);
		$res=$db->query("select tidson from pagetag where rowid=$rowid")->fetch();
		$tid=$res['0'];
	}elseif(isset($_GET['whid'])){ // 伪链接ID
		$rowid=intval(@$_GET['whid']);
		$res=$db->query("select tidson from pagetag where rowid=$rowid")->fetch();
		$tid=$res['0'];
	}elseif(isset($_GET['blog'])){ // 直接对博文删除
		$tid=intval(@$_GET['blog']);
	}elseif(isset($_GET['url'])){
		$tid=intval(@$_GET['url']);
	}elseif(isset($_GET['cate'])){ // 直接对标签类删除
		$tid=intval(@$_GET['cate']);
	}

	if(isset($_GET['bhid'])){
		$tagarr=$db->query("select btag,badd from blog where bid=$hid")->fetch();
	}elseif(isset($_GET['whid']) || isset($_GET['cate'])){
		$tagarr=$db->query("select tid,tadd from tag where tid=$hid")->fetch();
	}else{
		$tagarr=$db->query("select htag,hadd from href where hid=$hid")->fetch();
	}

	$hadd=date('d')%10;
	if($hadd<1)$hadd=10;
	if(!isset($_GET['whid'])){ //  如果不是伪链接
		$tagstr=trim($tagarr['0']);
		$tagstr=str_replace($tid,'',$tagstr);
		$tagstr=str_replace(',,',',',$tagstr);
		$tagstr=trim($tagstr,',');
		if(isset($_GET['bhid'])){
			$db->exec("update blog set btag='$tagstr',badd=$hadd where bid=$hid");
		}elseif(isset($_GET['cate'])){
			$db->exec("update tag set tadd=$hadd where tid=$hid");
		}else{
			$db->exec("update href set htag='$tagstr',hadd=$hadd where hid=$hid");
		}
	}else{
		$db->exec("update tag set tadd=$hadd where tid=$hid");
	}
	$db->exec("delete from pagehref where tid=$tid and hid=$hid");
//echo"delete from pagehref where tid=$tid and hid=$hid";

}elseif(isset($_GET['index'])){// 是对链接检查的异常数据的删除操作
	$index=intval($_GET['index']);
	$db->exec("delete from htmltn where rowid=$index");

}else{// 是对标签的删除操作
	$rowid=intval(@$_GET['rowid']);
	$res=$db->query("select tid,tidfather,tidson from pagetag where rowid=$rowid")->fetch();
	$tid=$res['0'];
	$tidfather=$res['1'];
	$tidson=$res['2'];
	if($tidfather<2){// 先删除子类
		$eof=$db->exec("delete from pagetag where tid=$tid and tidfather=$tidson");
	}
	$db->exec("delete from pagetag where rowid=$rowid");
}
redir($ref);