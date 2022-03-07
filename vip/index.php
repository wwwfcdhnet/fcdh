<?php
include 'adminUser.php';
$money=$upoint=$vrank=$uid=0;
$umotion=$actived=$ip=$email=$exptime=$logdate=$fondtime=$uname='';
$vip=array();
$nowtime=time();
if(isset($_SESSION['loginuser'])){
	$uid=intval($_SESSION['loginuid']);
	$uname=$_SESSION['loginuser'];
	$sql="SELECT uid,vrank,fondtime,logdate,exptime,upoint,ucoin,money,umotion,actived,uname,email,ip FROM vipuser WHERE uid='$uid'";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($uname != $row['uname']){
		header('Location: ./logout.php?ref='.$ref);exit;
	}
	$upoint=$row['upoint'];
	$ucoin=$row['ucoin'];
	$money=$row['money'];
	$email=$row['email'];
	$actived=$row['actived'];
	$umotion=$row['umotion'];
	if($actived)$email=substr($email,0,1).'********'.substr($email,strrpos($email,'@')-1);
	$ip=$row['ip'];
	$vrank=$row['vrank'];

	$datetime = new DateTime('@'.$row['fondtime']);
	$datetime->setTimeZone(new DateTimeZone('PRC'));
	$fondtime=$datetime->format('Y-m-d');
	$logdate=$row['logdate'];
	$exptime=$row['exptime'];

	$datetime = new DateTime('@'.$exptime);
	$datetime->setTimeZone(new DateTimeZone('PRC'));
	$expdate=$datetime->format('Y年m月d日H时i分s秒'); //2038-01-19 14:00:48

	$datetime = new DateTime('@'.$nowtime);
	$datetime->setTimeZone(new DateTimeZone('PRC'));
	$nowdate=$datetime->format('Y-m-d'); //2038-01-19 14:00:48
	$vip=viprank($vrank);
	if($nowdate != $logdate){
		//$upoint=$vip['point'];
	}
}else{
	 header('Location: ./logout.php?ref='.$ref);exit;
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title><?php echo$uname;?> 后台管理 - 粉美人</title>
    <meta name="keywords" content="VIP用户信息，后台管理，粉美人">
    <link rel="shortcut icon" href="../assets/images/favicon.png">
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
    <link rel="stylesheet" href="./css/admin.css">
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
	<style>
	#customers {
	  font-family: Arial, Helvetica, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	  font-size:16px;
	}

	#customers td, #customers th {
	  border: 1px solid #ddd;
	  padding: 8px;
	  text-align: center;
	}

	#customers tr:nth-child(even){background-color: #f2f2f2;}

	#customers tr:hover {background-color: #ddd;}

	#customers th {
	  padding-top: 12px;
	  padding-bottom: 12px;
	  background-color: #4CAF50;
	  color: white;
	}
	</style>
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
			<div id="main"><h3><a><i class="fa-bookmark"></i> 会员用户权限</a></h3>
				<table id="customers">
					<tr>
						<th>类 别</th>
						<th>名 称</th>
						<th>备 注</th>
					</tr>
					<tr>
						<td><strong>会员名称</strong></td>
						<td class="c5"><?php echo$uname;?></td>
						<td>累计充值：<b><?php echo$money;?>元</b></td>
					</tr>
					<tr>
						<td><strong>会员头衔</strong></td>
						<td class="c<?php echo$vrank;?>"><strong><?php echo$vip['label'];?></strong></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>会员等级</strong></td>
						<?php echo'<td class="c',(1+$vrank%9),'">VIP',$vrank,'</td>';?>
						<td><a href="upgrade.php"class="btn btn-primary">会员升级</a></td>
					</tr>
					<tr>
						<td><strong>美币</strong></td>
						<td>余<strong class="c10"><?php if($vip['coin']<0)echo'无限';else echo$ucoin;?></strong>美币</td>
						<td><a href="exchang.php"class="btn btn-primary">兑换积分</a></td>
					</tr>
					<tr>
						<td><strong>积分</strong></td>
						<td class="c1"><?php if($vip['point']<0)echo'无限';else echo$vip['point'];?></td>
						<td>余<b><?php if($vip['point']<0)echo'无限';else echo$upoint;?></b>积分</td>
					</tr>
					<tr>
						<td><strong>活力</strong></td>
						<td class="c10"><?php echo$umotion;?></td>
						<td><a href="exchang.php"class="btn btn-primary">兑换美币</a></td>
					</tr>
					<tr>
						<td><strong>电子邮箱</strong></td>
						<td class="c8"><?php echo$email;?></td>
						<td><?php if($actived)echo'<span class="c2">已激活</a>';else echo'<a href="active.php?act=1"class="btn btn-primary" disabled>激活邮电</a>';?></td>
					</tr>
					<tr>
						<td><strong>前次IP</strong></td>
						<td class="c3"><?php echo$ip;?></td>
						<td>本次IP：<?php echo$_SERVER['REMOTE_ADDR'];?></td>
					</tr>
					<tr>
						<td><strong>注册日期</strong></td>
						<td class="c4"><?php echo$fondtime;?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>上次登录日期</strong></td>
						<td class="c7"><?php echo$logdate;?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>会员过期时间</strong></td>
						<td class="c4"><?php echo$expdate;?></td>
						<td><?php if($exptime<$nowtime)echo'<strong class="c9">已过期</strong>';else echo'<span class="c2">未过期</span>';?></td>
					</tr>
					<tr>
						<td><strong>可重复下载期限</strong></td>
						<td class="c10"><?php echo$vip['week'];?>天</td>
						<td>在<b><?php echo$vip['week'];?>天</b>内打包下载不扣积分和美币</td>
					</tr>
				</table>
    </div>
			<?php include'footer.php';?>
			<div id="ti-meng"></div>
		</div>
    </div> <!-- end 最处层容器 -->
 <script src="../assets/js/fcdh.js"></script>
</body>

</html>
