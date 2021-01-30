<?php
set_time_limit(600); // 100分钟
ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
include 'admin.php';
if(isset($_GET['hid'])){ // 生成静态页面
	$start=intval($_GET['hid']);
	$end=$start+1000;
	$sql="select hid from href where hid between $start and $end";
	$res=$db->query($sql)->fetchAll();
	foreach($res as $r){
		++$count;
		hrefhtml($r['hid']);
	}
	if($count<1001)$end=0;
	echo ++$end;
}else{
	$tn='';
	$r=$db->query("select value from config where key='hrefstartid'")->fetch();
	$start=$r['value'];
	$end=$start+100;
	$sql="select hid,hindex,hurl from href where hid between $start and $end";
	$hrefarr=$db->query($sql)->fetchAll();
	$count=0;
	foreach($hrefarr as $href){
		++$count;
		$tn=$href['hid'];
		set_siteurl_state($href['hurl'],$href['hindex']); // 设置链接访问状态
	}
	if($count<101)$tn=1;
	$db->exec("update config set value=$tn where key='hrefstartid'");
	echo $tn;
}