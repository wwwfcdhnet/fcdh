<?php
if($_SERVER['HTTP_HOST']=='fcdh.net'){
	header("location:http://www.fcdh.net/");
	exit;
}
include 'sqlite_db.php';
include 'function.php';
$name=load_config('name');
$verify=load_config('verify');
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>新网站提交友情链接提交 - 非常导航</title>
    <meta name="keywords" content="新站提交，网站提交，友情链接，链接提交，网址修改">
    <meta name="description" content="提交新站的地方，只要你的网站访问正常就可正常收录，禁止垃圾网站，违法网站提交">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
	<link rel="stylesheet" href="http://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/fcdh.css">
    <link rel="stylesheet" href="./assets/css/bbs.css">
	<script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
</head>

<body>
    <!-- 最外层容器 -->
    <div id="container">
        <div id="sidebar"class="toggle-others">
		<div class="ti-fix">
                <header class="logo-env">
                    <!-- logo -->
                    <div class="logo">
                        <a href="http://www.fcdh.net/" class="logo-expanded">
                            <img src="./assets/images/logo@2x.png"alt="非常导航之电脑版本logo" />
                        </a>
                        <a href="http://www.fcdh.net/" class="logo-collapsed">
                            <img src="./assets/images/logo-collapsed@2x.png"alt="非常导航之手机版本logo" />
                        </a>
                    </div>
                </header>
                <ul id="menu"> 
                    <li>
                        <a href="./index.php" class="smooth">
                            <span>最新留言</span>
                            <i class="fa-fire"></i>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#this" class="smooth">
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
					<li><a href="http://www.fcdh.net/">非常导航</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
			<form id="myform">
				<h3><a class="c10"><i class="fa-bookmark"></i> 提交网站</a> <strong id="info"class="c2"></strong></h3>
				<p><label><input type="radio" name="ttype" value="1" checked>新站提交</label>&nbsp;&nbsp;<label><input type="radio" name="ttype" value="2">友情链接</label>&nbsp;&nbsp;<label><input type="radio" name="ttype" value="3">网站修改</label>
				</p>
				<p>
				<strong class="c8">友情提示：①所有网站提交不得违反中国法律。②友情链接需做好本站链接。③提交网站前，请用上述的“非”站内搜索查看你的网站是否已存在。如：你的网址www.fcdh.com,只搜索“fcdh”就可以。</strong>
				</p>
				<p>
				<label><a class="c3"><i class="fa-bookmark"></i> 网站链接</a></label><input type="text" class="form-control" name="url"maxlength="127"style="width:100%" id="url" placeholder="http://www.fcdh.net/"></p>
				
				<p><label><a class="c4"><i class="fa-bookmark"></i> 网站名称</a></label><input type="text" class="form-control" name="tname"maxlength="31"style="width:100%" id="tname" placeholder="非常导航"></p>

				<p><label><a class="c2"><i class="fa-bookmark"></i> 网站标题</a></label><input type="text" class="form-control" name="title"maxlength="63"style="width:100%" id="title" placeholder="非常绿色安全的上网导航 - 非常导航"></p>

				<p><label><a class="c6"><i class="fa-bookmark"></i> 网站关键词</a></label><input type="text" class="form-control" name="keyword"maxlength="127"style="width:100%" id="keyword" placeholder="非常导航，绿色导航，安全导航，绿色上网，安全上网，上网导航"></p>
				<p>
				<label><a class="c7"><i class="fa-bookmark"></i> 网站介绍</a></label>
				<textarea rows="5" id="text" placeholder="非常导航成立于冠状病毒爆发的2020年。本着网址收藏的爱好，所以要求导航网站既能自定义网址、上传下载网址，又能看着简洁美丽、不臃肿杂乱，还要绿色安全、无毒无弹窗，这是导航站的宗旨，也是上网爱好者的底线。www.fcdh.net" maxlength="100"></textarea>
				</p>
				<p>
				<label><?php if(load_config('scode')=='1'){?><img src="scode.php"alt="点击刷新" title="点击刷新" id="scodeimg" ></label><input type="text" class="form-control" name="code"maxlength="4"style="width:123px" id="scode" placeholder="验证码"><?php }?><button type="submit" class="btn btn-primary"value="4"onclick="optSite(this)">提交网站</button></p>
			</form>
			<div id="main">
				<h3><a><i class="fa-bookmark"></i>网站列表</a></h3>
				<div id="top">
				</div>
				<div id="list">
				</div>
			</div>
			<br/><br/><br/>
            <footer class="footer">
                    <div class="vcenter">
                         Since 2020 <strong><a href="http://www.fcdh.net/">www.fcdh.net</a></strong> <span class="ti-more"><a href="http://beian.miit.gov.cn/">渝ICP备20001609号</a></a>
                    </div>
                    <div id="go-up">
                        <a href="#" rel="go-top"title="顶部">
                            <i class="fa-tree"></i>
                        </a>
                    </div>
            </footer>
			<div id="ti-meng"></div>
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
 <script src="./assets/js/fcdh.js"></script>
	<script>
$(function(){
	var ttype=0;
	$('#scodeimg').bind('click',function(){this.src='scode.php?rand='+Math.random();});
	
    $('#myform').bind('submit',function(){
		if($('#url').val().trim()==''){
			$('#msg').html('请填写网站链接');
			$('#warning').modal('show');
			return false;
		}
		if($('#tname').val().trim()==''){
			$('#msg').html('请填写网站名称');
			$('#warning').modal('show');
			return false;
		}
		if($('#title').val().trim()==''){
			$('#msg').html('请填写网站标题');
			$('#warning').modal('show');
			return false;
		}
		if($('#keyword').val().trim()==''){
			$('#msg').html('请填写网站关键词');
			$('#warning').modal('show');
			return false;
		}
		if($('#text').val().trim()==''){
			$('#msg').html('请填写网站介绍');
			$('#warning').modal('show');
			return false;
		}
		if($('#scode').val()==''){
			$('#msg').html('请填写验证码');
			$('#warning').modal('show');
			return false;
		}
		ttype=$("input[name='ttype']:checked").val();
		$.post('js_post.php',{'content':$('#text').val(),'scode':$('#scode').val(),'rand':Math.random(),'ttype':ttype,'url':$('#url').val(),'tname':$('#tname').val(),'title':$('#title').val(),'keyword':$('#keyword').val()},function(data){
			if(data=='success'){
				$('#scode').val('');
				$('#title').val('');
				$('#keyword').val('');
				$('#text').val('');
				offset=0;
				load_content();
			}else if(data=='scode'){
				$('#scode').val('');
				$('#msg').html('验证码有误');
				$('#warning').modal('show');
			}else if(data=='chong'){
				$('#scode').val('');
				$('#msg').html('该网站重复提交');
				$('#warning').modal('show');
			}else{
				$('#scode').val('');
				$('#msg').html('不合法字符');
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
		$.post('js_content.php',{'page':page,'rand':Math.random(),'cate':1},function(data){
			$('#list').html(data);
		});
	}

	function load_top(){
		$.post('js_top.php',{'cate':1},function(data){
			$('#top').html(data);
		});
	}
	load_top();
	load_content();

});
</script>
</body>

</html>
