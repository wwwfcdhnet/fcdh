<?php
include 'admin.php';

$rowid=$tn=$tname=$tid=$tidfather=$tidson='';
$wd=@$_GET['wd'];
$fenlei=$pstate=0;
$cate=array('href','tag','blog');
$catename=array('链接','标签','博客');
$catelink=array('hrefDetail','page','blogDetail');
//$tidfather=-1;
//$tidfather=array();
$res=array();
if(isset($_GET['rowid']) || isset($_GET['whid']) || isset($_GET['bhid'])){
	if(isset($_GET['whid'])){
		$tn='whid';
		$rowid=intval($_GET['whid']);
	}elseif(isset($_GET['bhid'])){
		$tn='bhid';
		$rowid=intval($_GET['bhid']);
	}else{
		$tn='rowid';
		$rowid=intval($_GET['rowid']);
	}
	$res=$db->query("select tid,tidfather,tidson,pstate from pagetag where rowid=$rowid")->fetch();
	$tid=intval($res['0']);
	//echo"select tid,tidfather,tidson,pstate from pagetag where rowid=$rowid";
	$tidfather=intval($res['1']);
	$tidson=intval($res['2']);
	$pstate=intval($res['3']);
//if($tidfather<1)$tidson=$tid;
	$res=$db->query("select tname from tag WHERE tid = $tidson")->fetch();
	$tname=$res[0];
	//if($pstate==5 || $pstate==13){// 表示本类下头是标签，非链接
	if($tn=='whid'){
		$fenlei=1;
		$sql="SELECT pagehref.rowid,pagehref.rank,tag.tid as hid,tag.tname as hname,tag.html,tag.tstrong as hstrong,tag.tcolor as hcolor FROM pagehref INNER JOIN tag ON pagehref.hid=tag.tid WHERE pagehref.tid=$tidson ORDER BY pagehref.rank DESC";
	}elseif($tn=='bhid'){
		$fenlei=2;
		$sql="SELECT pagehref.rowid,pagehref.rowid,pagehref.rank,blog.bid as hid,blog.btitle as hname,blog.html,blog.bcolor as hcolor,blog.bstrong as hstrong FROM pagehref INNER JOIN blog ON pagehref.hid=blog.bid WHERE pagehref.tid=$tidson ORDER BY pagehref.rank DESC";
		//echo$sql;
	}else{
		$sql="SELECT pagehref.rowid,pagehref.rowid,pagehref.rank,href.hid,href.hname,href.hstate,href.hcolor,href.hstrong,href.html FROM pagehref INNER JOIN href ON pagehref.hid=href.hid WHERE pagehref.tid=$tidson ORDER BY pagehref.rank DESC";
	}
	//echo$sql;
	// update pagehref set hid=hid+1000000 where tid=46 and hid<300
	$res=$db->query($sql)->fetchAll();
}else{
	if(isset($_GET['tid'])){
		$tn='cate';
		$rowid=$tid=$tidson=intval($_GET['tid']);
	}elseif(isset($_GET['bid'])){
		$tn='blog';
		$rowid=$tid=$tidson=intval($_GET['bid']);
	}else{
		$tn='url';
		$rowid=$tid=$tidson=intval($_GET['hid']);
	}

	//$tn='tid';
	//$rowid=$tid=$tidson=intval(@$_GET['tid']);
	$res=$db->query("select tname from tag WHERE tid = $tidson")->fetch();
	$tname=$res[0];

	if($tn=='cate'){// 表示本类下头是标签，非链接
		$fenlei=1;
		$sql="SELECT pagehref.rowid,pagehref.rank,tag.tid as hid,tag.tname as hname,tag.html,tag.tstrong as hstrong,tag.tcolor as hcolor FROM pagehref INNER JOIN tag ON pagehref.hid=tag.tid WHERE pagehref.tid=$tidson ORDER BY pagehref.rank DESC";
		//echo$sql;
	}elseif($tn=='blog'){// 表示本类下头是博客
		$fenlei=2;
		$sql="SELECT pagehref.rowid,pagehref.rank,blog.bid as hid,blog.btitle as hname,blog.html,blog.bcolor as hcolor,blog.bstrong as hstrong FROM pagehref INNER JOIN blog ON pagehref.hid=blog.bid WHERE pagehref.tid=$tidson ORDER BY pagehref.rank DESC";
	}else{
		$sql="SELECT pagehref.rowid,pagehref.rank,href.hid,href.hname,href.hstate,href.hcolor,href.hstrong,href.html FROM pagehref INNER JOIN href ON pagehref.hid=href.hid WHERE pagehref.tid=$tidson ORDER BY pagehref.rank DESC";
	}
	//echo$sql;
	$res=$db->query($sql)->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>页面列表 - 后台管理 - 非常导航</title>
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
									<input type="text" style="padding-left:15px;"class="form-control" name="wd" placeholder="搜索标签" maxlength="63">
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
	
<div id="main">
<h3><a class="c10" href="page.php?tid=<?php echo$tid,'&wd=',$wd;?>"><i class="fa-reply"></i> 返回上一级</a></h3>		
<h3><a><i class="fa-bookmark"></i><?php echo$tname;?></a> <?php echo'<a href="',$cate[$fenlei],'.php?',$tn,'=',$rowid,'&wd2=',$wd,'"><i class="fa-plus"></i>增加',$catename[$fenlei];?></h3>
	<div>
				 <?php
$count=0;
foreach($res as $href){
	$html='<strong class="c2">[未静态]</strong>';
	if($href['html'])$html='[已静态]';
	$del='';
	if(isset($href['hstate']) && $href['hstate']==0){
		$del=' del';
	}
?>
		<dl>
			<dd><?php echo ++$count,'#',$href['rank'];if($href['hstrong'])echo'<strong>';?><a href="<?php echo $catelink[$fenlei],'.php?tid=',$href['hid'],'&wd=',$wd,'"target="view"class="c',$href['hcolor'],$del;?>"><?php echo'【',$href['hname'],'】';?></a><?php if($href['hstrong'])echo'</strong>';?>
				<a href="pageHtml.php?<?php if($fenlei==1)echo'tid=';elseif($fenlei==2)echo'bid=';else echo'hid=';echo$href['hid'];?>"><?php echo$html;?></a>
				<a href="pageEdit.php?<?php echo"$tn=$rowid&hid=",$href['hid'];?>">[修改]</a>
				<a href="pageDel.php?<?php echo"$tn=$rowid&hid=",$href['hid'];?>" onclick="return window.confirm('是否删除？')">[删除]</a>
			</dd>
		</dl>
	<?php
		}
	?>
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
