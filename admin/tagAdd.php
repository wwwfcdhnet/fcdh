<?php
include 'admin.php';
$act=@$_GET['act'];
$tid=-1;
$backtn=$tn='';
$add=false;
if(isset($_GET['tid'])){
	$backtn=$tn='tid';
	$rowid=$tid=intval(@$_GET['tid']);
}elseif(isset($_GET['rowid'])){
	$backtn=$tn='rowid';
	$rowid=$tid=intval(@$_GET['rowid']);
	$add=true;
	// 2021-1-10 加的修改 增加博客返回有问题
	$res=$db->query("SELECT tid,tidfather,tidson FROM pagetag WHERE rowid=$rowid")->fetch(); 
	$tid=$res['0'];
}elseif(isset($_GET['cate'])){
	$backtn=$tn='cate';
	$rowid=$tid=intval(@$_GET['cate']);
}else{
	$backtn=$tn='whid';
	$rowid=$tid=intval(@$_GET['whid']);
	if($tid)$add=true;
	$res=$db->query("SELECT tid,tidfather,tidson FROM pagetag WHERE rowid=$rowid")->fetch(); 
	$tid=$res['0'];
}
$wd2='';
if(isset($_GET['wd2'])){
	$wd2=@$_GET['wd2'];
}else{
	$wd2=@$_GET['wd'];
}
$tstrong=$tcolor=$tindex=$tico=$rank=$tname=$seotitle=$ttitle=$tkey=$tdesc=$tagwd=$msg='';
if($act=='add'){ 
	if($tn=='whid'){
		//$rowid=$tid=intval(@$_GET['whid']);
	}
    $tindex=mb_substr(filterTitle(trim(@$_POST['tindex'])),0,32);
    $tico=mb_substr(filterTitle(trim(@$_POST['tico'])),0,32);
    $tname=mb_substr(filterTitle(trim(@$_POST['tname'])),0,8);
    $ttitle=mb_substr(filterTitle(trim(@$_POST['ttitle'])),0,16);
    $seotitle=mb_substr(filterText(trim(@$_POST['seotitle'])),0,64);
    $tkey=mb_substr(filterText(trim(@$_POST['tkey'])),0,256);
    $tdesc=mb_substr(filterText(trim(@$_POST['tdesc'])),0,512);
    $tagwd=mb_substr(trim(@$_POST['tagwd']).','.$tindex,0,256);
    $tstrong=intval(@$_POST['tstrong'])%2;
    $tcolor=intval(@$_POST['tcolor'])%11;
	$tadd=0;
	$msg='<strong class="c3">保存成功</strong>';
	$eof=$db->exec("insert into tag(tindex,tico,tname,ttitle,seotitle,tkey,tdesc,tstrong,tcolor,tadd) values('$tindex','$tico','$tname','$ttitle','$seotitle','$tkey','$tdesc',$tstrong,$tcolor,$tadd)");
	if(!$eof){
		$msg='<strong class="c9">标签 '.$tindex.' 重复!</strong>';
	}else{
		$res=$db->query("select last_insert_rowid() from tag")->fetch(); 
		$lasttid=$res[0];
		$arr = array('，' => ';', ',' => ';', "；" => ';', "|" => ';', "、" => ';'); 
		$tagwd=strtr($tagwd, $arr); 
		$tagarr=explode(';',$tagwd);
		$i=0;
		$widarr=array();
		foreach($tagarr as $str){
			$word=filterTitle(trim($str));
			$res=$db->query("select wid from tagword where word='$word'")->fetch();
			$wid=intval($res['wid']);
			if(!$wid){ //如果没有该关键词
				$db->exec("insert into tagword(word) VALUES('$word')");
				$res=$db->query("select last_insert_rowid() from tagword")->fetch(); 
				$widarr[$i++]=$res[0];
			}else{					
				$widarr[$i++]=$wid;
			}
		}
		$i=0;
		$sql='';
		foreach($widarr as $wid){
			if($i++==0){
				$sql="INSERT INTO tagso SELECT $lasttid AS tid, $wid AS wid";
			}
			$sql.=" UNION SELECT $lasttid, $wid";
		}
		$db->exec($sql);
	}
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>增加标签 - 后台管理 - 非常导航</title>
    <link rel="shortcut icon" href="../assets/images/favicon.png">
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
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
					<li>
                        <div class="searchs">
                            <form action="tag.php" method="get">
								<div class="input-group">
									<input type="text" style="padding-left:15px;"class="form-control" name="wd" placeholder="搜索标签" maxlength="63"autocomplete="on"value="<?php echo$wd2;?>">
									<?php 
										if(isset($_GET['wd2'])){
									?>
									<input type="hidden" name="wd2" value="<?php echo$wd2;?>">
									<?php 
										}
									?>
									<input type="hidden" name="<?php echo$tn;?>" value="<?php echo$tid;?>">
									<span class="input-group-btn">
										<button type="submit"class="btn btn-primary fa-search"> 标签</button>
									</span>	
								</div>
							</form>
                        </div>
                    </li>
                </ul>
            </nav>
			<br/>
			<br/>
			<?php 
				if($add){
			?>
			<h3><a href="pageHref.php?<?php echo"$tn=",$rowid,'&wd=',$wd2;?>"class="c2"><i class="fa-mail-reply"></i> 返回标签</a>  <a href="page.php?<?php echo"tid=",$tid,'&wd=',$wd2;?>"class="c10"><i class="fa-mail-reply-all"></i> 返回页面管理</a></h3>
			
			<?php 
				}
			?>

			<h3><a href="tag.php<?php if(!empty($tid))echo"?$backtn=$rowid&wd2=$wd2";?>"><i class="fa-bookmark"></i>标签列表</a> <a class="c10"><i class="fa-plus"></i>增加标签</a></h3>

			<form method="post" action="tagAdd.php?act=add<?php echo"&$tn=$rowid&wd2=$wd2";?>">
			<p>
				<label>标签简称</label>* <span>(8字符)</span><br/>
				<input type="text" class="form-control"maxlength="8"placeholder="导航"style="width:100%"value="<?php echo$tname;?>" name="tname" required>
			</p>
			<p>
				<label>标签全称</label>* <span>(16字符)</span><br/>
				<input type="text" class="form-control"maxlength="16"placeholder="非常导航"style="width:100%"value="<?php echo$ttitle;?>" name="ttitle" required>
			</p>
			<p>
				<label>标签英文<strong>(唯一)</strong></label>* <span>(32字符)</span><br/>
				<input type="text" class="form-control"maxlength="32"placeholder="daohang"style="width:100%" name="tindex"value="<?php echo$tindex;?>" required>
			</p>
			<p>
				<label><a href="http://www.htmleaf.com/ziliaoku/font-awesome/2014100887.html"target="_blank">标签图标</a></label>* <span>
			<?php echo $msg; ?><span> <span>(32字符)</span><br/>
				<input type="text" class="form-control"maxlength="32"placeholder="fa-home"style="width:100%"value="<?php echo$tico;?>" name="tico" required>
			</p>
			<p>
				<label class="c8">搜索字词*</label> <span>(256字符)</span>
				<input type="text" class="form-control"maxlength="256"placeholder="用分号;或逗号,隔开"style="width:100%"name="tagwd"value="<?php echo$tagwd;?>" required>
			</p>
			<p>
				<label>SEO标题*</label> <span>(64字符)</span><br/>
				<input type="text" class="form-control"maxlength="64"placeholder="非常绿色安全的导航"style="width:100%"value="<?php echo$seotitle;?>" name="seotitle" required>
			</p>
			<p>
				<label>页面关键词</label> <span>(256字符)</span>
				<input type="text" class="form-control"maxlength="256"placeholder="用分号;或逗号,隔开"style="width:100%"value="<?php echo$tkey;?>" name="tkey">
			</p>
			<p>
				<label>页面描述</label> <span>(512字符)</span>
				<input type="text" class="form-control"maxlength="512"style="width:100%"value="<?php echo$tdesc;?>" name="tdesc">
			</p>
			<p>
				<label>标题粗细</label>
				<label><input type="radio" name="tstrong" value="0" checked>正常</label><label><input type="radio" name="tstrong" value="1">粗体</label>
			</p>
			<p>
				<label>标题颜色</label>
				<select class="form-control" name="tcolor"style="width:50%">
				<option value="0"class="c0" selected>正常</option>
				<option value="1"class="c1">浅绿</option>
				<option value="2"class="c2">深绿</option>
				<option value="3"class="c3">暗蓝</option>
				<option value="4"class="c4">蓝色</option>
				<option value="5"class="c5">深蓝</option>
				<option value="6"class="c6">深紫</option>
				<option value="7"class="c7">浅紫</option>
				<option value="8"class="c8">粉色</option>
				<option value="9"class="c9">红色</option>
				<option value="10"class="c10">橙色</option>
				</select>
			</p>
				<button type="submit" class="btn btn-primary btn-block">增加标签</button>
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
