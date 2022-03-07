<?php
function get_ip() {
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

function cookie($name,$val=FALSE){
    if($val){
      setcookie($name,$val,0);
    }else{
      return @$_COOKIE[$name];
    }
}

function redir($url){
    header('Location:'.$url);
    exit;
}

function checklogin(){
    $pass=cookie('pass');
    if($pass==MD5(PASSWORD)){
      return true;
    }
    else{
      return false;
    }
}

// 获取链接的唯一索引
function get_url_index($hurl){
	$search ='/(http|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\d\-_#\/]+)/i';
	preg_match_all($search, $hurl.'/' ,$r);
	$urlarr = @explode(".", $r['2']['0']);
	$arr = array('/' => '', '-' => '', "_" => '', ":" => '', "#" => ''); 
	$lastdir='';
	if(isset($r['3']['0']))$lastdir=strtr($r['3']['0'], $arr); 
	$index='';
	for($len=$i=count($urlarr);$i>0;$i--){
		if($i==1){
			if($len<3){
				$index=$urlarr[$i-1].$index;
			}else{
				$index.=$urlarr[$i-1];
			}
		}else{
			$index=$urlarr[$i-1].$index;
		}
	}
	$index=strtolower($index.$lastdir);
	return $index;
}



function _CheckInput($str,$type){
	switch($type){
		case 'number': // 纯数字
				return preg_match("/^[0-9]{1,16}$/",$str)?true:false;
			break;
		case 'email': //电子邮箱
				return preg_match("/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$/",$str)?true:false;
			break;
		case 'upload': //上传文件格式
				return preg_match("/^[a-zA-Z0-9]{2,32}(.mp3|.mp4|.jpg|.png|.gif)$/",$str)?true:false;
			break;
		case 'letter': // 字母
				return preg_match("/^[a-zA-Z]{4,64}$/",$str)?true:false;
			break;
		case 'tel': // 电话号码
				return preg_match("/^1[0-9]{10}$/",$str)?true:false;
			break;
		case 'nick': // 昵称字符
				return preg_match("/^[\x80-\xffa-zA-Z0-9-_]{3,30}$/", $str)?true:false;
			break;
		case 'text': // 昵称字符
				return preg_match("/^[\u4e00-\u9fa5]{1,1024}$/", $str)?true:false;
			break;
		case 'numchar':  // 数字和字母组合
				return preg_match("/^[a-zA-Z0-9-]{1,32}$/",$str)?true:false;
			break;
		case 'user': // 注册用户的核对
				return preg_match("/^[a-zA-Z0-9]{4,16}$/",$str)?true:false;
			break;
		case 'path': // 效验路径
				return preg_match("/^[a-zA-Z0-9-_\/]{0,127}$/",$str)?true:false;
			break;
		case 'url':			
				return preg_match("/^([[:alnum:]]|[:#%&_=\(\)\.\? \+\-@\/])+$/",$str)?true:false;
		default:
			return false;
	}
}

function filterText($str){	// 过滤输入文本	
	$str=trim(addslashes($str));  
    $arr = array('"' => '&#34;', '\"' => '&#34;', "'" => '&#39;', "\'" => '&#39;', "<" => '&#60;', ">" => '&#62;'); 
    return strtr($str, $arr);  
}

function filterTitle($str,$allow=true){ // 中文输入方式过滤特殊字符
	$str=trim(addslashes(sprintf("%s",$str)));
	//$str=trim(addslashes($str));  
    $arr = array('`' => '', '~' => '', '!' => '', '$' => '', '%' => '', '^' => '', '*' => '', '（' => '(', '）' => ')', '|' => '', '\\' => '', '{' => '', '[' => '', '}' => '', ']' => '', ';' => '', '"' => '\"', "'" => '\'','\"' => '\"', '\'' => '\'');
	if($allow)$str=strip_tags($str,'<img><strong>');
    return strtr($str, $arr);  
}

function get_page_info($output, $curl_info=array()) {
    $page_info = array();
    $page_info['site_title'] = '';
    $page_info['site_description'] = '';
    $page_info['site_keywords'] = '';
    //$page_info['friend_link_status'] = 0;
   // $page_info['site_claim_status'] = 0;
   // $page_info['site_home_size'] = 0;
     
    if(empty($output)) return $page_info;
    //echo$output;
    // 获取网页编码，把非utf-8网页编码转成utf-8，防止网页出现乱码
    $meta_content_type = '';
    if(isset($curl_info['content_type']) && strstr($curl_info['content_type'], "charset=") != "") {
        $temp = explode("charset=", $curl_info['content_type']);
        $meta_content_type = $temp['1'];
    }
	
    if($meta_content_type == '') {
        preg_match('/<META\s+http-equiv=["|\']Content-Type["|\']\s+content=["|\']([\w\W]*?)["|\']/si', $output, $matches);       // 中文编码，如 http://www.qq.com

        if (empty($matches[1])) {
            preg_match('/<META\s+content=["|\']([\w\W]*?)["|\']\s+http-equiv=["|\']Content-Type["|\']/si', $output, $matches);
        }
        if (empty($matches[1])) {
            preg_match('/<META\s+charset=["|\']([\w\W]*?)["|\']/si', $output, $matches);       // 特殊字符编码，如 http://www.500.com
        }
		//print_r($matches);
		if(isset($matches[1]))$meta_content_type=$matches[1];
        if (!empty($matches[1]) && strstr($matches[1], "charset=") != "") {
            $temp = explode("charset=", $matches[1]);
            $meta_content_type = $temp[1];
        }
    }
	//echo$meta_content_type,'xxx';
    if(!in_array(strtolower($meta_content_type), array('','utf-8','utf8','gb2312'))) {
		if($meta_content_type=='text/html')$meta_content_type='gbk';
        $output = @mb_convert_encoding($output, "utf-8", $meta_content_type);        // gbk, gb2312
    }
	//echo  json_encode($output);
    // 若网页仍然有乱码，有乱码则gbk转utf-8
	//echo $meta_content_type;
    if(json_encode($output) == '' || json_encode($output) == null) { // $meta_content_type=gbk 乱码 https://www.panle.net
        $output = @mb_convert_encoding($output, "utf-8", $meta_content_type);
    }

   // $page_info['site_home_size'] = strlen($output);
   //echo$output;
    # Title
    preg_match('/<TITLE(.*?)>([\w\W]*?)<\/TITLE>/si', $output, $matches);
    if (!empty($matches[2])) {
        $page_info['site_title'] = $matches[2];
    }
    // 正则匹配，获取全部的meta元数据
    preg_match_all('/<META(.*?)>/si', $output, $matches);
    $meta_str_array = $matches[1];
     
    $meta_array = array();
    $meta_array['description'] = '';
    $meta_array['keywords'] = '';
	//print_r($meta_str_array);echo'<br><br><br>';
	$i=0;
    foreach($meta_str_array as $k=>$meta_str) {
		//echo$i++,"<br>";
        preg_match('/(.*)name(\s*)=(\s*)([\w\W]*?)(.*)content=(.*)/si', $meta_str, $res);
		//print_r($res);
		$arr = array('"' => '',' ' => '', "'" => '', "/" => ''); 
		$arr2 = array('"' => '', "'" => '', "/" => ''); 
		$key='';
        if(!empty($res) && isset($res[5]) && isset($res[6])){			
			preg_match('/description/si', $res[5], $key1);
			if(empty($key1)){
				preg_match('/keywords|keyword/si', $res[5], $key1);
			}else{
				$key=$key1[0];
			}
			if(!empty($key1)){
				$key=$key1[0];
				$key=strtolower(trim($key));
				switch($key){
					case 'keyword':
						$key='keywords';
					break;
					default:
				}
			}
			$desc=trim(strtr($res[6], $arr2));
			if(!empty($desc) && ($key=='keywords' || $key=='description'))$meta_array[$key] = $desc;
		}
        preg_match('/(.*)content=(.*)name=([\w\W]*)/si', $meta_str, $res);
		if(!empty($res) && isset($res[2]) && isset($res[3])){
			preg_match('/description/si', $res[3], $key1);
			if(empty($key1)){
				preg_match('/keywords|keyword/si', $res[3], $key1);
			}else{
				$key=$key1[0];
			}
			//print_r($key1);
			if(!empty($key1)){
				$key=$key1[0];
				$key=strtolower(trim($key));
				switch($key){
					case 'keyword':
						$key='keywords';
					break;
					default:
				}
			}
			//$key=strtr($res[3], $arr);
			$desc=trim(strtr($res[2], $arr2));
			if(!empty($desc) && ($key=='keywords' || $key=='description'))$meta_array[$key] = $desc;
		}
        preg_match('/(.*)http-equiv(\s*)=(\s*)([\w\W]*?)(.*)content=(.*)/si', $meta_str, $res);
        if(!empty($res) && isset($res[5]) && isset($res[6])){
			preg_match('/description/si', $res[5], $key1);
			if(empty($key1)){
				preg_match('/keywords|keyword/si', $res[5], $key1);
			}else{
				$key=$key1[0];
			}
			if(!empty($key1)){
				$key=$key1[0];
				$key=strtolower(trim($key));
				switch($key){
					case 'keyword':
						$key='keywords';
					break;
					default:
				}
			}
			$desc=trim(strtr($res[6], $arr2));
			if(!empty($desc) && ($key=='keywords' || $key=='description'))$meta_array[$key] = $desc;
		}
        preg_match('/(.*)content=(.*)http-equiv=([\w\W]*)/si', $meta_str, $res);
		if(!empty($res) && isset($res[2]) && isset($res[3])){
			preg_match('/description/si', $res[3], $key1);
			if(empty($key1)){
				preg_match('/keywords|keyword/si', $res[3], $key1);
			}else{
				$key=$key1[0];
			}
			if(!empty($key1)){
				$key=$key1[0];
				$key=strtolower(trim($key));
				switch($key){
					case 'keyword':
						$key='keywords';
					break;
					default:
				}
			}
			$desc=trim(strtr($res[2], $arr2));
			if(!empty($desc) && ($key=='keywords' || $key=='description'))$meta_array[$key] = $desc;
		}
    }
	//print_r($meta_array);
    $page_info['site_keywords'] = $meta_array['keywords'];
    $page_info['site_description'] = $meta_array['description'];
    //$page_info['meta_array'] = $meta_array;
     
    # mimvp-site-verification
    preg_match('/<META\s+name="mimvp-site-verification"\s+content="([\w\W]*?)"/si', $output, $matches);
    if (empty($matches[1])) {
       // preg_match('/<META\s+content="([\w\W]*?)"\s+name="mimvp-site-verification"/si', $output, $matches);
    }
    if (!empty($matches[1])) {
        //$page_info['site_claim_status'] = 1;
    }
     
    # mimvp-site-verification
    if(strstr($output, 'https://proxy.mimvp.com') != "") {
        //$page_info['friend_link_status'] = 1;
    }
     
    return $page_info;
}

function get_site_favicon($url,$haveico=0){//$haveico=0,七牛云没有ico，$haveico=1 七牛云有ico
	$iconame=get_url_index($url);
	if($haveico==1 || file_exists("../assets/logos/{$iconame}.png")){
		return;
	}

	$timeout=35; $conntimeout=33;
	$url    = trim($url);        
	$scheme = parse_url($url, PHP_URL_SCHEME);
	$host   = parse_url($url, PHP_URL_HOST);

	$suf='.png';
	$hostpng='https://api.iowen.cn/favicon/'.$host.'.png';
	$ico    = get_siteurl_curlinfo($hostpng,$timeout,$conntimeout,0);
	if(is_ico_image($ico,$suf))
	{
		$imgname="../assets/ico/{$iconame}".$suf;
		file_put_contents($imgname, $ico);
	}
	

	if(!file_exists("../assets/ico/{$iconame}.png"))
	{
		$tn=false;
		$suf='.ico';
		$ico    = get_siteurl_curlinfo("{$scheme}://{$host}/favicon.ico",$timeout,$conntimeout,0);
		if(!is_ico_image($ico,$suf)){
			$ico = get_siteurl_curlinfo("{$scheme}://{$host}/favicon.png",$timeout,$conntimeout,0);
			if(!is_ico_image($ico,$suf)){
				$ico = get_siteurl_curlinfo("{$scheme}://{$host}/favicon.jpg",$timeout,$conntimeout,0);
				if(is_ico_image($ico,$suf)){
					$tn=true;
					$suf='.jpg';
				}
			}else{
				$tn=true;
				$suf='.png';
			}
		}else{
			$tn=true;
		}
		if(!$tn)
		{
			// 抓取首页匹配是否有自定义ICO
			$ico_html = get_siteurl_curlinfo("{$scheme}://{$host}/",$timeout,$conntimeout,0);
			preg_match('/href=\"(.*?)(\.[ico|png|jpg]+)\"/i', $ico_html, $match);
			// 匹配HTTP/HTTPS类型ICO，匹配相对路径和绝对路径ICO
			//print_r($match);
			if(isset($match[2]))$suf=$match[2];
			if(isset($match[1]) && $match[1])
			{
				$match[1]=str_replace('./','/',$match[1]);
				$url = substr($match[1], 0, 4) == 'http' ? $match[1] : $scheme . '://' . $host . $match[1];
				$url.= $suf;
				//echo '<br>',$url;
				$ico = get_siteurl_curlinfo($url,$timeout,$conntimeout,0);
			}
		}
		if(is_ico_image($ico,$suf))
		{
			$imgname="../assets/ico/{$iconame}".$suf;
			file_put_contents($imgname, $ico);
		}
	}
}


	
// 判断是否是图片，可能是404页面
function is_ico_image($ico,$suf)
{
	if($ico)
	{	$imgname='../assets/ico/favicon'.$suf;
		file_put_contents($imgname, $ico);
		if('43802bddf65eeaab643adb8265bfbada'==md5_file($imgname)){ // 如果没有获取到ico
			unlink($imgname);
			return false;
		}
		$type = getimagesize($imgname);
		unlink($imgname);
		if($type)
		{
			return true;
		}
	}
	return false;
}

function get_siteurl_curlinfo($url='https://www.fcdh.net', $timeout=35, $conntimeout=33,$tn=1) {
    $ch = curl_init();
    $temp = @explode("/", $url);
    $url_host = @$temp[2];
    $header = array();
	
	$iparr=array(14,27,36,39,42,47,49,58,60,61,106,110,111,112,113,114,115,116,117,119,120,121,122,123,125,175,182,183,218,219,220,222,223);
	$ip1=$iparr[mt_rand(0,32)];
	$ip2='';
	switch($ip1){
		case 14:$ip2=103;break;
		case 27:$ip2=184;break; 
		case 36:$ip2=96;break; 
		case 39:$ip2=128;break; 
		case 42:$ip2=202;break; 
		case 47:$ip2=92;break; 
		case 49:$ip2=64;break; 
		case 58:$ip2=192;break; 
		case 60:$ip2=160;break; 
		case 61:$ip2=128;break; 
		case 106:$ip2=11;break; 
		case 110:$ip2=240;break;
		case 111:$ip2=126;break;
		case 112:$ip2=0;break;
		case 113:$ip2=62;break;
		case 114:$ip2=208;break;
		case 115:$ip2=190;break;
		case 116:$ip2=128;break;
		case 117:$ip2=124;break;
		case 119:$ip2=112;break;
		case 120:$ip2=182;break;
		case 121:$ip2=8;break;
		case 122:$ip2=64;break;
		case 123:$ip2=64;break;
		case 125:$ip2=64;break;
		case 175:$ip2=46;break;
		case 182:$ip2=96;break;
		case 183:$ip2=184;break;
		case 218:$ip2=56;break;
		case 219:$ip2=128;break;
		case 220:$ip2=160;break;
		case 222:$ip2=16;break;
		case 223:$ip2=64;break;
		default:
			$ip2=96;
	}
	$ip3=mt_rand(0,255);
	$ip4=mt_rand(0,255);
	//$cip = '123.'.mt_rand(125,200).'.68.'.mt_rand(0,254);
	//$xip = '125.'.mt_rand(90,254).'.88.'.mt_rand(0,254);
	$cip = $ip1.'.'.$ip2.'.'.$ip3.'.'.$ip4;
	$xip = $ip1.'.'.$ip2.'.'.$ip3.'.'.$ip4;

    //array_push($header, 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
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
    curl_setopt($ch, CURLOPT_HEADER, $tn);                // 0表示不输出Header，1表示输出
    curl_setopt($ch, CURLOPT_NOBODY, 0);                // 1表示不输出Body，0表示输出
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conntimeout);     // 尝试连接时等待的秒数。设置为0，则无限等待
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);              // 允许 cURL 函数执行的最长秒数
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');					// 解决网页压缩产生的乱码

//    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);					//  使用提交后得到的cookie数据做参数

    $output = curl_exec($ch);
	//echo$output;
    if($tn){
		$curl_info = curl_getinfo($ch);
	}
    curl_close($ch);
     
    if($tn==1){
		$curl_info['page_info'] = get_page_info($output, $curl_info);
		return $curl_info;
	}elseif($tn==2){
		return $curl_info;
	}else{
		return $output;
	}
}

function formatBytes($size,$kb='kb') { // KB转换

    $units = array('KB', 'MB', 'GB', 'TB');
    $kbarr = array('KB'=>0, 'MB'=>1, 'GB'=>2, 'TB'=>3);
	$kb=strtoupper($kb);
	if(!in_array($kb, $units)){
		$kb='KB';
	}
    for ($i = $kbarr[$kb]; $size >= 1000 && $i < 3; $i++) {

        $size /= 1024;

    }
    return round($size,2).$units[$i];

}

function deleteAllPic($dir){ // 删除所有图片和文件
	if (is_dir($dir)){ // href html
	  if (false != ($handle = opendir ( $dir ))) {
		$i=0;
		while ( false !== ($htmlfile = readdir ( $handle )) ) {
			$suf=substr(strrchr($htmlfile, '.'), 1);
			if ($htmlfile != "." && $htmlfile != "..") {
				unlink($dir.$htmlfile);
			}
		}
		closedir($handle );
	  }
	}
}
/*
$SFile 源图片路径名称 如: pic/pics/pic.jpg
$DFile 目标图片路径,就是缩略图要存放的路径 如 thumbs/thum/thum.jpg
$DWidth  要生成的图片尺寸宽
$DHeight 要生成的图片尺寸高 为0 时按宽度比例生成
$SWidth 源图片尺寸宽 
$SHeight  源图片尺寸高
$Suf  图片的后缀名称 ,因网站就是生成.jpg格式,所以本函数就涉及得这种格式.
$leftX 左上角的X坐标
$leftY 左上角的Y坐标
$cropW 选择区域的宽度
$cropH 选择区域的高度
$DWidth=0 $DHeight!=0, 则固定DHeight高度
$DWidth!=0 $DHeight==0, 则固定DWidth高度
$DWidth!=0 $DHeight!=0, 则固定DWidth,DHeight
$cropW!=0 && $cropH!=0, 则按照裁剪生成
*/
function createThumbs($SFile,$DFile,$DWidth,$DHeight,$SWidth,$SHeight,$Suf,$leftX=0,$leftY=0,$cropW=0,$cropH=0){
	if($SHeight!=0 && $SWidth!=0){
		$rSrc=$SWidth/$SHeight;  //源图片的宽和高比值
	}else{
		return;
	}

	if($DHeight==0 && $DWidth!=0){//     固定宽度，按照比例生成中间图的宽和高
		$DHeight=round($DWidth/$rSrc);
		$leftX=$leftY=0;
		$cropW=$SWidth;
		$cropH=$SHeight;
	}elseif($DHeight!=0 && $DWidth==0){//     固定高度，按照比例生成中间图的宽和高
		$DWidth=round($DHeight*$rSrc);
		$leftX=$leftY=0;
		$cropW=$SWidth;
		$cropH=$SHeight;
	}elseif($DHeight!=0 && $DWidth!=0){//     固定值生成缩略图
		$rDes=$DWidth/$DHeight;
		if($cropW!=0 && $cropH!=0 ){ // 按照裁剪的参数生成缩略图
			$rCrop=$cropW/$cropH; //目标文件的宽和高比值
			if($DHeight==0 && $DWidth!=0){
				$DHeight=round($DWidth/$rCrop);
			}elseif($DHeight!=0 && $DWidth==0){
				$DWidth=round($DHeight*$rCrop);
			}elseif($DHeight!=0 && $DWidth!=0){
				if($rDes>$rCrop){
					$DWidth=round($DHeight*$rCrop);
				}else{
					$DHeight=round($DWidth/$rCrop);
				}
			}else{
				return;
			}
			
		}else{ // 按照固定尺寸生成缩略图
			if($rDes>$rSrc){ // 
				$cropW=$SWidth;
				$cropH=round($cropW/$rDes);
				$leftX=0;
				$leftY=round(($SHeight-$cropH)/2);
			}else{
				$cropH=$SHeight;
				$cropW=round($cropH*$rDes);
				$leftX=round(($SWidth-$cropW)/2);
				$leftY=0;
			}
		}
	}else{
		return;
	}


	$DWidth<400?$v=75:$v=75;  // 如果宽小于400,那就图片生成质量为32,否则是97 自己可以适当修改,最大值为100

	$canvas=imagecreatetruecolor($DWidth,$DHeight);

	if($Suf=='.jpg')$image=imagecreatefromjpeg($SFile);
	elseif($Suf=='.png')$image=imagecreatefrompng($SFile);
	elseif($Suf=='.gif') $image=imagecreatefromgif($SFile);
	imagecopyresampled($canvas,$image,0,0,$leftX,$leftY,$DWidth,$DHeight,$cropW,$cropH);

	if($Suf=='.jpg')$image=imagejpeg($canvas,$DFile,$v);
	elseif($Suf=='.png')$image=imagepng($canvas,$DFile,$v);
	elseif($Suf=='.gif') $image=imagegif($canvas,$DFile,$v);
	
	ImageDestroy($canvas);
}
function pricetovip($m,$i=0){
	++$i;
	for($i;$i<11;$i++){
		if($m<viprank($i)['money'])break;
	}
	return(--$i);
}
function viprank($i){
	if($i<0)$i=-$i;
	if($i>10)$i=10;
	$price=10;
	$mb=10;
	$vip=array('label'=>'','month'=>'','day'=>'','point'=>'','coin'=>'','money'=>'','week'=>'');
	$viparr=array('黑铁','青铜','白银','黄金','铂金','钻石','星耀','王者','王者荣耀','王者至尊','王者永恒');
	$color=$i+2;
	$day=$i;
	$month=1;
	switch($i){
		case 0:	$month=0;	$n=0;	$k=0;
		break;
		case 1:	$month=1;	$n=1.0;	$k=1.0;
		break;
		case 2:	$month=6;	$n=0.6;	$k=1.1;
		break;
		case 3:	$month=12;	$n=0.5;	$k=1.2;
		break;
		/****************下面会员永久*********************/
		case 4:	$month=-3;	$n=0.6;	$k=1.3;
		break;
		case 5:	$month=-6;	$n=0.8;	$k=1.4;
		break;
		case 6:	$month=-12;	$n=1.1;	$k=1.5;
		break;
		case 7:	$month=-24;	$n=1.5;	$k=1.6;
		break;
		case 8:	$month=-36;	$n=1.6;	$k=1.7;
		break;
		case 9:	$month=-48;	$n=1.7;	$k=1.8;
		break;
		default:$month=-1200;$n=1200;$k=1.9;
	}
	if($month<0){
		$money=$price*12*$n;
		if($month<-1000)$money=65535;
		$day=-$month*30;
		$week=12*10;
	}else{
		$money=$price*$month*$n;
		$day=$month*30;
		$week=$month*30;
	}
	$point=$i*$i*10;if($month==0)$point=1;
	$coin=$money*$mb*$k; // 美币计算方式

	if($i>13)$i=13;
	$vip['label']=$viparr[$i];
	$vip['month']=$month;
	$vip['day']=$day;
	$vip['point']=$point;
	$vip['coin']=$coin;
	$vip['money']=$money;
	$vip['week']=$week;
	return $vip;
}

//// show:是否显示图片原名称
function getGrabImage($url,$id,$show=0) { // 获取远程图片，并隐藏相应图片地址
	if($show)return $url;
	return "srcimg.php?id=$id";
} 


// 参数解释
// $string： 明文 或 密文
// $operation：DECODE表示解密,其它表示加密
// $key： 密匙
// $expiry：密文有效期
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {   
   // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙   
   $ckey_length = 4;   
      
    // 密匙   
    $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);   
       
    // 密匙a会参与加解密   
    $keya = md5(substr($key, 0, 16));   
    // 密匙b会用来做数据完整性验证   
    $keyb = md5(substr($key, 16, 16));   
    // 密匙c用于变化生成的密文   
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length):substr(md5(microtime()), -$ckey_length)) : '';   
    // 参与运算的密匙   
    $cryptkey = $keya.md5($keya.$keyc);   
    $key_length = strlen($cryptkey);   
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性   
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确   
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)):sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;   
    $string_length = strlen($string);   
    $result = '';   
    $box = range(0, 255);   
    $rndkey = array();   
    // 产生密匙簿   
    for($i = 0; $i <= 255; $i++) {   
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);   
    }   
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度   
    for($j = $i = 0; $i < 256; $i++) {   
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;   
        $tmp = $box[$i];   
        $box[$i] = $box[$j];   
        $box[$j] = $tmp;   
    }   
    // 核心加解密部分   
    for($a = $j = $i = 0; $i < $string_length; $i++) {   
        $a = ($a + 1) % 256;   
        $j = ($j + $box[$a]) % 256;   
        $tmp = $box[$a];   
        $box[$a] = $box[$j];   
        $box[$j] = $tmp;   
        // 从密匙簿得出密匙进行异或，再转成字符   
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));   
    }   
    if($operation == 'DECODE') {   
        // substr($result, 0, 10) == 0 验证数据有效性   
        // substr($result, 0, 10) - time() > 0 验证数据有效性   
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性   
        // 验证数据有效性，请看未加密明文的格式   
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {   
            return substr($result, 26);   
        } else {   
            return '';   
        }   
    } else {   
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因   
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码   
        return $keyc.str_replace('=', '', base64_encode($result));   
    }   
}


class Mail
{
	protected $config;
	function __construct($config)
	{
		$this->config = $config;
	}

	public function send($to_user,$subject,$content)
	{
		
		// 判断是否使用html类型
		$type = $this->config['html'] ? 'Content-type: text/html;' : 'Content-type: text/plain;';

	    $cmd = [
	    	"EHLO {$this->config['smtp_name']}\r\n",
	    	"AUTH LOGIN\r\n",
	    	base64_encode($this->config['smtp_user'])."\r\n",
	    	base64_encode($this->config['smtp_pass'])."\r\n",
	    	"MAIL FROM: <{$this->config['smtp_user']}>\r\n",
	    	"RCPT TO: <{$to_user}>\r\n",
	    	"DATA\r\n",
	    	"From: \"{$this->config['smtp_name']}\"<{$this->config['smtp_user']}>\r\n",
	    	"To: <{$to_user}>\r\n",
	    	"Subject:{$subject}\r\n",
	    	$type."\r\n",
	    	"\r\n",
	    	$content." \r\n",
	    	".\r\n",
	    	"QUIT\r\n",
	    ];

	    $this->connect($cmd);

	    return true;
	}

	// 链接 发送
	protected function connect($cmd)
	{
	    //打开smtp服务器端口
	    $fp = @pfsockopen($this->config['smtp_host'], $this->config['smtp_port']);
	    $fp or die("Error: Cannot conect to ".$smtp_host);

	    // 执行命令
	    foreach ($cmd as $k => $v) {
	    	@fputs($fp, $v );

	    	// ************ 打印 *********** 
	    	$res= fgets($fp);
	    	echo "\n {$v} {$res} \n";
	    	// *****************************

	    	// sleep(1);
	    	// 延迟 0.5秒
	    	usleep(500000);
	    }
	}

}


/**
     * 发起异步请求，忽略返回值
     * @param $url  请求url
     * @return bool
     */
function asyncPost($url)
{
	$args = parse_url($url); //对url做下简单处理
	$host = $args['host']; //获取上报域名
	$path = $args['path'] . '?' . $args['query'];//获取上报地址
	$fp = fsockopen($host, 80, $error_code, $error_msg, 1);
	if (!$fp) {
		Log::record('获取错误信息:'.$error_code . ' _ ' . $error_msg, Log::INFO, true);
		Log::save('',LOG_PATH . '_' . date('y_m_d') . '.txt');
		return false;//
	} else {
		stream_set_blocking($fp, true);//开启了手册上说的非阻塞模式
		stream_set_timeout($fp, 1);//设置超时
		$header = "GET $path HTTP/1.1\r\n";  //注意 GET/POST请求都行 我们需要自己按照要求拼装Header http协议遵循1.1
		$header .= "Host: $host\r\n";
		$header .= "Connection: close\r\n\r\n";//长连接关闭
		fputs($fp, $header);
		fclose($fp);
	}
}

// php 后端加密
function hrefEncode($string,$key) {   
    $string = base64_encode ( $string );  
  //  $key = 'fcdh.net';   
    $len = strlen ( $key );  
	$strlen=strlen ( $string );
    $code = '';   
    for($i = 0; $i < $strlen; $i++) {   
        $k = $i % $len;   
        $code .= $string [$i] ^ $key [$k];   
    }   
    return base64_encode ($code);   
}  