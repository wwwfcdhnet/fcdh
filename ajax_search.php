<?php
	include 'sqlite_db.php';
	include 'function.php';
	header("Access-Control-Request-Method:GET,POST");
	header("Access-Control-Allow-Credentials:true");
	header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept, Authorization');

	$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
	header('Access-Control-Allow-Origin:'.$origin); // 允许单个域名跨域

	$resply=array(true,true,'','');
	$wd='';$pages=1;
	$i=4;
	if(isset($_POST['wd']) && !empty($_POST['wd'])){ //  从云端下载sqlite库中网址
		$arrstr=json_decode($_POST['wd'],true);
		$wd=mb_substr(filterTitle($arrstr['0']),0,16);
		$table=filterTitle($arrstr['1']);

	
	//	if($table!='tag'&&$table!='blog')$table='href';

		$page=intval($arrstr['2']);
		if($page>30)$page=30;// 最多30页
		if(!$page) $page=1;
		$pagesize=10;
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
			$sql="SELECT tindex as hindex,tname as hname,tdesc as htitle FROM tagso INNER JOIN tag on tag.tid=tagso.tid WHERE tagso.wid=$wid AND html>0 LIMIT $offset,$pagesize";
		}elseif($table=='blog'){ // 博客搜索
			$table='blog';
			$tableso='blogso'; 
			$sql="SELECT bindex as hindex,btitle as hname,bview as hview,btime as htime FROM blogso INNER JOIN blog on blog.bid=blogso.bid WHERE blogso.wid=$wid LIMIT $offset,$pagesize";
		}else{// 网站搜索
			$table='href';
			$tableso='hrefso'; 
			$sql="SELECT hindex,hname,hurl,htitle,hview,htime FROM hrefso INNER JOIN href on href.hid=hrefso.hid WHERE hrefso.wid=$wid LIMIT $offset,$pagesize";
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
				$resply[$i++]=$r['hurl'];
			}
			if($table!='blog'){
				$resply[$i++]=$r['htitle'];
			}
			if($table=='href' || $table=='blog'){
				$resply[$i++]=$r['htime'];
				$resply[$i++]=$r['hview'];
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
		if(empty($hindex)){// 增加访问次数
			if($hindex=='0'){ // blog增加访问次数
				$db->exec("update blog set bview=bview+1 WHERE bid=$hid");
				$res=$db->query("SELECT bview as hview FROM blog WHERE bid=$hid")->fetch();  
				$resply[3]=intval($res['hview']); // 获取访问次数
		
			}else{
				$db->exec("update href set hview=hview+1 WHERE hid=$hid");
				$res=$db->query("SELECT hindex,hview FROM href WHERE hid=$hid")->fetch(); 
				$resply[3]=intval($res['hview']); // 获取访问次数
		
				
				$hindex=$res['hindex']; // 如果存在则报告按钮为灰色
				$res=$db->query("SELECT htmlname FROM htmltn WHERE htmlname='$hindex' limit 1")->fetch();  
				if(!empty($res['htmlname'])){
					$resply[2]=true;
				}else{
					$resply[2]=false;
				}
			}
		}else{// 用户在报链接错误 
			if(!_CheckInput($hindex,'numchar')){
				$resply[0]=false;
				$resply[2]='非常字符';
			}else{
				$db->exec("INSERT INTO htmltn (htmlname,tn) VALUES('$hindex',$opt)");
			}
		}

	}else{
		$resply[0]=false;
	}
	echo json_encode($resply);

?>