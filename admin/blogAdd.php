<?php
include 'admin.php';
$act=@$_GET['act'];
$wd2='';
if(isset($_GET['wd2'])){
	$wd2=@$_GET['wd2'];
}else{
	$wd2=@$_GET['wd'];
}
$tid=$bhid=0;
$tn='tid';
$add=false;
if(isset($_GET['bhid'])){
	$tn='bhid';
	$bhid=intval(@$_GET['bhid']);
	
	$res=$db->query("select tid,tidfather,tidson,pstate from pagetag where rowid=$bhid")->fetch();
	$tid=intval($res['0']);
	$add=true;

}elseif(isset($_GET['blog'])){
	$tn='blog';
	$bhid=intval($_GET['blog']);
}

$btitle=$bkey=$bdesc=$btip=$btag=$bstrong=$blogwd=$msg='';
$bid=$bcolor=0;
$eof=true;
if($act=='add'){   
	$curtime=time();
	$bindex=$curtime-1580083200; // 1607040000 = 24*3600*18600  1580083200 2020-01-27

    $btitle=mb_substr(filterText(trim(@$_POST['btitle'])),0,32);
    $bkey=mb_substr(filterText(trim(@$_POST['bkey'])),0,256);
    $bdesc=mb_substr(filterText(htmlspecialchars(trim(@$_POST['bdesc']))),0,2048);
    $btag=$tagstr=mb_substr(filterText(strtolower(@$_POST['btag'])),0,128);
    $bstrong=intval(trim(@$_POST['bstrong']));
    $btip=mb_substr(filterText(trim(@$_POST['btip'])),0,256);
    $bcolor=intval(trim(@$_POST['bcolor']));
    $blogwd=mb_substr(trim(strtolower(@$_POST['blogwd'])).','.$bindex,0,256);
	$bview=100;
	$btime=date("Y-m-d H:i:s");
	if(isset($_POST['bhid'])){
		$tn='bhid';
		$bhid=intval(@$_POST['bhid']);
	}elseif(isset($_POST['blog'])){
		$tn='blog';
		$bhid=$tid=intval(@$_POST['blog']);
	}

	

	if($eof){
		$msg='<strong class="c2">增加成功</strong>';
		$eof=$db->exec("insert into blog(bindex,btitle,bkey,bdesc,btip,bview,btime,bcolor,bstrong) values($bindex,'$btitle','$bkey','$bdesc','$btip',$bview,'$btime',$bcolor,$bstrong)");
		if(!$eof){
			$msg='<strong class="c9">博客 '.$bindex.' 重复!</strong>';
		}else{
			$res=$db->query("select last_insert_rowid() from blog")->fetch(); 
			$bid=$res[0];
			$rank=$bid%100;
			$arr = array('，' => ';', ',' => ';', "；" => ';', "|" => ';', "、" => ';'); 
			$blogwd=strtr($blogwd, $arr); 
			$hrefarr=explode(';',$blogwd);
			$sql='';
			$i=0;
			$widarr=array();
			foreach($hrefarr as $str){
				$word=filterTitle(trim($str));
				$res=$db->query("select wid from tagword where word='$word'")->fetch();
				$wid=intval($res[0]);
				if(!$wid){ //如果没有该关键词
					$db->exec("insert into tagword(word) VALUES('$word')");
					$res=$db->query("select last_insert_rowid() from tagword")->fetch(); 
					$widarr[$i++]=$res[0];
				}else{					
					$widarr[$i++]=$wid;
				}
			}
			$i=0;
			foreach($widarr as $wid){
				if($i++==0){
					$sql="INSERT INTO blogso SELECT $bid AS bid, $wid AS wid";
				}
				$sql.=" UNION SELECT $bid, $wid";
			}
			$db->exec($sql);
			$hadd=date('d')%10+1;
			if($bhid || !empty($tagstr)){//   对新增的进行进入类别标签操作
				if(isset($_POST['bhid'])){
					$res=$db->query("select tag.tid,tidson,tag.tindex from pagetag INNER JOIN tag on tag.tid=pagetag.tid where pagetag.rowid=$bhid limit 1")->fetch();
					$tidtag=$tid=intval($res['0']);
					$tidson=intval($res['1']);
					$onetags=$res['tindex'];
					$eof=$db->exec("insert into pagehref(tid,hid,rank) values($tidson,$bid,$rank)");
				}else{ // blog 直接插入
					$tidtag=$tid;
					$eof=$db->exec("insert into pagehref(tid,hid,rank) values($tid,$bid,$rank)");
					$res=$db->query("select tindex from tag where tid=$tid limit 1")->fetch();
					$onetags=$res['tindex'];
					if($onetags=='index')$onetags='';
				}
				if($onetags=='index'){ // 如果标签是首页index则换一个标签
					$tidtag=$tidson;
					$res=$db->query("select tindex from tag where tid=$tidson limit 1")->fetch();
					$onetags=$res['tindex'];
				}
				$btag=$tidtag;
				$tags=$onetags;
				if(!empty($tagstr)){// 如果网站类型不为空
					$tags=$btag=',';
					$arr = array('，' => ',', ';' => ',', "；" => ','); 
					$tagstr=trim(strtr($tagstr, $arr),','); 
					$strarr=explode(',',$tagstr);
					$taginput='';
					foreach($strarr as $v){
						$temp=filterText(trim($v));
						$taginput.=","."'".$temp."'";
						$tags.=$temp.",";
					}
					$taginput=trim($taginput,',');
					$tag=$db->query("select tid from tag where tindex in($taginput)")->fetchAll();
					foreach($tag as $r){
						$btag.=$r['tid'].',';
					}
					if(!empty($tidtag)){
						if(!strstr($btag,','.$tidtag.',')){ //如果字符id不存在
							$btag.=$tidtag.',';
						}
						if(!strstr($tags,','.$onetags.',')){ //如果字符标签不存在
							$tags.=$onetags.',';
						}
					}
				}
				$btag=trim($btag,',');
				if(!empty($btag)){
					$db->exec("update blog set btag='$btag',badd=$hadd where bid=$bid");
				}
				$btag=trim($tags,',');
			}else{
				//$db->exec("update blog set badd=$badd where bid=$bid");
			}
		}
	
	}else{
		$msg='<strong class="c9">输入格式有误</strong>';
	}
}

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>增加网址 - 后台管理 - 非常导航</title>
    <link rel="shortcut icon" href="../assets/images/favicon.png">
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/fcdh.css">

	<link rel="stylesheet" href="css/jquery.qeditor.css" type="text/css">
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
	<script src="js/jquery.qeditor.js" type="text/javascript"></script>

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
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>	
                    <li id="ti-home">
                        <a href="./">
                            <i class="fa-home"></i>
                        </a>
                    </li>
					<li>
                        <div class="searchs">
                            <form action="blog.php" method="get">
								<div class="input-group">
									<input type="text" style="padding-left:15px;"class="form-control" name="wd" id="wd"placeholder="搜索博客" maxlength="63"autocomplete="on"value="<?php echo$wd2;?>">
									<input type="hidden" name="<?php echo$tn;?>" value="<?php echo$bhid;?>">
									<?php 
										if(isset($_GET['wd2'])){
									?>
									<input type="hidden" name="wd2" value="<?php echo$wd2;?>">
									<?php 
										}
									?>
									<span class="input-group-btn">
										<button type="submit"class="btn btn-primary fa-search"> 博客</button>
									</span>	
								</div>
							</form>
                        </div>
                    </li>
                </ul>
				<ul class="ti-ads">
					<li><a href="tag.php?wd=<?php echo$wd2;?>">搜标签</a></li>
				</ul>
            </nav>
			<br/>
			<br/>
				<?php 
					if($add){
				?>
				<h3><a href="pageHref.php?<?php echo"$tn=",$bhid,'&wd=',$wd2;?>"class="c2"><i class="fa-mail-reply"></i> 返回博文</a>  <a href="page.php?<?php echo"tid=",$tid,'&wd=',$wd2;?>"class="c2"><i class="fa-mail-reply-all"></i> 返回分类</a></h3>
				
				<?php 
					}
				?>

				<h3><a href="blog.php<?php if(!empty($bhid))echo"?$tn=$bhid&wd2=$wd2";?>"><i class="fa-bookmark"></i>博客列表</a> <a class="c10"><i class="fa-plus"></i>增加博客</a></h3>
				

			<form method="post" action="?act=add&<?php echo'wd2=',$wd2;?>">
			<p>
				<label>博客标题</label> <span>(32字符)</span><br/>
				<input type="text" class="form-control"maxlength="32"placeholder="标题"style="width:100%"value="<?php echo$btitle;?>" id="htitle"name="btitle" required>
			</p>
			<p>
				<label class="c8">搜索字词</label>* <span id="info"><?php echo $msg;?></span> <span>(256字符)</span>
				<input type="text" class="form-control"maxlength="256"placeholder="用分号;或逗号,隔开"style="width:100%"value="<?php echo$blogwd;?>"id="blogwd"name="blogwd" onchange="getRemoteUrl(this,-1);"  required>
			</p>

			<p>
				<label>博客关键词</label> <span>(256字符)</span>
				<input type="text" class="form-control"maxlength="256"placeholder="关键词用分号;或逗号,隔开"style="width:100%"value="<?php echo$bkey;?>" id="hkey" name="bkey"required>
			</p>
     
			<div class="ti-fwb">
				<label class="c4">博客内容</label> <span>(2048字符)</span>
				<textarea class="form-control"maxlength="2048"placeholder="博客内容正文"style="width:100%" id="hdesc"name="bdesc" required><?php echo$bdesc;?></textarea>
			</div>
			<p>
				<label>隐藏内容</label> <span>(256字符)</span>
				<input type="text" maxlength="256"class="form-control"style="width:100%"value="<?php echo$btip;?>" id="btip"name="btip">
			</p>

			<p>
				<label>博客类型</label> <span>(128字符)</span>
				<input type="text" maxlength="128"class="form-control"style="width:100%"value="<?php echo$btag;?>" id="htag"name="btag">
			</p>
			<p>
				<label>标题粗细</label>
				<label><input type="radio" name="bstrong" value="0" checked>正常</label><label><input type="radio" name="bstrong" value="1">粗体</label>
			</p>
			<p>
				<label>标题颜色</label>
				<select class="form-control" name="bcolor"style="width:50%">
				<option value="0"class="c0" selected>正常</option>
				<option value="1"class="c1">浅绿</option>
				<option value="2"class="c2">深绿</option>
				<option value="3"class="c3">暗蓝</option>
				<option value="4"class="c4">蓝色</option>
				<option value="5"class="c5">深蓝</option>
				<option value="6"class="c6">深紫</option>
				<option value="7"class="c7">浅紫</option>
				<option value="8"class="c8">粉色</option>
				<option value="9"class="c9">红色</option>
				<option value="10"class="c10">橙色</option>
				</select>
			</p>
				<input type="hidden" name="<?php echo$tn;?>" value="<?php echo$bhid;?>">
				<button type="submit" class="btn btn-primary btn-block">增加博客</button>
			</form>

			<br/><br/><br/>
            <footer class="footer">
                    <div class="vcenter">
                         Since 2020 <strong><a href="https://www.fcdh.net/">www.fcdh.net</a></strong> <span class="ti-more"><a href="https://beian.miit.gov.cn/">渝ICP备20001609号</a></a>
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
 <script src="../assets/js/fcdh.js"></script>
 <script src="./js/admin.js"></script>
    <script type="text/javascript">
	var hdesc=$("#hdesc");
    var obj=hdesc.qeditor({});
    
    // Custom a toolbar icon
    var toolbar = hdesc.parent().find(".qeditor_toolbar");
    var link	= $("<a href='#'><span class='fa-smile-o' title='src code view'></span></a>");
	var video	= $("<a href='#'><span class='fa-video-camera' title='insert video'></span></a>");
    link.click(function(){
	//	$(".qeditor_preview").css(');
      alert(hdesc.val());
    });
    video.click(function(){
	//	$(".qeditor_preview").css(');
      p = prompt("Image URL:");
      if (p.trim().length === 0) {
        return false;
      }
	  p='<audio src="'+p+'"></audio>';
		document.execCommand('insertHTML', false, p);
    });
    toolbar.append(video,link);
    
  // prepend
    
    $("#submit").click(function(){
     // return;
    });
    </script>

</body>
</html>
