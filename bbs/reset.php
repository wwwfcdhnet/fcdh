<?php
include 'function.php';
$act=@$_GET['act'];
if($act=='save'){
    $name=@$_POST['name'];
    $pass=@$_POST['pass'];
    $pass=md5($pass);
    $db->exec("UPDATE `admin` SET name='$name',pass='$pass' WHERE id=1");
    echo '重置成功';
    session_start();
    unset($_SESSION['login']);
    exit;
}
?>
<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>管理员用户名和密码重置</title>
  <meta name="renderer" content="webkit">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >
</head>
<body>
<div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-xs-12 pt-5">
                <form method="post" class="form" id="myform" action="?act=save">
                    <label for="name">账号:</label>
                    <input type="text" name="name" id="name" value="" class="form-control" required>
                    <br>
                    <label for="pass">密码:</label>
                    <input type="password" name="pass" id="pass" class="form-control" value="" required>
                    <br>
                    <input type="submit" name="" value="重置" class="btn btn-primary btn-block">
                </form>
            </div>
        </div>
    </div>
</body>
</html>