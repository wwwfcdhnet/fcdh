<?php
include 'adminUser.php';
$umotion=$ucoin=$newrank=$vrank=0;
$msg='';
$JF=10;
$HL=10;
if(isset($_SESSION['loginuid'])){
	$uid=intval($_SESSION['loginuid']);
	$uname=$_SESSION['loginuser'];
	$sql="SELECT uid,vrank,fondtime,exptime,upoint,ucoin,money,actived,umotion,uname,email,codestr FROM vipuser WHERE uid=$uid LIMIT 1";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($uname != $row['uname']){header('Location: ./logout.php');exit;}
	
	$money=$row['money'];
	$ucoin=$row['ucoin'];
	$upoint=$row['upoint'];
	$umotion=$row['umotion'];

	if(isset($_POST['coin'])){
		$coin=intval($_POST['coin']);
		if($coin>0 && $coin<1000){
			$ucoin-=$coin;
			$upoint+=$coin*$JF;
			if($ucoin>=0){
				$sql="UPDATE vipuser SET upoint=$upoint,ucoin=$ucoin WHERE uname='$uname' LIMIT 1";
				$result=mysqli_query($mydb,$sql);
				if(!$result){ // 升级失败
					$msg='<b>兑换失败!</b>';
				}else{ //升级成功
					$msg='<strong class="c2">成功兑换!</strong>';
				}
			}else{
				$ucoin+=$coin;
				$upoint-=$coin*$JF;
				$msg='<b>美币不够!</b>';
			}
		}else{
			$msg='<b>请输入0-999的整数!</b>';
		}
	}elseif(isset($_POST['motion'])){
		$coin=intval($_POST['motion']);
		$motion=$coin*$HL;
		if($motion>0 && $motion<1001){
			$umotion-=$motion;
			$ucoin+=$coin;
			if($umotion>=0){
				$sql="UPDATE vipuser SET umotion=$umotion,ucoin=$ucoin WHERE uname='$uname' LIMIT 1";
				$result=mysqli_query($mydb,$sql);
				if(!$result){ // 升级失败
					$str='<b>兑换失败!</b>';
				}else{ //升级成功
					$str='<strong class="c2">成功兑换!</strong>';
				}
			}else{
				$ucoin-=$coin;
				$umotion+=$motion;
				$str='<b>活力不够!</b>';
			}
		}else{
			$str='<b>请输入1-100的正整数!</b>';
		}
	}
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
    <title>美币兑换积分 - 粉美人</title>
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
				<h3><a><i class="fa-bookmark"></i>美币兑换积分</a></h3>
			<form method="post" action="">
			
			<p>
				<label>当前有<strong class="c10"><?php echo$ucoin;?>美币</strong>和<b><?php echo$upoint;?>积分</b></label>
			</p>
			<p>
				<label>（<strong class="c10">1美币</strong>可以兑换<b><?php echo$JF;?>积分</b>）</label>
			</p>
			<p><label for="coin">美币<strong>×1</strong>:</label><br/>
		<input type="number" class="form-control" name="coin" id="coin"maxlength="2"value=""autocomplete="off"placeholder="美&nbsp; 币" required> </p>
				<button type="submit" class="btn btn-primary btn-block">兑换积分</button> <?php echo$msg;?>
			<p><b>注：</b><strong class="c10">1美币</strong>可以兑换<b><?php echo$JF;?>积分</b>，<strong>所兑换的积分只能当天使用</strong></p>
			</form> 

			<h3><a><i class="fa-bookmark"></i>活力兑换美币</a></h3>

			<form method="post" action="">
			<p>
				<label>当前有<b><?php echo$umotion;?>活力</b>和<strong class="c10"><?php echo$ucoin;?>美币</strong></label>
			</p>
			<p>
				<label>（<b><?php echo$HL;?>活力</b>可以兑换<strong class="c10">1美币</strong>）</label>
			</p>
			<p><label for="motion">活力<strong>×<?php echo$HL;?></strong>:</label><br/>
		<input type="number" class="form-control" name="motion" id="motion"maxlength="2"value=""autocomplete="off"placeholder="活&nbsp; 力" required><b>×<?php echo$HL;?></b></p>
				<button type="submit" class="btn btn-primary btn-block">兑换美币</button> <?php echo$str;?>
				<p><b>注：</b><span>输入框里的数值<b>自动乘以<?php echo$HL;?></b><strong>（×<?php echo$HL;?>）</strong></span></p>
			</form> 
           <?php include'footer.php';?>
			<div id="ti-meng"></div>
		</div>
    </div> <!-- end 最处层容器 -->
 <script src="../assets/js/fcdh.js"></script>
</body>
</html>
