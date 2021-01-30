<?php
include 'admin.php';
$act=@$_GET['act'];
$rowid=intval(@$_GET['rowid']);

$wd2=@$_GET['wd'];

$bindex=$btitle=$bkey=$bdesc=$btip=$bstrong=$blogwd=$msg='';
$bcolor=10;
$tn='tid';
if(isset($_GET['rowid'])){
	$tn='rowid';
}else{
	$rowid=intval(@$_GET['tid']);
	
}
if($act=='edit'){   
    $bid=intval(trim(@$_POST['bid']));
    $ref=@$_POST['ref'];
    $btitle=mb_substr(filterText(trim(@$_POST['btitle'])),0,32);
    $bkey=mb_substr(filterText(trim(@$_POST['bkey'])),0,256);
    $bdesc=mb_substr(filterText(htmlspecialchars(trim(@$_POST['bdesc']))),0,2048);
    $tagstr=mb_substr(filterText(strtolower(@$_POST['btag'])),0,128);
    $btip=mb_substr(filterText(strtolower(@$_POST['btip'])),0,256);
    $bstrong=intval(trim(@$_POST['bstrong']));
    $bcolor=intval(trim(@$_POST['bcolor']));
    $blogwd=mb_substr(strtolower(@$_POST['blogwd']),0,256);
	$bview=intval(@$_POST['bview']);
	//$btime=date("Y-m-d H:i:s");
	$html=0;
	$arr = array('，' => ',', ';' => ',', "；" => ','); 
	$tagstr=trim(strtr($tagstr, $arr),','); 
	$strarr=explode(',',$tagstr);
	$taginput='';
	foreach($strarr as $v){
		$taginput.=","."'".trim($v)."'";
	}
	$taginput=trim($taginput,',');
	$tag=$db->query("select tid from tag where tindex in($taginput)")->fetchAll();
	$btag='';
	foreach($tag as $r){
		$btag.=$r['tid'].',';
	}
	$btag=trim($btag,',');


	$msg='<strong class="c2">编辑成功</strong>';
	$eof=$db->exec("update blog set btitle='$btitle',bkey='$bkey',bdesc='$bdesc',btag='$btag',btip='$btip',bview='$bview',bcolor=$bcolor,bstrong=$bstrong,html=$html where bid=$bid");
	if(!$eof){
		$msg='<strong class="c9">链接 '.$bid.' 重复!</strong>';
	}else{
		$arr = array('，' => ';', ',' => ';', "；" => ';', "|" => ';', "、" => ';'); 
		$blogwd=strtr($blogwd, $arr); 
		$blogarr=explode(';',trim($blogwd,';'));
		$widarr=array();

		foreach($blogarr as $str){ // 要存入数据库中的word
			$word=filterTitle(trim($str));
			$res=$db->query("select wid from tagword where word='$word'")->fetch();			
			$wid=intval($res['wid']);
			if(in_array($wid,$widarr))continue;
			if(!$wid){ //如果没有该关键词
				$db->exec("insert into tagword(word) VALUES('$word')");
				$res=$db->query("select last_insert_rowid() as lastwid from tagword")->fetch(); 
				$widarr[$i++]=$res['lastwid'];
			}else{					
				$widarr[$i++]=$wid;
			}
		}
		$blogso=$db->query("SELECT wid FROM blogso where bid=$bid")->fetchAll(); 
		$tagWidHave=array();
		$delwid=$sql='';
		$i=0;
		
		foreach($blogso as $v){
			if(!in_array($v['wid'],$widarr)){
				$delwid.=$v['wid'].',';
			}else{
				$tagWidHave[$i++]=$v['wid'];
			}
		}
		$delwid=trim($delwid,','); // 不要的搜索字词
		$db->exec("DELETE FROM blogso WHERE bid=$bid AND wid IN($delwid)");

		$i=0;
		foreach($widarr as $wid){
			if(in_array($wid,$tagWidHave))continue;
			if($i++==0){
				$sql="INSERT INTO blogso SELECT $bid AS bid, '$wid' AS wid";
			}else{
				$sql.=" UNION SELECT $bid, $wid";
			}
		}
		if(!empty($sql))$db->exec($sql);
	}
	if(!empty($ref))redir($ref);
}else{
	$ref=@$_SERVER['HTTP_REFERER'];
	$bid=intval(@$_GET['bid']);
	$blog=$db->query("SELECT * FROM blog WHERE bid=$bid")->fetch(); 
	$blogso=$db->query("SELECT tagword.word FROM blogso INNER JOIN tagword on blogso.wid=tagword.wid WHERE blogso.bid=$bid")->fetchAll(); 
	//$blogso=$db->query("SELECT * FROM blogso WHERE bid=$bid")->fetchAll(); 
	
	$blogwd='';
	foreach($blogso as $wd){
		$blogwd.=$wd['word'].';';
	}

	$blogwd=trim($blogwd,';');
    $btitle=$blog['btitle'];
    $bkey=$blog['bkey'];
    $bdesc=htmlspecialchars_decode($blog['bdesc']);
    $btip=$blog['btip'];
    $btag=$blog['btag'];
    $bstrong=$blog['bstrong'];
    $html=$blog['html'];
    $bcolor=$blog['bcolor'];
	$bview=$blog['bview'];
	$tag=$db->query("select tindex from tag where tid in($btag)")->fetchAll();
	$tagstr='';
	foreach($tag as $r){
		$tagstr.=$r['tindex'].',';
	}
	$tagstr=trim($tagstr,',');
}
	

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>增加网址 - 后台管理 - 非常导航</title>
    <link rel="shortcut icon" href="../assets/images/favicon.png">
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
	<link rel="stylesheet" href="css/jquery.qeditor.css" type="text/css">
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
	<script src="js/jquery.qeditor.js" type="text/javascript"></script>
</head>

<body>
    <!-- 最外层容器 -->
    <div id="container">
        <?php include 'head.php';?>
        <div class="content">
            <nav class="navbar user-info-navbar" role="navigation">
                <!-- User Info, Notifications and Menu Bar -->
                <!-- Left links for user info navbar -->
                <ul class="user-info-menu">
                    <li id="ti-side">
                        <a href="#">
                            <i class="fa-bars"></i>
                        </a>
                    </li>					
                    <li id="ti-menu">
                        <a href="#">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>	
                    <li id="ti-home">
                        <a href="./">
                            <i class="fa-home"></i>
                        </a>
                    </li>
                </ul>
				<ul class="ti-ads">
					<li><a href="blog.php<?php if(!empty($rowid))echo"?$tn=$rowid&wd2=$wd2";?>">博客列表</a></li>
					<li><a href="blogAdd.php<?php if(!empty($rowid))echo"?$tn=$rowid&wd2=$wd2";?>">增加博客</a></li>
				</ul>
            </nav>
			<br/>
			<br/>

			<form method="post" action="?act=edit">
				<h3><a><i class="fa-bookmark"></i>编辑博客</a> </h3>
			<p>
				<label>博客标题</label> <span>(32字符)</span><br/>
				<input type="text" maxlength="32"class="form-control"style="width:100%"value="<?php echo$btitle;?>" name="btitle" required>
			</p>
			<p>
				<label>博客关键词</label> <span>(256字符)</span>
				<input type="text" class="form-control"maxlength="256"style="width:100%"value="<?php echo$bkey;?>" name="bkey" required>
			</p>
			<p>
				<label class="c8">搜索字词</label>* <span>(256字符)</span>
				<input type="text" class="form-control"maxlength="256"placeholder="用分号;或逗号,隔开"style="width:100%"value="<?php echo$blogwd;?>"name="blogwd" required>
			</p>
			
			<div class="ti-fwb">
				<label class="c4">博客内容</label> <span>(2048字符)</span>
				<textarea class="form-control" autoHeight="true" maxlength="2048"placeholder="博客内容正文"style="width:100%" id="hdesc"name="bdesc" required><?php echo$bdesc;?></textarea>
			</div>

			<p>
				<label>隐藏内容</label> <span>(256字符)</span>
				<input type="text" class="form-control"maxlength="256"style="width:100%"value="<?php echo$btip;?>" name="btip">
			</p>
			<p>
				<label>网站类型</label> <span>(128字符)</span>
				<input type="text" class="form-control"maxlength="128"style="width:100%"value="<?php echo$tagstr;?>" name="btag">
			</p>
			<p>
				<label>浏览次数</label>
				<input type="text" class="form-control"style="width:100%"value="<?php echo$bview;?>" name="bview">
			</p>
			<p>
				<label>标题粗细</label>
				<label><input type="radio" name="bstrong" value="0"<?php if($bstrong==0)echo'checked';?>>正常</label>&nbsp;&nbsp;<label><input type="radio" name="bstrong" value="1"<?php if($bstrong==1)echo'checked';?>>粗体</label>
			</p>
			<p>
				<label>标题颜色</label>
				<select class="form-control" name="bcolor"style="width:50%">
				<option value="0"class="c0"<?php if($bcolor==0)echo'selected';?>>正常</option><option value="1"class="c1"<?php if($bcolor==1)echo'selected';?>>浅绿</option><option value="2"class="c2"<?php if($bcolor==2)echo'selected';?>>深绿</option><option value="3"class="c3"<?php if($bcolor==3)echo'selected';?>>暗蓝</option>
				<option value="4"class="c4"<?php if($bcolor==4)echo'selected';?>>蓝色</option><option value="5"class="c5"<?php if($bcolor==5)echo'selected';?>>深蓝</option><option value="6"class="c6"<?php if($bcolor==6)echo'selected';?>>深紫</option><option value="7"class="c7"<?php if($bcolor==7)echo'selected';?>>浅紫</option>
				<option value="8"class="c8"<?php if($bcolor==8)echo'selected';?>>粉色</option><option value="9"class="c9"<?php if($bcolor==9)echo'selected';?>>红色</option><option value="10"class="c10"<?php if($bcolor==10)echo'selected';?>>橙色</option>
				</select>
			</p>
				<input type="hidden" name="bid"value="<?php echo$bid;?>"></button>
				<input type="hidden" name="ref"value="<?php echo$ref;?>"></button>
				<button type="submit" class="btn btn-primary btn-block">编辑博客</button>
			</form>

			<br/><br/><br/>
            <footer class="footer">
                    <div class="vcenter">
                         Since 2020 <strong><a href="https://www.fcdh.net/">www.fcdh.net</a></strong> <span class="ti-more"><a href="https://beian.miit.gov.cn/">渝ICP备20001609号</a></a>
                    </div>
                    <div id="go-up">
                        <a href="#" rel="go-top"title="顶部">
                            <i class="fa-tree"></i>
                        </a>
                    </div>
            </footer>
			<div id="ti-meng"></div>
		</div>
    </div> <!-- end 最处层容器 -->
 <script src="../assets/js/fcdh.js"></script>
</body>

</html>
