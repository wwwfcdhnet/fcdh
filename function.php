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

function redir($uri){
    header('Location:'.$uri);
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
	$lastdir=strtr($r['3']['0'], $arr); 
	$index='';
	for($len=$i=count($urlarr);$i>0;$i--){
		if($i==1){
			//if($urlarr[0]=='www')$urlarr[0]='';
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
function load_config($key){
  global $db;
  $data=$db->query("select value from config where key='$key'")->fetch();
  //var_dump($data);
  if($data)
    return $data['value'];
  else
    return false;
}

function save_config($key,$value){
  global $db;
  $eof=$db->exec("update config set value='$value' where key='$key'");
  if(!$eof){
    $db->exec("insert into config(key,value) values('$key','$value')");
  }
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
    $arr = array('`' => '', '~' => '', '!' => '', '@' => '#', '$' => '', '%' => '', '^' => '', '*' => '', '（' => '(', '）' => ')', '|' => '', '\\' => '', '{' => '', '[' => '', '}' => '', ']' => '', ';' => '', '"' => '\"', "'" => '\'','\"' => '\"', '\'' => '\'');
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
        $meta_content_type = explode("charset=", $curl_info['content_type'])[1];
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
            $meta_content_type = explode("charset=", $matches[1])[1];
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
    $url_host = @explode("/", $url)[2];
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
// 设置网站状态
function set_siteurl_state($url='https://www.fcdh.net',$index='') {
	global $db;
	$curl_info=get_siteurl_curlinfo($url, $timeout=35, $conntimeout=33,$tn=2); // 获取网址状态
	$httpcode=intval($curl_info['http_code']);
	//echo$httpcode;
	if($httpcode!=200 && !empty($index)){ //表示网站状态正常
		$db->exec("insert into htmltn(htmlname,tn) values('$index',$httpcode)");
	}
}

// 生成静态html页面
function hrefhtml($hid,$tn=0){// 0:表示生成href 2：表示生成blog
	global $db;
	$hrelate=$htmlfile='';
	if($tn==2){
		$template='tempBlog.html';
		$res=$db->query("select bindex as hindex,btitle as htitle,bkey as hkey,bdesc as hdesc,btag as htag,bview as hview,btime as htime,bcolor as hcolor,bstrong as hstrong from blog where bid=$hid limit 1")->fetch(); 
	}else{
		$template='tempHref.html';
		$res=$db->query("select hindex,hname,hurl,htitle,hkey,hdesc,htag,hstate,hview,htime,hcolor,hstrong,hico,hrelate from href where hid=$hid limit 1")->fetch(); 

		// 获取相关网站
		//echo"select hindex,hname,hurl,htitle from href where hid IN($res[hrelate])";exit;
		$relatearr=$db->query("select hindex,hname,hurl,htitle,hstate,hcolor,hstrong,hico from href where hid IN($res[hrelate])")->fetchAll(); 
		$hrelate='<h4><a onclick="moreHref(\'related\',this);"><i class="fa-codepen"></i> 相关网站<span class="more"></span></a></h4><div class="ti-ulbg"><ul class="row"id="related">';
		$items='';
		foreach($relatearr as $arr){
			$delwd=$del=$img=$color='';
			if(isset($arr['hico'])){
				if($arr['hico']==1){ // 已经上传到七牛云
					$img='<img src="/assets/logos/'.$arr['hindex'].'.png">';
				}elseif(file_exists($imgsrc)){
					$img='<img src="/assets/logos/'.$arr['hindex'].'.png">';
				}
			}
			if($arr['hstate']==0){ // 表示死链
				$delwd=' del';
				$color=$del=' class="del"';
			}

			if($arr['hcolor']){
				$color=' class="c'.$arr['hcolor'].'"';
			}
			$name=$arr['hname']=strtr($arr['hname'],array(' '=>''));
			if($arr['hstrong']){
				$name='<strong>'.$arr['hname'].'</strong>';
			}else{
				$name=$arr['hname'];
			}
			$items.='<li><a href="'.$arr['hindex'].'.html"><span'.$color.' title="'.$arr['htitle'].'">'.$img.$name.'</span></a><p'.$del.' onclick="openHref(\''.$arr['hurl'].'\',this,-1)">'.$arr['htitle'].'</p></li>';
		}
		if(!empty($items))$hrelate.=$items.'</ul></div>';
		else $hrelate='';
	}
	
	$hindex=$res['hindex'];
    $htitle=$res['htitle'];
    $hkey=$res['hkey'];
    $hdesc=html_entity_decode($res['hdesc']);
    $hstrong=$res['hstrong'];
    $hcolor=$res['hcolor'];
	$hview=$res['hview'];
	$htime=$res['htime']; 
	$htag=$res['htag'];
		
	if($tn==0){ // href 静态页面生成
		$hurl=$res['hurl'];
		$hname=$res['hname'];
		$hstate=$res['hstate'];
		$hico=$res['hico'];
		if($hstate==0)$hstate=9;
		elseif($hstate==2)$hstate=10;
		elseif($hstate==4)$hstate=8;
		$statearr=array('9'=>'死链','1'=>'正常','10'=>'异常','3'=>'改版','8'=>'被屏蔽');
	}

	$tag=$db->query("select tid,tindex,tname from tag where tid in($htag)")->fetchAll(); 
	$tags='';
	foreach($tag as $r){
		$htmlfile='../'.$r['tindex'].'.html';
		if(!empty($r['tid'])&&file_exists($htmlfile))$tags.='<a href="'.$r['tindex'].'.html">['.strtr($r['tname'],array(' '=>'')).']</a> ';
	}
	$htmlfile="$hindex.html";
	$html_fp = fopen('../html/'.$htmlfile, 'w');
    $handle = fopen($template, "r");//读取二进制文件时，需要将第二个参数设置成'rb'

    
    //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
    $contents = fread($handle, filesize ($template));

	$sider=file_get_contents('tempSider.html');
	if($tn==2){
		$tags='<p>'.$tags.'</p>';
	}

	if($tn==0){
		$imgsrc='../assets/logos/'.$hindex.'.png';
		$img='';

		if($hico==1){ // 已经上传到七牛云
			$img='<img src="../assets/logos/'.$hindex.'.png"id="ico">';
		}elseif(file_exists($imgsrc)){
			$img='<img src="../assets/logos/'.$hindex.'.png"id="ico">';
		}

		$opt=$hcolor.'#'.$hstrong.'#'.$hname;
		$hname=$img.'<strong class="c'.$hcolor.'">'.$hname.'</strong>';
		$contents = str_replace('{{hrelate}}', $hrelate, $contents);
		$contents = str_replace('{{opt}}', $opt, $contents);
		$contents = str_replace('{{hurl}}', $hurl, $contents);
		$contents = str_replace('{{hstate}}', '<strong id="state"class="c'.$hstate.'">'.$statearr[$hstate].'</strong>', $contents);
	
		$contents = str_replace('{{hindex}}', $hindex, $contents);
		$contents = str_replace('{{hname}}', $hname, $contents);
		$contents = str_replace('{{hurl}}', $hurl, $contents);
	}
	$contents = str_replace('{{hid}}', $hid, $contents);
	$contents = str_replace('{{htitle}}', $htitle, $contents);
	$contents = str_replace('{{hkey}}', $hkey, $contents);
	$contents = str_replace('{{hdescmeta}}', mb_strcut(strip_tags($hdesc),0,200), $contents);
	$contents = str_replace('{{hdesc}}', $hdesc, $contents);

	$contents = str_replace('{{hview}}', $hview, $contents);
	$contents = str_replace('{{htime}}', $htime, $contents);
	$contents = str_replace('{{htag}}', $tags, $contents);
	$contents = str_replace('{{sider}}', $sider, $contents);
   
	fwrite($html_fp, $contents);//4.将内容写入静态文件
	
	//5.文件必须关闭
	fclose($html_fp);
	fclose($handle);
	if($tn==0){
		$db->exec("update href set html=1 where hid=$hid");
	}elseif($tn==2){
		$db->exec("update blog set html=1 where bid=$hid");
	}
	copy('../html/'.$htmlfile,'../'.$htmlfile);
}