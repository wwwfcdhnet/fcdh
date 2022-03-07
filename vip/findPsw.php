<?php
include '../mysql_mydb.php';
include '../functionOpen.php';
$msg='';
$step=1;
$tn=1;
$uname=@$_POST['account'];
$email=@$_POST['email'];
$vcode=@$_POST['vcode'];
$uid=0;
$nowtime=time();
session_start();
if(isset($_POST['account']) && !empty($_POST['account'])){
	if(!_CheckInput($_POST['account'],'user')){
		$msg='<b>用户名需4个以上的数字和字母组合</b>';
		$tn=0;
	}
	$scode=@strtolower(trim($_POST['scode']));
//	echo $scode,' --#-- ',$_SESSION['scode'],':',$scode!=$_SESSION['scode'];//exit;
	if($scode!=$_SESSION['scode']){
		$tn=0;
		$msg='<b>验证码不对</b>';
		$step=1;
	}
	if($tn){
		$step=2;
		$uname=filterTitle($_POST['account']);
		$sql="SELECT uid,email FROM vipuser WHERE uname='$uname'";
	//	echo$sql;exit;
		$result=mysqli_query($mydb,$sql);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$uid=intval($row['uid']);
		$email=substr($row['email'],0,1).'********'.substr($row['email'],strrpos($row['email'],'@')-1);
		if(empty($uid)){
			$tn=0;
			$msg='<b>用户名不存在</b>';
			$step=1;
		}
	}
}elseif(isset($_POST['email']) && !empty($_POST['email'])){
	$uid=intval($_POST['uid']);
	$sql="SELECT uid,uname,email FROM vipuser WHERE uid=$uid";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$uname=$row['uname'];
	$email=substr($row['email'],0,1).'********'.substr($row['email'],strrpos($row['email'],'@')-1);
	if($row['email'] != $_POST['email']){
		$tn=0;
		$msg='<b>电子邮箱错误</b>';
		$step=2;
	}else{
		$step=3;
		$dif=$nowtime-5*60; //5分钟
		if(isset($_SESSION['key']) && isset($_SESSION['exptime']) && $_SESSION['exptime']>$dif){
			$msg= '<b>验证码已发送至电子邮箱：'.$email.'</b><br><strong class="c5">请过 '.($_SESSION['exptime']-$dif).'秒 后重试</strong>';  
		}else{
			require_once 'Smtp.class.php';
			$key=$_SESSION['key']=mt_rand(10000,65535);
			$_SESSION['exptime']=$nowtime;

			$mailto=$row['email']; //收件人            
			$smtpserver = "ssl://smtp.qq.com";              //SMTP服务器
			$smtpserverport =465;                      //SMTP服务器端口
			$smtpusermail = "2281195245@qq.com";      //SMTP服务器的用户邮箱
			$smtpemailto = $mailto;		    //发送给谁
			$smtpuser = "2281195245@qq.com";         //SMTP服务器的用户帐号
			$smtppass = "fyrxpgolqiurdhie";			  //SMTP服务器的用户密码
			$title='粉美人';

			$mailsubject = $title." - 找回密码";        //邮件主题
			$mailbody = "<h1> 找回密码用的验证码: <span style='color:blue'>$key</span> </h1>";//邮件内容  
			$mailtype = "HTML";                      //邮件格式（HTML/TXT）,TXT为文本邮件
			$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
			$smtp->debug = false;                     //是否显示发送的调试信息
			$state=$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype, $title);
			
//			$mailto=substr($mailto,0,1).'******'.strrchr($mailto,'@');
			$msg= "<strong class='c5'>验证码已发送到: ".$email."</strong><br><b>有效期 5分钟</b>";  
			$_SESSION['uid']=$uid;
			if($state==""){  
				$msg= "<b>验证码发送失败。</b>";  
				$step=2;
			}
		}
	}
}elseif(isset($_POST['vcode']) && !empty($_POST['vcode'])){
	if($_SESSION['key']==$_POST['vcode'] && isset($_SESSION['uid']) && isset($_SESSION['exptime'])){//验证码一致
		$dif=$nowtime-5*60;
		if($_SESSION['exptime'] < $dif){
			unset($_SESSION['key']);
			unset($_SESSION['exptime']);
			unset($_SESSION['uid']);
			$msg='<b>验证码已失效</b>';
			$step=2;
			$uid=intval($_POST['uid']);
			$sql="SELECT uid,uname,email FROM vipuser WHERE uid=$uid";
			$result=mysqli_query($mydb,$sql);
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$uname=$row['uname'];
			$email=substr($row['email'],0,1).'********'.substr($row['email'],strrpos($row['email'],'@')-1);
		}else{
			$uid=intval($_SESSION['uid']);
			$psw=substr(md5($_KEY.$_POST['newpsw'].$_KEY),0,32);
			$sql="UPDATE vipuser SET psw='$psw' WHERE uid=$uid LIMIT 1";
			$result=mysqli_query($mydb,$sql);
			if($result){
				$msg='<strong class="c2">密码修改成功!</strong><h2><a href="login.php">[立即登录]</a></h2>';
				$step=4;
			}else{
				$msg= "<b>密码修改失败</b>";  
				$step=3;
			}
		}
	}else{
		$msg= "<b>验证码不匹配</b>";  
		$step=3;
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
    <title>找回密码 - 粉美人</title>
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
				<h3><a><i class="fa-bookmark"></i>找回密码</a></h3>

			<form method="post"method="post" action=""id="logoform">
			<?php if($step<2) { ?>
			<p><label for="account">用户名</label><b>(4个以上的数字和字母组合)</b><br>
			<input type="text" class="form-control" name="account" style="width:223px"id="account"maxlength="16"value="<?php echo$uname;?>" placeholder="用户名" required> 
			<p class="pcode">
			<label><img src="../scode.php"alt="点击刷新" title="点击刷新" id="scodeimg"></label><input type="text" class="form-control" name="scode"maxlength="4"autocomplete="off"style="width:133px"id="scode" placeholder="验证码">
			</p>

			<?php } if($step==2) { ?>
			<p><label for="email">输入电子邮箱</label><b>(<?php echo$email;?>)</b><br>
			<input type="text" class="form-control" name="email" id="email"maxlength="32"value=""autocomplete="off"placeholder="电子邮箱" required>
			<input type="hidden"name="uid"value="<?php echo$uid;?>">
			<input type="hidden"name="uname"value="<?php echo$uname;?>">
			</p>

			<?php } if($step==3) { ?>
			<p><label for="vcode">发送到邮箱的验证码</label><br>
			<input type="text" class="form-control" name="vcode" id="vcode"maxlength="5"value=""autocomplete="off"placeholder="验证码" required>
			<input type="hidden"name="uid"value="<?php echo$uid;?>">
			<input type="hidden"name="uname"value="<?php echo$uname;?>">
			</p>
			<p><label for="newpsw">新密码</label><br>
			<input type="password" class="form-control" name="newpsw" id="newpsw"maxlength="16"value=""autocomplete="off"placeholder="新密码" required>
			</p>
			<?php } if($step<4) { ?>
				<button type="submit" class="btn btn-primary btn-block">下一步</button>
			<?php } ?>
			<p> <?php echo$msg;?></p>
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
<script>
	$(function () {
		$('#scodeimg').bind('click',function(){this.src='../scode.php?rand='+Math.random();});
		if(!<?php echo$tn;?>){
			$('#msg').html('<?php echo$msg;?>');
			$('#warning').modal('show');
		}
	});
</script>
</body>
</html>
