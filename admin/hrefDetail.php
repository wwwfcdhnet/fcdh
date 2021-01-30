<?php
include 'admin.php';
$hid=intval(@$_GET['tid']);
$wd=@$_GET['wd'];$tn=true;
$tags=$hindex=$hurl=$hname=$hurl=$hstate=$htitle=$hkey=$hdesc=$hstrong='';
	$res=$db->query("select hindex,hname,hurl,htitle,hkey,hdesc,htip,htag,hstate,hview,htime,hcolor,hstrong from href where hid=$hid limit 1")->fetch(); 
    $hurl=$res['hurl'];
    $hname=$res['hname'];
    $hstate=$res['hstate'];
    $hindex=$res['hindex'];
    $htitle=$res['htitle'];
    $hkey=$res['hkey'];
    $hdesc=htmlspecialchars_decode($res['hdesc']);
    $htip=$res['htip'];
    $hstrong=$res['hstrong'];
    $hcolor=$res['hcolor'];
	$hview=$res['hview'];
	$htime=$res['htime'];
	$htag=$res['htag'];
	if($hstate==0)$hstate=9;
	elseif($hstate==2)$hstate=10;
	$statearr=array('9'=>'死链','1'=>'正常','10'=>'异常','3'=>'改版');

	$tag=$db->query("select tid,tindex,tname from tag where tid in($htag)")->fetchAll(); 
	foreach($tag as $r){
		if(!empty($r['tid']))$tags.='<a href="../'.$r['tindex'].'.html">['.strtr($r['tname'],array(' '=>'')).']</a> ';
		//echo$r['tname'],',';
	}
	$imgsrc='../assets/logos/'.$hindex.'.png';
	$img='';
	if(file_exists($imgsrc)){
		$img='<img src="'.$imgsrc.'"> ';
	}
	if(empty($hurl)){
		$tn=false;
	}
	$opt=$hcolor.'#'.$hstrong.'#'.$hname;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>非常导航 - 非常绿色安全的上网导航</title>
    <meta name="keywords" content="非常导航，绿色导航，安全导航，绿色上网，安全上网，上网导航">
    <meta name="description" content="非常导航成立于冠状病毒爆发的2020年。本着网址收藏的爱好，所以要求导航网站既能自定义网址、上传下载网址，又能看着简洁美丽、不臃肿杂乱，还要绿色安全、无毒无弹窗，这是导航站的宗旨，也是上网爱好者的底线。www.fcdh.net">
    <link rel="shortcut icon" href="../assets/images/favicon.png">
    <!--<link rel="stylesheet" href="../assets/css/font-awesome.min.css">-->
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
    <!--<script src="../assets/js/jquery-1.11.1.min.js"></script>-->
    <script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/blueimp-md5/2.12.0/js/md5.min.js"></script>
</head>
<body>
    <!-- 最外层容器 -->
    <div id="container">
        <?php include 'head.php';?>

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
					<li>
                        <div class="searchs">
                            <form action="href.php" method="get">
								<div class="input-group">
									<input type="text" style="padding-left:15px;"class="form-control" name="wd" placeholder="搜索链接" maxlength="63"autocomplete="on"value="<?php echo$wd;?>">
									<span class="input-group-btn">
										<button type="submit"class="btn btn-primary fa-search"> 链接</button>
									</span>	
								</div>
							</form>
                        </div>
                    </li>
                </ul>
            </nav>
			<br/>
			<br/>
			<div id="main">

				<h4><a><i class="fa-bookmark"></i> <?php if($tn)echo'网站摘要';else echo$hname;?></span> </a> &nbsp; <span id="info"class="c9"></span></h4>
				<div class="ti-ulbg">
					<?php 
						if($tn){
					?>
					<ul class="rowso">
					<li>【网站名称】<?php echo$img;?> <span class="c<?php echo$hcolor;?>"><?php if($hstrong)echo'<strong>';echo$hname;if($hstrong)echo'</strong>';?></span> </li>
					<li>【网站地址】<strong class="c7"><?php echo$hurl;?></strong> </li>
					<li><span>【网站标题】</span> <strong class="c5"><?php echo$htitle;?></strong> </li>
					<li><span>【关键字词】</span> <strong class="c5"><?php echo$hkey;?></strong> </li>
					<li><span>【网站介绍】</span> <strong class="c5"><?php echo$hdesc;?></strong></li>
					<li><span>【备用网址】</span> <strong class="c5"><?php echo$hdesc;?></strong></li>
					<li><span>【网站类型】</span> <strong class="c1"><?php echo$tags;?></strong> </li>
					<li><span>【网站状态】</span> <?php echo'<strong class="c',$hstate,'">',$statearr[$hstate],'</strong>';?></li>
					<li><span>【访问次数】</span> <strong><?php echo$hview;?></strong> </li>
					<li><span>【收录时间】</span> <strong><?php echo$htime;?></strong> </li>
					<li>
					<button onclick="openHref('<?php echo$hurl;?>',0,'<?php echo$opt;?>')"  type="submit"class="btn btn-primary fa-paper-plane"> 进入网站</button>
					<button onclick="heartHref('<?php echo$hurl;?>','<?php echo$opt;?>')"  id="heart" class="btn btn-primary fa-heart"> 收藏</button>
					</li>
					</ul>
					<?php 
						}else{
					?>
						
					<div class="rowx">
					<h2><?php echo$htitle;?></h2>
					<?php if(!empty($tags))echo'<p>',$tags,'</p>';?>
					<hr>
					<?php echo$hdesc;?>
					<p><i class="fa-calendar"> <?php echo$htime;?></i> &nbsp; <i class="fa-eye" id="view"> <?php echo$hview;?></i></p>
					</div>

					<?php 
						}
					?>
				</div>

			</div>

            <br/><br/><br/>
            <footer class="footer">
                    <div class="vcenter">
                         Since 2020 <a href="https://www.fcdh.net/"><strong>fcdh.net</strong></a> &nbsp;<span class="ti-more"><a href="https://beian.miit.gov.cn/"target="_blank">渝ICP备20001609号</a></span>
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
	<script src="../assets/js/fcdh.js"></script>
</body>
</html>
