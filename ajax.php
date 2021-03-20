<?php
	include 'mysql_mydb.php';
	include 'function.php';
	header("Access-Control-Request-Method:GET,POST");
	header("Access-Control-Allow-Credentials:true");
	header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept, Authorization');
/* 跨域使用
	$allowOrigin = array(
		'http://www.fcdh.net',
		'https://www.fcdh.net',
		'http://127.0.0.1'
	);

	if (in_array($origin, $allowOrigin)) {
		header("Access-Control-Allow-Origin:".$origin);
	}
*/

	$res=array(true,'上传成功!','');
	if(isset($_POST['uname']) && !empty($_POST['uname'])){ //  从云端下载收藏网址
		$uname=substr(filterTitle(trim($_POST['uname'])),0,15);
		$all=intval($_POST['all']);
		if(_CheckInput($uname,'numchar')){
			$sql='SELECT uid,logtime,urlnum,maxnum,psw FROM user WHERE uname="'.$uname.'" LIMIT 1';
			$result=mysqli_query($mydb,$sql);		
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$uid=intval($row['uid']);
			$maxnum=$row['maxnum'];
			$urlnum=$row['urlnum'];
			$count=$urlnum+2;
			$sql2='SELECT urlkey,nums,html FROM href WHERE uid="'.$uid.'" ORDER BY nums DESC';
			$result2=mysqli_query($mydb,$sql2);	
			$i=2;
			//echo$all,'xxxxxxxxxxxxxx';
			while($row2=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
				//$sql3='UPDATE href SET nums='.($urlnum--).' WHERE uid='.$uid.' AND urlkey = "'.$row2['urlkey'].'" LIMIT 1';
				//mysqli_query($mydb,$sql3);
				if($i>$count){
					$sql2='DELETE FROM href WHERE uid='.$uid.' AND urlkey = "'.$row2['urlkey'].'" LIMIT 1';
					mysqli_query($mydb,$sql2);
					continue;
				}
				$i++;
				if($row2['nums']<$maxnum && !$all)continue;
				$res[$i]=stripslashes($row2['html']); //删除反斜杠：
			}
			$res['1']='成功!下载'.($i-3).'条记录';
			
		}else{
			$res['0']=false;
			$res['2']='失败!用户名错误';
		}
	}elseif(isset($_POST['upstr']) && !empty($_POST['upstr'])){ //  上传收藏网址到云端
		//"numbers@https://www.west.cn/@1@西部数码是基于云计算知名的互联网服务提供商,18年专业知名品牌@bg2@w2@西部@c1@西部数码@key",

		if(get_magic_quotes_gpc()){//如果get_magic_quotes_gpc()是打开的
			$str=stripslashes($_POST['upstr']);//将字符串进行处理 删除反斜杠：
		}else{
			$str=$_POST['upstr'];
		}
		//echo$str;
		$arrstr=json_decode($str,true);
		$c = array("c1", "c2", "c3", "c4", "c5", "c6", "c7", "c8", "c9", "c0","null");	
		$o = array("1", "2","3","4","5");
		$uname	=	substr(filterTitle(trim($arrstr['0'])),0,15);
		$upsw	=	md5($arrstr['1'].$_KEY);
		
		//print_r($arrstr);
		//echo $arrstr;
		$uid=$oldtime=$count=0;
		if(_CheckInput($uname,'numchar')){			
			$sql='SELECT uid,logtime,psw,urlnum FROM user WHERE uname="'.$uname.'" LIMIT 1';			
			$result=mysqli_query($mydb,$sql);		
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$uid=intval($row['uid']);
			$count=$row['urlnum']; // 网址的条数
			$oldtime=$row['logtime'];
		}else{// 用户名不合法
			$res['0']=false;
			$res['2']='失败!用户名不合法';
		}
		$nowtime=time(); // 记录当前时间
		$dif=$nowtime-$oldtime;
		if($dif<0){
			$dif=0-$dif;
			$min=ceil($dif/60);
			$res['0']=false;
			$res['1']='forbid';
			$res['2']='过'.$min.'分后再操作!';
		}elseif($dif>72000){
			$oldtime=$nowtime-72000;
		}
		if(!$res['0']){
			echo json_encode($res);
			exit;
		}
		$num=count($arrstr)-2;
		if(empty($uid) && $res['0']){//表示用户不存在，则自动生成用户			
			// 一个客户端口只能生成一个用户，
			//session_start();
			if(!isset($_SESSION['have'])){
				$_SESSION['have']=true;
			}else{
				$res['0']=false;
				$res['2']='你在重复注册账号!';
				echo json_encode($res);
				exit;
			}

			$sql='INSERT INTO user SET uname="'.$uname.'",found="'.date('Y-m-d').'",logtime='.($nowtime-72000).',urlnum='.$_URLNUM.',psw="'.$upsw.'",ip="'.$_SERVER['REMOTE_ADDR'].'"';
			$count=$_URLNUM;
			if($num<=$_URLNUM){
				$result=mysqli_query($mydb,$sql);
				if (!$result) {
					$res['0']=false;
					$res['2']=mysqli_error($mydb);
				}else{	
					$res['1']='成功!新用户名:'.$uname;	
					$uid=mysqli_insert_id($mydb);
				}
			}

		}elseif($row['psw']!=$upsw){//用户存在，但密码错误
			$res['0']=false;
			$res['2']='失败!密码错误！';
		}
		if($count==0){			
			$res['0']=false;
			$res['2']='失败!用户名禁用';
		}
		if($num>$count){
			$res['0']=false;
			$res['2']='失败!多了'.($num-$count).'条记录';
		}else if($num<1){
			$res['0']=false;
			$res['2']='失败!无可用记录';
		}else{			
			$sql='SELECT count(uid) as hrefs FROM href WHERE uid='.$uid;
			$result=mysqli_query($mydb,$sql);
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$hrefs=$row['hrefs'];
			if($hrefs>(10+$count)){
				$res['0']=false;
				$res['2']='失败!云端有'.$count.'条记录';
			}
		}
		$max=0;
		if($res['0']){
			$sql='SELECT MAX(nums) as max FROM href WHERE uid='.$uid;
			$result=mysqli_query($mydb,$sql);
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$max=$row['max'];
			$sqlstr='';
			//print_r($arrstr);
			foreach ($arrstr as $key => $value){
				//if($count<0)break; // 非认证用户只能存15条记录
				if($key==0||$key==1)continue; // 跳过帐户和密码

				$arr	=explode('@',$value);

				$nums	=intval(@$arr['0'])+$max;
				$url	=@$arr['1'];
				$opt	=intval(@$arr['2']);
				$desc	=filterTitle(@$arr['3'],false);
				$color	=@$arr['4'];
				$title	=filterTitle(@$arr['5']);
				$key	=@$arr['6'];
				if($nums>20000){$nums-=20000;}
				if(!in_array($opt,$o)){$opt=1;}
				if(!in_array($color,$c)){$color='null';}

				if(sizeof($arr)>7 || !_CheckInput($url,'url') || !_CheckInput($key,'numchar')){
					continue;
				}

				$html=$url.'@'.$opt.'@'.$desc.'@'.$color.'@'.$title;

				$sqlstr.='('.$uid.',"'.$key.'",'.$nums.',"'.$html.'"),';
		  }
			$sqstr=rtrim($sqlstr,',');
			$sql='INSERT INTO href (uid,urlkey,nums,html) VALUES '.$sqstr.' ON DUPLICATE KEY UPDATE nums=values(nums),html=values(html)';
			//echo$sql;
			if (!mysqli_query($mydb,$sql)) {
				$res['0']=false;
				$res['2']=mysqli_error($mydb);
			}else{
				$n=$count-$hrefs;
				if($n<0)$n=0;
				$res['1']='成功!剩余'.$n.'条记录';				
			}
		}
		$tempmax='';
		if($max!=0){
			$tempmax=',maxnum='.$max;
		}

		$sql='UPDATE user SET logtime='.($oldtime+3600).$tempmax.' WHERE uid='.$uid.' LIMIT 1';
		if (!mysqli_query($mydb,$sql)) {
			$res['0']=false;
			$res['2']=mysqli_error($mydb);
		}
		
	}elseif(isset($_POST['oldname']) && !empty($_POST['oldname'])){ // 修改用户信息资料	
		session_start();
		$res['1']='修改成功';
		$scode=@strtoupper($_POST['scode']);
		if($scode!=$_SESSION['scode']){ // 验证码不正确
			$res['0']=false;
			$res['1']='验证码错误';
			$res['2']='scode';
			echo json_encode($res);
			exit;
		}

		$oldname=substr(trim(filterTitle($_POST['oldname'])),0,15);		
		$newname=substr(trim(filterTitle($_POST['newname'])),0,15);	
		$email=substr(trim($_POST['email']),0,32);	
		$qq=intval($_POST['qq']);
		$phone=intval($_POST['phone']);
		$newpsw=md5($_POST['newpsw'].$_KEY);
		$oldpsw=md5($_POST['oldpsw'].$_KEY);
		if($_POST['newpsw']=='')$newpsw='';
		if(!_CheckInput($email,'email') && !empty($email)){
			$res['0']=false;
			$res['1']='电子邮箱格式不正确';
			$res['2']='errs';
			echo json_encode($res);
			exit;
		}


		if(!empty($oldname)){			
			$sql='SELECT uid,logtime,psw,email,utel,uqq FROM user WHERE uname="'.$oldname.'" LIMIT 1';	
			$result=mysqli_query($mydb,$sql);		
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$uid=intval($row['uid']);
			$oldtime=$row['logtime'];
			$nowtime=time(); // 记录时间
			$dif=$nowtime-$oldtime;
			if($dif<0){
				$dif=0-$dif;
				$min=ceil($dif/60);
				$res['0']=false;
				$res['1']='过'.$min.'分后再操作!';
				$res['2']='forbid';
				echo json_encode($res);
				exit;
			}else{
				if($dif>36000){
					$oldtime=$nowtime-36000;
				}
			}

			if($uid){// 用户名存在
				if($oldpsw==$row['psw']){// 用户名和密码正确
					$i=0;
					$sqlstr='';
					if(!empty($newname)){
						$i++;
						$sqlstr.='uname="'.$newname.'",';
					}
					if(!empty($email)){
						$i++;
						$sqlstr.='email="'.$email.'",';
					}
					if(!empty($phone)){
						$i++;
						$sqlstr.='utel='.$phone.',';
					}
					if(!empty($qq)){
						$i++;
						$sqlstr.='uqq='.$qq.',';
					}
					if(!empty($newpsw)){
						$i++;
						$sqlstr.='psw="'.$newpsw.'",';
					}
					$sqlstr=trim($sqlstr,',');
		
					if($i){
						$sql='UPDATE user SET '.$sqlstr.' WHERE uid='.$uid.' LIMIT 1';

						if (!mysqli_query($mydb,$sql)) {
							$res['0']=false;
							$res['1']=mysqli_error($mydb);
							$res['2']='errs';
						}
					}
					empty($email)?$res['3']=$row['email']:$res['3']=$email;
					empty($phone)?$res['4']=$row['utel']:$res['4']=$phone;
					empty($qq)?$res['5']=$row['uqq']:$res['5']=$qq;
						
				}else{// 用户名正确，密码不正确，
					$i=0;

					//如果email,utel,uqq填对两项，则可以单独重置密码
					if($row['email']==$email){
						$i++;
					}
					if($row['utel']==$phone){
						$i++;
					}
					if($row['uqq']==$qq){
						$i++;
					}
						
					if(empty($row['email']) || empty($row['utel']) || empty($row['uqq'])){ // 如果没有完善资料
						$res['0']=false;
						$res['1']='QQ,email和电话未完善';
						$res['2']='errs';
					}else{
						if($i>1 && !empty($newpsw)){ //email,utel,uqq填对两项
							$sql='UPDATE user SET psw="'.$newpsw.'" WHERE uid='.$uid.' LIMIT 1';
							if (!mysqli_query($mydb,$sql)) {
								$res['0']=false;
								$res['1']=mysqli_error($mydb);
								$res['2']='errs';
							}else{
								$res['1']='密码修改成功';
							}
						}else{//email,utel,uqq 没有完全填对 输出提示
							$end=strpos($row['email'],'@')-2;
							$res['0']=false; 
							$res['1']='QQ,email和电话提示';
							$res['2']='pswtip';
							$res['3']=substr_replace($row['email'],'********',1,$end);
							$res['4']=substr_replace($row['utel'],'********',1,-2);
							$res['5']=substr_replace($row['uqq'],'********',1,-2);
						}
					}
					$sql='UPDATE user SET logtime='.($oldtime+3600).' WHERE uid='.$uid.' LIMIT 1';
					if (!mysqli_query($mydb,$sql)) {
						$res['0']=false;
						$res['1']=mysqli_error($mydb);
						$res['2']='errs';
					}
				}

			}else{
				$res['0']=false;
				$res['1']='失败!用户名不存在';
				$res['2']='errs';
			}
		}else{// 用户名不合法
			$res['0']=false;
			$res['1']='失败!用户名不合法';
			$res['2']='errs';
		}

		

	}else{
		$res[0]=false;
		$res[1]='未知错误';
		$res[2]='errs';
	}
	echo json_encode($res);

?>