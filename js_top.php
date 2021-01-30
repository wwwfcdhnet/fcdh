<?php
include 'sqlite_db.php';
include 'function.php';

$cate=intval(@$_POST['cate']);
if($cate){
	$res=$db->query("select * from contenthref where verify=1 and top=1 order by top desc,addtime desc limit 0,10")->fetchAll();
}else{
	$res=$db->query("select * from content where verify=1 and top=1 order by top desc,addtime desc limit 0,10")->fetchAll();
}
$ttype=array('1'=>'新站提交','2'=>'友情链接','3'=>'网站修改');
if(!$res) exit;
foreach($res as $r){
?>
<dl>
	<dt>
		 <strong> <?php echo date('Y-m-d H:i',$r['addtime']);?></strong> | <strong><?php echo $r['ip'];?></strong>
	</dt>
	<dd>
		<?php if($r['top']==1) echo '<strong class="c2">【置顶】</strong>';if($cate)echo'<strong class="c10">〖',$ttype[$r['cate']],'〗</strong><br>【网站名称】<strong class="c4">',$r['tname'],'</strong><br>【网站链接】<strong class="c1">',$r['url'],'</strong><br>【网站标题】<strong class="c5">',$r['title'],'</strong><br>【关键字词】<strong class="c6">',$r['keyword'],'</strong><br>【网站介绍】<strong class="c3">';echo $r['content'];if($cate)echo'</strong>';?>
	</dd>
	<?php if($r['reply']!=''){?>
	<dd class="c5">
		 <strong class="c0">【回复】</strong><?php echo $r['reply'];?>
	</dd>
    <?php }?>
</dl>
<?php
}
?>