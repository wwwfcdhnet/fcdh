<?php
include 'admin.php';
set_time_limit(600); // 10分钟60*10
ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
use Qiniu\Config;
use Qiniu\Qiniu;
require dirname(__FILE__) . '/autoload.php';

$ref=$_SERVER['HTTP_REFERER'];
$qiniu = new Qiniu($accessKey, $secretKey);

if(isset($_GET['up'])){ // 上传文件
	$dir = "../html/";
	$htmlfile=$_GET['up'];
	$type='';
	if(isset($_GET['type'])){
		$type=filterTitle($_GET['type']);
	}
	if($type=='tag'){
		$dir = "../html/tag/";
	}

	if($htmlfile!='half' && file_exists($dir.$htmlfile)){ //  一个页面一个页面的上传
		$file = $qiniu->file($_bucket, $htmlfile);
		$response = $file->put($dir.$htmlfile);
		if((bool)$response){// 如果上传成功 ，并删除本地数据
			unlink($dir.$htmlfile);
		}
	}else{ // 一次上传50个页面
		$dir = "../html/";
		if (is_dir($dir)){ // href html
		  if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($htmlfile = readdir ( $handle )) ) {
				if ($htmlfile != "." && $htmlfile != ".." && 'html'==substr(strrchr($htmlfile, '.'), 1)) {
					if($htmlfile=='404.html')continue;
					if(1000==$i++){
						break;
					}
					$file = $qiniu->file($_bucket, $htmlfile);
					$response = $file->put($dir.$htmlfile);
					if((bool)$response){// 如果上传成功 ，并删除本地数据
						unlink($dir.$htmlfile);
					}
				}
			}
			closedir($handle );
		  }
		}

		$dir = "../html/tag/";
		if (is_dir($dir)){ // tag html
		  if (false != ($handle = opendir ( $dir ))) {
			while ( false !== ($htmlfile = readdir ( $handle )) ) {
				if ($htmlfile != "." && $htmlfile != ".." && 'html'==substr(strrchr($htmlfile, '.'), 1)) {
					if($htmlfile=='404.html')continue;
					if(1000==$i++){
						break;
					}
					$file = $qiniu->file($_bucket, $htmlfile);
					$response = $file->put($dir.$htmlfile);
					if((bool)$response){// 如果上传成功 ，并删除本地数据
						unlink($dir.$htmlfile);
					}
				}
			}
			closedir($handle );
		  }
		}
	}

}elseif(isset($_GET['del'])){ // 删除文件
	$type='';
	if(isset($_GET['type'])){
		$type=filterTitle($_GET['type']);
	}
	$htmlfile=$_GET['del'];
	$index=substr($htmlfile,0,strripos($htmlfile,'.'));
	if($type=='href'){ // 删除href html文件
		if(file_exists('../html/'.$htmlfile)){
			unlink('../html/'.$htmlfile);
		}
		if(file_exists('../'.$htmlfile)){
			unlink('../'.$htmlfile);
		}
		$response = true;
		if((bool)$response){
			$db->exec("update href set html=-1 where hindex='$index'");
			$db->exec("insert into htmltn(htmlname,tn) values('$index',1)");
		}
		$file = $qiniu->file($_bucket, $htmlfile);
		$file->delete();

		$file = $qiniu->file($_bucket, 'assets/logos/'.$index.'.png');
		$file->delete();
	}elseif($type=='tag'){// 删除tag html文件
		if(file_exists('../html/tag/'.$htmlfile)){
			unlink('../html/tag/'.$htmlfile);
		}
		if(file_exists('../'.$htmlfile)){
			unlink('../'.$htmlfile);
		}
		$response = true;
		if((bool)$response){
			$db->exec("update tag set html=-1 where tindex='$index'");
			$db->exec("insert into htmltn(htmlname,tn) values('$index',2)");
		}
		$file = $qiniu->file($_bucket, $htmlfile);
		$file->delete();
	}else{ // 删除ico图标
		$htmlfile=$_GET['del'];
		if(file_exists('../assets/ico/'.$htmlfile) && 'png'==substr(strrchr($htmlfile, '.'), 1)){
			list($width, $height) = getimagesize('../assets/ico/'.$htmlfile);
			if($width==32 && $height==32){
				unlink('../assets/ico/'.$htmlfile);
			}
		}
		if(file_exists('../assets/logos/'.$htmlfile)){
			unlink('../assets/logos/'.$htmlfile);
		}
		//$file = $qiniu->file($_bucket, 'assets/logos/'.$htmlfile);
		//$response = $file->delete();
		$response=true;
		if((bool)$response){
			$hindex=substr($htmlfile,0,strripos($htmlfile,'.'));
			$db->exec("update href set hico=0 where hindex='$hindex'");
		}
	}
}elseif(isset($_GET['ico'])){ // 上传ico图标
	$dir = "../assets/ico/";
	$dirlogo = "../assets/logos/";
	$qndir='assets/logos/';
	$htmlfile=$_GET['ico'];
	if($htmlfile!='half' && file_exists($dir.$htmlfile) && 'png'==substr(strrchr($htmlfile, '.'), 1)){ //  一个页面一个页面的上传
		$file = $qiniu->file($_bucket, $qndir.$htmlfile);
		list($width, $height, $type, $attr) = getimagesize($dir.$htmlfile);
		if($width!=32 || $height!=32){
			unlink($dir.$htmlfile);
		}else{
			$response = $file->put($dir.$htmlfile);
			$hindex=substr($htmlfile,0,strripos($htmlfile,'.'));
			if((bool)$response){// 如果上传成功 ，并删除本地数据
				//unlink($dir.$htmlfile);
				$db->exec("update href set hico=1 where hindex='$hindex'");
				rename($dir.$htmlfile,$dirlogo.$htmlfile);
			}
		}
	}else{ // 一次上传50个页面
		if (is_dir($dir)){
		  if (false != ($handle = opendir ( $dir ))) {
			while ( false !== ($htmlfile = readdir ( $handle )) ) {
				$i=0;
				if ($htmlfile != "." && $htmlfile != ".." && 'png'==substr(strrchr($htmlfile, '.'), 1)) {
					$hindex=substr($htmlfile,0,strripos($htmlfile,'.'));
					$file = $qiniu->file($_bucket, $qndir.$htmlfile);
					list($width, $height, $type, $attr) = getimagesize($dir.$htmlfile);
					if($width!=32 || $height!=32)continue;
					if(50==$i++){
						break;
					}
					$response = $file->put($dir.$htmlfile);
					if((bool)$response){// 如果上传成功 ，并删除本地数据
						//unlink($dir.$htmlfile);
						$db->exec("update href set hico=1 where hindex='$hindex'");
						rename($dir.$htmlfile,$dirlogo.$htmlfile);
					}
				}
			}
			closedir($handle );
		  }
		}
	}

}
redir($ref);
?>