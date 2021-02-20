<?php
include 'admin.php';
$page=intval(@$_GET['page']);
if(!$page) $page=1;
$pagesize=10;
$offset=($page-1)*$pagesize;
$wd='';
if(isset($_GET['wd'])&&!empty($_GET['wd'])){
	$wd=$_GET['wd'];
	$res=$db->query("SELECT count(id) FROM admin WHERE name='$wd'")->fetch();  
	$sql="SELECT * FROM admin WHERE name='$wd'";
}else{
	$res=$db->query("select count(id) from admin")->fetch();
	$sql="select id,name from admin order by id desc limit $offset,$pagesize";
}
	$total=$res[0];
	$pages=ceil($total/$pagesize);
	$res=$db->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>用户列表 - 后台管理 - 非常导航</title>
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
                </ul>
				<ul class="ti-ads">
					<li class=" cur"><a href="index.php">留言</a></li>
					<li><a href="config.php">设置</a></li>
					<li><a href="password.php">密码</a></li>
					<li><a href="logout.php">退出</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
<div id="main">
				<?php 
					if(isset($_GET['tid'])&&!empty($_GET['tid'])){
				?>
				<h3><a href="pageHref.php?tid=<?php echo$tid,'&tidson=',$tidson;?>"class="c10"><i class="fa-bookmark"></i>返回页面管理</a></h3>
				<?php
					}
				?>
				<h3><a class="c10"><i class="fa-bookmark"></i>用户列表</a> <a href="userAdd.php"><i class="fa-plus"></i>增加用户</a></h3>
				<div id="list">

				 <?php

foreach($res as $r){
?>

<dl>
	<dd>【<?php echo$r['name'];?>】 <a href="userEdit.php?id=<?php echo $r['id']?>">[修改]</a></dd>
</dl>

              
                <?php
}
?>
 

            <nav id="pages">
                    <?php
if($page>1){
?>
                        <a href="?page=<?php echo $page-1,'&tid=',$tid,'&tidson=',$tidson;?>" class="btn btn-primary" id="prev">上一页</a>
                    <?php
}
if($pages>$page){
?>
                   
                        <a href="?page=<?php echo $page+1,'&tid=',$tid,'&tidson=',$tidson;?>" class="btn btn-primary" id="next">下一页</a>
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
