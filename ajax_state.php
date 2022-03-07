<?php
	ignore_user_abort(true); 
	set_time_limit(18); 
	include 'sqlite_db.php';
	header("Access-Control-Request-Method:GET,POST");
	header("Access-Control-Allow-Credentials:true");
	header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept, Authorization');

	$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
	header('Access-Control-Allow-Origin:'.$origin); // 允许单个域名跨域
	$curtime=time()-1612345678;
	$resply=array(true,true,'','');
	$wd='';$pages=1;
	$i=4;
	if(isset($_POST['hid']) && !empty($_POST['hid'])){
		$db->exec("PRAGMA synchronous=OFF"); //SQLite所有的同步操作都会被忽略
		$hid=intval($_POST['hid']);
		if($hid){// 设置网址状态
			$res=$db->query("SELECT hindex,hurl,hstate,lasttime FROM href WHERE hid=$hid")->fetch(); 
			$lasttime=intval($res['lasttime']); // 获取链接状态
			if($lasttime<$curtime){
				$hstate=intval($res['hstate']); // 获取链接状态
				$hindex=$res['hindex']; // 获取链接状态
				$urlarr=explode("{|}", $res['hurl']);
				$url=$urlarr['0'];
				if(isset($urlarr['1'])){
					$url=$urlarr['1'];
				}
				$state=getUrlState($url);
				if((($state<200 || $state>=300) && $hstate!=0) || ($state>=200 && $state<300 && ($hstate==0||$hstate==2))){
					$tn=0;
					if($state==200 || $state==201 || $state==401 || $state==402 || $state==412)$tn=1;
					elseif($state>=300 && $state<400){
						$tn=2;
					}
					$db->exec("INSERT INTO htmltn (htmlname,tn,state) VALUES('$hindex',$tn,$state)");
				//	$resply['2']="INSERT INTO htmltn (htmlname,tn,state) VALUES('$hindex',$tn,$state)";
				}
				//$resply[2]='state:'.$state;
				$lasttime = $curtime + 3600*24*7;//7天后再检查
				$db->exec("update href set lasttime=$lasttime WHERE hid=$hid");
			}
		}
	}elseif(isset($_GET['url'])){//   测试
		$url=$_GET['url'];
		//$info=@get_headers($url);
		$state=getUrlState($url,1);
		echo'<br>state：<strong>',$state,'</strong><br>';
	}else{
		$resply[0]=false;
	}
	echo json_encode($resply);

function getUrlState($url,$tn=0){
	$ch = curl_init();
	$temp = @explode("/", $url);
	$url_host = @$temp[2];
	$header = array();
//	$cip = '123.'.mt_rand(125,200).'.68.'.mt_rand(0,254);
//	$xip = '125.'.mt_rand(90,254).'.88.'.mt_rand(0,254);
	$cip=$xip='58.215.145.110';
	array_push($header, 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36');
	array_push($header, 'Referer:' . $url);
	array_push($header, 'host:' . $url_host);
	array_push($header, 'accept:  text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8');
	array_push($header, 'upgrade-insecure-requests:1');
	array_push($header, 'X-FORWARDED-FOR:'.$cip);
	array_push($header, 'CLIENT-IP:'.$xip);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);        // HTTP 头中的 "Location: "重定向
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        // 字符串返回, 不直接输出
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_HEADER, 1);                // 0表示不输出Header，1表示输出
	curl_setopt($ch, CURLOPT_NOBODY, 1);                // 1表示不输出Body，0表示输出
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);     // 尝试连接时等待的秒数。设置为0，则无限等待
	curl_setopt($ch, CURLOPT_TIMEOUT, 18);              // 允许 cURL 函数执行的最长秒数
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip');					// 解决网页压缩产生的乱码

//    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);					//  使用提交后得到的cookie数据做参数

	$output = curl_exec($ch);
if($tn)	echo$output;
	$state=0;
	if(!empty($output)){
		$str=$output;	//HTTP/1.1 200 OK
		preg_match_all("/^HTTP\/[0-9\.]+\s([0-9]+).*/",$str, $match); 
		print_r($match);
		$state=intval($match['1']['0']);
	}
	return $state;
}
?>