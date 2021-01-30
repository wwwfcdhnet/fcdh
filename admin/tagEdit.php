<?php
include 'admin.php';
$act=@$_GET['act'];
$ref=$tindex=$tico=$tname=$ttitle=$seotitle=$tkey=$tdesc=$tagwd=$msg='';
$tid=-1;
$tn='tid';
if(isset($_GET['tid'])){
	$tid=intval(@$_GET['tid']);
}else{
	$tn='whid';
	$tid=intval(@$_GET['whid']);
}
$wd2=@$_GET['wd'];

if($act=='edit'){
    $edit=intval(@$_POST['edit']);
    $ref=@$_POST['ref'];
    $tindex=mb_substr(filterTitle(trim(@$_POST['tindex'])),0,32);
    $tico=mb_substr(filterTitle(trim(@$_POST['tico'])),0,32);
    $tname=mb_substr(filterTitle(trim(@$_POST['tname'])),0,8);
    $ttitle=mb_substr(filterTitle(trim(@$_POST['ttitle'])),0,16);
    $seotitle=mb_substr(filterText(trim(@$_POST['seotitle'])),0,64);
    $tkey=mb_substr(filterText(trim(@$_POST['tkey'])),0,256);
    $tdesc=mb_substr(filterText(trim(@$_POST['tdesc'])),0,512);
    $tagwd=mb_substr(trim(@$_POST['tagwd']),0,256);
    $tstrong=intval(@$_POST['tstrong'])%2;
    $tcolor=intval(@$_POST['tcolor'])%11;
	$html=0;
	$msg='<strong class="c2">编辑成功</strong>';
	$eof=$db->exec("update tag set tindex='$tindex',html=$html,tico='$tico',tname='$tname',ttitle='$ttitle',seotitle='$seotitle',tkey='$tkey',tdesc='$tdesc',tstrong=$tstrong,tcolor=$tcolor where tid=$edit");
	
	if(!$eof){
		$msg='<strong class="c9">标签 '.$tindex.' 重复!</strong>';
	}else{
		$arr = array('，' => ';', ',' => ';', "；" => ';', "|" => ';', "、" => ';');
		$tagwd=strtr($tagwd, $arr); 
		$tagarr=explode(';',trim($tagwd,';'));
		$widarr=array();
		$i=0;
		foreach($tagarr as $str){ // 要存入数据库中的word
			$word=filterTitle(trim($str));
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
		$tagso=$db->query("SELECT wid FROM tagso where tid=$edit")->fetchAll(); 
			
		$tagWidHave=array();
		$delwid=$sql='';
		$i=0;
		foreach($tagso as $v){
			if(!in_array($v['wid'],$widarr)){
				$delwid.=$v['wid'].',';
			}else{
				$tagWidHave[$i++]=$v['wid'];
			}
		}
		$delwid=trim($delwid,','); // 不要的搜索字词
		$db->exec("DELETE FROM tagso WHERE tid=$edit AND wid IN($delwid)");
		//$db->exec("DELETE FROM tagword WHERE wid IN($delwid)");

		$i=0;
		foreach($widarr as $wid){
			if(in_array($wid,$tagWidHave))continue;
			if($i++==0){
				$sql="INSERT INTO tagso SELECT $edit AS tid, '$wid' AS wid";
			}else{
				$sql.=" UNION SELECT $edit, $wid";
			}
		}
		if(!empty($sql))$db->exec($sql);
	}
	if(!empty($ref))redir($ref);
}else{
	$ref=@$_SERVER['HTTP_REFERER'];
	$edit=intval(@$_GET['edit']);
	if($edit<1 && !empty($ref))redir($ref);
	$tag=$db->query("SELECT * FROM tag WHERE tid=$edit")->fetch(); 
	//$tagso=$db->query("SELECT wid FROM tagso WHERE tid=$edit")->fetchAll(); 
	$tagso=$db->query("SELECT tagword.word FROM tagso INNER JOIN tagword on tagso.wid=tagword.wid WHERE tagso.tid=$edit")->fetchAll(); 
	//echo"SELECT tagword.word FROM tagso INNER JOIN tagword on tagso.wid=tagword.wid and tid=$edit";
	$tagwd='';
	foreach($tagso as $wd){
		$tagwd.=$wd['word'].';';
	}
	$tagwd=trim($tagwd,';');
	$tindex=$tag['tindex'];
	$html=$tag['html'];
    $tico=$tag['tico'];
    $tname=$tag['tname'];
    $ttitle=$tag['ttitle'];
    $seotitle=$tag['seotitle'];
    $tkey=$tag['tkey'];
    $tdesc=$tag['tdesc'];
    $tstrong=$tag['tstrong'];
    $tcolor=$tag['tcolor'];
	//echo'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-',$html;
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>系统设置 - 后台管理 - 非常导航</title>
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
					<li><a href="tag.php<?php if(!empty($tid))echo"?$tn=$tid&wd2=$wd2";?>">标签列表</a></li>
					<li><a href="tagAdd.php<?php if(!empty($tid))echo"?$tn=$tid&wd=$wd2";?>">增加标签</a></li>
				</ul>
            </nav>
			<br/>
			<br/>

			<form method="post" action="tagEdit.php?act=edit">
				<h3><a><i class="fa-bookmark"></i>编辑标签</a></h3>
			<p>
				<label>标签简称</label>* <span>(8字符)</span><br/>
				<input type="text" class="form-control"maxlength="8"placeholder="导航"style="width:100%"value="<?php echo$tname;?>" name="tname" required>
			</p>
			<p>
				<label>标签全称</label>* <span>(16字符)</span><br/>
				<input type="text" class="form-control"maxlength="16"placeholder="非常导航"style="width:100%"value="<?php echo$ttitle;?>" name="ttitle" required>
			</p>
			<p>
				<label>标签英文<strong>(唯一)</strong></label>* <span>(32字符)</span><br/>
				<input <?php if($html>-1) echo'disabled ';?>type="text" class="form-control"maxlength="32"placeholder="daohang"style="width:100%" name="tindex"value="<?php echo$tindex;?>" required>
				<?php
					if($html>-1){
				?>
					<input type="hidden" value="<?php echo$tindex;?>" name="tindex" required>
				<?php
					}
				?>
			</p>
			<p>
				<label><a href="https://www.htmleaf.com/ziliaoku/font-awesome/2014100887.html"target="_blank">标签图标</a></label>* <?php echo $msg; ?><span> <span>(32字符)</span><br/>
				<input type="text" class="form-control"maxlength="32"placeholder="fa-home"style="width:100%"value="<?php echo$tico;?>" name="tico" required>
			</p>
			<p>
				<label>SEO标题</label>* <span>(64字符)</span><br/>
				<input type="text" class="form-control"maxlength="64"placeholder="非常绿色安全的网址导航"style="width:50%"value="<?php echo$seotitle;?>" name="seotitle" required>
			</p>
			<p>
				<label class="c8">搜索字词</label> <span>(256字符)</span>
				<input type="text" class="form-control"maxlength="256"placeholder="用分号;或逗号,隔开"style="width:100%"name="tagwd"value="<?php echo$tagwd;?>">
			</p>
			<p>
				<label>页面关键词</label> <span>(256字符)</span>
				<input type="text" class="form-control"maxlength="256"placeholder="用分号;或逗号,隔开"style="width:100%"value="<?php echo$tkey;?>" name="tkey">
			</p>
			<p>
				<label>页面描述</label> <span>(512字符)</span>
				<input type="text" class="form-control"maxlength="512"style="width:100%"value="<?php echo$tdesc;?>" name="tdesc">
			</p>
			<p>
				<label>标题粗细</label>
				<label><input type="radio" name="tstrong" value="0"<?php if($tstrong==0)echo'checked';?>>正常</label><label><input type="radio" name="tstrong" value="1"<?php if($tstrong==1)echo'checked';?>>粗体</label>
			</p>
			<p>
				<label>标题颜色</label>
				<select class="form-control" name="tcolor"style="width:50%">
				<option value="0"class="c0"<?php if($tcolor==0)echo'selected';?>>正常</option><option value="1"class="c1"<?php if($tcolor==1)echo'selected';?>>浅绿</option><option value="2"class="c2"<?php if($tcolor==2)echo'selected';?>>深绿</option><option value="3"class="c3"<?php if($tcolor==3)echo'selected';?>>暗蓝</option>
				<option value="4"class="c4"<?php if($tcolor==4)echo'selected';?>>蓝色</option><option value="5"class="c5"<?php if($tcolor==5)echo'selected';?>>深蓝</option><option value="6"class="c6"<?php if($tcolor==6)echo'selected';?>>深紫</option><option value="7"class="c7"<?php if($tcolor==7)echo'selected';?>>浅紫</option>
				<option value="8"class="c8"<?php if($tcolor==8)echo'selected';?>>粉色</option><option value="9"class="c9"<?php if($tcolor==9)echo'selected';?>>红色</option><option value="10"class="c10"<?php if($tcolor==10)echo'selected';?>>橙色</option>
				</select>
			</p>
				<input type="hidden" name="edit"value="<?php echo$edit;?>"></button>
				<input type="hidden" name="ref"value="<?php echo$ref;?>"></button>
				<button type="submit" class="btn btn-primary btn-block">编辑标签</button>
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
</body>

</html>
