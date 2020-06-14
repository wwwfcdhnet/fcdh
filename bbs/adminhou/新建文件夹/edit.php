<?php
include 'admin.php';
$act=@$_GET['act'];
$id=@$_GET['id'];
$data=$db->query("select * from content where id=$id")->fetch();
if($act=='save'){
    $content=@$_POST['content'];
    $ref=@$_POST['ref'];
    $db->exec("UPDATE `content` SET content='$content' WHERE id=$id");
    redir($ref);
}
else{
    $ref=$_SERVER['HTTP_REFERER'];
}
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
    <form method="POST" action="?id=<?php echo $id;?>&act=save">
    <input type="hidden" name="ref" value="<?php echo $ref;?>">
    <div class="form-group">
    <label>修改</label>
    <textarea class="form-control" name="content" rows="5"><?php echo $data['content'];?></textarea>
    </div>
    <button type="submit" class="btn btn-primary btn-block">保存留言</button>
    
    </form>
    
</div>
</div>
</body>
</html>