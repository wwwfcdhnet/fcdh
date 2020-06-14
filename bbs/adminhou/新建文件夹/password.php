<?php
include 'admin.php';
$act=@$_GET['act'];
$msg='';
if($act=='save'){
    $password=@$_POST['password'];
    if($password=='' || !$password){
        $msg='密码不能为空';
    }
    $password=md5($password);
    $db->exec("update admin set pass='$password' where name='admin'");
    $msg='密码修改成功';
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
    <form method="POST" action="?act=save">
    <div class="form-group">
    <label>新密码</label>
    <input type="text" class="form-control" value="" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">修改密码</button>
    </form>
    <p class="text-danger mt-3"><?php echo $msg; ?></p>
</div>
</div>
</body>
</html>