<?php
if(isset($_GET['url'])){
	include '../function.php';
	$hurl=$_GET['url'];
	$pageArr=get_siteurl_curlinfo($hurl);
	print_r($pageArr);
	//echo$pageArr['page_info']['site_keywords'];
}else{
	include 'admin.php';
//	header('Access-Control-Allow-Origin:https://www.fcdh.net'); // 允许单个域名跨域

	$res=array(true,'获取成功!','');
	if(isset($_POST['add']) && !empty($_POST['add'])){ 
		if(get_magic_quotes_gpc()){//如果get_magic_quotes_gpc()是打开的
			$str=stripslashes($_POST['add']);//将字符串进行处理
		}else{
			$str=$_POST['add'];
		}
		$arrstr=json_decode($str,true);
		$hurl=$arrstr['0'];
		$edit=intval($arrstr['1']);
		$res['index']=get_url_index($hurl);
		$haveico=0;
		$url=$db->query('select hid,hico from href where hindex="'.$res['index'].'"')->fetch(); 
		if(empty($url[0]) || $edit){ // 查看是否库中存在该网址 $tn=1 表示是在编辑该网站
			$res['have']=false;
			
			$pageArr=get_siteurl_curlinfo($hurl);
			$res['title']=$res['key']=$res['desc']='';
			if(isset($pageArr['page_info']['site_title']))$res['title']=$pageArr['page_info']['site_title'];		
			if(isset($pageArr['page_info']['site_keywords']))$res['key']=$pageArr['page_info']['site_keywords'];	
			if(isset($pageArr['page_info']['site_description']))$res['desc']=$pageArr['page_info']['site_description'];	
			if(empty($res['desc']) && isset($pageArr['page_info']['meta_array']['description'])){
				$res['desc']=$pageArr['page_info']['meta_array']['description'];
			}
			if(empty($res['key']) && isset($pageArr['page_info']['meta_array']['keywords'])){
				$res['key']=$pageArr['page_info']['meta_array']['keywords'];
				if(empty($res['key'])&&isset($pageArr['page_info']['meta_array']['keyword'])){
					$res['key']=$pageArr['page_info']['meta_array']['keyword'];
				}
			}
			get_site_favicon($hurl,$haveico); // 获取网站的 favicon.ico
		}else{
			$res['have']=true;
			$haveico=$url[1];

			//获取域名前缀
			$urlarr=parse_url($hurl);
			$host=$urlarr['host'];
			$host=str_replace('com.cn','cn',$host);
			$host=str_replace('net.cn','cn',$host);
			$host=str_replace('org.cn','cn',$host);
			$host=str_replace('gov.cn','cn',$host);
			$host=str_replace('tw.cn','cn',$host);
			$hostarr=explode('.',$host);
			$len=count($hostarr);
			$res['domain']=$hostarr[$len-2];
		}
	}else{
		$res[0]=false;
		$res[2]='err 555';
	}
		
	echo json_encode($res);
}
?>