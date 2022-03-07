<?php
include'../functionOpen.php';
$str='';
$trade_no = date("YmdHis").mt_rand(100,999);
for($i=1;$i<8;$i++){
	$color=$i+3;
	$vip=viprank($i);
	if($vip['point']<0)$vip['point']='无限';
	if($vip['month']==0)$vip['month']='已过期';
	elseif($vip['month']<0)$vip['month']='永久';
	else $vip['month']=$vip['month'].'个月';

	$vip['day']<0?$vip['day']='——':$vip['day'].='天';;
	if($vip['money']<0)$vip['money']='0';
	$str.="<option class='c".$i."'value='".$i."'>VIP$i".$vip['label']."----".$vip['month']."(￥".$vip['money']."元)</option>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>会员购买界面 - 粉美人</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="shortcut icon" href="../assets/images/favicon.png">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
	<style>
	#login{
		text-align:center;
		padding-top:120px;
	}
	#login input{
		1width:240px;
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
	label img{width:25%;}
	</style>
</head>

<body>
<div id="login">
	<form method="post" action="pay/epayapi.php"id="logoform" target="_blank">
		<h2>邀请码/升级码购买界面</h2>
		<p>
			<label for="account">VIP会员等级和价格: </label>
			<select name="WIDtotal_fee"><?php echo$str;?></select>
		</p>
		<p>
			<label for="email">电子邮箱<b>（用于接收注册码）</b></label>
			<input type="email" class="form-control" name="WIDsubject" id="WIDsubject"maxlength="32"value=""placeholder="xxxx@126.com" required> </p>
		<p>
			<label for="WIDsubject">请选择<b>支付宝</b>或者<b>微信</b>支付</label><br>		
			<label><input type="radio" name="type"id="alipay" value="alipay" checked><img src="../assets/images/alipay.png"></label>&nbsp; <label><input type="radio" id="wxpay"name="type" value="wxpay"><img src="../assets/images/wxpay.png"></label>
		</p>
		<p>
			<input type="hidden" name="WIDout_trade_no" value="<?php echo$trade_no;?>"/>
		</p>
		<p class="pcode">
			<button type="submit" class="btn btn-primary"value="购买">确认购买（进入支付页面）</button>
		</p>
		<p class="infomore">
			<a href="../"class="logleft">[首页]</a><a href="grade.php"class="logright">[会员权限]</a><a href="register.php"class="logright">[注册会员]</a>
		</p>
		<input type="hidden"name="ref" value="<?php echo$ref;?>">
	</form>
</div>
</body>

</html>