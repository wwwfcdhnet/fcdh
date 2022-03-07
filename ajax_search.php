<?php
	include 'sqlite_db.php';
	include 'function.php';
	include 'functionOpen.php';
	include 'config.php';
	header("Access-Control-Request-Method:GET,POST");
	header("Access-Control-Allow-Credentials:true");
	header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept, Authorization');

	$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
	header('Access-Control-Allow-Origin:'.$origin); // 允许单个域名跨域

	$resply=array(true,true,'','','','','','');
	$wd='';$pages=1;
	$i=4;
	if(isset($_POST['wd']) && !empty($_POST['wd'])){ //  从云端下载sqlite库中网址
		$arrstr=json_decode($_POST['wd'],true);
		$wd=strtolower(mb_substr(filterTitle($arrstr['0']),0,16));
		$table=filterTitle($arrstr['1']);

	
	//	if($table!='tag'&&$table!='blog')$table='href';

		$page=intval($arrstr['2']);
		if($page>30)$page=30;// 最多30页
		if(!$page) $page=1;
		$pagesize=10;
		if($table=='photo')$pagesize=20;
		$offset=($page-1)*$pagesize;
		++$pagesize;

		$res=$db->query("SELECT wid FROM tagword WHERE word='$wd'")->fetch();  
		$wid=intval($res[0]);
		if(!$wid){
			$resply['0']=$resply['1']=false; 
			$resply['2']='N/A';
			echo json_encode($resply);
			exit;
		}
		if($table=='tag'){ // 标签搜索
			$tableso='tagso';
			$sql="SELECT tindex as hindex,tname as hname,seotitle as htitle FROM tagso INNER JOIN tag on tag.tid=tagso.tid WHERE tagso.wid=$wid AND html>0 LIMIT $offset,$pagesize";
		}elseif($table=='blog'){ // 博客搜索
			$table='blog';
			$tableso='blogso'; 
			$sql="SELECT bindex as hindex,btitle as hname,bview as hview,btime as htime FROM blogso INNER JOIN blog on blog.bid=blogso.bid WHERE blogso.wid=$wid LIMIT $offset,$pagesize";
		}elseif($table=='photo'){ // 套图搜索
			$table='photo';
			$tableso='photoso'; 
			$sql="SELECT pindex as hindex,ptitle as hname,pdate as hview,year as htime,pthumb,pvip,pcolor FROM photoso INNER JOIN photo on photo.pid=photoso.pid WHERE photoso.wid=$wid LIMIT $offset,$pagesize";
		}else{// 网站搜索
			$table='href';
			$tableso='hrefso'; 
			$sql="SELECT hindex,hname,hurl,htitle,hstate,hview,htime,hcolor,hstrong,hico FROM hrefso INNER JOIN href on href.hid=hrefso.hid WHERE hrefso.wid=$wid LIMIT $offset,$pagesize";
		}
		//$res=$db->query("SELECT count(rowid) FROM $tableso WHERE wid=$wid")->fetch();  
		//$sql="SELECT hindex,hname,hurl,htitle,hview,htime FROM href WHERE hid IN (SELECT hid FROM hrefso WHERE wid=$wid LIMIT $offset,$pagesize)";
		

		//SELECT hindex,hname,hurl,htitle,hview,htime FROM href INNER JOIN hrefso on href.hid=hrefso.hid and wid=6065
		//EXPLAIN QUERY PLAN SELECT hindex,hname,hurl,htitle,hview,htime FROM href WHERE hid IN (SELECT hid FROM hrefso WHERE wid=6065)
		//$total=intval($res[0]);
		//$pages=ceil($total/(--$pagesize));
		$res=$db->query($sql)->fetchAll();
		$resply['3']=$table;
		$count=0;
		foreach($res as $r){
			++$count;
			if(--$pagesize<1)continue;
			$resply[$i++]=$r['hname'];
			$resply[$i++]=$r['hindex'];

			if($table=='href'){
				$urlarr=explode("{|}", $r['hurl']);
				$r['hurl']=$urlarr['0'];
				if(isset($urlarr['1'])){
					$r['hurl']=$urlarr['1'];
				}
				$resply[$i++]=base64_encode($r['hurl']);
				$resply[$i++]=$r['hstate'];
			}

			if($table!='blog' && $table!='photo'){
				$resply[$i++]=$r['htitle'];
			}
			if($table!='tag'){
				$resply[$i++]=$r['htime'];
				$resply[$i++]=$r['hview'];
				if($table=='photo'){
					$resply[$i-1]=date("Y-m-d",$resply[$i-1]);
					$resply[$i++]=$r['pthumb'];
					$resply[$i++]=$r['pvip'];
					$resply[$i++]=$r['pcolor'];
					$resply[$i++]=$_imgaddr;
				}
			}
		}
		$resply['2']=$page;
		if($pagesize>0){// 表示没有下一页
			$resply['1']=false; //
		}
		if($count==0){// 没有数据
			$resply['0']=$resply['1']=false; 
			$resply['2']='N/A';
		}
	}elseif(isset($_POST['hid']) && !empty($_POST['hid'])){ // 增加访问次数
		/*
		if(get_magic_quotes_gpc()){//如果get_magic_quotes_gpc()是打开的
			$str=stripslashes($_POST['hid']);//将字符串进行处理
		}else{
			$str=$_POST['hid'];
		}
		//echo$str;
		$arrstr=json_decode($str,true);
		*/

		$db->exec("PRAGMA synchronous=OFF");
		$hid=intval($_POST['hid']);
		$hindex=$_POST['hindex'];
		$opt=intval($_POST['opt']);
		if($hindex=='0' || $hindex=='3' || $hindex==''){// 增加访问次数
			if($hindex=='0'){ // blog增加访问次数
				$db->exec("update blog set bview=bview+1 WHERE bid=$hid");
				$res=$db->query("SELECT bindex as hindex,bview as hview FROM blog WHERE bid=$hid")->fetch();  
				$resply[3]=$res['hview']; // 获取访问次数
		
			}elseif($hindex=='3'){ //  套图增加访问次数
				$db->exec("update photo set pview=pview+1 WHERE pindex=$hid");
				$res=$db->query("SELECT pindex as hindex,pdown,pview as hview FROM photo WHERE pindex=$hid")->fetch();  
				$resply[3]=$res['hview']; // 获取访问次数
				$resply[6]=$res['pdown']; // 获取访问次数
				$pindex=$res['hindex'];

				$resply[4]=0;
				$pindex=$hid;
				$vip=$_POST['vip'];
				session_start(); 
				if(isset($_SESSION['loginuid']) && intval($_SESSION['loginuid'])>0 && isset($_SESSION['loginuser']) && isset($_SESSION['loginrank'])){
					$vrank=intval($_SESSION['loginrank']);
					$resply[5]='[会员'.$_SESSION['loginuser'].'等级为VIP'.$vrank.']';
					if($vrank<$vip){ //VIP等级不够
						 $resply[4]=4;
					}else{
						include'mysql_mydb.php';
						$uid=$_SESSION['loginuid'];
						$nowtime=time();
						$sql="SELECT SQL_CACHE uid as iid,downtime FROM vipimg WHERE uid=$uid AND pindex=$pindex LIMIT 1";
						$result=mysqli_query($mydb,$sql);		
						$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
						$downtime=$row['downtime'];
						$iid=intval($row['iid']);
						$resply[4]=1; //首次下载
						if($iid>0){ // 之前下载过
							if($nowtime<$downtime){ //下载未过期
								$resply[4]=2;
							}else{ //下载过期
								$resply[4]=3;
							}
						}
					}
				}
			}else{
				$res=$db->query("SELECT hindex,hurl,hstate,montime,hview,preview,nowview FROM href WHERE hid=$hid")->fetch(); 
				$hview=intval($res['hview']); // 获取访问次数
				$preview=intval($res['preview']); // 获取访问次数
				$nowview=intval($res['nowview']); // 获取访问次数
				$resply['1']=intval($res['hstate']); // 获取链接状态
				$urlarr=explode("{|}", $res['hurl']);
				$resply['6']=$urlarr['0'];
				$dingxurl=get_url_index($urlarr['0']);
				if(isset($urlarr['1'])){
					$resply['6']=$urlarr['1'];
					$dingxurl=get_url_index($urlarr['1']);
				}
				if($res['hindex']==$dingxurl && $resply['1']==8){
					$resply['1']=1;
				}

				++$hview;
				$montime=date('Ym',time());
				if($montime!=$res['montime']){
					$db->exec("update href set montime=$montime,hview=$hview,preview=$nowview,nowview=1 WHERE hid=$hid");
					$preview=$nowview;
					$nowview=1;
				}else{
					++$nowview;
					$db->exec("update href set hview=$hview,nowview=$nowview WHERE hid=$hid");
				}
				$resply['3']=$hview;
				$resply['4']=$preview;
				$resply['5']=$nowview;
			}

			$hindex=$res['hindex']; // 如果存在则报告按钮为灰色
			$res=$db->query("SELECT htmlname FROM htmltn WHERE htmlname='$hindex' limit 1")->fetch();  
			if(!empty($res['htmlname'])){
				$resply[2]=true;
			}else{
				$resply[2]=false;
			}
		}else{// 用户在报链接错误 
			if(!_CheckInput($hindex,'numchar')){
				$resply[0]=false;
				$resply[2]='非常字符';
			}else{
				$db->exec("INSERT INTO htmltn (htmlname,tn) VALUES('$hindex',$opt)");
			}
			//	echo"INSERT INTO htmltn (htmlname,tn) VALUES('$hindex',$opt)";
		}

	}elseif(isset($_POST['pindex']) && !empty($_POST['pindex'])){ // 加载图片
		$pindex	= intval($_POST['pindex']);
		$pshow	= intval($_POST['pshow']);
		$year	= intval($_POST['year']);
		$start	= intval($_POST['start']);
		$limit='';
		if(!empty($start)){
			$limit=' limit '.$start.','.($pshow+1);
		}
		$photo=$db->query("SELECT pmid,psize,pwidth,pheight,psuf,pdesc FROM photoimg WHERE pindex=$pindex$limit")->fetchAll(); 
		$i=0;
		$hdesc=$size='';
		foreach($photo as $img){
			$size=formatBytes($img['psize']);
			if($i++ < $pshow){
				$hdesc .= '<p><img src="'.$_imgaddr.'thumb/'.$year.'/'.$pindex.'-'.$img['pmid'].'-m-'.$img['psuf'].'">〖'.$img['pdesc'].' &nbsp;<a href="downpic.php?id='.$pindex.'-'.$img['pmid'].'-'.$year.'-'.$img['psuf'].'"target="_blank">[查看原图]</a> &nbsp;原图像素：'.$img['pwidth'].'*'.$img['pheight'].'，原图大小：'.$size.'〗</p>';
			}
		}
		if($i<($pshow+1)){// 表示没有新的图片了
			$resply[1]='over';
		}else{
			$resply[1]=$start+$pshow;
		}
		$resply[0]=true;
		$resply[2]=$hdesc;
	}else{
		$resply[0]=false;
	}
	echo json_encode($resply);

?>