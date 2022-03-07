<?php
include '../mysql_mydb.php';
include '../functionOpen.php';
$msg='';
$tn=1;
$uname=@$_POST['account'];
$email=@$_POST['email'];
$vcode=@$_POST['vcode'];
$psw=@$_POST['psw'];
$ref='';
if(isset($_GET['ref'])){
	$ref=$_SERVER['HTTP_REFERER'];
}
session_start();
if(isset($_POST['account']) && !empty($_POST['account'])){
	if(!_CheckInput($_POST['account'],'user')){
		$msg='用户名只能是数字和字母';
		$tn=0;
	}
	$ref=$_POST['ref'];
	$scode=@strtolower(trim($_POST['scode']));
//	echo $scode,' --#-- ',$_SESSION['scode'],':',$scode!=$_SESSION['scode'];//exit;
	if($scode!=$_SESSION['scode']){
		$tn=0;
		$msg='验证码不对';
	}
	if($tn){
		$uname=filterTitle($_POST['account']);
		$psw=substr(md5($_KEY.$_POST['psw'].$_KEY),0,32);
		$sql="SELECT uid,vrank,uname,exptime FROM vipuser WHERE uname='$uname' AND psw='$psw'";
	//	echo$sql;exit;
		$result=mysqli_query($mydb,$sql);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$uid=intval($row['uid']);
		$vrank=intval($row['vrank']);
		$exptime=intval($row['exptime']);
		$vip=viprank($vrank);
		
		if(empty($uid)){
			$tn=0;
			$msg='用户和密码不匹配';
		}else{
			$nowtime=time();
			if($exptime>$nowtime && $vip['month']<0){// 表示会员没有过期且是永久会员，则在规定时间间隔内登录一次自动延期
				$exptime=$nowtime + $vip['day']*24*3600;
				$sql="UPDATE vipuser SET exptime=$exptime WHERE uid=$uid LIMIT 1";
				$result=mysqli_query($mydb,$sql);
			}

			 //表示登录成功
			$_SESSION['loginuser']=$uname;
			$_SESSION['loginuid']=$uid;
			$_SESSION['loginrank']=$vrank;
			if(empty($ref)){
				header('Location: ./');
			}else{
				redir($ref);
			}
		}
	}
}else{
	if(isset($_SESSION['loginuid']) && intval($_SESSION['loginuid'])>0)header('Location: ./index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>会员登录界面 - 粉美人</title>
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
		width:240px;
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
		width:330px;
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
	<form method="post" action=""id="logoform">
		<h2>会员用户登录</h2>
		<p><label for="account">账号: </label>
		<input type="text" class="form-control" name="account" id="account"maxlength="16"value="<?php echo$uname;?>"autocomplete="off"placeholder="账号" required></p>
		<p><label for="psw">密码: </label>
		<input type="password" class="form-control" name="psw" id="psw" maxlength="16"autocomplete="off"placeholder="密码" required></p>
		<p class="pcode">
		<label><img src="../scode.php"alt="点击刷新" title="点击刷新" id="scodeimg"></label><input type="text" class="form-control" name="scode"maxlength="4"autocomplete="off"style="width:133px"id="scode" placeholder="验证码"><button type="submit" class="btn btn-primary"value="登 录">登 录</button>
		</p>
		<p class="infomore">
		<a href="findPsw.php"class="logleft">[忘记密码]</a><a href="../"class="logleft">[首页]</a><a href="grade.php"class="logright">[会员权限]</a><a href="register.php"class="logright">[注册会员]</a>
		</p>
		<input type="hidden"name="ref" value="<?php echo$ref;?>">
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