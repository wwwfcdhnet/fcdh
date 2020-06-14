<?php
include 'admin.php';
$act=@$_GET['act'];
$id=@$_GET['id'];
$data=$db->query("select * from content where id=$id")->fetch();
if($act=='save'){
    $reply=@$_POST['reply'];
    $ref=@$_POST['ref'];
    $db->exec("UPDATE `content` SET reply='$reply' WHERE id=$id");
    
    redir($ref);
}
else{
    $ref=@$_SERVER['HTTP_REFERER'];
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>回复留言 - 后台管理 - 非常导航</title>
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
					<li class="cur"><a href="index.php">留言</a></li>
					<li><a href="config.php">设置</a></li>
					<li><a href="password.php">密码</a></li>
					<li><a href="logout.php">退出</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
			<form method="POST" action="?id=<?php echo $id;?>&act=save">
				<h4><a><i class="fa-bookmark"></i>回复</a></h4>
				<input type="hidden" name="ref" value="<?php echo $ref;?>">
				<textarea name="reply" rows="5"id="text"><?php echo $data['reply'];?></textarea>
				<p><button type="submit" class="btn btn-primary">保存回复</button></p>
			</form>
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
