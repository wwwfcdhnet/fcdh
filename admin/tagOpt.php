<?php
include 'admin.php';
$act=@$_GET['act'];
$id=@$_GET['id'];
$data=$db->query("select * from cate where cid=$id")->fetch();
if($act=='save'){
    $content=@$_POST['content'];
    $ref=@$_POST['ref'];
    $db->exec("UPDATE `content` SET content='$content' WHERE id=$id");
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title><?php echo$data['ctitle'];if($data['cname']!='index')echo ' - 非常导航';?></title>
    <meta name="keywords" content="<?php echo$data['keys'];?>">
    <meta name="description" content="<?php echo$data['desc'];?>">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <!--<link rel="stylesheet" href="./assets/css/font-awesome.min.css">-->
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
    <!--<script src="./assets/js/jquery-1.11.1.min.js"></script>-->
    <script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/blueimp-md5/2.12.0/js/md5.min.js"></script>
</head>
<body>
    <!-- 最外层容器 -->
    <div id="container">
        <div id="sidebar"class="toggle-others">
		<div class="ti-fix">
                <header class="logo-env">
                    <!-- logo -->
                    <div class="logo">
                        <a href="tag.php" class="logo-expanded">
                            <img src="../assets/images/logo@2x.png"alt="非常导航之电脑版本logo" />
                        </a>
                        <a href="tag.php" class="logo-collapsed">
                            <img src="../assets/images/logo-collapsed@2x.png"alt="非常导航之手机版本logo" />
                        </a>
                    </div>
                </header>
                <ul id="menu"> 
					<li>
						<a class="ti-hand">
                            <span>分类直达</span><i class="fa-paper-plane"></i>
                        </a>
					</li>
					  
					<li>
					<a class="ti-hand">
						<span>分类直达</span><i class="fa-paper-plane"></i>
					</a> 
					<ul class="ti-arrow">
						<li class="active">
							<a href="#records" class="smooth">
								<span>常 用</span>
							</a>
						</li>
					</ul>
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
                            <i class="fa-bars"></i>
                        </a>
                    </li>	
                </ul>
				<ul class="ti-ads">
					<li data-toggle="modal"data-target="#addcate"><a class="ti-hand"><i class="fa-plus"></i> 增加分类</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
			<div id="main">
			</div>
            <!--END 友情链接	 -->

            <br/><br/><br/>
            <footer class="footer">
                    <div class="vcenter">
                         Since 2020 <a href="https://www.fcdh.net/"><strong>www.fcdh.net</strong></a> <span class="ti-more">渝ICP备20001609号</span>
                    </div>
                    <div id="go-up">
                        <a href="#" rel="go-top"title="顶部">
                            <i class="fa-tree"></i>
                        </a>
                    </div>
            </footer>
			<div id="ti-meng">
			</div>
		</div>
    </div> <!-- end 最处层容器 -->
	<!-- Scripts -->
<!-- 模态框 增加分类 --> 
	<div class="modal fade" id="addcate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
				<h3 class="modal-title" id="myModalLabel"> 增加左侧顶一级分类 </h3> 
			</div>
			<div class="modal-body"> 
				<p><label>标签英文：</label><input type="text" class="form-control" name="cname" maxlength="25" style="width:50%" placeholder="index">*</p>
				<p><label>标签简称：</label><input type="text" class="form-control" name="ctag"maxlength="63" style="width:70%" placeholder="娱乐">*</p>
				<p><label>标签全称：</label><input type="text" class="form-control" name="ctagall"maxlength="63" style="width:70%" placeholder="休闲娱乐"></p>
				<p><label>页面标题：</label><input type="text" class="form-control" name="ctitle"maxlength="63" style="width:70%" placeholder="非常导航">*</p>
				<p><label> 关 键 词：</label><input type="text" class="form-control" name="keys"maxlength="63" style="width:70%" placeholder="非常导航，绿色导航，安全导航"></p>
				<p><label>页面描述：</label><input type="text" class="form-control" name="desc"maxlength="63" style="width:70%" placeholder="非常导航成立于冠状病毒爆发的2020年"></p>
				<p>
				<strong><label>标签类型:</label>
					<label><input type="radio" name="ctn" value="0" checked="checked">无链接</label>
					<label><input type="radio" name="ctn" value="1">有链接</label>
					<label><input type="radio" name="ctn" value="2"><span class="c8">有子类</span></label>
				</strong>
				</p>
				<p><label>权限值：</label><input type="number" class="form-control" name="crank" maxlength="25" style="width:50%" placeholder="0-255"></p>
				<input type="hidden" name="cid"value="<?php echo $id;?>">
			</div> 
			<div class="modal-footer"> 
				<button type="submit" class="btn btn-primary" data-dismiss="modal"> 取消 </button> 
				<button type="submit" class="btn btn-primary"value="1"onclick="addCate(this)"> 提交 </button> 
			</div> 
		</div>
	</div>


	<script src="../assets/js/fcdh.js"></script>
	<script src="js/admin.js"></script>
</body>
</html>
