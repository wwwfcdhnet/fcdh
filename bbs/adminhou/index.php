<?php
include 'admin.php';
$page=intval(@$_GET['page']);
if(!$page) $page=1;
$pagesize=10;
$offset=($page-1)*$pagesize;
$res=$db->query("select count(id) from content")->fetch();
$total=$res[0];
$pages=ceil($total/$pagesize);
$res=$db->query("select * from content order by top desc,addtime desc limit $offset,$pagesize")->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>留言列表 - 后台管理 - 非常导航</title>
    <meta name="keywords" content="留言列表，后台管理，非常导航">
    <link rel="shortcut icon" href="../assets/images/favicon.png">
	<link rel="stylesheet" href="http://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
    <link rel="stylesheet" href="../assets/css/bbs.css">
	<script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
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
				<h4><a><i class="fa-bookmark"></i>留言列表</a></h4>
				<div id="list">

				 <?php

foreach($res as $r){
?>

<dl>
  <dt><strong><?php echo date('Y-m-d H:i:s',$r['addtime']);?></strong> | <strong><?php echo $r['ip'];?></strong> | <strong><?php echo $r['email'];?></strong></dt>
  <dd>
<?php if($r['top']==1) echo '<strong class="c2">【置顶】</strong>';?><?php if($r['verify']!=1) echo '<strong class="c9">[未审核]</strong>';?><?php echo $r['content'];?><?php if($r['reply']){?><dd><strong class="c0">【回复】</strong><?php echo $r['reply'];?></dd><?php }?>
  <dd class="card-footer"><?php if($r['top']==1){?>
                        <a href="top.php?id=<?php echo $r['id']?>&cancel=1" onclick="return window.confirm('是否取消置顶？')">[取消置顶]</a> 
                        <?php }else{ ?>
                        <a href="top.php?id=<?php echo $r['id']?>" onclick="return window.confirm('是否置顶？')">[置顶]</a> 
                        <?php }?>
                        <?php if($r['verify']==1){?>
                        <a href="verify.php?id=<?php echo $r['id']?>&cancel=1" onclick="return window.confirm('是否取消审核？')">[取消审核]</a> 
                        <?php }else{ ?>
                        <a href="verify.php?id=<?php echo $r['id']?>" onclick="return window.confirm('是否通过审核？')">[审核]</a> 
                        <?php }?>
                        <a href="edit.php?id=<?php echo $r['id']?>">[修改]</a> 
                        <a href="reply.php?id=<?php echo $r['id']?>">[回复]</a> 
                        <a href="del.php?id=<?php echo $r['id'];?>" onclick="return window.confirm('是否删除？')">[删除]</a></dd>
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
                         Since 2020 <strong><a href="http://www.fcdh.net/">www.fcdh.net</a></strong> <span class="ti-more"><a href="http://beian.miit.gov.cn/">渝ICP备20001609号</a></a>
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
