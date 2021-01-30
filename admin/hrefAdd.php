<?php
include 'admin.php';
$act=@$_GET['act'];
$wd2='';
if(isset($_GET['wd2'])){
	$wd2=@$_GET['wd2'];
}else{
	$wd2=@$_GET['wd'];
}
//$rowid=-1;
$tn='tid';
$rowid=$tid=0;
$add=false;
if(isset($_GET['rowid'])){
	$tn='rowid';
	$rowid=intval(@$_GET['rowid']);
	
	$res=$db->query("select tid,tidfather,tidson,pstate from pagetag where rowid=$rowid")->fetch();
	$tid=intval($res['0']);
	$add=true;

}elseif(isset($_GET['url'])){
	$tn='url';
	$rowid=$tid=intval($_GET['url']);
}

$hindex=$hurl=$hname=$hurl=$htitle=$hkey=$hdesc=$htip=$htag=$hstrong=$hrefwd=$msg='';
$hid=$hcolor=0;$hstate=1;
$err=array('hurl'=>'','hindex'=>'');
$eof=true;
if($act=='add'){   
    $hstate=intval(@$_POST['hstate']);
    $hurl=mb_substr(trim(@$_POST['hurl']),0,64);
    $hname=mb_substr(filterTitle(trim(@$_POST['hname'])),0,24);
    $hindex=mb_substr(filterTitle(trim(@$_POST['hindex'])),0,32);
    $htitle=mb_substr(filterText(trim(@$_POST['htitle'])),0,64);
    $hkey=mb_substr(filterText(trim(@$_POST['hkey'])),0,256);
    $hdesc=mb_substr(filterText(htmlspecialchars(trim(@$_POST['hdesc']))),0,512);
    $htip=mb_substr(filterText(trim(@$_POST['htip'])),0,256);
    $htag=$tagstr=mb_substr(filterText(strtolower(@$_POST['htag'])),0,128);
    $hrefwd=mb_substr(trim(strtolower(@$_POST['hrefwd'])),0,256);
    $hstrong=intval(trim(@$_POST['hstrong']));
    $hcolor=intval(trim(@$_POST['hcolor']));
	$hview=100;
	$htime=date("Y-m-d H:i:s");

	if(!_CheckInput($hindex,'numchar')){//字母
		$err['hindex']='请输入英文字母';
		$eof=false;
	}
	if(!_CheckInput($hurl,'url')){//字母
		$err['hurl']='请输入正确的网址格式';
		$eof=false;
	}
	if($eof){
		$msg='<strong class="c2">保存成功</strong>';
		$eof=$db->exec("insert into href(hindex,hname,hurl,htitle,hkey,hdesc,htip,hstate,hview,htime,hcolor,hstrong) values('$hindex','$hname','$hurl','$htitle','$hkey','$hdesc','$htip',$hstate,$hview,'$htime',$hcolor,$hstrong)");
		if(!$eof){
			$msg='<strong class="c9">标签 '.$hindex.' 重复!</strong>';
		}else{
			$res=$db->query("select last_insert_rowid() from href")->fetch(); 
			$hid=$res[0];
			$rank=$hid%100;
			$arr = array('，' => ';', ',' => ';', "；" => ';', "|" => ';', "、" => ';'); 
			$hrefwd=strtr($hrefwd, $arr); 
			$hrefarr=explode(';',$hrefwd);
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
					$sql="INSERT INTO hrefso SELECT $hid AS hid, $wid AS wid";
				}
				$sql.=" UNION SELECT $hid, $wid";
			}
			$db->exec($sql);
			$hadd=date('d')%10+1;
			if($rowid || !empty($tagstr)){//   对新增的链接进入类别标签操作
				$onetags=$tidtag='';
				if($rowid){
					if($tn=='rowid'){
						$res=$db->query("select tag.tid,tidson,tag.tindex from pagetag INNER JOIN tag on tag.tid=pagetag.tid where pagetag.rowid=$rowid limit 1")->fetch();
						$tidtag=$tid=intval($res['0']);
						$tidson=intval($res['1']);
						$onetags=$res['tindex'];
						//$pstate=intval($res['2']);
						$eof=$db->exec("insert into pagehref(tid,hid,rank) values($tidson,$hid,$rank)");
					}else{
						$tidtag=$tid;
						$eof=$db->exec("insert into pagehref(tid,hid,rank) values($tid,$hid,$rank)");
						$res=$db->query("select tindex from tag where tid=$tid limit 1")->fetch();
						$onetags=$res['tindex'];
						if($onetags=='index')$onetags='';
					}
					if($onetags=='index'){ // 如果标签是首页index则换一个标签
						$tidtag=$tidson;
						$res=$db->query("select tindex from tag where tid=$tidson limit 1")->fetch();
						$onetags=$res['tindex'];
					}
				}
				$htag=$tidtag;
				$tags=$onetags;
				if(!empty($tagstr)){// 如果网站类型不为空
					$tags=$htag=',';
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
						$htag.=$r['tid'].',';
					}
					if(!empty($tidtag)){
						if(!strstr($htag,','.$tidtag.',')){ //如果字符id不存在
							$htag.=$tidtag.',';
						}
						if(!strstr($tags,','.$onetags.',')){ //如果字符标签不存在
							$tags.=$onetags.',';
						}
					}
				}
				$htag=trim($htag,',');
				if(!empty($htag)){
					$db->exec("update href set htag='$htag',hadd=$hadd where hid=$hid");
				}
				$htag=trim($tags,',');
			}else{
				//$db->exec("update href set hadd=$hadd where hid=$hid");
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
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
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
                            <form action="href.php" method="get">
								<div class="input-group">
									<input type="text" style="padding-left:15px;"class="form-control" name="wd" id="wd"placeholder="搜索链接" maxlength="64"autocomplete="on"value="<?php echo$wd2;?>">
									<input type="hidden" name="<?php echo$tn;?>" value="<?php echo$rowid;?>">
									<?php 
										if(isset($_GET['wd2'])){
									?>
									<input type="hidden" name="wd2" value="<?php echo$wd2;?>">
									<?php 
										}
									?>
									<span class="input-group-btn">
										<button type="submit"class="btn btn-primary fa-search"> 链接</button>
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
				<h3><a href="pageHref.php?<?php echo"$tn=",$rowid,'&wd=',$wd2;?>"class="c2"><i class="fa-mail-reply"></i> 返回链接</a>  <a href="page.php?<?php echo"tid=",$tid,'&wd=',$wd2;?>"class="c2"><i class="fa-mail-reply-all"></i> 返回分类</a></h3>
				
				<?php 
					}
				?>

				<h3><a href="href.php<?php if(!empty($rowid))echo"?$tn=$rowid&wd2=$wd2";?>"><i class="fa-bookmark"></i>链接列表</a> <a class="c10"><i class="fa-plus"></i>增加链接</a></h3>
				

			<form method="post" action="?act=add&<?php echo$tn,'=',$rowid,'&wd2=',$wd2;?>">
			<p>
				<label>网站索引</label><strong>*</strong> <span>(32字符)</span><strong><?php echo$err['hindex'];?></strong> <span id="info"><?php echo $msg;?></span>
				<input type="text"maxlength="32" class="form-control"placeholder="wwwfcdhnet"style="width:100%"value="<?php echo$hindex;?>" id="hindex"name="hindex" required>
			</p>
			<p>
				<label>网站网址</label><strong>*</strong> <span>(64字符)</span> <strong><?php echo$err['hurl'];?></strong> <a href="https://bbs.fcdh.net/adminhou/ajaxAdmin.php?url="id="href"target="_blank">数据抓取</a>
				<input type="text"maxlength="64" class="form-control"placeholder="https://"style="width:100%"value="<?php echo$hurl;?>" id="hurl"name="hurl" onchange="getRemoteUrl(this);" required>
			</p>
			<p>
				<label class="c8">搜索字词</label><strong>*</strong> <span>(256字符)</span>
				<input type="text"maxlength="256" class="form-control"placeholder="用分号;或逗号,隔开"style="width:100%"value="<?php echo$hrefwd;?>"name="hrefwd" required>
			</p>
			<p>
				<label>网站名称</label><strong>*</strong> <span>(32字符)</span>
				<input type="text"maxlength="32" class="form-control"placeholder="非常导航"style="width:100%"value="<?php echo$hname;?>" id="hname"name="hname"required>
			</p>
			<p>
				<label>页面标题</label><strong>*</strong> <span>(64字符)</span>
				<input type="text"maxlength="64" class="form-control"placeholder="非常绿色的网址导航"style="width:100%"value="<?php echo$htitle;?>" id="htitle"name="htitle" required>
			</p>
			<p>
				<label>页面关键词</label><strong>*</strong> <span>(256字符)</span>
				<input type="text"maxlength="256"class="form-control"placeholder="绿色导航，绿色网站"style="width:100%"value="<?php echo$hkey;?>" id="hkey"name="hkey" required>
			</p>
			<p>
				<label>页面描述</label><strong>*</strong> <span>(512字符)</span>
				<textarea rows="9"maxlength="512"class="form-control"style="width:100%"placeholder="网站描述" id="hdesc"name="hdesc" required><?php echo$hdesc;?></textarea>
			</p>
			<p>
				<label>隐藏内容</label> <span>(256字符)</span>
				<input type="text" maxlength="256"class="form-control"style="width:100%"value="<?php echo$htip;?>" id="htip"name="htip">
			</p>
			<?php
				//if(empty($_GET['tid'])){
			?>
			<p>
				<label>网站类型</label> <span>(128字符)</span>
				<input type="text"  maxlength="128"class="form-control"style="width:100%"value="<?php echo$htag;?>" id="htag"name="htag">
			</p>
			<?php
				//}
			?>
			<p>
				<label>标题粗细</label>
				<label><input type="radio" name="hstrong" value="0" checked>正常</label><label><input type="radio" name="hstrong" value="1">粗体</label>
			</p>
			<p>
				<label>标题颜色</label>
				<select class="form-control" name="hcolor"style="width:50%">
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
			<p>
				<label>网站状态</label>
				<select class="form-control" name="hstate"style="width:50%"><option value="1"<?php if($hstate==1)echo'selected';?>>正常(1)</option><option value="2"<?php if($hstate==2)echo'selected';?>>异常(2)</option><option value="3"<?php if($hstate==3)echo'selected';?>>改版(3)</option><option value="0"<?php if($hstate==0)echo'selected';?>>死链(0)</option></select>
			</p>
				<button type="submit" class="btn btn-primary btn-block">增加链接</button>
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
</body>

</html>
