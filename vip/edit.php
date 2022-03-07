<?php
include 'adminUser.php';
$msg=array('','','','','','');
$edit=0;
$actived=0;
if(isset($_SESSION['loginuid'])){
	$uid=intval($_SESSION['loginuid']);
	$uname=$_SESSION['loginuser'];
	$sql="SELECT uid,uname,actived,psw,email FROM vipuser WHERE uid='$uid' LIMIT 1";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($uname != $row['uname']){ header('Location: ./logout.php');exit;}
	else{
		$actived=$row['actived'];
	}
}else{
	 header('Location: ./logout.php');exit;
}

if(isset($_GET['edit'])){
		$psw=substr(md5($_KEY.$_POST['psw'].$_KEY),0,32);
	//	echo$psw;exit;
		$edit=@intval($_GET['edit']);
		if($psw == $row['psw']){
			switch($edit){
				case 1:
					$tn=0;
					$sql='';
					if(!_CheckInput($_POST['email'],'email')){
						$msg[$edit]='<b>电子邮箱字符不合法</b>';
					}else{
						$email=filterText($_POST['email']);
						if($actived){
							$key=intval($_POST['ekey']);
							if(@$_SESSION['key'] != $key){
								$msg[$edit]='<b>验证码不匹配</b>';
								$tn=0;
							}else{
								$sql="UPDATE vipuser SET actived=0,email='$email' WHERE uid=$uid LIMIT 1";
								$tn=1;
							}
						}else{
							$sql="UPDATE vipuser SET email='$email' WHERE uid=$uid LIMIT 1";
							$tn=1;
						}
						if($email != $row['email'] && $tn){ //不一致则更新
							$result=mysqli_query($mydb,$sql);
							if($result){// 如果更新成功，则更新用户信息
								$msg[$edit]='<strong class="c2">修改成功</strong>';
								$actived=0;
								unset($_SESSION['key']);
							}else{
								$msg[$edit]='<b>'.$email.' 已经存在</b>';
							}
						}
					}
					break;
				case 2:
					$newpsw=substr(md5($_KEY.$_POST['newpsw'].$_KEY),0,32);
					$sql="UPDATE vipuser SET psw='$newpsw' WHERE uid=$uid LIMIT 1";
					$result=mysqli_query($mydb,$sql);
					if($result){// 如果更新成功，则更新用户信息
						$msg[$edit]='<strong class="c2">修改成功</strong>';
					}else{
						$msg[$edit]='<b>修改失败</b>';
					}
					break;
				default:
					$msg[$edit]='';
			}
		}else{
			$msg[$edit]='<b>原密码错误</b>';
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
    <title>会员资料修改 - 粉美人</title>
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
				
				<ul class="ti-ads">
					<li><a href="index.php">资料</a></li>
					<li><a href="upgrade.php">升级</a></li>
					<li><a href="edit.php">修改</a></li>
					<li><a href="logout.php">退出</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
				<h3><a><i class="fa-bookmark"></i>修改电子邮箱</a></h3>

			<form method="post" action="?edit=1"><?php if($actived) { ?>
			<p><label for="psw">原邮箱验证码</label> <a href="active.php?act=2"><b>[发送验证码]</b></a><br>
			<input type="text" class="form-control" name="ekey" id="ekey"maxlength="5"value=""autocomplete="off"placeholder="邮箱验证码" required> </p>
			<?php } ?>
			<p><label for="email">新电子邮箱:</label><br>
		<input type="text" class="form-control" name="email" id="email"maxlength="32"value="" placeholder="新电子邮箱:" required> </p>
			
			<p><label for="psw">密码:</label><br>
			<input type="password" class="form-control" name="psw" id="psw"maxlength="16"value=""autocomplete="off"placeholder="原密码:" required> </p>
			<button type="submit" class="btn btn-primary btn-block">邮箱修改</button> <?php echo$msg['1'];?>
			</form> 

			<h3><a><i class="fa-bookmark"></i>修改密码</a></h3>
			<form method="post" action="?edit=2">
			<p><label for="newpsw">新密码:</label><br>
		<input type="password" class="form-control" name="newpsw" id="newpsw"maxlength="16"value=""autocomplete="off"placeholder="新密码" required> </p>
			<p><label for="psw">原密码:</label><br>
		<input type="password" class="form-control" name="psw" id="psw"maxlength="16"value=""autocomplete="off"placeholder="原密码" required> </p>
				<button type="submit" class="btn btn-primary btn-block">密码修改</button> <?php echo$msg['2'];?>
			</form> 
            <?php include'footer.php';?>
			<div id="ti-meng"></div>
		</div>
    </div> <!-- end 最处层容器 -->
 <script src="../assets/js/fcdh.js"></script>
</body>
</html>
