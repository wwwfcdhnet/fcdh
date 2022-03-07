<?php
include 'adminUser.php';
include 'faka.php';
$ucoin=$newrank=$buyvrank=$vrank=0;
$str=$msg='';
if(isset($_SESSION['loginuid'])){
	$uid=intval($_SESSION['loginuid']);
	$uname=$_SESSION['loginuser'];
	$sql="SELECT uid,vrank,fondtime,exptime,ucoin,money,actived,uname,email,codestr FROM vipuser WHERE uid=$uid LIMIT 1";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($uname != $row['uname']){header('Location: ./logout.php');exit;}
	$newrank=$oldvrank=$vrank=$row['vrank'];
	$newrank++;
	$datetime = new DateTime('@'.$row['fondtime']);
	$datetime->setTimeZone(new DateTimeZone('PRC'));
	$fondtime=$datetime->format('Y-m-d');

	$vip=viprank($newrank);
//	$fondtime=Date('Y-m-d',$row['fondtime']);
//	$logdate=$row['logdate'];
//	$exptime=Date('Y年m月d日',intval($row['exptime']));
//	$imgnum=$row['imgnum'];

	$exptime=$row['exptime'];
	$email=$row['email'];
	$money=$row['money'];
	$ucoin=$row['ucoin'];
	$difmoney=$vip['money']-$money;
	$actived=$row['actived'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$codestr=$row['codestr'];
	$codearr = explode(',',$row['codestr']);
	$tn=false;
	$nowtime=$regtime=time();
	if(isset($_POST['vcode'])){
		$vcode=filterTitle($_POST['vcode']);
		$sql="SELECT vid,vrank,vcode,saled,uname FROM vipcode WHERE vcode='$vcode' LIMIT 1";
		$result=mysqli_query($mydb,$sql);

		$saled=$vid=0;
		$vcode='';
		if($result){
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$vid=intval($row['vid']);
			$saled=intval($row['saled']);
			$buyvrank=intval($row['vrank']);
			$vcode=$row['vcode'];
		}
		if(!empty($vid))$codestr=$vid.','.$codestr;
		$codestr=trim($codestr,',');

		if(0==$vid || empty($vcode)){
			$tn=false;
			$msg='<b>邀请码无效</b>';
		}elseif($saled){ // 如果邀请码已经出售
			if($uname!=$row['uname']){
				$tn=false;
				$msg='<b>邀请码已被他人使用</b>';
			}else{
				if (!in_array($vid, $codearr)){
					$tn=true;
				}else{
					$tn=false;
					$msg='<b>邀请码已被你使用</b>';
				}
			}

		}else{ // 如果邀请码没有出售
			//mysqli_query($mydb,"BEGIN"); //或者mysql_query("START TRANSACTION");
			$sql="update vipcode set saled=1,regtime=$regtime,uname='$uname',email='$email',ip='$ip' WHERE vcode='$vcode' LIMIT 1";
			//echo$sql;exit;
			$result=mysqli_query($mydb,$sql);
			if($result){// 如果更新成功，则更新用户信息
				$tn=true;
			}
			//mysqli_query($mydb,"END"); 
		}
		if($tn){
			$buyvrank=viprank($buyvrank);
			$money+=$buyvrank['money'];

			$newrank=$vrank=pricetovip($money,$oldvrank); // 通过充值累计金额计算VIP等级
			if($vrank<$oldvrank)$newrank=$vrank=$oldvrank; //比当前等级低，则保持原来VIP等级
			 // 计算距离下一个等级的金额差
			$newrank++;
			$vip=viprank($newrank);
			$difmoney=$vip['money']-$money;
			if($difmoney<0)$money=65535;
			// 获取新升级后积分和美人币
			$viprank=viprank($vrank);
			$upoint=$viprank['point'];
			$coin=$viprank['coin'];

			// 刚购买的会员级别对应的美人币和会员期限
			$buycoin=$buyvrank['coin'];
			if($buycoin<0)$buycoin='无限';
			else $ucoin+=$buycoin;
			$day=$buyvrank['day'];
			$month=$buyvrank['month'];
			
			if($exptime<$nowtime || $month<0){// 如果会员过期了
				$exptime=$nowtime + $day*24*3600;
			}else{
				$exptime+=$day*24*3600;
			}

			if($upoint<0){
				$upoint=99;
				if($coin<0)$ucoin=99;
			}

			$sql="UPDATE vipuser SET vrank=$vrank,exptime=$exptime,upoint=$upoint,ucoin=$ucoin,money=$money,codestr='$codestr' WHERE uid=$uid LIMIT 1";
		
			$result=mysqli_query($mydb,$sql);
			if(!$result){ // 升级失败
				$msg='<b>升级失败!</b>';
			}else{ //升级成功
				$msg='<strong class="c2">升级成功!</strong>';
				$str='<p><label>美人币<b>+'.$buycoin.'枚</b></label></p>';
			}
		}
	}elseif($difmoney<=0){ // 充值金额已经满足升级要求，不需要充值
		if($exptime<$nowtime){
			$vrank=$oldvrank=0;
			$money=5;
			$sql="UPDATE vipuser SET vrank=$oldvrank,money=$money WHERE uid=$uid LIMIT 1";
			$result=mysqli_query($mydb,$sql);
		}
		$newrank=$vrank=pricetovip($money,$oldvrank); // 通过充值累计金额计算VIP等级
		if($vrank<$oldvrank)$newrank=$vrank=$oldvrank; //比当前等级低，则保持原来VIP等级
	
		$newrank++;
		$vip=viprank($newrank);
		$difmoney=$vip['money']-$money;
		if($difmoney<0)$money=65535;
		if($newrank>11 || $exptime<$nowtime){
			$msg='<b>执行失败!</b>';
			if($exptime<$nowtime){
				$msg='<b>用户已经过期!</b>';
			}
		}else{
			$viprank=viprank($vrank);
			$upoint=$viprank['point'];
			$coin=$viprank['coin'];
			if($coin<0)$coin='无限';
			else $ucoin+=$coin;

			$day=$viprank['day'];	
			$month=$viprank['month'];	
			
			if($exptime<$nowtime || $month<0){// 如果会员过期了
				$exptime=$nowtime + $day*24*3600;
			}else{
				$exptime+=$day*24*3600;
			}

			if($upoint<0){
				$upoint=99;
				if($coin<0)$ucoin=99;
			}

			$sql="UPDATE vipuser SET vrank=$vrank,exptime=$exptime,upoint=$upoint,ucoin=$ucoin WHERE uid=$uid LIMIT 1";
			$result=mysqli_query($mydb,$sql);
			if(!$result){ // 升级失败
				$msg='<b>执行失败!</b>';
			}else{
				$msg='<strong class="c2">自动升级成功!</strong>';
				$str='<p><label>美人币<b>+'.$coin.'枚</b></label></p>';
			}
		}
	}elseif($exptime<$nowtime){
		$vrank=$oldvrank=0;
		$money=5;
		$sql="UPDATE vipuser SET vrank=$oldvrank,money=$money WHERE uid=$uid LIMIT 1";
		$result=mysqli_query($mydb,$sql);
	}
	if($vrank==10)$vrank='10终极会员';
}else{
	 header('Location: ./logout.php');exit;
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>会员升级 - 粉美人</title>
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
				<h3><a><i class="fa-bookmark"></i>会员升级</a></h3>
			<form method="post" action="">
			
			<p>
				<label>当前会员级别：<strong>VIP<?php echo$vrank;?></strong> <a href="grade.php"class="c2">[会员价格]</a></label>
			</p>
			<?php if($newrank<11){ ?>
			<p>
				<label>再充值 <b><?php echo$difmoney,'元 </b>可升级到 <strong>VIP',$newrank,'</strong><span class="c',$newrank,'">(',$vip['label'],'会员)</span>';?></label>
			</p>
			<p><label for="vcode">升&nbsp; 级&nbsp; 码(邀请码):</label>
		<input type="text" class="form-control" name="vcode" id="vcode"maxlength="18"value=""autocomplete="off"placeholder="邀&nbsp; 请&nbsp; 码" required> </p>
				<button type="submit" class="btn btn-primary btn-block">确认升级</button> <?php echo$msg;?>
				<?php echo$str;?>
			<p><label><b>注:</b> "升级码" 和 "邀请码" 是同一个意思。如果是新用户注册，则是邀请码，如果已是注册会员，升级会员级别则是升级码。 <b><a href="<?php echo$_FAKA;?>"class="logleft c9"target="_blank">[获取升级码]</a></b></label></p>
			<?php } ?>
			</form> 
            <?php include'footer.php';?>
			<div id="ti-meng"></div>
		</div>
    </div> <!-- end 最处层容器 -->
 <script src="../assets/js/fcdh.js"></script>
</body>
</html>
