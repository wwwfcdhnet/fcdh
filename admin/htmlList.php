<?php
include 'admin.php';

$dir = "../html/";
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>静态页面列表 - 后台管理 - 非常导航</title>
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
					<li><a class="c10"><i class="fa-bookmark"></i> 前50个静态页面</a></li>
					<?php
						if($accessKey!='' && $secretKey!=''){
					?>
					<li> <a href="upload.php?up=half" onclick="return window.confirm('是否上传1000个页面？')"><i class="fa-upload"></i> 上传壹仟个页面</a></li>
					<?php
						}
					?>
				</ul>
            </nav>
			<br/>
			<br/>
			<div id="main">
				<h3><a><i class="fa-bookmark"></i> 静态页面列表</a></h3>
				<div id="list">

<?php

	// 打开目录，然后读取其内容
	if (is_dir($dir)){
	  if (false != ($handle = opendir ( $dir ))) {
			$i=0;
		while ( false !== ($file = readdir ( $handle )) ) {
			if ($file != "." && $file != ".." && 'html'==substr(strrchr($file, '.'), 1)) {
				if($file=='404.html')continue;
				if($i++==50){
                    break;
                }
?>

<dl>
	<dd><a href="../<?php echo$file;?>"target="_blank"class="c5"><strong><?php echo'#',$i,'#【',$file,'】';?></strong></a>
		<a href="upload.php?type=href&up=<?php echo $file;?>">[上传]</a>
		<a href="upload.php?type=href&del=<?php echo $file;?>" onclick="return window.confirm('是否删除？')">[删除]</a>
	</dd>
</dl>

              
                <?php
			}
		}
		closedir($handle);
	  }
	}

	$dir = "../html/tag/";
	// 打开目录，然后读取其内容
	if (is_dir($dir)){
	  if (false != ($handle = opendir ( $dir ))) {
			$i=0;
		while ( false !== ($file = readdir ( $handle )) ) {
			if ($file != "." && $file != ".." && 'html'==substr(strrchr($file, '.'), 1)) {
				if($file=='404.html')continue;
				if($i++==50){
                    break;
                }
?>

<dl>
	<dd><a href="../<?php echo$file;?>"target="_blank"class="c5"><strong><?php echo'#',$i,'#【',$file,'】';?></strong></a>
		<a href="upload.php?type=tag&up=<?php echo $file;?>">[上传]</a>
		<a href="upload.php?type=tag&del=<?php echo $file;?>" onclick="return window.confirm('是否删除？')">[删除]</a>
	</dd>
</dl>

              
                <?php
			}
		}
		closedir($handle);
	  }
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
