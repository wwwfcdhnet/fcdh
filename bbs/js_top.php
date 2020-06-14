<?php
include 'function.php';
$res=$db->query("select * from content where verify=1 and top=1 order by top desc,addtime desc limit 0,10")->fetchAll();
if(!$res) exit;
foreach($res as $r){
?>
<dl>
	<dt>
		 <strong> <?php echo date('Y-m-d H:i',$r['addtime']);?></strong> | <strong><?php echo $r['ip'];?></strong>
	</dt>
	<dd>
		<?php if($r['top']==1) echo '<strong class="c2">【置顶】</strong>';echo $r['content'];?>
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