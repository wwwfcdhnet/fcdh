<?php
include 'admin.php';
$idtn=$info=$table='';
$id=0;
if(isset($_GET['id']) && !empty($_GET['id'])){
	$id=intval($_GET['id']);
	$table='content';
	$info='修改留言';
	$idtn='id';
}elseif(isset($_GET['hid']) && !empty($_GET['hid'])){
	$id=intval($_GET['hid']);
	$table='contenthref';
	$info='网站介绍';
	$idtn='hid';
}
$act=@$_GET['act'];
$data=$db->query("select * from $table where id=$id")->fetch();
if($act=='save'){
	if(isset($_POST['ttype']) && !empty($_POST['ttype'])){
		$cate=intval(@$_POST['ttype']);
		$url=@$_POST['url'];
		$tname=@$_POST['tname'];
		$title=@$_POST['title'];
		$keyword=@$_POST['keyword'];
		$content=@$_POST['content'];
		$db->exec("UPDATE `contenthref` SET cate=$cate,url='$url',tname='$tname',title='$title',keyword='$keyword',content='$content' WHERE id=$id");
	}else{
		$content=@$_POST['content'];
		$db->exec("UPDATE `content` SET content='$content' WHERE id=$id");
	}
	$ref=@$_POST['ref'];
    redir($ref);
}
else{
    $ref=$_SERVER['HTTP_REFERER'];
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>修改留言 - 后台管理 - 非常导航</title>
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
					<li class="cur"><a href="index.php">留言</a></li>
					<li><a href="config.php">设置</a></li>
					<li><a href="password.php">密码</a></li>
					<li><a href="logout.php">退出</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
			<form method="POST" action="?<?php echo $idtn,'=',$id;?>&act=save">
				<?php 
					if($table=='contenthref'){
				?>
					<h4><a class="c3"><i class="fa-bookmark"></i>网站类型</a></h4>
					<p><label><input type="radio" name="ttype" value="1"<?php if($data['cate']==1)echo' checked';?>>新站提交</label>&nbsp;&nbsp;<label><input type="radio" name="ttype" value="2"<?php if($data['cate']==2)echo' checked';?>>友情链接</label>&nbsp;&nbsp;<label><input type="radio" name="ttype" value="3"<?php if($data['cate']==3)echo' checked';?>>网站修改</label>
					</p>

					<h4><a class="c5"><i class="fa-bookmark"></i>网站链接</a></h4>
					<p><input type="text" class="form-control" name="url"maxlength="127"autocomplete="off"style="width:100%" id="url" value="<?php echo $data['url'];?>"></p>

					<h4><a class="c4"><i class="fa-bookmark"></i>网站名称</a></h4>
					<p><input type="text" class="form-control" name="tname"maxlength="31"autocomplete="off"style="width:100%" id="tname" value="<?php echo $data['tname'];?>"></p>

					<h4><a class="c2"><i class="fa-bookmark"></i>网站标题</a></h4>
					<p><input type="text" class="form-control" name="title"maxlength="63"autocomplete="off"style="width:100%" id="title" value="<?php echo $data['title'];?>"></p>
					<h4><a class="c6"><i class="fa-bookmark"></i>网站关键词</a></h4>
					<p><input type="text" class="form-control" name="keyword"maxlength="127"autocomplete="off"style="width:100%" id="keyword" value="<?php echo $data['keyword'];?>"></p>
				<?php
					}
				?>
				<h4><a><i class="fa-bookmark"></i><?php echo$info;?></a></h4>
				<input type="hidden" name="ref" value="<?php echo $ref;?>">
				<textarea name="content"id="text" rows="5"><?php echo $data['content'];?></textarea>
				<p><button type="submit" class="btn btn-primary">保存信息</button></p>
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
