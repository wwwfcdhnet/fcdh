<?php
include '../sqlite_db.php';
include '../function.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理员登录 - 非常导航</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="../assets/images/favicon.png">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
</head>

<body>
<div id="login">
	<form method="post" id="logoform">
		<h2>管理员登录</h2>
		<p><label for="name">账号: </label>
		<input type="text" class="form-control" name="name" id="name"maxlength="16"autocomplete="off"placeholder="账号" required></p>
		<p><label for="pass">密码: </label>
		<input type="password" class="form-control" name="pass" id="pass" maxlength="16"autocomplete="off"placeholder="密码" required></p>
		<p class="pcode">
		<label><?php if(load_config('scode')=='1'){?><img src="../scode.php"alt="点击刷新" title="点击刷新" id="scodeimg"></label><input type="text" class="form-control" name="code"maxlength="4"autocomplete="off"style="width:117px"id="scode" placeholder="验证码"><?php }?><button type="submit" class="btn btn-primary"value="登 录">登 录</button>
		</p>
	</form>
</div>

<div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog"> 
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
			<h3 class="modal-title" id="myModalLabel"> 提示信息 </h3> 
		</div>
		<div class="modal-body"><p class="modal-body"><strong id="msg"></strong></p></div>
		<div class="modal-footer"> 
			<button id="cancel"type="submit" class="btn btn-primary" data-dismiss="modal"> 确 定 </button> 
		</div> 
	</div><!-- end 模态 3eff17c13a49b6c775cc678cced01548 14cbb03dcc85b12ba1aabff470a90dcbc -->
</div>
<script src="../assets/js/fcdh.js"></script>
<script>
	$(function () {
		$('#scodeimg').bind('click',function(){this.src='../scode.php?rand='+Math.random();});
		$('#logoform').bind('submit', function () {//alert();return;
			var pass = $('#pass').val();
			var name = $('#name').val();
			var scode= $('#scode');
			if(scode.val()==''){
				$('#msg').html('请填写验证码');
				$('#warning').modal('show');
				return false;
			}
			$.post('js_login.php', { 'name': name, 'pass': pass,'scode':scode.val(), 'rand': Math.random() }, function (data) {
				//alert(data);
				if (data == 'success') {
					window.location.href = 'index.php';
				}else if(data=='scode') {
					scode.val('');
					$('#msg').html('验证码有误');
					$('#warning').modal('show');
				}else{
					$('#msg').html('账户或密码错误');
					$('#warning').modal('show');
				}
					$('#scodeimg').attr('src','../scode.php?rand='+Math.random());
			});
			return false;
		});
	});
</script>
</body>

</html>