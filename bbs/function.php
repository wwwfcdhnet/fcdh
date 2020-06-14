<?php
date_default_timezone_set('Asia/Shanghai');
define('ROOT',__DIR__);
try{
  $db = new PDO('sqlite:'.ROOT.'/book.db');
}catch (Exception $e) {
  echo $e->getMessage();
  exit;
}
$db->exec('set names utf8');



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
				return preg_match("/^[a-zA-Z0-9]{1,32}$/",$str)?true:false;
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
    $arr = array('`' => '', '~' => '', '!' => '', '@' => '#', '$' => '', '%' => '', '^' => '', '&' => '', '*' => '', '(' => '', ')' => '-', '_' => '', '+' => '', '|' => '', '\\' => '', '{' => '', '[' => '', '}' => '', ']' => '', ';' => '', ':' => '', '"' => '\"', "'" => '\'','\"' => '\"', '\'' => '\'', ',' => '', '?' => '');
	if($allow)$str=strip_tags($str,'<img><strong>');
    return substr(strtr($str, $arr),0,90);  
}

