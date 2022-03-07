<?php
include 'sqlite_db.php';
include 'function.php';
$page=intval(@$_POST['page']);
$cate=intval(@$_POST['cate']);
$pagesize=load_config('pagesize');
$offset=($page-1)*$pagesize;
$table='content';
if($cate){
	$table='contenthref';
}
$res=$db->query("select count(id) from $table where verify=1 and top<>1")->fetch();
$total=$res[0];
$pages=ceil($total/$pagesize);
$res=$db->query("select * from $table where verify=1 and top<>1 order by top desc,addtime desc limit $offset,$pagesize")->fetchAll();
if(!$res) exit('<p class="mt-3 text-danger">没有数据</p>');
$ttype=array('1'=>'新站提交','2'=>'友情链接','3'=>'网站修改');
foreach($res as $r){
?>
<dl>
	<dt>
		<strong><?php echo date('Y-m-d H:i',$r['addtime']);?></strong> | <strong><?php echo $r['ip'];?></strong>
	</dt>
	<dd>
		<?php if($r['top']==1) echo '<strong class="c2">【置顶】</strong>';if($cate)echo'<strong class="c10">〖',$ttype[$r['cate']],'〗</strong><br>【网站名称】<strong class="c4">',$r['tname'],'</strong><br>【网站链接】<strong class="c1">',$r['url'],'</strong><br>【网站标题】<strong class="c5">',$r['title'],'</strong><br>【关键字词】<strong class="c6">',$r['keyword'],'</strong><br>【网站介绍】<strong class="c3">';echo $r['content'];if($cate)echo'</strong>';?>
	</dd>
	<?php if($r['reply']!=''){?>
	<dd class="c5">
		 <strong class="c7">【回复】</strong><?php echo $r['reply'];?>
	</dd>
    <?php }?>
</dl>
<?php
}
?>
<nav id="pages">
    <?php
if($page>1){
?>
      <a href="#<?php echo $page-1;?>" class="btn btn-primary" id="prev">上一页</a>
    <?php
}
if($pages>$page){
?>
      <a href="#<?php echo $page+1;?>" class="btn btn-primary" id="next">下一页</a>
    <?php }?>
</nav>