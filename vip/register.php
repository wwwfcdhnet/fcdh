<?php
include '../mysql_mydb.php';
include '../functionOpen.php';
include 'faka.php';
$msg='';
$tn=1;
$uname=@$_POST['account'];
$email=@$_POST['email'];
$vcode=@$_POST['vcode'];
$psw=@$_POST['psw'];
if(isset($_POST['account']) && !empty($_POST['account'])){
	if(!_CheckInput($_POST['account'],'user')){
		$msg='用户名字符不合法';
		$tn=0;
	}
	if(!_CheckInput($_POST['email'],'email')){
		$msg='电子邮箱字符不合法';
		$tn=0;
	}
	if(!_CheckInput($_POST['vcode'],'numchar')){
		$msg='邀请码字符不合法';
		$tn=0;
	}
	session_start();
	$scode=@strtolower($_POST['scode']);
	if($scode!=$_SESSION['scode']){
		$tn=0;
		$msg='验证码不对';
	}
	if($tn){
		$tn=0;
		$vcode=filterTitle($_POST['vcode']);
		$uname=filterTitle($_POST['account']);
		$email=filterText($_POST['email']);
		//$ip=get_ip();
		$ip = $_SERVER['REMOTE_ADDR'];
		$regtime=time();

		$sql="SELECT vid,vrank,vcode,price,saled,uname FROM vipcode WHERE vcode='$vcode' LIMIT 1";
		$result=mysqli_query($mydb,$sql);
		$vid=$price=$saled=$vrank=$exptime=$upoint=$ucoin=$money=0;
		$vcode=$logdate='';
		if($result){
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$vid=intval($row['vid']);
			$saled=intval($row['saled']);
			$price=intval($row['price']);
		//	$vrank=intval($row['vrank']);
			$vcode=$row['vcode'];

			$vrank=pricetovip($price);

			$datetime = new DateTime('@'.$regtime);
			$datetime->setTimeZone(new DateTimeZone('PRC'));
			$logdate=$datetime->format('Y-m-d');

			$exptime=$regtime + 366*24*3600*1;//$vrank;
			$vip=viprank($vrank);
			$upoint=$vip['point'];
			$ucoin=$vip['coin'];
			$money=$vip['money'];
		}

		

		$psw=substr(md5($_KEY.$_POST['psw'].$_KEY),0,32);
		if(0==$vid || empty($vcode)){
			$tn=0;
			$msg='邀请码无效';
		}elseif($saled){ // 如果邀请码已经出售
			$buyname=$row['uname'];
			if($uname!=$buyname){
				$tn=0;
				$msg='<b>邀请码已被他人使用</b>';
			}else{
				$sql="SELECT uid FROM vipuser WHERE uname='$buyname' LIMIT 1";
				$result=mysqli_query($mydb,$sql);
				$count=mysqli_num_rows($result);
				if(!$count){
					$tn=1;
				}else{
					$tn=0;
					$msg='<b>'.$buyname.'  已经是会员</b>';
				}
			}
		}else{ // 如果邀请码没有出售
			$sql="update vipcode set saled=1,regtime=$regtime,uname='$uname',email='$email',ip='$ip' WHERE vcode='$vcode' LIMIT 1";
			$result=mysqli_query($mydb,$sql);
			if($result){// 如果更新成功，则插入用户信息
				$tn=1;
			}
		}

		if($tn){
			$sql="INSERT INTO vipuser SET vrank='$vrank',fondtime=$regtime,logdate='$logdate',exptime='$exptime',upoint=$upoint,ucoin=$ucoin,money=$money,uname='$uname',psw='$psw',email='$email',ip='$ip',codestr='$vid'";
			$result=mysqli_query($mydb,$sql);
			if($result){ // 注册成功
				header('Location: ./login.php'); 
			}else{
				$tn=0;
				$msg='<strong>用户名或邮箱已被使用</strong>';
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>会员注册界面 - 粉美人</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="../assets/images/favicon.png">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
	<style>
	#login{
		text-align:center;
		padding-top:120px;
	}
	#login input{
		width:205px;
		border-color:gray;
	}
	fieldset{
		font-size:15px;
		font-weight:700;
		color:gray;
		border:1px solid #dddddd;
		background-color:#fff;
	}
	#login h2{
		background-color:#444;
		margin-top:0;
		color:#fbfbfb;
		padding-top:5px;
		padding-bottom:10px;

	}
	#logoform{
		width:320px;
		margin:0 auto;
		padding-bottom:10px;
		border:1px solid #444;
	}
	#logoform .pcode{
		width:280px;
		margin:0 auto;
	}
	body{background:url('../assets/images/bg.jpg') #f2f2f2;}
	form{background:#f2f2f2;}
	.infomore{
		border-top:1px solid gray;padding-top:10px;margin-bottom:0;overflow:hidden;
	}
	.logleft{float:left;margin-left:15px;}
	.logright{float:right;margin-right:15px;}
	</style>
</head>

<body>
<div id="login">
	<form method="post"action="" id="logoform">
		<h2>会员用户注册</h2>
		<p><label for="account">会员账号: </label>
		<input type="text" class="form-control" name="account" id="account"maxlength="16"value="<?php echo$uname;?>"placeholder="账号[0-9][a-z]" required></p>
		<p><label for="psw">登录密码: </label>
		<input type="password" class="form-control" name="psw" id="psw" maxlength="16"value="<?php echo$psw;?>"autocomplete="off"placeholder="密码" required> </p>
		<p><label for="email">电子邮箱: </label>
		<input type="email" class="form-control" name="email" id="email"maxlength="32"value="<?php echo$email;?>"placeholder="xxxx@126.com" required> </p>
		<p><label for="vcode">邀&nbsp; 请&nbsp; 码:</label>
		<input type="text" class="form-control" name="vcode" id="vcode"maxlength="18"autocomplete="off"value="<?php echo$vcode;?>"placeholder="邀&nbsp; 请&nbsp; 码" required> </p>
		<p class="pcode">
		<label><img src="../scode.php"alt="点击刷新" title="点击刷新" id="scodeimg"></label><input type="text" class="form-control" name="scode"maxlength="4"style="width:133px"id="scode" placeholder="验证码"><button type="submit" class="btn btn-primary"value="注 册">注 册</button>
		</p>
		<p class="infomore">
		<a href="<?php echo$_FAKA;?>"class="logleft c8"target="_blank">[获取邀请码]</a><a href="../"class="logleft">[首页]</a><a href="grade.php"class="logright">[权限和条款]</a><a href="login.php"class="logright">[会员登录]</a>
		</p>
	</form>
</div>

<div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog"> 
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
			<h3 class="modal-title" id="myModalLabel"> 提示信息 </h3> 
		</div>
		<div class="modal-body"><p class="modal-body"><strong id="msg"></strong></p></div>
		<div class="modal-footer"> 
			<button id="cancel"type="submit" class="btn btn-primary" data-dismiss="modal"> 确 定 </button> 
		</div> 
	</div><!-- end 模态 -->
</div>
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