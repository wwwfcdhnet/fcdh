<?php
include 'admin.php';
$tid=$tidfather=$tidson=-1;
$page=intval(@$_GET['page']);
if(!$page) $page=1;
$pagesize=10;
$offset=($page-1)*$pagesize;
$wd2=$wd='';
if(isset($_GET['wd2'])){
	$wd2=@$_GET['wd2'];
}else{
	$wd2=@$_GET['wd'];
}
$tn='';
$backtn='';
$add=false;$rowid=0;
if(isset($_GET['bhid']) || isset($_GET['bid']) || isset($_GET['blog'])){
	if(isset($_GET['blog'])){
		$tn='blog';
		$backtn='bid';
		$tid=$rowid=intval($_GET['blog']);
	}else{
		$rowid=intval(@$_GET['bhid']);
		$backtn=$tn='bhid';
		if(isset($_GET['bid'])){
			$rowid=intval(@$_GET['bid']);
			$backtn='bid';
		}
		$res=$db->query("select tid,tidfather,tidson,pstate from pagetag where rowid=$rowid")->fetch();
		$tid=intval($res['0']);
		$tidfather=intval($res['1']);
		$tidson=intval($res['2']);
		$pstate=intval($res['3']);
	}
	$add=true;
}elseif(isset($_GET['tid'])){
	$rowid=$tid=intval(@$_GET['tid']);
	if($tid)$add=true;
	$tn='tid';
}

if(isset($_GET['wd'])&&!empty($_GET['wd'])){
	$wd=filterTitle(strtolower($_GET['wd']));
	$res=$db->query("SELECT wid FROM tagword WHERE word='$wd'")->fetch();  
	$wid=intval($res['wid']);
	$res=$db->query("SELECT count(bid) FROM blogso WHERE wid=$wid")->fetch();  
	$sql="SELECT blog.bid,bindex,btitle,bcolor,bstrong,html,badd FROM blogso INNER JOIN blog on blog.bid=blogso.bid WHERE blogso.wid=$wid LIMIT $offset,$pagesize";
}else{
	$res=$db->query("select count(bid) from blog")->fetch();
	$sql="select bid,bindex,btitle,bcolor,bstrong,html,badd from blog order by bid desc limit $offset,$pagesize";
}
	$total=$res[0];
	$time=time();
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
    <title>链接列表 - 后台管理 - 非常导航</title>
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
                            <form action="blog.php" method="get">
								<div class="input-group">
									<input type="text" style="padding-left:15px;"class="form-control" name="wd" placeholder="搜索博客" maxlength="63"autocomplete="on"value="<?php echo$wd;?>">
									<input type="hidden" name="<?php echo$tn;?>" value="<?php echo$rowid;?>">
									<?php 
										if(isset($_GET['wd2'])){
									?>
									<input type="hidden" name="wd2" value="<?php echo$wd2;?>">
									<?php 
										}
									?>
									<span class="input-group-btn">
										<button type="submit"class="btn btn-primary fa-search"> 博客</button>
									</span>	
								</div>
							</form>
                        </div>
                    </li>
                </ul>
				<ul class="ti-ads">
					<li><a href="tag.php?wd=<?php echo$wd;?>">搜标签</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
			<div id="main">
				<?php 
					if($add){
				?>
				<h3><a href="pageHref.php?<?php echo"$backtn=",$rowid,'&wd=',$wd2;?>"class="c2"><i class="fa-mail-reply"></i> 返回博客</a> <a href="page.php?<?php echo"tid=",$tid,'&wd=',$wd2;?>"class="c2"><i class="fa-mail-reply-all"></i> 返回分类</a></h3>
				<?php
					}
				?>
				<h3><a class="c10"><i class="fa-bookmark"></i>链接列表</a> <a href="blogAdd.php<?php if(!empty($rowid))echo"?$tn=$rowid&wd2=$wd2";?>"><i class="fa-plus"></i>增加博客</a></h3>
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
	<dd><a href="upload.php?type=blog&del=<?php echo $r['bid'],'.html';?>"title="解锁索引并删除页面" onclick="return window.confirm('是否解锁索引并删除页面？')"><?php echo'<i class="',$fa,'"></i></a>',$r['bid'];if($r['bstrong'])echo'<strong>';?><a href="blogDetail.php?tid=<?php echo $r['bid'],'&wd=',$wd?>" class="c<?php echo$r['bcolor'];?>"target="blank"><?php echo'【',$r['btitle'],'】';?></a><?php if($r['bstrong'])echo'</strong>';?></dd>
	<dd class="card-footer">
		<a href="pageHtml.php?bid=<?php echo $r['bid']?>"><?php echo$html;?></a>
		<a href="blogEdit.php?bid=<?php echo $r['bid'],"&wd=$wd2";if(!empty($tn))echo"&$tn=$rowid";?>">[修改]</a>
		<a href="../<?php echo $r['bindex'];?>.html"target="_blank">[预览]</a>
		<?php if($r['badd'])$color='class="c'.$r['badd'].'"';else $color="";if($add)echo'<a '.$color.'href="pageAdd.php?',"$tn=",$rowid,'&bid=',$r['bid'],'"><strong>[加入]</strong></a>';?>
	</dd>
</dl>

              
                <?php
}
?>
 

            <nav id="pages">
                    <?php
if($page>1){
?>
                        <a href="?page=<?php echo $page-1,"&$tn=",$rowid,'&wd2=',$wd2,'&wd=',$wd;?>" class="btn btn-primary" id="prev">上一页</a>
                    <?php
}
if($pages>$page){
?>
                   
                        <a href="?page=<?php echo $page+1,"&$tn=",$rowid,'&wd2=',$wd2,'&wd=',$wd;?>" class="btn btn-primary" id="next">下一页</a>
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
