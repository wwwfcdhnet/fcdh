<?php
include 'admin.php';
set_time_limit(600); // 10����60*10
ignore_user_abort();//�ص��������PHP�ű�Ҳ���Լ���ִ��.
use Qiniu\Config;
use Qiniu\Qiniu;
require dirname(__FILE__) . '/autoload.php';

$ref=$_SERVER['HTTP_REFERER'];
$qiniu = new Qiniu($accessKey, $secretKey);

if(isset($_GET['up'])){ // �ϴ��ļ�
	$dir = "../html/";
	$htmlfile=$_GET['up'];
	$type='';
	if(isset($_GET['type'])){
		$type=filterTitle($_GET['type']);
	}
	if($type=='tag'){
		$dir = "../html/tag/";
	}

	if($htmlfile!='half' && file_exists($dir.$htmlfile)){ //  һ��ҳ��һ��ҳ����ϴ�
		$file = $qiniu->file($_bucket, $htmlfile);
		$response = $file->put($dir.$htmlfile);
		if((bool)$response){// ����ϴ��ɹ� ����ɾ����������
			unlink($dir.$htmlfile);
		}
	}else{ // һ���ϴ�50��ҳ��
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
					if((bool)$response){// ����ϴ��ɹ� ����ɾ����������
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
					if((bool)$response){// ����ϴ��ɹ� ����ɾ����������
						unlink($dir.$htmlfile);
					}
				}
			}
			closedir($handle );
		  }
		}
	}

}elseif(isset($_GET['del'])){ // ɾ���ļ�
	$type='';
	if(isset($_GET['type'])){
		$type=filterTitle($_GET['type']);
	}
	$htmlfile=$_GET['del'];
	$index=substr($htmlfile,0,strripos($htmlfile,'.'));
	if($type=='href'){ // ɾ��href html�ļ�
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
	}elseif($type=='tag'){// ɾ��tag html�ļ�
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
	}else{ // ɾ��icoͼ��
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
}elseif(isset($_GET['ico'])){ // �ϴ�icoͼ��
	$dir = "../assets/ico/";
	$dirlogo = "../assets/logos/";
	$qndir='assets/logos/';
	$htmlfile=$_GET['ico'];
	if($htmlfile!='half' && file_exists($dir.$htmlfile) && 'png'==substr(strrchr($htmlfile, '.'), 1)){ //  һ��ҳ��һ��ҳ����ϴ�
		$file = $qiniu->file($_bucket, $qndir.$htmlfile);
		list($width, $height, $type, $attr) = getimagesize($dir.$htmlfile);
		if($width!=32 || $height!=32){
			unlink($dir.$htmlfile);
		}else{
			$response = $file->put($dir.$htmlfile);
			$hindex=substr($htmlfile,0,strripos($htmlfile,'.'));
			if((bool)$response){// ����ϴ��ɹ� ����ɾ����������
				//unlink($dir.$htmlfile);
				$db->exec("update href set hico=1 where hindex='$hindex'");
				rename($dir.$htmlfile,$dirlogo.$htmlfile);
			}
		}
	}else{ // һ���ϴ�50��ҳ��
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
					if((bool)$response){// ����ϴ��ɹ� ����ɾ����������
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