<?php
include 'adminUser.php';
$upoint=$vrank=$uid=0;
$actived=$ip=$email=$exptime=$logdate=$fondtime=$uname='';
$res=array();
$offset=$result='';
$page=0;
$pagesize=15;

if(isset($_SESSION['loginuid'])){
	$uid=intval($_SESSION['loginuid']);
	$uname=$_SESSION['loginuser'];
	$sql="SELECT uid,vrank,fondtime,logdate,exptime,upoint,ucoin,actived,uname,email,ip FROM vipuser WHERE uid='$uid'";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($uname != $row['uname']){
		header('Location: ./logout.php');exit;
	}
	
	$page=intval(@$_GET['page']);
	if(!$page) $page=1;
	$offset=($page-1)*$pagesize;
	$temp=$pagesize+1;
	$sql="SELECT pindex,pmid,downtime FROM vipimg WHERE uid='$uid' limit $offset,$temp";
	$result=mysqli_query($mydb,$sql);


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
    <title><?php echo$uname;?> 图片列表 - 粉美人</title>
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
			<div id="main"><h3><a><i class="fa-bookmark"></i> 图片列表</a></h3>
<?php
$count=0;$i=$pagesize*($page-1);
while($res=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$i++;$count++;
	if($count>$pagesize)continue;
	$datetime = new DateTime('@'.$res['downtime']);
	$datetime->setTimeZone(new DateTimeZone('PRC'));
	$downtime=$datetime->format('Y-m-d H:m:s');

?>

<dl>
	<dd><?php echo$i;?>#<a href="../<?php echo$res['pindex'];?>p.html"target="_blank"><?php echo$res['pindex'];?></a> &nbsp; 下载时间: <b><?php echo$downtime;?></b></dd>
</dl>
              
                <?php
}
?>
 
 <nav id="pages">
                    <?php
if($page>1){
?>
                        <a href="?page=<?php echo $page-1;?>" class="btn btn-primary" id="prev">上一页</a>
                    <?php
}
if($count>$pagesize){
?>
                   
                        <a href="?page=<?php echo $page+1;?>" class="btn btn-primary" id="next">下一页</a>
                    <?php
}
?>
            </nav>
         
    </div>
           <?php include'footer.php';?>
			<div id="ti-meng"></div>
		</div>
    </div> <!-- end 最处层容器 -->
 <script src="../assets/js/fcdh.js"></script>
</body>

</html>
