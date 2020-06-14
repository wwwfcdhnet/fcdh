<?php
include 'function.php';
$page=intval(@$_POST['page']);
$pagesize=load_config('pagesize');
$offset=($page-1)*$pagesize;
$res=$db->query("select count(id) from content where verify=1 and top<>1")->fetch();
$total=$res[0];
$pages=ceil($total/$pagesize);

$res=$db->query("select * from content where verify=1 and top<>1 order by top desc,addtime desc limit $offset,$pagesize")->fetchAll();
if(!$res) exit('<p class="mt-3 text-danger">没有数据</p>');
foreach($res as $r){
?>
<dl>
	<dt>
		<strong><?php echo date('Y-m-d H:i',$r['addtime']);?></strong> | <strong><?php echo $r['ip'];?></strong>
	</dt>
	<dd>
		<?php if($r['top']==1) echo '<strong class="c2">【置顶】</strong>';echo $r['content'];?>
	</dd>
	<?php if($r['reply']!=''){?>
	<dd class="c5">
		 <strong class="c9">【回复】</strong><?php echo $r['reply'];?>
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