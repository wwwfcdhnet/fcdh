<?php
include 'admin.php';
$act=@$_GET['act'];
$rowid=intval(@$_GET['rowid']);

$wd2=@$_GET['wd'];

$hrelate=$hindex=$hurl=$hname=$hurl=$htitle=$hkey=$hdesc=$htip=$hstrong=$hrefwd=$msg=$hico='';
$hcolor=10;$hstate=1;
$tn='tid';
if(isset($_GET['rowid'])){
	$tn='rowid';
}else{
	$rowid=intval(@$_GET['tid']);
	
}
if($act=='edit'){   
    $hid=intval(trim(@$_POST['hid']));
    $ref=@$_POST['ref'];
    $hstate=intval(@$_POST['hstate']);
    $uptime=intval(@$_POST['uptime']);
    $hurl=mb_substr(trim(@$_POST['hurl']),0,64);
    $hname=mb_substr(filterTitle(trim(@$_POST['hname'])),0,24);
    $hindex=mb_substr(filterTitle(trim(@$_POST['hindex'])),0,32);
    $htitle=mb_substr(filterText(trim(@$_POST['htitle'])),0,64);
    $hkey=mb_substr(filterText(trim(@$_POST['hkey'])),0,256);
    $hdesc=mb_substr(filterText(htmlspecialchars(trim(@$_POST['hdesc']))),0,512);
    $htip=mb_substr(filterText(trim(@$_POST['htip'])),0,256);
    $tagstr=mb_substr(filterText(strtolower(@$_POST['htag'])),0,128);
    $relatestr=mb_substr(filterText(@$_POST['hrelate']),0,128);
    $hrefwd=mb_substr(strtolower(@$_POST['hrefwd']),0,256);
    $hstrong=intval(trim(@$_POST['hstrong']));
    $hcolor=intval(trim(@$_POST['hcolor']));
	$hview=intval(@$_POST['hview']);
	$hico=intval(@$_POST['hico']);
	$htime='';
	if($uptime){
		$htime=",htime='".date("Y-m-d H:i:s")."'";
	}
	if($hstate==0){//表示死链
		$hrefwd.=';死链';
	}

	$html=0;
	$arr = array('，' => ',', ';' => ',', "；" => ','); 
	$tagstr=trim(strtr($tagstr, $arr),','); 
	$strarr=explode(',',$tagstr);
	$taginput='';
	foreach($strarr as $v){
		$taginput.=","."'".trim($v)."'";
	}
	$taginput=trim($taginput,',');
	$tag=$db->query("select tid from tag where tindex in($taginput)")->fetchAll();
	$htag='';
	foreach($tag as $r){
		$htag.=$r['tid'].',';
	}
	$htag=trim($htag,',');

	if(!empty($relatestr)){
		$arr = array('，' => ',', ';' => ',', "；" => ','); 
		$relatestr=trim(strtr($relatestr, $arr),','); 
		$relatearr=array_unique(explode(',',$relatestr));
		foreach($relatearr as $v){
			$temp=intval(trim($v));
			if($v==$hid)continue;
			$hrelate.=",".$temp;
		}
		$hrelate=trim($hrelate,',');
	}

	$msg='<strong class="c2">编辑成功</strong>';
	$eof=$db->exec("update href set hindex='$hindex',hname='$hname',hurl='$hurl',htitle='$htitle',hkey='$hkey',hdesc='$hdesc',htag='$htag',htip='$htip',hstate=$hstate,hview='$hview'$htime,hcolor=$hcolor,hstrong=$hstrong,html=$html,hico=$hico,hrelate='$hrelate' where hid=$hid");
	//echo"update href set hindex='$hindex',hname='$hname',hurl='$hurl',htitle='$htitle',hkey='$hkey',hdesc='$hdesc',htag='$htag',htip='$htip',hstate=$hstate,hview='$hview'$htime,hcolor=$hcolor,hstrong=$hstrong,html=$html,hico=$hico where hid=$hid";
	if(!$eof){
		$msg='<strong class="c9">链接 '.$hindex.' 重复!</strong>';
	}else{
		$arr = array('，' => ';', ',' => ';', "；" => ';', "|" => ';', "、" => ';'); 
		$hrefwd=strtr($hrefwd, $arr); 
		$hrefarr=explode(';',trim($hrefwd,';'));
		$widarr=array();
		$i=0;
		foreach($hrefarr as $str){ // 要存入数据库中的word
			$word=filterTitle(trim($str));
			if($hstate!=0 && $word=='死链')continue;
			$res=$db->query("select wid from tagword where word='$word'")->fetch();			
			$wid=intval($res['wid']);
			if(in_array($wid,$widarr))continue;
			if(!$wid){ //如果没有该关键词
				$db->exec("insert into tagword(word) VALUES('$word')");
				$res=$db->query("select last_insert_rowid() as lastwid from tagword")->fetch(); 
				$widarr[$i++]=$res['lastwid'];
			}else{					
				$widarr[$i++]=$wid;
			}
		}
		$hrefso=$db->query("SELECT wid FROM hrefso where hid=$hid")->fetchAll(); 
		$tagWidHave=array();
		$delwid=$sql='';
		$i=0;
		
		foreach($hrefso as $v){
			if(!in_array($v['wid'],$widarr)){
				$delwid.=$v['wid'].',';
			}else{
				$tagWidHave[$i++]=$v['wid'];
			}
		}
		$delwid=trim($delwid,','); // 不要的搜索字词
		$db->exec("DELETE FROM hrefso WHERE hid=$hid AND wid IN($delwid)");

		$i=0;
		foreach($widarr as $wid){
			if(in_array($wid,$tagWidHave))continue;
			if($i++==0){
				$sql="INSERT INTO hrefso SELECT $hid AS hid, '$wid' AS wid";
			}else{
				$sql.=" UNION SELECT $hid, $wid";
			}
		}
		if(!empty($sql))$db->exec($sql);
	}
	if(!empty($ref))redir($ref);
}else{
	$ref=@$_SERVER['HTTP_REFERER'];
	$hid=intval(@$_GET['hid']);
	$href=$db->query("SELECT * FROM href WHERE hid=$hid")->fetch(); 
	$hrefso=$db->query("SELECT tagword.word FROM hrefso INNER JOIN tagword on hrefso.wid=tagword.wid WHERE hrefso.hid=$hid")->fetchAll(); 
	//$hrefso=$db->query("SELECT * FROM hrefso WHERE hid=$hid")->fetchAll(); 
	
	$hrefwd='';
	foreach($hrefso as $wd){
		$hrefwd.=$wd['word'].';';
	}

	$hrefwd=trim($hrefwd,';');
	$hindex=$href['hindex'];
   
    $hurl=$href['hurl'];
    $hname=$href['hname'];
    $hurl=$href['hurl'];
    $hstate=$href['hstate'];
    $hindex=$href['hindex'];
    $htitle=$href['htitle'];
    $hkey=$href['hkey'];
    $hdesc=htmlspecialchars_decode($href['hdesc']);
    $htip=$href['htip'];
    $htag=$href['htag'];
    $hstrong=$href['hstrong'];
    $html=$href['html'];
    $hcolor=$href['hcolor'];
	$hview=$href['hview'];
	$hico=$href['hico'];
	$hrelate=$href['hrelate'];
	//echo"select tindex from tag where tid in($htag)";
	$tag=$db->query("select tindex from tag where tid in($htag)")->fetchAll();
	$tagstr='';
	foreach($tag as $r){
		$tagstr.=$r['tindex'].',';
	}
	$tagstr=trim($tagstr,',');
}
	

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>编辑网址 - 后台管理 - 非常导航</title>
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
                </ul>
				<ul class="ti-ads">
					<li><a href="href.php<?php if(!empty($rowid))echo"?$tn=$rowid&wd2=$wd2";?>">链接列表</a></li>
					<li><a href="hrefAdd.php<?php if(!empty($rowid))echo"?$tn=$rowid&wd2=$wd2";?>">增加链接</a></li>
				</ul>
            </nav>
			<br/>
			<br/>

			<form method="post" action="?act=edit">
				<h3><a><i class="fa-bookmark"></i>编辑链接</a>  <span id="info"><?php echo $msg; ?></span></h3>
			<p>
				<label>网站索引</label><strong>*</strong> <span>(32字符)</span>
				<input <?php if($html>-1) echo'disabled ';?>type="text" maxlength="32"class="form-control"placeholder="wwwfcdhnet"style="width:100%"value="<?php echo$hindex;?>"id="hindex" name="hindex" required>
				<?php
					if($html>-1){
				?>
					<input type="hidden" value="<?php echo$hindex;?>"id="ohindex" name="hindex" required>
				<?php
					}
				?>
			</p>
			<p>
				<label>网站网址</label><strong>*</strong> <span>(64字符)</span> <button type="button"onclick="getRemoteUrl(this,1);return true;">获取META</button>
				<input type="text"maxlength="64" class="form-control"placeholder="https://"style="width:100%"value="<?php echo$hurl;?>"id="hurl" name="hurl" required>
			</p>
			<p>
				<label class="c8">搜索字词</label><strong>*</strong> <span>(256字符)</span>
				<input type="text"maxlength="256" class="form-control"placeholder="用分号;或逗号,隔开"style="width:100%"value="<?php echo$hrefwd;?>"name="hrefwd" required>
			</p>
			<p>
				<label>网站名称</label><strong>*</strong> <span>(32字符)</span>
				<input type="text"maxlength="32" class="form-control"placeholder="非常导航"style="width:100%"value="<?php echo$hname;?>" id="hname"name="hname" required>
			</p>
			<p>
				<label>页面标题</label> <strong>*</strong> <span>(64字符)</span>
				<input type="text"maxlength="64" class="form-control"style="width:100%"value="<?php echo$htitle;?>" id="htitle"name="htitle" required>
			</p>
			<p>
				<label>页面关键词</label><strong>*</strong> <span>(256字符)</span>
				<input type="text" maxlength="256"class="form-control"style="width:100%"value="<?php echo$hkey;?>" id="hkey"name="hkey" required>
			</p>
			<p>
				<label>页面描述</label><strong>*</strong> <span>(512字符)</span>
				<textarea rows="9"maxlength="512" class="form-control"style="width:100%" id="hdesc"name="hdesc" required><?php echo$hdesc;?></textarea>
			</p>
			<p>
				<label>网站类型</label> <span>(128字符)</span>
				<input type="text" maxlength="128" class="form-control"style="width:100%"value="<?php echo$tagstr;?>" id="htag"name="htag">
			</p>
			<p>
				<label>备用网址</label> <span>(256字符)</span>
				<input type="text" maxlength="256"class="form-control"style="width:100%"value="<?php echo$htip;?>" id="htip"name="htip">
			</p>
			<p>
				<label>相关网站ID</label> <span>(128字符)</span>
				<input type="text" maxlength="128"class="form-control"style="width:100%"value="<?php echo$hrelate;?>" id="hrelate"name="hrelate">
			</p>
			<p>
				<label>访问次数</label>
				<input type="text" class="form-control"style="width:100%"value="<?php echo$hview;?>" name="hview">
			</p>
			<p>
				<label>标题粗细</label>
				<label><input type="radio" name="hstrong" value="0"<?php if($hstrong==0)echo'checked';?>>正常</label>&nbsp;&nbsp;<label><input type="radio" name="hstrong" value="1"<?php if($hstrong==1)echo'checked';?>>粗体</label>
			</p>
			<p>
				<label>网站图标</label>
				<label><input type="radio" name="hico" value="0"<?php if($hico==0)echo'checked';?>>本地ICO</label>&nbsp;&nbsp;<label><input type="radio" name="hico" value="1"<?php if($hico==1)echo'checked';?>>七牛ICO</label>
			</p>
			<p>
				<label>更新时间 <input type="checkbox" name="uptime" value="1"></label>
			</p>
			<p>
				<label>标题颜色</label>
				<select class="form-control" name="hcolor"style="width:50%">
				<option value="0"class="c0"<?php if($hcolor==0)echo'selected';?>>正常</option><option value="1"class="c1"<?php if($hcolor==1)echo'selected';?>>浅绿</option><option value="2"class="c2"<?php if($hcolor==2)echo'selected';?>>深绿</option><option value="3"class="c3"<?php if($hcolor==3)echo'selected';?>>暗蓝</option>
				<option value="4"class="c4"<?php if($hcolor==4)echo'selected';?>>蓝色</option><option value="5"class="c5"<?php if($hcolor==5)echo'selected';?>>深蓝</option><option value="6"class="c6"<?php if($hcolor==6)echo'selected';?>>深紫</option><option value="7"class="c7"<?php if($hcolor==7)echo'selected';?>>浅紫</option>
				<option value="8"class="c8"<?php if($hcolor==8)echo'selected';?>>粉色</option><option value="9"class="c9"<?php if($hcolor==9)echo'selected';?>>红色</option><option value="10"class="c10"<?php if($hcolor==10)echo'selected';?>>橙色</option>
				</select>
			</p>
			<p>
				<label>网站状态</label>
				<select class="form-control" name="hstate"style="width:50%"><option value="1"<?php if($hstate==1)echo'selected';?>>正常(1)</option><option value="2"<?php if($hstate==2)echo'selected';?>>异常(2)</option><option value="3"<?php if($hstate==3)echo'selected';?>>改版(3)</option><option value="4"<?php if($hstate==4)echo'selected';?>>被屏蔽(4)</option><option value="0"<?php if($hstate==0)echo'selected';?>>死链(0)</option></select>
			</p>
				<input type="hidden" name="hid"value="<?php echo$hid;?>"></button>
				<input type="hidden" name="ref"value="<?php echo$ref;?>"></button>
				<button type="submit" class="btn btn-primary btn-block">编辑链接</button>
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
