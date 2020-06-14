<?php
include 'admin.php';
$act=@$_GET['act'];
$msg='';
if($act=='save'){
    $name=@$_POST['name'];
    $pagesize=@$_POST['pagesize'];
    $scode=@$_POST['scode'];
    $verify=@$_POST['verify'];

    save_config('name',$name);
    save_config('pagesize',$pagesize);
    save_config('scode',$scode);
    save_config('verify',$verify);

    $msg='保存成功';
}

$name=load_config('name');
$pagesize=load_config('pagesize');
$scode=load_config('scode');
$verify=load_config('verify');

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
    <form method="post" action="?act=save">
    <div class="form-group">
    <label>留言板名称</label>
    <input type="text" class="form-control" value="<?php echo $name;?>" name="name" required>
    </div>
    <div class="form-group">
    <label>每页显示留言数量</label>
    <input type="text" class="form-control" value="<?php echo $pagesize;?>" name="pagesize" required>
    </div>
    <div class="form-group">
    <label>是否显示验证码</label>
    <select class="form-control" name="scode"><option value="1" <?php echo $scode=='1'?'selected':''; ?>>是</option><option value="0" <?php echo $scode=='0'?'selected':''; ?>>否</option></select>
    </div>
    <div class="form-group">
    <label>是否需要审核</label>
    <select class="form-control" name="verify"><option value="1" <?php echo $verify=='1'?'selected':''; ?>>是</option><option value="0" <?php echo $verify=='0'?'selected':''; ?>>否</option></select>
    </div>
    <button type="submit" class="btn btn-primary btn-block">保存设置</button>
    </form>
    <p class="text-danger mt-3"><?php echo $msg; ?></p>
</div>
</div>
</body>
</html>