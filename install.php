<?php
if($_SERVER['HTTP_HOST']=='fcdh.net'){
	header("location:https://www.fcdh.net/");
	exit;
}
//include './sqlite_db.php';
include './function.php';
include './functionOpen.php';
$steparr=array('1'=>'配置Mysql数据','2'=>'后台用户名和密码','3'=>'修改后台目录','4'=>'修改后台目录');
$errarr=array('1'=>'成功连接数据库','9'=>'未成功连接数据库');
$len=count($steparr);
$step=intval(@$_GET['step']);
if($step<2)$step=1;
if($step>$len)$step=$len;
$key=$err=$pre='';$nexstep='下一步';
if($step==$len)$nexstep='安装完成';
$next='<button type="submit"name="submit" value="'.($step+1).'"class="btn btn-primary"value="'.($step+1).'">'.$nexstep.'</button>';
$data = '[].,{}|:12345678923456789ABCDEFGHIJKMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz!@#^&()%+=-';
for ($i = 0; $i < 16; $i++) {
	$fontcontent = substr($data, rand(0, strlen($data) - 1), 1);
	$key .= $fontcontent;
}
if(!file_exists('fcdh.db')){
	exit('程序已经安装！<br>如重新安装请重置数据库sqlite名称为“fcdh.db”');
}
$DBHOSTname=$DBuser=$DBpass=$DBname='';
if($step>1){
	$ref='?step='.($step-1);
	$pre='<a class="btn btn-primary" href="'.$ref.'">上一步</a> ';
	if(isset($_POST['submit']) && $_POST['submit']==$step){
		if($step==2){ //第一步提交的数据
			$DBHOSTname=$_POST['dbhostname'];
			$DBuser=$_POST['dbuser'];
			$DBpass=$_POST['dbpass'];
			$DBname=$_POST['dbname'];
			

			$mydb=@mysqli_connect($DBHOSTname,$DBuser,$DBpass,$DBname);
			if (!$mydb){ // 链接数据库未成功!
				$err='<strong class="c9">'.$errarr['9'].'</strong>';
				$next='<a diable class="btn btn-gray">'.$nexstep.'</a>';
			}else{
				$err='<strong class="c1">'.$errarr['1'].'</strong>';
				$mysql='<?php
		$_DBHOSTname="'.$DBHOSTname.'";               
		$_DBuser="'.$DBuser.'";                    
		$_DBpass="'.$DBpass.'";                    
		$_DBname="'.$DBname.'";                   
		$_KEY="'.$key.'";     
		$_URLNUM=100;						

		$mydb=mysqli_connect($_DBHOSTname,$_DBuser,$_DBpass,$_DBname);
		if (!$mydb)
		{
			die("err link : " . mysqli_connect_error());
		}
		mysqli_set_charset($mydb,"utf8");
	?>';
				$fp = fopen('mysql_mydb.php', 'w');
				fwrite($fp, $mysql);
				fclose($fp);

		$sql="CREATE TABLE IF NOT EXISTS `hidviews` (
		  `hid` int(11) NOT NULL AUTO_INCREMENT,
		  `views` int(10) unsigned NOT NULL DEFAULT '0',
		  PRIMARY KEY (`hid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		mysqli_query($mydb,$sql);	

		$sql="CREATE TABLE IF NOT EXISTS `href` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `urlkey` varchar(31) CHARACTER SET ascii NOT NULL,
  `nums` smallint(5) unsigned NOT NULL DEFAULT '0',
  `html` varchar(255) NOT NULL,
  UNIQUE KEY `uid` (`uid`,`urlkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		mysqli_query($mydb,$sql);	

		$sql="CREATE TABLE IF NOT EXISTS `msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tn` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `uname` varchar(31) CHARACTER SET ascii NOT NULL DEFAULT '0',
  `times` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(19) CHARACTER SET ascii NOT NULL DEFAULT '0',
  `ask` varchar(255) NOT NULL DEFAULT '0',
  `reply` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		mysqli_query($mydb,$sql);	

				// 插入数据结构
			$sql="CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `found` date NOT NULL DEFAULT '2012-03-05',
  `times` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logtime` int(10) unsigned NOT NULL DEFAULT '0',
  `urlnum` tinyint(3) unsigned NOT NULL DEFAULT '15',
  `maxnum` int(10) unsigned NOT NULL DEFAULT '0',
  `unick` varchar(15) NOT NULL,
  `uname` varchar(15) CHARACTER SET ascii NOT NULL DEFAULT 'mimipic',
  `psw` varchar(32) CHARACTER SET ascii NOT NULL DEFAULT 'mimipic',
  `email` varchar(32) CHARACTER SET ascii NOT NULL DEFAULT 'mimi@pic.com',
  `utel` bigint(11) unsigned NOT NULL DEFAULT '0',
  `uqq` bigint(11) unsigned NOT NULL DEFAULT '0',
  `bgcolor` varchar(127) CHARACTER SET ascii NOT NULL DEFAULT '0',
  `bordcolor` varchar(7) CHARACTER SET ascii NOT NULL DEFAULT '#000000',
  `ip` varchar(15) CHARACTER SET ascii NOT NULL DEFAULT '0.0.0.0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `name` (`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		mysqli_query($mydb,$sql);	

				$sql="CREATE TABLE IF NOT EXISTS `vipcode` (
  `vid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vcode` varchar(18) CHARACTER SET ascii NOT NULL DEFAULT '0',
  `vrank` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `price` tinyint(3) unsigned NOT NULL DEFAULT '10',
  `saled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `regtime` int(10) unsigned NOT NULL DEFAULT '0',
  `uname` varchar(16) CHARACTER SET ascii DEFAULT NULL,
  `email` varchar(32) CHARACTER SET ascii DEFAULT NULL,
  `ip` varchar(15) CHARACTER SET ascii DEFAULT NULL,
  PRIMARY KEY (`vid`),
  UNIQUE KEY `vcode` (`vcode`),
  KEY `actdate` (`regtime`),
  KEY `uname` (`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		mysqli_query($mydb,$sql);

		$sql="CREATE TABLE IF NOT EXISTS `vipimg` (
  `iid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `pindex` int(10) unsigned NOT NULL,
  `pmid` tinyint(3) unsigned NOT NULL,
  `downtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`iid`),
  UNIQUE KEY `uid` (`uid`,`pindex`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1";
		mysqli_query($mydb,$sql);

		$sql="CREATE TABLE IF NOT EXISTS `vipuser` (
  `uid` int(5) unsigned NOT NULL,
  `vrank` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fondtime` int(10) unsigned NOT NULL,
  `logdate` date NOT NULL DEFAULT '2021-07-06',
  `exptime` int(10) unsigned NOT NULL,
  `upoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ucoin` smallint(5) unsigned NOT NULL DEFAULT '0',
  `money` smallint(5) unsigned NOT NULL DEFAULT '0',
  `actived` smallint(6) NOT NULL DEFAULT '0',
  `umotion` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uname` varchar(16) CHARACTER SET ascii NOT NULL DEFAULT 'mimipic',
  `psw` varchar(32) CHARACTER SET ascii NOT NULL DEFAULT 'mimipic',
  `email` varchar(32) CHARACTER SET ascii NOT NULL DEFAULT 'mimi@pic.com',
  `ip` varchar(15) CHARACTER SET ascii NOT NULL DEFAULT '0.0.0.0',
  `codestr` varchar(255) CHARACTER SET ascii NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8";
		mysqli_query($mydb,$sql);


	}

		}elseif($step==3){  //第二步提交的数据
			$key=$_POST['key'];	
			$user=$_POST['user'];
			$pass=md5($key.$_POST['pass'].$key);
			$data = '12345678923456789ABCDEFGHIJKMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
			$name='00';
			for ($i = 0; $i < 16; $i++) {
				$fontcontent = substr($data, rand(0, strlen($data) - 1), 1);
				$name .= $fontcontent;
			}
				$sqlite='<?php
	date_default_timezone_set("Asia/Shanghai");
	define("ROOT",__DIR__);
	try{
	  $db = new PDO("sqlite:".ROOT."/'.$name.'.db");
	}catch (Exception $e) {
	  echo $e->getMessage();
	  exit;
	}
	$db->exec("set names utf8");
	$_KEY="'.$key.'";
	?>';		
				if(file_exists('fcdh.db') && rename('fcdh.db',$name.'.db')){
					$fp = fopen('sqlite_db.php', 'w');
					fwrite($fp, $sqlite);
					fclose($fp);
				}


			include './sqlite_db.php';
			$eof=$db->exec("insert into admin(name,pass) values('$user','$pass')");
			if(!$eof){
				$db->exec("UPDATE admin SET pass='$pass' WHERE name='$user'");
			//exit("UPDATE user admin pass='$pass' WHERE name='$user'");
			}

				header("location:admin");
				exit;
			if(!file_exists('fcdh.db')){
			}else{
				$webname=$_POST['webname'];
				$yuming=$_POST['yuming'];
				$beian=$_POST['beian'];
				header("location:admin");
			}

		}else{// 最后一步安装完成
		}
	}else{
		header("location:?step=1");
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
    <title><?php echo$steparr[$step];?> - 非常导航</title>
    <meta name="keywords" content="留言板，非常导航">
    <meta name="description" content="留言板，非常导航">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/fcdh.css">
    <link rel="stylesheet" href="./assets/css/bbs.css">
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
</head>

<body>
    <!-- 最外层容器 -->
    <div id="container">
        <div id="sidebar"class="toggle-others">
		<div class="ti-fix">
                <header class="logo-env">
                    <!-- logo -->
                    <div class="logo">
                        <a href="./" class="logo-expanded">
                            <img src="./assets/images/logo@2x.png"alt="非常导航之电脑版本logo" />
                        </a>
                        <a href="./" class="logo-collapsed">
                            <img src="./assets/images/logo-collapsed@2x.png"alt="非常导航之手机版本logo" />
                        </a>
                    </div>
                </header>
                <ul id="menu"> 
                    <li<?php if($step==1) echo' class="active"';?>>
                        <a href="?step=1" class="smooth">
                            <span><?php echo$steparr['1'];?></span>
                            <i class="fa-fire"></i>
                        </a>
                    </li>
                    <li<?php if($step==2) echo' class="active"';?>>
                        <a href="?step=2" class="smooth">
                            <span><?php echo$steparr['2'];?></span>
                            <i class="fa-user"></i>
                        </a>
                    </li>
                    <li<?php if($step==3) echo' class="active"';?>>
                        <a href="?step=3" class="smooth">
                            <span><?php echo$steparr['3'];?></span>
                            <i class="fa-link"></i>
                        </a>
                    </li>
                </ul>
        </div>
        </div>
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
                </ul>
				<ul class="ti-ads">
					<li><span class="c8">欢迎安装非常导航程序，请加QQ①群690912541交流</span></li>
				</ul>
            </nav>
			<br/>
			<br/>
			<form action="?step=<?php echo($step+1);?>" method="post">
				<h3><a class="c10"><i class="fa-bookmark"></i> <?php echo'第 ',$step,' 步 ：',$steparr[$step];?></a></h3>
			<?php if($step==1) { ?>
				<p>
				<label><a class="c7">Mysql数据库地址</a> </label><input type="text" class="form-control" name="dbhostname"value="localhost"maxlength="21"style="width:100%" placeholder="localhost"></p>
				<p>
				<label><a class="c7"> Mysql数据库登录帐户</a> </label><input type="text" class="form-control" value="root"name="dbuser"maxlength="21"style="width:100%" placeholder="root"></p>
				<p>
				<label><a class="c7">Mysql数据库登录密码</a> </label><input type="text" class="form-control" value="root"name="dbpass"maxlength="21"style="width:100%"autocomplete="off" placeholder="root"></p>
				<p>
				<label><a class="c7">Mysql数据库名称</a> </label><input type="text" class="form-control" name="dbname"value="test"maxlength="21"style="width:100%" placeholder="test"></p>
			<?php } ?>
			<?php if($step==2) { ?>
				<p><label><a class="c3">后台登录用户名</a> </label><input type="text" class="form-control" name="user"value="admin"maxlength="32"style="width:100%" placeholder="数字、字母"></p>
				<p><label><a class="c3">后台登录密码</a> </label><input type="password" class="form-control" name="pass"maxlength="32"style="width:100%" placeholder="最大32字符"></p>
				<p><input type="hidden" name="key"value="<?php echo$key;?>"></p>
			<?php } ?>
			<?php if($step==3) { ?>
				<p><label><a class="c5">网站名称</a> </label><input type="text" class="form-control" name="webname"maxlength="32"value="admin"style="width:100%"placeholder="admin"></p>
				<p><label><a class="c5">网站域名</a> </label><input type="text" class="form-control" name="yuming"maxlength="32"value="admin"style="width:100%"placeholder="admin"></p>
				<p><label><a class="c5">网站备案号</a> </label><input type="text" class="form-control" name="beian"maxlength="32"value="admin"style="width:100%"placeholder="admin"></p>
			<?php } ?>
				<p><?php echo$pre,$next;?></p>
				<p><label> <?php echo$err;?></label> </p>
			</form>
			<br/><br/><br/>
            <footer class="footer">
                    <div class="vcenter">
                         Since 2020 <strong><a href="./">fcdh.net</a></strong> <span class="ti-more"><a href="https://beian.miit.gov.cn/">渝ICP备20001609号</a></span>
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

<script src="./assets/js/fcdh.js"></script>
</body>

</html>
