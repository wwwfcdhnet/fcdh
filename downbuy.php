<?php
header('Content-Type:text/html;charset=utf-8');  
include 'mysql_mydb.php';
include 'functionOpen.php';
define('ADMIN',__DIR__);
session_start();
$tn=$encode=$isvip=1;
$umotion=$point=$coin=$upoint=$ucoin=$exptime=$vrank=$iid=$uid=0;
$logdate=$uname=$str=$msg='';
$nowtime=time();
$viparr=Array();
if(!isset($_SESSION['loginuid']) || intval($_SESSION['loginuid'])<1 || !isset($_SESSION['loginuser'])){
	$isvip=0;
}else{ // 会员用户
	$uid=intval($_SESSION['loginuid']);
	$uname=$_SESSION['loginuser'];
	$sql="SELECT vrank,logdate,exptime,upoint,ucoin,umotion,uname FROM vipuser WHERE uid=$uid LIMIT 1";
	$result=mysqli_query($mydb,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$exptime=$row['exptime'];

	if($uname != $row['uname'] || $uid<1 || empty($uname)){$isvip=0;}

	$vrank=$row['vrank'];
	$logdate=$row['logdate'];
	$upoint=$row['upoint'];
	$ucoin=$row['ucoin'];
	$uname=$row['uname'];
	$umotion=$row['umotion']+1;
}
$id='';
if(!isset($_GET['encode'])){ // 浏览图片
	$encode=0;
}
$resply=array(true,'','','','','');
if($isvip){ // 会员用户
	$datetime = new DateTime('@'.$nowtime);
	$datetime->setTimeZone(new DateTimeZone('PRC'));
	$nowdate=$datetime->format('Y-m-d');

	$upsql=$upuser='';
	$viparr=viprank($vrank);
	if($nowdate!=$logdate){ //不是同一天
		$logdate=$nowdate;
		if($nowtime>$exptime){ 
			$vrankstr='vrank=0,';
			$money='money=5,';
			$upoint=1;
		}else{
			$upoint=$viparr['point'];
			if($vrank)++$ucoin; // 大于VIP0
			if($viparr['point']<0){
				$upoint=99;
				if($viparr['coin']<0)$ucoin=99;
			}
			$exptime+=$viparr['day']*24*3600;
			$money='';
			$vrankstr='';
		}
		$upuser=$vrankstr."logdate='".$logdate."',exptime=$exptime,".$money.",ip='".$_SERVER['REMOTE_ADDR']."',";
		$upsql="UPDATE vipuser SET ".$upuser."upoint=$upoint,ucoin=$ucoin WHERE uid=$uid LIMIT 1";
	}else{
		if($viparr['point']<0){
			$upoint=99;
			if($viparr['coin']<0)$ucoin=99;
		}
	}
	if($encode){
//		$ref=$_SERVER['HTTP_REFERER'];
		$host=$_SERVER['HTTP_HOST'];
		$encodestr=authcode($_POST['encode'],'DECODE',$_KEY);
		$encodearr=unserialize($encodestr);
		if(empty($encodearr)){
			$resply['0']=false;
			$resply['2']='验证失败';
			$resply['3']=$resply['4']=$resply['5']='';
		//	echo json_encode($resply);exit;
			$tn=0;
		}
		if($tn){
			$vip	=	$encodearr['vip']; // 套图下载等级
//			$url	=	$encodearr['url'];
			$link	=	$encodearr['link'];
			$link1	=	$encodearr['link1'];
			$code	=	$encodearr['code'];
			$code1	=	$encodearr['code1'];
			$pnum	=	intval($encodearr['point']); //套图的积分数
			$cnum	=	intval($encodearr['pcoin']); //套图的美币
			$pid	=	intval($encodearr['pid']); //套图的图片数量
			$pindex	=	intval($encodearr['pindex']);
			if($vrank<$vip && $vip>4){
				$resply['0']=false;
				$resply['2']='需 VIP'.$vip.' 等级会员。<a href="vip/upgrade.php"class="c2">[升级会员]</a>';
				$resply['3']=$resply['4']=$resply['5']='';
			//	echo json_encode($resply);
				$tn=0;
			}else{
				$resply['2']=$link;
				$resply['3']=$code;
				$resply['4']=$link1;
				$resply['5']=$code1;
			}
	//		$year=intval($encodearr['year']);
			if($tn){
				$sql="SELECT iid,uid,downtime FROM vipimg WHERE uid=$uid AND pindex=$pindex ORDER BY downtime DESC LIMIT 1";

				
				$result=mysqli_query($mydb,$sql);
				$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
				$dif=7*24*3600*$vrank;
				if($vrank==0)$dif=24*3600;
				$downtime=intval($row['downtime']);
				$iid=intval($row['iid']);
			}
		}
		if($tn && (!$iid || ($downtime+$dif) < $nowtime)){ // 之前未下载过该图片 或者 下载已过期
			$upoint	-=	$pnum; // 计算积分数量
			$ucoin	-=	$cnum; // 计算美币数量
			
			if($upoint>=0 && $ucoin>=0){ //积分和美币都够
				if($iid){ // 曾经下载过该套图
					$sql="UPDATE vipimg SET downtime = $nowtime WHERE iid=$iid";
					$resply['1']='过期获取，扣<b>'.$pnum.'积分</b>和<strong class="c10">'.$cnum.'美币</strong>，余<b>'.$upoint.'积分</b>和<strong class="c10">'.$ucoin.'美币</strong>';
				}else{
					$sql="INSERT INTO vipimg(uid,pindex,downtime) VALUES ($uid,$pindex,$nowtime)";
					$resply['1']='扣<b>'.$pnum.'积分</b>和<strong class="c10">'.$cnum.'美币</strong>，余<b>'.$upoint.'积分</b>和<strong class="c10">'.$ucoin.'美币</strong>';
				}

				$result=false;
				if($pindex)$result=mysqli_query($mydb,$sql);
				if($result){ //成功扣除积分和美币，并增加活动数
					$sql="UPDATE vipuser SET ".$upuser."upoint=$upoint,ucoin=$ucoin,umotion=$umotion WHERE uid=$uid LIMIT 1";
					$result=mysqli_query($mydb,$sql);
					if($result){
					//	echo json_encode($resply);exit;
					}
				}else{
					$resply['0']=false;
					$resply['2']='插入vipimg 数据失败';
					$resply['3']=$resply['4']=$resply['5']='';
				//	echo json_encode($resply);exit;
				}
			}else{ // 积分不够
				if($encode){
					$temp='';
					if($upoint<0){
						$upoint+=$pnum;
						$temp='<b>〖积分〗</b>';
					}
					if($ucoin<0){
						$ucoin+=$cnum;
						empty($temp)?$temp='<strong class="c10">〖美币〗</strong>':$temp='和<strong class="c10">〖美币〗</strong>';
					}
					$resply['0']=false;
					$resply['2']='会员 <a href="vip/" class="c2">['.$uname.']</a>，'.$temp.'不足，仅剩 <span class="c5">'.$upoint.'积分</span>和<strong class="c10">'.$ucoin.'美币</strong>。<a href="vip/upgrade.php"class="c2">[升级会员]</a>';
				//	echo json_encode($resply);exit;
				}
			}
		}else{ // 之前下载过该图片，且未过期
			$resply['1']='重复获取，扣<b>0积分</b>和<strong class="c10">0美币</strong>，余<b>'.$upoint.'积分</b>和<strong class="c10">'.$ucoin.'美币</strong>';
		//	echo json_encode($resply);exit;
		}
	}
}else{
	if($encode){
		$resply['0']=false;
		$resply['2']='非会员无权下载套图 &nbsp; <a href="vip/login.php" class="c2"target="_blank">[登录]</a> &nbsp; <a href="vip/register.php"class="c5"target="_blank">[注册]</a>';
	//	echo json_encode($resply);exit;
	}
}

?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="fcdh.net" />
    <title><?php echo$img;?>图片下载页面 - 粉美人</title>
    <link rel="shortcut icon" href="./assets/images/favicon.png">
	<style>
		img{max-width:100%;}
		body{color:gray}
		b{color:#ff5675}
		h3{margin:0;padding:0}
		strong,.c9{color:#ff1244} /* 红色 */
		.c2{color:#009f5d} /* 深绿 */
		.c10{color:#ff8345} /* 橙色 */
		a{text-decoration:none;}
		a:hover{color:#007cdc}
	</style>
</head>
<body>
<center>
<h3><?php echo$msg;?></h3>
<?php echo$resply['1'];?>
</center>
</body>
</html>