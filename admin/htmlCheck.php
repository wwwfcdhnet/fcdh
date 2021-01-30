<?php
include 'admin.php';
set_time_limit(600); // 100分钟
ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
$hid=1;
if(isset($_GET['html'])){
	$arr=explode('-',$_GET['hid']);
	$start=intval($arr['0'])+1000000;
	$end=intval(@$arr['1'])+1000000;
	if($_GET['html']=='1'){ // 生成静态页面
		$count=$end-$start+1;
		if($end==0){
			$end=$start+1;
			$count=1;
		}elseif($count>1001){
			$end=$start+1000;
			$count=1001;
		}
		$sql="select hid from href where hid >= $start and hid<=$end";
		$res=$db->query($sql)->fetchAll();
		foreach($res as $r){
			--$count;
			$hid=$r['hid'];
			hrefhtml($r['hid']);
		}

		if($count>0)$hid=0;
		++$hid;
	}else{
		$tn='';
		$r=$db->query("select value from config where key='hrefstartid'")->fetch();
		$start=$r['value'];
		$count=$end-$start+1;
		if($end==0){
			$end=$start+1;
			$count=1;
		}elseif($count>101){
			$end=$start+100;
			$count=101;
		}
		$sql="select hid,hindex,hurl from href where hid >= $start and hid<= $end";
		$hrefarr=$db->query($sql)->fetchAll();
		foreach($hrefarr as $href){
			--$count;
			$hid=$href['hid'];
			set_siteurl_state($href['hurl'],$href['hindex']); // 设置链接访问状态
		}
		if($count>0)$hid=0;
		$db->exec("update config set value=$hid where key='hrefstartid'");
		++$hid;
	}
}
$state = array('<strong class="c9">死链</strong>','<strong class="c2">正常</strong>','<strong class="c10">异常</strong>','<strong class="c5">改版</strong>');
$page=intval(@$_GET['page']);
if(!$page) $page=1;
$pagesize=10;
$offset=($page-1)*$pagesize;


$res=$db->query("select count(rowid) from htmltn")->fetch();
$sql="select rowid,htmlname,tn from htmltn limit $offset,$pagesize";

$total=$res[0];
$time=time();
$pages=ceil($total/$pagesize);
$res=$db->query($sql)->fetchAll();
$re=$db->query("select value from config where key='hrefstartid'")->fetch();
$hid=$re['value'];

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>异常链接 - 后台管理 - 非常导航</title>
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
                            <form action="href.php" method="get">
								<div class="input-group">
									<input type="text" style="padding-left:15px;"class="form-control" name="wd" placeholder="搜索链接" maxlength="63"autocomplete="on"value="">
									<span class="input-group-btn">
										<button type="submit"class="btn btn-primary fa-search"> 链接</button>
									</span>	
								</div>
							</form>
                        </div>
                    </li>
                </ul>
				<ul class="ti-ads">
					<li><a href="tag.php">搜标签</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
			<div id="main">
				<h3><a><i class="fa-bookmark"></i> 异常链接列表</a></h3>
				<form method="get" action="">
					<p><label>标题粗细</label>
					<label><input type="radio" name="html" value="1"checked>生成HTML</label><label><input type="radio" name="html" value="0">对网址进行检查</label>
					</p>
					<p>
					<label>起始序号</label>*<br/>
					<input type="text" class="form-control"placeholder="n-m"style="width:40%"value="<?php echo$hid;?>" name="hid" required>
					</p>
					<button type="submit" class="btn btn-primary btn-block">生成HTML或检查</button>
				</form>
			<br>

				<div id="list">

				 <?php
foreach($res as $r){
	$tn='hid';
	$id=0;
	$pagetab='hrefDetail.php?';
	$edit='hrefEdit.php?hid=';
	$url=$hurl='';
	if($r['tn']==-1){ // 标签
		$re=$db->query('select tid,tname,html from tag where tindex="'.$r['htmlname'].'" limit 1')->fetch(); 
		$hname=$re['tname'];
		$hurl=$r['htmlname'].'.html';
		$url='../'.$hurl;
		$id=$re['tid'];
		$tn='tid';
		$pagetab='page.php?';
		$edit='tagEdit.php?edit=';
	}else{ //链接
		$re=$db->query('select hid,hname,hurl,html from href where hindex="'.$r['htmlname'].'" limit 1')->fetch(); 
		$hname=$re['hname'];
		$hurl=$url=$re['hurl'];
		$id=$re['hid'];
	}
	
	$html='<strong class="c2">[未静态]</strong>';
	if($re['html'])$html='[已静态]';
?>

<dl>
	<dd>#<?php if($r['tn']<4)echo $state[$r['tn']];else echo$r['tn'];?>#<a href="<?php echo $url;?>"target="_blank"><?php echo'【',$hname,'】',$hurl;?></a></dd>
	<dd class="card-footer">
		<a href="pageHtml.php?<?php echo $tn,'=',$id;?>"><?php echo$html;?></a>
		<a href="<?php echo $edit,$id;?>">[修改]</a>
		<a href="../<?php echo $r['htmlname'],'.html';?>"target="_blank">[预览]</a>
		<a href="pageDel.php?index=<?php echo $r['rowid'];?>"onclick="return window.confirm('是否删除记录？')">[删除]</a>
	</dd>
</dl>

              
                <?php
}
?>
 

            <nav id="pages">
                    <?php
if($page>1){
?>
                        <a href="?page=<?php echo $page-1;?>" class="btn btn-primary" id="prev">上一页</a>
                    <?php
}
if($pages>$page){
?>
                   
                        <a href="?page=<?php echo $page+1;?>" class="btn btn-primary" id="next">下一页</a>
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
