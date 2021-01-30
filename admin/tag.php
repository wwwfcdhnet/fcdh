<?php
include 'admin.php';

$page=intval(@$_GET['page']);
$tidfather=intval(@$_GET['tidfather']);
$tidson=intval(@$_GET['tidson']);
$state=array('fa-tag','fa-link','fa-bitcoin');
$whid=$tid=$rowid=0;
$fenlei=$wd2=$wd='';
if(isset($_GET['wd2'])){
	$wd2=@$_GET['wd2'];
}else{
	$wd2=@$_GET['wd'];
}
$backtn=$tn='tid';
if(isset($_GET['rowid']) || isset($_GET['cate'])){
	if(isset($_GET['cate'])){
		$tn='cate';
		$backtn='tid';
		$tid=$rowid=intval($_GET['cate']);
		$goback="page.php?tid=".$tid."&wd=$wd2";
	}else{
		$tn='rowid';
		$tid=$rowid=intval(@$_GET['rowid']);
		$res=$db->query("SELECT tid,tidfather,tidson FROM pagetag WHERE rowid=$rowid")->fetch(); 
		$rowid=$res['0'];
		$goback="page.php?tid=".$res['0']."&wd=$wd2";
	}
}elseif(isset($_GET['tid'])){
	$rowid=$tid=intval(@$_GET['tid']);
	$goback="page.php?tid=$tid&wd=$wd2";
}

if(isset($_GET['whid'])){// 标签以链接的形式进库
	$rowid=$tid=intval(@$_GET['whid']);
	$backtn=$tn='whid';
	$fenlei='&hid=';
	$res=$db->query("SELECT tid,tidfather,tidson FROM pagetag WHERE rowid=$rowid")->fetch(); 
	$goback="page.php?tid=".$res['0']."&wd=$wd2";
}

if(!$page) $page=1;
$pagesize=15;
$offset=($page-1)*$pagesize;
if(isset($_GET['wd'])&&!empty($_GET['wd'])){
	$wd=filterTitle(strtolower($_GET['wd']));
	$res=$db->query("SELECT wid FROM tagword WHERE word='$wd'")->fetch();  
	$wid=intval($res['wid']);
	$res=$db->query("SELECT count(tid) FROM tagso WHERE wid=$wid")->fetch();   
	$sql="SELECT tag.tid,tindex,html,tico,tname,tcolor,tadd FROM tagso INNER JOIN tag on tag.tid=tagso.tid WHERE tagso.wid=$wid LIMIT $offset,$pagesize";
}else{
	$res=$db->query("select count(tid) from tag")->fetch();
	$sql="select tid,tindex,html,tico,tname,tcolor,tadd from tag where tid>0 order by tid desc limit $offset,$pagesize";
}
	$total=$res[0];
	$pages=ceil($total/$pagesize);
	//echo$sql;
	$res=$db->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>标签管理 - 后台管理 - 非常导航</title>
    <meta name="keywords" content="留言列表，后台管理，非常导航">
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
									<input type="text" style="padding-left:15px;"class="form-control" name="wd" placeholder="搜索标签" maxlength="63"autocomplete="on"value="<?php echo$wd;?>">
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
				<ul class="ti-ads">
					<li><a href="href.php?wd=<?php echo$wd;?>">搜链接</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
			<div id="main">
				<?php 
					if($tid || $whid || $rowid){
				?>
				<h3><a href="pageHref.php?<?php echo"$backtn=",$rowid,'&wd=',$wd2;?>"class="c2"><i class="fa-mail-reply"></i> 返回标签</a> <a href="<?php echo$goback;?>"class="c10"><i class="fa-bookmark"></i>返回页面管理</a></h3>
				<?php
					}
				?>
				<h3><a class="c10"><i class="fa-bookmark"></i>标签列表</a> <a href="tagAdd.php<?php if(!empty($tid))echo"?$tn=$tid&wd=$wd2";?>"><i class="fa-plus"></i>增加标签</a></h3>
				<div id="list">

				 <?php

foreach($res as $r){
	$html='<strong class="c2">[未静态]</strong>';
	$fa='fa-lock';
	if($r['html']>0){
		$html='[已静态]';
	}
	if($r['html']<0){
		$fa='fa-unlock-alt';
	}
?>

<dl>
	<dd><a href="upload.php?type=tag&del=<?php echo $r['tindex'],'.html';?>" title="解锁索引并删除页面"onclick="return window.confirm('是否解锁索引并删除页面？')"><?php echo'<i class="',$fa,'"></i></a>',$r['tid'],' <i class="',$r['tico'],'"></i>';?><strong><span class="c<?php echo$r['tcolor'];?>">【<?php echo$r['tname'];?>】</span><a href="page.php?tid=<?php echo $r['tid'],'&wd=',$wd2;?>"class="c5"><?php echo $r['tindex'];?>.html</a></strong>
	<i class="<?php echo$state[$r['tstate']];?>"></i>
		<a href="pageHtml.php?tid=<?php echo $r['tid'];?>"><?php echo$html;?></a>
		<a href="tagEdit.php?edit=<?php echo $r['tid'],"&wd=$wd2";if(!empty($tid))echo"&$tn=$tid";?>">[修改]</a>
		<a href="../<?php echo $r['tindex'];?>.html"target="_blank">[预览]</a>
		<?php 
		if($r['tadd'])$color='class="c'.$r['tadd'].'"';else $color="";
		if($tid || $rowid || $whid)echo'<a ',$color,'href="pageAdd.php?',"$tn=",$tid,'&tidson=',$r['tid'],'"><strong>[加入]</strong></a>';
		?>
	</dd>
</dl>

              
                <?php
}
?>
 

            <nav id="pages">
                    <?php
if($page>1){
?>
                        <a href="?<?php echo"$tn=$tid&wd2=$wd2&wd=$wd&";?>page=<?php echo $page-1;?>" class="btn btn-primary" id="prev">上一页</a>
                    <?php
}
if($pages>$page){
?>
                   
                        <a href="?<?php echo"$tn=$tid&wd2=$wd2&wd=$wd&";?>page=<?php echo $page+1;?>" class="btn btn-primary" id="next">下一页</a>
                    <?php
}
?>
            </nav>
        </div>
    </div>

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
