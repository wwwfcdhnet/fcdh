<?php
if($_SERVER['HTTP_HOST']=='fcdh.net'){
//	header("location:https://www.fcdh.net/");
//	exit;
}
include 'sqlite_db.php';
include 'function.php';
//$name=load_config('name');
//$verify=load_config('verify');
$url='';
if(isset($_GET['url'])){
	$url=$_GET['url'].'.html 网址有问题!';
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>留言板 - 非常导航</title>
    <meta name="keywords" content="留言板，非常导航">
    <meta name="description" content="留言板，非常导航">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/fcdh.css">
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
	<style>body{background: #f2f2f2;opacity:1;}</style>
</head>

<body>
    <!-- 最外层容器 -->
    <div id="container">
        <div id="sidebar"class="toggle-others">
		<div class="ti-fix">
                <header class="logo-env">
                    <!-- logo -->
                    <div class="logo">
                        <a href="./" class="logo-expanded">
                            <img src="./assets/images/logo@2x.png"alt="非常导航之电脑版本logo" />
                        </a>
                        <a href="./" class="logo-collapsed">
                            <img src="./assets/images/logo-collapsed@2x.png"alt="非常导航之手机版本logo" />
                        </a>
                    </div>
                </header>
                <ul id="menu"> 
                    <li class="active">
                        <a href="#this" class="smooth">
                            <span>最新留言</span>
                            <i class="fa-fire"></i>
                        </a>
                    </li>
                    <li>
                        <a href="sitesub.php" class="smooth">
                            <span>提交网站</span>
                            <i class="fa-link"></i>
                        </a>
                    </li>
                </ul>
        </div>
        </div>
        <div class="content">
            <nav class="navbar user-info-navbar" role="navigation">
                <!-- User Info, Notifications and Menu Bar -->
                <!-- Left links for user info navbar -->
                <ul class="user-info-menu">
                    <li id="ti-side">
                        <a href="#">
                            <i class="fa-bars"></i>
                        </a>
                    </li>					
                    <li id="ti-menu">
                        <a href="#">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>	
                    <li id="ti-home">
                        <a href="./">
                            <i class="fa-home"></i>
                        </a>
                    </li>
					
					<li>
                        <div id="ti-search"class="searchs">
                            <form action="https://www.baidu.com/s" target="_blank" method="get">
								<div class="input-group">
									<img id="ti-icon"src="assets/images/baidu.png" alt="0">
									<ul id="ti-engine">
									</ul>				
									<!-- 搜索热词 -->
									<input id="txt"type="text" class="form-control" name="word" placeholder="随时搜索一下" maxlength="255"autocomplete="off">
									<span class="input-group-btn">
										<button type="submit"class="btn btn-primary fa-search"> 百度</button>
									</span>	
									<ul id="box">	
										
									</ul>	
								</div>
							</form>
                        </div>
                    </li>
                </ul>
				<ul class="ti-ads">
					<li><a href="./">粉美人</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
			<form id="myform">
				<h3><a class="c10"><i class="fa-bookmark"></i> BBS留言</a> <strong id="info"class="c2"></strong></h3>
				<p>
				<label><a class="c2"><i class="fa-bookmark"></i> 电子邮箱</a> <a href="https://www.fcdh.net/youxiang.html" title="免费电子邮箱申请"class="ti-more"target="_blank">◆邮箱申请 &raquo;</a> <strong id="info"class="c2"></strong></label><input type="text" class="form-control" name="email"maxlength="21"style="width:100%" id="email" placeholder="电子邮箱，方便回复您"></p>
				<p>
				<label><a class="c5"><i class="fa-bookmark"></i> 留言内容</a></label>
				<textarea rows="5" id="text" placeholder="留言内容" maxlength="100"><?php echo$url;?></textarea>
				</p>
				
				<p>
				<label><?php if(load_config('scode')=='1'){?><img src="scode.php"alt="点击刷新" title="点击刷新"id="scodeimg" ></label><input type="text" class="form-control" name="code"maxlength="4"style="width:123px" id="scode" placeholder="验证码"><?php }?><button type="submit" class="btn btn-primary"value="4">发布留言</button></p>
			</form>
			<div id="main">
				<h3><a><i class="fa-bookmark"></i>留言列表</a></h3>
				<div id="top">
				</div>
				<div id="list">
				</div>
			</div>
			<div class="endnav">
            <footer class="footer">
					<div class="vcenter">
						<span>Copyright © Since 2020 <a href="/"><strong>非常导航</strong></a>. All Rights Reserved.</span>
						<span>Powered By fcdh.net. <a href="https://beian.miit.gov.cn/"target="_blank">渝ICP备20001609号</a></span>
						<span>非常导航留言系统V1.0</span>
                    </div>
                    <div id="go-up">
                        <a href="#" rel="go-top"title="顶部">
                            <i class="fa-tree"></i>
                        </a>
                    </div>
            </footer>
			<div id="ti-meng"></div>
			</div>
		</div>
    </div> <!-- end 最处层容器 -->

		<!-- 确定信息 --> 
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
		</div><!-- end 模态 -->
	</div>
	<script>
$(function(){

	$('#scodeimg').bind('click',function(){this.src='scode.php?rand='+Math.random();});

    $('#myform').bind('submit',function(){
		if($('#text').val().trim()==''){
			$('#msg').html('请填写留言内容');
			$('#warning').modal('show');
			return false;
		}
		if($('#scode').val()==''){
			$('#msg').html('请填写验证码');
			$('#warning').modal('show');
			return false;
		}
		$.post('js_post.php',{'content':$('#text').val(),'scode':$('#scode').val(),'rand':Math.random(),'email':$('#email').val()},function(data){
			//alert(data);
			if(data=='success'){
				$('#text').val('');
				$('#scode').val('');
				$('#email').val('');
				offset=0;
				load_content();
			}else if(data=='scode'){
				$('#scode').val('');
				$('#msg').html('验证码有误');
				$('#warning').modal('show');
			}else{
				$('#scode').val('');
				$('#msg').html('电子邮箱包含不合法字符');
				$('#warning').modal('show');
			}
			$('#scodeimg').attr('src','scode.php?rand='+Math.random());
		});
		return false;
	});

	$(window).bind('hashchange',function(){
		load_content();
	});




	function load_content(){
		var page=window.location.hash;
		page=page.replace('#','');
		if(page=='') page=1;
		$('#list').html('数据加载中');
		$.post('js_content.php',{'cate':0,'page':page,'rand':Math.random()},function(data){
			$('#list').html(data);
		});
	}

	function load_top(){
		$.post('js_top.php',{'cate':0},function(data){
			$('#top').html(data);
		});
	}
	load_top();
	load_content();

});
</script>
<script src="./assets/js/fcdh.js"></script>
</body>

</html>
