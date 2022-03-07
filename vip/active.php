<?php
include 'adminUser.php';
header("content-type:text/html;charset=utf8");    
$vrank=0;
$uname=$msg='';
$uid=0;
if(isset($_SESSION['loginuid'])){
	$uid=intval($_SESSION['loginuid']);
	$uname=$_SESSION['loginuser'];
	$sql="SELECT uid,vrank,fondtime,exptime,actived,uname,email,codestr FROM vipuser WHERE uid='$uid' LIMIT 1";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($uname != $row['uname']){header('Location: ./logout.php');exit;}
}else{
	header('Location: ./logout.php');exit;
}

if(isset($_GET['act'])){// 激活邮件
	require_once 'Smtp.class.php';
	$nowtime=time();
	$dif=$nowtime-5*60; //5分钟
	$actcode=array('1'=>'激活码','2'=>'验证码');
	if(isset($_SESSION['key']) && isset($_SESSION['exptime']) && $_SESSION['exptime']>$dif){
		$_GET['act']=-1;
	}else{
		$key=$_SESSION['key']=mt_rand(10000,65535);
		$_SESSION['exptime']=$nowtime;
	}
	$mailto=$row['email']; //收件人            
	$smtpserver = "smtp.qq.com";              //SMTP服务器
	$smtpserverport =25;                      //SMTP服务器端口
	$smtpusermail = "2281195245@qq.com";      //SMTP服务器的用户邮箱
	$smtpemailto = $mailto;		    //发送给谁
	$smtpuser = "2281195245@qq.com";         //SMTP服务器的用户帐号
	$smtppass = "fyrxpgolqiurdhie";			  //SMTP服务器的用户密码
	$title='粉美人';
	if($row['actived'] && $_GET['act']=='2'){ // 发送验证码
		$mailsubject = $title." - ".$actcode['2'];        //邮件主题
		$mailbody = "<h1> 更换电子邮箱的".$actcode['2'].": <span style='color:blue'>$key</span> </h1>";//邮件内容  
		$mailtype = "HTML";                      //邮件格式（HTML/TXT）,TXT为文本邮件
		$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
		$smtp->debug = false;                     //是否显示发送的调试信息
		$state=$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype, $title);

		$msg= "<strong class='c5'>恭喜！".$actcode['2']."发送成功！</strong>";  
		if($state==""){  
			$msg= "<b>对不起，".$actcode['2']."发送失败！请检查邮箱填写是否有误。</b>";  
		}

	}elseif(!$row['actived'] && $_GET['act']=='1'){ // 激活电子邮箱
		$ref=$_SERVER['PHP_SELF'];
		$actlink='http://'.$_SERVER['HTTP_HOST'].$ref.'?email='.$mailto.'&key='.$key;
		$mailsubject = $title." - ".$actcode['1'];        //邮件主题
		$mailbody = "<h1 style='color:blue'>尊敬的VIP会员$uname，请点击下面链接激活你的电子邮箱</h1><a href='$actlink'>$actlink</a><h2 style='color:red'>请务必收藏本邮件，牢记你的用户名</h2>";//邮件内容  
		$mailtype = "HTML";                      //邮件格式（HTML/TXT）,TXT为文本邮件
		$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
		$smtp->debug = false;                     //是否显示发送的调试信息
		$state=$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype,$title);
		$msg= "<strong class='c5'>恭喜！邮件发送成功！</strong>";  
		if($state==""){  
			$msg= "对不起，邮件发送失败！请检查邮箱填写是否有误。";  
		}
		
	}elseif($_GET['act']=='-1'){ // 激活电子邮箱
		$msg= "<b>5分钟</b> 后再发送".$actcode['1'];  
	}else{
		$msg= "未知操作";  
	}
//	require 'email.class.php';
	// 使用
/*// qq
	 $config = [
	 	'smtp_host'	=>	'smtp.qq.com',
	 	'smtp_port'	=>	587, // 25，465，587
	 	'smtp_user'	=>	'2281195245@qq.com',
	 	'smtp_pass'	=>	'fyrxpgolqiurdhie', //fyrxpgolqiurdhie
	 	'smtp_name'	=>	'粉美人m',
	 	'html'		=>	true,
	 ];
*/
	/**
	*实例化邮件类
	*/
}elseif(!$row['actived'] && isset($_GET['email']) && isset($_GET['key'])){
	if($_GET['email']!=$row['email']){
		$msg= '<b>激活码与邮箱不匹配</b>';
	}else{
		$key=intval($_GET['key']);
		$email=$row['email'];
		if(isset($_SESSION['key']) &&  $_SESSION['key']== $key){
			$sql="UPDATE vipuser SET actived=$key WHERE uid=$uid LIMIT 1";
			$result=mysqli_query($mydb,$sql);
			if(!$result){
				$msg='<b>用户与邮箱不匹配</b>';
			}else{
				$msg='<strong class="c2">恭喜，电子邮箱被激活</strong>';
				unset($_SESSION['key']);
				unset($_SESSION['exptime']);
			}
		}

	}
}else{
	if($row['actived']){
		$msg= '<strong class="c2">你的电子邮箱已经激活</strong>';
	}else{
		$msg= '<b>你的电子邮箱还未激活</b><p><a href="active.php?act=1"class="btn btn-primary" disabled>激活邮电</a></p>';
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
    <title>电子邮箱激活页面 - 粉美人</title>
    <link rel="shortcut icon" href="../assets/images/favicon.png">
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
    <link rel="stylesheet" href="./css/admin.css">
	<link rel="stylesheet" href="zyupload/skins/zyupload-1.0.0.css " type="text/css">
	<script src="https://libs.baidu.com/jquery/1.7.2/jquery.min.js"></script>
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
            </nav>
			<br/>
			<br/>
				<h3><a><i class="fa-bookmark"></i>电子邮箱激活状态</a></h3>
				<p>
				<h1><?php echo$msg;?></h1>
				</p>
			<br/><br/><br/>
            <?php include'footer.php';?>
			<div id="ti-meng"></div>
		</div>
    </div> <!-- end 最处层容器 -->
 <script src="../assets/js/fcdh.js"></script>
</body>
</html>