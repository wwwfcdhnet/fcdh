<?php
include 'admin.php';
$act=@$_GET['act'];
$msg='';
if($act=='save'){
    $name=@$_POST['name'];
    $pagesize=@$_POST['pagesize'];
    $scode=@$_POST['scode'];
    $verify=@$_POST['verify'];

    save_config('name',$name);
    save_config('pagesize',$pagesize);
    save_config('scode',$scode);
    save_config('verify',$verify);

    $msg='保存成功';
}

$name=load_config('name');
$pagesize=load_config('pagesize');
$scode=load_config('scode');
$verify=load_config('verify');

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>系统设置 - 后台管理 - 非常导航</title>
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
					<li><a href="cate.php">标签</a></li>
					<li class="cur"><a href="cateAdd.php">增加标签</a></li>
				</ul>
            </nav>
			<br/>
			<br/>

			<form method="post" action="?act=save">
				<h4><a><i class="fa-bookmark"></i>增加标签</a></h4>
			<p>
				<label>标签简称</label>
				<input type="text" class="form-control"placeholder="导航"style="width:100%"value="" name="ctag" required>
			</p>
			<p>
				<label>标签全称</label>
				<input type="text" class="form-control"placeholder="非常导航"style="width:100%"value="" name="ctagall" required>
			</p>
			<p>
				<label>标签英文</label>
				<input type="text" class="form-control"placeholder="daohang"style="width:100%"value="" name="cname" required>
			</p>
			<p>
				<label>页面标题</label>
				<input type="text" class="form-control"style="width:100%"value="" name="ctitle" required>
			</p>
			<p>
				<label>页面关键词</label>
				<input type="text" class="form-control"style="width:100%"value="" name="keys" required>
			</p>
			<p>
				<label>页面描述</label>
				<input type="text" class="form-control"style="width:100%"value="" name="desc" required>
			</p>
			<p>
				<label>标签标识</label><br/>
				<select class="form-control" name="ctn"style="width:100%"><option value="1" <?php echo $scode=='0'?'selected':''; ?>>分类标识</option><option value="0" <?php echo $scode=='1'?'selected':''; ?>>可链接</option><option value="0" <?php echo $scode=='2'?'selected':''; ?>>不可链接</option></select>
			</p>
				<button type="submit" class="btn btn-primary btn-block">增加标签</button>
			</form>
			<p class="c2"><?php echo $msg; ?></p>

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
