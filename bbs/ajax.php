<?php
	include 'inc_config.php';
	include 'inc_function.php';
//	header('Access-Control-Allow-Origin:http://www.fcdh.net'); // 允许单个域名跨域
	header('Access-Control-Allow-Origin:*'); // 允许所有域名跨域


	
	$res=array(true,'上传成功!','');
	if(isset($_POST['uname']) && !empty($_POST['uname'])){ //  从云端下载收藏网址
		$uname=substr(trim($_POST['uname']),0,15);
		if(_CheckInput($uname,'numchar')){
			$sql='SELECT SQL_CACHE uid,logtime,urlnum,psw FROM user WHERE uname="'.$uname.'" LIMIT 1';
			$result=mysqli_query($db,$sql);		
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$uid=intval($row['uid']);
			$urlnum=$row['urlnum'];
			$count=$urlnum+2;
			$sql2='SELECT urlkey,nums,html FROM href WHERE uid="'.$uid.'" ORDER BY nums DESC';
			$result2=mysqli_query($db,$sql2);	
			$i=3;
			while($row2=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
				$sql3='UPDATE href SET nums='.($urlnum--).' WHERE uid='.$uid.' AND urlkey = "'.$row2['urlkey'].'" LIMIT 1';
				mysqli_query($db,$sql3);
				if($i>$count){
					$sql2='DELETE FROM href WHERE uid='.$uid.' AND urlkey = "'.$row2['urlkey'].'" LIMIT 1';
					mysqli_query($db,$sql2);
					continue;
				}
				$res[$i++]=$row2['html'];
			}
			$res['1']='成功!下载'.($i-3).'条记录';
			
		}else{
			$res['0']=false;
			$res['2']='失败!用户名错误';
		}
	}elseif(isset($_POST['upstr']) && !empty($_POST['upstr'])){ //  上传收藏网址到云端
		//"numbers@https://www.west.cn/@1@西部数码是基于云计算知名的互联网服务提供商,18年专业知名品牌@bg2@w2@西部@c1@西部数码@key",

		if(get_magic_quotes_gpc()){//如果get_magic_quotes_gpc()是打开的
			$str=stripslashes($_POST['upstr']);//将字符串进行处理
		}else{
			$str=$_POST['upstr'];
		}
		//echo$str;
		$arrstr=json_decode($str,true);
		$c = array("c1", "c2", "c3", "c4", "c5", "c6", "c7", "c8", "c9", "c0","null");	
		$o = array("1", "2","3","4","5");
		$uname=substr(trim($arrstr['0']),0,15);
		$upsw	=md5($arrstr['1'].$_KEY);
		
		//print_r($arrstr);
		//echo $arrstr;
		$uid=$oldtime=$count=0;
		if(_CheckInput($uname,'numchar')){			
			$sql='SELECT SQL_CACHE uid,logtime,psw,urlnum FROM user WHERE uname="'.$uname.'" LIMIT 1';			
			$result=mysqli_query($db,$sql);		
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$uid=intval($row['uid']);
			$count=$row['urlnum']; // 网址的条数
			$oldtime=$row['logtime'];
		}else{// 用户名不合法
			$res['0']=false;
			$res['2']='失败!用户名不合法';
		}
		$nowtime=time(); // 记录时间
		$dif=$nowtime-$oldtime;
		if($dif<0){
			$res['0']=false;
			$res['2']='失败!你的操作频繁';
			$oldtime=$nowtime;
		}elseif($dif>43200){
			$oldtime=$nowtime-43200;
		}
		$num=count($arrstr)-2;
		if(empty($uid) && $res['0']){//表示用户不存在，则自动生成用户
			$oldtime=$nowtime-43200;
			$sql='INSERT INTO user SET uname="'.$uname.'",found="'.date('Y-m-d').'",logtime='.$oldtime.',urlnum='.$_URLNUM.',psw="'.$upsw.'",ip="'.$_SERVER['REMOTE_ADDR'].'"';
			$count=$_URLNUM;
			if($num<=$_URLNUM){
				$result=mysqli_query($db,$sql);
				if (!$result) {
					$res['0']=false;
					$res['2']=mysqli_error($db);
				}else{	
					$res['1']='成功!新用户名:'.$uname;	
					$uid=mysqli_insert_id($db);
				}
			}
		}elseif($row['psw']!=$upsw){//用户存在，但密码错误
			$res['0']=false;
			$res['2']='失败!密码错误！';
			$oldtime+=36000;
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
			$result=mysqli_query($db,$sql);
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$hrefs=$row['hrefs'];
			if($hrefs>$count){
				$res['0']=false;
				$res['2']='失败!云端有'.$count.'条记录';
			}
		}

		if($res['0']){
			$sql='SELECT MAX(nums) as max FROM href WHERE uid='.$uid;
			$result=mysqli_query($db,$sql);
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$max=$row['max'];
			$sqlstr='';
			foreach ($arrstr as $key => $value){
				//if($count<0)break; // 非认证用户只能存15条记录
				if($key==0||$key==1)continue; // 跳过帐户和密码
				$arr	=explode('@',$value);
				$nums	=intval($arr['0'])+$max;
				$url	=$arr['1'];
				$opt	=intval($arr['2']);
				$desc	=filterTitle($arr['3'],false);
				$color	=$arr['4'];
				$title	=filterTitle($arr['5']);
				$key	=$arr['6'];
				
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
			if (!mysqli_query($db,$sql)) {
				$res['0']=false;
				$res['2']=mysqli_error($db);
			}else{
				$n=$count-$hrefs;
				if($n<0)$n=0;
				$res['1']='成功!剩余'.$n.'条记录';				
			}
		}

		$sql='UPDATE user SET logtime='.($oldtime+3600).' WHERE uid='.$uid.' LIMIT 1';
		if (!mysqli_query($db,$sql)) {
			$res['0']=false;
			$res['2']=mysqli_error($db);
		}
		
	}else{
		$res[0]=false;
		$res[2]='err 555';
	}
	echo json_encode($res);

?>