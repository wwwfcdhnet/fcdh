<?php
include'../functionOpen.php';
$str='';
for($i=1;$i<8;$i++){
	$color=$i+3;
	$vip=viprank($i);
	if($vip['point']<0)$vip['point']='无限';
	if($vip['month']==0)$vip['month']='<details><summary><b>已过期</b></summary><span class="c2">过期会员自动降为<b>VIP0</b></span></details>';
	elseif($vip['month']<0)$vip['month']='<details><summary>永久</summary><span class="c2">需要<b>'.$vip['day'].'天</b>内至少登录一次，否则过期降为<b>VIP0</b>会员</span></details>';
	else $vip['month']='<details><summary>'.$vip['month'].'个月</summary><span class="c2">过期降为<b>VIP0</b>会员</span></details>';

	$vip['day']<0?$vip['day']='——':$vip['day'].='天';;
	if($vip['money']<0)$vip['money']='0';
	$str.="<option value='$i'>VIP$i".$vip['label']."(￥".$vip['money']."元)</option>";
}
?><!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="applicable-device"content="pc,mobile">
    <meta name="author" content="fenmeiren.net" />
	<title>支付选项 - 粉美人</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="./assets/css/fcdh.css">
</head>
<body>
	<div id="main">
		<div id="head">
            <span class="title">粉美人会员购买</span>
		</div>
        <div class="cashier-nav">
            <ol>
				<li class="current">1、确认信息 →</li>
				<li>2、点击确认 →</li>
				<li class="last">3、确认完成</li>
            </ol>
        </div>
        <form name="alipayment" action="epayapi.php" method="post" target="_blank">
			  <input type="hidden" id="WIDsubject"name="WIDsubject" value="测试商品"/>
			  <input type="hidden" name="WIDout_trade_no" value="<?php echo date("YmdHis").mt_rand(100,999); ?>"/>
			  <p>
				<select name="WIDtotal_fee">
					<?php echo$str;?>
				</select>
			  </p>
			  <p>
			  <input type="radio" name="type" value="alipay" checked="">支付宝</label>&nbsp; <label><input type="radio" name="type" value="wxpay">微信支付</label>
			  </p>
			  <p>
				<span class="new-btn-login-sp">
					<button class="new-btn-login" type="submit" style="text-align:center;">确 认</button>
				</span>
				</p>
		</form>
        <div id="foot">
			<ul class="foot-ul">
				<li><font class="note-help">如果您点击“确认”按钮，即表示您同意该次的执行操作。 </font></li>
				<li>
				
				</li>
			</ul>
		</div>
	</div>
</body>
</html>