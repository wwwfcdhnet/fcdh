<?php
header('Content-Type:text/html;charset=utf-8');  
include 'mysql_mydb.php';
include 'sqlite_db.php';
include 'functionOpen.php';
define('ADMIN',__DIR__);
session_start();
$encode=$isvip=1;
$umotion=$point=$coin=$upoint=$ucoin=$exptime=$vrank=$iid=$uid=0;
$logdate=$uname=$img=$msg='';
$nowtime=time();
$viparr=Array();
$_IMGDIR='https://photo.xiago.cn/';
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
$back=$id='';
if(!isset($_POST['encode'])){ // 浏览图片
	$encode=0;
	$msg='';
	$id=@$_GET['id'];
	$img=explode("-", $id);
	$pindex=intval($img['0']);
	$pmid=intval($img['1']);
	$year=intval($img['2']);
	$psuf=$img['3'];
	$img = $_IMGDIR.'thumb/'.$year.'/'.$pindex.'-'.$pmid.'-m-'.$psuf;// 文件的真实地址（支持url,不过不建议用url）
	$back=' <a href="'.$pindex.'p.html"class="c2">[返回]</a>';
	$msg='<b>非会员用户</b>不能查看原图 <a href="vip/login.php?ref=1"class="c2">[登录]</a> <a href="vip/register.php"class="c2">[注册]</a> <a href="vip/grade.php"class="c2">[会员权限]</a>'.$back;
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
				$upoint=999;
				$exptime=$nowtime+$viparr['day']*24*3600;
			}
			$money='';
			$vrankstr='';
		}
		$upuser=$vrankstr."logdate='".$logdate."',exptime=$exptime,".$money."ip='".$_SERVER['REMOTE_ADDR']."',";
		$upsql="UPDATE vipuser SET ".$upuser."upoint=$upoint,ucoin=$ucoin WHERE uid=$uid LIMIT 1";
		$result=mysqli_query($mydb,$upsql);
	}

	if($encode){
		$ref=$_SERVER['HTTP_REFERER'];
		$host=$_SERVER['HTTP_HOST'];
		$encodestr=authcode($_POST['encode'],'DECODE',$_KEY);
		$encodearr=unserialize($encodestr);
		if(empty($encodearr)){
			$resply['0']=false;
			$resply['2']='验证失败';
			$resply['3']=' 验证失败';
			$resply['4']=$resply['5']='';
			echo json_encode($resply);exit;
		}
		$vip	=	$encodearr['vip']; // 套图下载等级
		$url	=	$encodearr['url'];
		$link	=	$encodearr['link'];
		$link1	=	$encodearr['link1'];
		$code	=	$encodearr['code'];
		$code1	=	$encodearr['code1'];
		$pnum	=	intval($encodearr['point']); //套图的积分数
		$cnum	=	intval($encodearr['pcoin']); //套图的美币
		$pid	=	intval($encodearr['pid']); //套图的图片数量
		$pindex	=	intval($encodearr['pindex']);
		/*
		if($url != $ref && $host!='127.0.0.1' && !$_DEBUG){
			$resply['0']=false;
			$resply['2']='链接异常'.$host;
			$resply['3']=' 链接异常';
			$resply['4']=$resply['5']='';
			echo json_encode($resply);exit;
		}else */
		if($vrank<$vip){
			$resply['0']=false;
			$resply['2']='需 VIP'.$vip.' 等级会员。<a href="vip/upgrade.php"class="c2">[升级会员]</a>';
			$resply['3']=' 升级会员';
			$resply['4']=$resply['5']='';
			echo json_encode($resply);exit;
		}else{
			$resply['2']=$link;
			$resply['3']=$code;
			$resply['4']=$link1;
			$resply['5']=$code1;
		}
//		$year=intval($encodearr['year']);
		$sql="SELECT uid as iid,downtime FROM vipimg WHERE uid=$uid AND pindex=$pindex LIMIT 1";

		$db->exec("update photo set pdown=pdown+1 WHERE pindex=$pindex");
		$result=mysqli_query($mydb,$sql);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		//$dif=7*24*3600*$vrank;
		//if($vrank==0)$dif=24*3600;
		$downtime=intval($row['downtime']);
		$iid=intval($row['iid']);

		if(!$iid || $downtime < $nowtime){ // 之前未下载过该图片 或者 下载已过期
		//	$upoint	-=	$pnum; // 计算积分数量
		//	$ucoin	-=	$cnum; // 计算美币数量
			$upoint	=	$upoint; // 计算积分数量
			$ucoin	=	$ucoin; // 计算美币数量
			
			if($upoint>=0 && $ucoin>=0){ //积分和美币都够
				$nowtime+=$viparr['week']*24*3600;
				if($iid){ // 曾经下载过该套图
					$sql="UPDATE vipimg SET downtime = $nowtime WHERE uid=$uid AND pindex=$pindex";
					$resply['1']='<strong class="c5">过期※</strong>扣<b>'.$pnum.'积分</b>和<strong class="c10">'.$cnum.'美币</strong>，余<b>'.$upoint.'积分</b>和<strong class="c10">'.$ucoin.'美币</strong>，活力<b>+1</b>';
				}else{
					$sql="INSERT INTO vipimg(uid,pindex,downtime) VALUES ($uid,$pindex,$nowtime)";
					$resply['1']='<strong class="c5">首次※</strong>扣<b>'.$pnum.'积分</b>和<strong class="c10">'.$cnum.'美币</strong>，余<b>'.$upoint.'积分</b>和<strong class="c10">'.$ucoin.'美币</strong>，活力<b>+1</b>';
				}

				$result=false;
				if($pindex)$result=mysqli_query($mydb,$sql);
				if($result){ //成功扣除积分和美币，并增加活动数
					$sql="UPDATE vipuser SET ".$upuser."upoint=$upoint,ucoin=$ucoin,umotion=$umotion WHERE uid=$uid LIMIT 1";
					$result=mysqli_query($mydb,$sql);
					if($result){
						echo json_encode($resply);exit;
					}else{
						$resply['0']=false;
						$resply['2']='更新数据失败';
						$resply['3']=' 更新失败';
						$resply['4']=$resply['5']='';
						echo json_encode($resply);exit;
					}
				}else{
					$resply['0']=false;
					$resply['2']='插入vipimg 数据失败';
					$resply['3']=' 插入失败';
					$resply['4']=$resply['5']='';
					echo json_encode($resply);exit;
				}
			}else{ // 积分不够
				if($encode){
					$temp='';
					if($upoint<0){
						$upoint+=$pnum;
						$temp='<b>〖积分〗</b>';
						$resply['3']=' 积分不足';
					}
					if($ucoin<0){
						$ucoin+=$cnum;
						empty($temp)?$temp='<strong class="c10">〖美币〗</strong>':$temp='和<strong class="c10">〖美币〗</strong>';
						$resply['3']=' 美币不足';
					}
					$resply['0']=false;
					$resply['2']='会员 <a href="vip/" class="c2">['.$uname.']</a>，'.$temp.'不足，仅剩<span class="c5">'.$upoint.'积分</span>和<strong class="c10">'.$ucoin.'美币</strong>。<a href="vip/upgrade.php"class="c2">[升级会员]</a>';
					$resply['4']=$resply['5']='';
					echo json_encode($resply);exit;
				}
			}
		}else{ // 之前下载过该图片，且未过期
			$resply['1']='<strong class="c5">重复※</strong>扣<b>0积分</b>和<strong class="c10">0美币</strong>，余<b>'.$upoint.'积分</b>和<strong class="c10">'.$ucoin.'美币</strong>';
			echo json_encode($resply);exit;
		}
	}else{ // 会员查看原图
		if($nowtime>$exptime){
			$msg='会员<a href="vip/" class="c2">['.$uname.']</a>已经过期。 <a href="vip/upgrade.php">[会员升级]</a> <a href="vip/grade.html">[会员权限]</a>'.$back;
		}else{
			$phash=substr(md5($pindex.$_KEY.$pmid.$psuf),0, 8); //截取7个字符
			//$img= $_IMGDIR.'photo/'.$year.'/'.$pindex.'-'.$pmid.'-'.$phash.'-'.$psuf;// 文件的真实地址（支持url,不过不建议用url）
			$msg='<span class="c9"> VIP'.$vrank.'会员</span><a href="vip/"span class="c2">['.$uname.']</a> 今日剩<b>'.$upoint.'积分</b>和<strong class="c10">'.$ucoin.'美币</strong>'.$back;	
		}
		if(!empty($upsql)){
			$msg.='，<strong class="c10">美币+1枚</strong>';
		}
	}
	$img = getGrabImage($img,$id,1); 
}else{
	if($encode){
		$resply['0']=false;
		$resply['2']='非会员无权下载套图 &nbsp; <a href="vip/login.php?ref=1" class="c2">[登录]</a> &nbsp; <a href="vip/register.php"class="c5">[注册]</a>';
		$resply['3']=' 会员登录';
		echo json_encode($resply);exit;
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
<?php
if ($img):echo '<img src="'.$img.'">';
else:echo "false";
endif;
?>
</center>
</body>
</html>