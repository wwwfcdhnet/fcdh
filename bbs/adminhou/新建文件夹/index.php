<?php
include 'admin.php';
$page=intval(@$_GET['page']);
if(!$page) $page=1;
$pagesize=10;
$offset=($page-1)*$pagesize;
$res=$db->query("select count(id) from content")->fetch();
$total=$res[0];
$pages=ceil($total/$pagesize);
$res=$db->query("select * from content order by top desc,addtime desc limit $offset,$pagesize")->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include 'head.php';?>
    
    <div class="container-fluid">
        <div class="col-lg-12 mt-3">
        <?php
    if(str_replace([ROOT,'\\','/'],['','',''],ADMIN)=='admin'){
    ?>
    <div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span>管理目录为admin,建议更换！</span>
    </div>
    <?php
    }
    ?>
                <?php

foreach($res as $r){
?>
<div class="card mb-3" style="font-size:14px;">
  <div class="card-header">时间：<?php echo date('Y-m-d H:i:s',$r['addtime']);?></div>
  <div class="card-body">
<?php if($r['top']==1) echo '<span style="color:#F00;">[置顶]</span>';?><?php if($r['verify']!=1) echo '<span style="color:#F00;">[未审核]</span>';?><?php echo $r['content'];?><hr><?php if($r['reply']){?>管理回复:<?php echo $r['reply'];?><hr><?php }?><?php echo $r['ip'];?></div> 
  <div class="card-footer"><?php if($r['top']==1){?>
                        <a href="top.php?id=<?php echo $r['id']?>&cancel=1" onclick="return window.confirm('是否取消置顶？')">取消置顶</a> 
                        <?php }else{ ?>
                        <a href="top.php?id=<?php echo $r['id']?>" onclick="return window.confirm('是否置顶？')">置顶</a> 
                        <?php }?>
                        <?php if($r['verify']==1){?>
                        <a href="verify.php?id=<?php echo $r['id']?>&cancel=1" onclick="return window.confirm('是否取消审核？')">取消审核</a> 
                        <?php }else{ ?>
                        <a href="verify.php?id=<?php echo $r['id']?>" onclick="return window.confirm('是否通过审核？')">审核</a> 
                        <?php }?>
                        <a href="edit.php?id=<?php echo $r['id']?>">修改</a> 
                        <a href="reply.php?id=<?php echo $r['id']?>">回复</a> 
                        <a href="del.php?id=<?php echo $r['id'];?>" onclick="return window.confirm('是否删除？')">删除</a></div>
</div>

              
                <?php
}
?>
           
            
            <nav aria-label="Page navigation ">
                <ul class="pagination" id="pager">
                    <?php
if($page>1){
?>
                    <li class="page-item">
                        <a href="?page=<?php echo $page-1;?>" class="page-link" id="prev">上一页</a>
                    </li>
                    <?php
}
if($pages>$page){
?>
                    <li class="page-item">
                        <a href="?page=<?php echo $page+1;?>" class="page-link" id="next">下一页</a>
                    </li>
                    <?php
}
?>
                </ul>
            </nav>
            
            <div style="height:30px;"></div>
        </div>
    </div>

    
    <script>
        $(function () {

        });
    </script>

</body>

</html>