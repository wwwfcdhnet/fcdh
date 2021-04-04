<?php
include 'admin.php';
$act=@$_GET['act'];
$msg='';
$conf=array();
if($act=='save'){
    $conf['Bucket']=mb_substr(filterText(trim(@$_POST['Bucket'])),0,16);
    $conf['AK']=mb_substr(filterText(trim(@$_POST['AK'])),0,40);
    $conf['SK']=mb_substr(filterText(trim(@$_POST['SK'])),0,40);
    $conf['title']=mb_substr(filterText(trim(@$_POST['title'])),0,16);
    $conf['beian']=mb_substr(filterText(trim(@$_POST['beian'])),0,32);
    $conf['domain']=mb_substr(filterText(trim(@$_POST['domain'])),0,32);
    $conf['year']=intval(@$_POST['year']);
    $conf['mon']=intval(@$_POST['mon']);
    $conf['day']=intval(@$_POST['day']);
	$conf['tongji']=mb_substr($_POST['tongji'],0,512);
    $tongji=mb_substr(filterText(htmlspecialchars(trim(@$_POST['tongji']))),0,512);
	save_config('Bucket',$conf['Bucket']);
	save_config('AK',$conf['AK']);
	save_config('SK',$conf['SK']);
	save_config('title',$conf['title']);
	save_config('beian',$conf['beian']);
	save_config('domain',$conf['domain']);
	save_config('year',$conf['year']);
	save_config('mon',$conf['mon']);
	save_config('day',$conf['day']);
	save_config('tongji',$tongji);

	$admin='<?php
include \'../sqlite_db.php\';
include \'../function.php\';
define(\'ADMIN\',__DIR__);
session_start();
if($_SESSION[\'login\']!=\'OK\') redir(\'login.php\');
$accessKey = "'.$conf['AK'].'";
$secretKey = "'.$conf['SK'].'";
$_bucket = "'.$conf['Bucket'].'";';
	$fp = fopen('admin.php', "w"); 
	fwrite($fp, $admin);
	fclose($fp);

	$fp = fopen('../edit.html', "w"); 
	$content=file_get_contents('../assets/template/edit.htm');
	$content = str_replace('{{title}}', $conf['title'], $content);
	$content = str_replace('{{year}}', $conf['year'], $content);
	$content = str_replace('{{domain}}', $conf['domain'], $content);
	$content = str_replace('{{beian}}', $conf['beian'], $content);
	$content = str_replace('{{tongji}}', $conf['tongji'], $content);
	fwrite($fp, $content);
	fclose($fp);

	$fp = fopen('../home.html', "w"); 
	$content=file_get_contents('../assets/template/home.htm');
	$content = str_replace('{{title}}', $conf['title'], $content);
	$content = str_replace('{{year}}', $conf['year'], $content);
	$content = str_replace('{{domain}}', $conf['domain'], $content);
	$content = str_replace('{{beian}}', $conf['beian'], $content);
	$content = str_replace('{{tongji}}', $conf['tongji'], $content);
	fwrite($fp, $content);
	fclose($fp);

	$fp = fopen('../search.html', "w"); 
	$content=file_get_contents('../assets/template/search.htm');
	$content = str_replace('{{title}}', $conf['title'], $content);
	$content = str_replace('{{year}}', $conf['year'], $content);
	$content = str_replace('{{domain}}', $conf['domain'], $content);
	$content = str_replace('{{beian}}', $conf['beian'], $content);
	$content = str_replace('{{tongji}}', $conf['tongji'], $content);
	fwrite($fp, $content);
	fclose($fp);

	$fp = fopen('../user.html', "w"); 
	$content=file_get_contents('../assets/template/user.htm');
	$content = str_replace('{{title}}', $conf['title'], $content);
	$content = str_replace('{{year}}', $conf['year'], $content);
	$content = str_replace('{{domain}}', $conf['domain'], $content);
	$content = str_replace('{{beian}}', $conf['beian'], $content);
	$content = str_replace('{{tongji}}', $conf['tongji'], $content);
	fwrite($fp, $content);
	fclose($fp);

	$fp = fopen('tempBlog.html', "w"); 
	$content=file_get_contents('../assets/template/tempBlog.htm');
	$content = str_replace('{{title}}', $conf['title'], $content);
	$content = str_replace('{{year}}', $conf['year'], $content);
	$content = str_replace('{{domain}}', $conf['domain'], $content);
	$content = str_replace('{{beian}}', $conf['beian'], $content);
	$content = str_replace('{{tongji}}', $conf['tongji'], $content);
	fwrite($fp, $content);
	fclose($fp);
	
	$fp = fopen('tempHref.html', "w"); 
	$content=file_get_contents('../assets/template/tempHref.htm');
	$content = str_replace('{{title}}', $conf['title'], $content);
	$content = str_replace('{{year}}', $conf['year'], $content);
	$content = str_replace('{{domain}}', $conf['domain'], $content);
	$content = str_replace('{{beian}}', $conf['beian'], $content);
	$content = str_replace('{{tongji}}', $conf['tongji'], $content);
	fwrite($fp, $content);
	fclose($fp);
	
	$fp = fopen('tempPage.html', "w"); 
	$content=file_get_contents('../assets/template/tempPage.htm');
	$content = str_replace('{{title}}', $conf['title'], $content);
	$content = str_replace('{{year}}', $conf['year'], $content);
	$content = str_replace('{{domain}}', $conf['domain'], $content);
	$content = str_replace('{{beian}}', $conf['beian'], $content);
	$content = str_replace('{{tongji}}', $conf['tongji'], $content);
	fwrite($fp, $content);
	fclose($fp);

	$msg='保存成功';
}else{
	$config=$db->query("SELECT key,value FROM config where cate=2")->fetchAll(); 
	foreach($config as $v){
		if($v['0']=='tongji')$v['1']=htmlspecialchars_decode($v['1']);
		$conf[$v['0']]=$v['1'];
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
    <title>网站设置 - 后台管理 - 非常导航</title>
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
					<li><a href="config.php">BBS设置</a></li>
					<li><a href="password.php">密码</a></li>
					<li><a href="logout.php">退出</a></li>
				</ul>
            </nav>
			<br/>
			<br/>

			<form method="post" action="?act=save">
				<h3><a><i class="fa-bookmark"></i>网站设置</a></h3><p>
				<label>七牛Bucket</label>
				<input type="text" maxlength="16" class="form-control"style="width:100%"value="<?php echo$conf['Bucket'];?>" name="Bucket">
			</p>
			<p>
				<label>七牛AccessKey（AK）</label>
				<input type="text" maxlength="40" class="form-control"style="width:100%"value="<?php echo$conf['AK'];?>" name="AK">
			</p>
			<p>
				<label>七牛SecretKey（SK）</label>
				<input type="text" maxlength="40" class="form-control"style="width:100%"value="<?php echo$conf['SK'];?>" name="SK">
			</p>
			<p>
				<label>网站名称</label><br/>
				<input type="text" maxlength="16" class="form-control"style="width:100%" value="<?php echo$conf['title'];?>" name="title">
			</p>
			<p>
				<label>底部显示的域名</label><br/>
				<input type="text" maxlength="32" class="form-control"style="width:100%" value="<?php echo$conf['domain'];?>" name="domain">
			</p>
			<p>
				<label>网站创建日期 年-月-日</label><br/>
				<input type="text" maxlength="4" class="form-control"style="width:32%" value="<?php echo$conf['year'];?>" name="year">
				<input type="text" maxlength="2" class="form-control"style="width:32%" value="<?php echo$conf['mon'];?>" name="mon">
				<input type="text" maxlength="2" class="form-control"style="width:32%" value="<?php echo$conf['day'];?>" name="day">
			</p>
			<p>
				<label>备案号</label><br/>
				<input type="text" maxlength="32" class="form-control"style="width:100%" value="<?php echo$conf['beian'];?>" name="beian">
			</p>
			<p>
				<label>统计代码</label><br/>
				<textarea rows="9"maxlength="512" class="form-control"style="width:100%" id="tongji"name="tongji"><?php echo$conf['tongji'];?></textarea>
			</p>
				<button type="submit" class="btn btn-primary btn-block">保存设置</button>
			</form>
			<p class="c2"><?php echo $msg; ?></p>

			<br/><br/><br/>
            <footer class="footer">
                    <div class="vcenter">
                         Since 2020 <strong><a href="https://www.fcdh.net/">www.fcdh.net</a></strong>
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
