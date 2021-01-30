<?php
include 'admin.php';
$act=@$_GET['act'];
$strtn='本页+self内部';
$msg='';
$ref=@$_SERVER['HTTP_REFERER'];
$tid=$hid=$tidson=$rank=0;
$tntwo=-2;$rowid=-1;
$tn=$pstate=$tidfather=-1;
if(isset($_GET['hid'])){
	$txt='链接';
	$hid=intval(@$_GET['hid']);
	$tid=intval(@$_GET['tid']);
	if(isset($_GET['cate'])){ // 直接编辑标签
		$tid=intval(@$_GET['cate']);
	}elseif(isset($_GET['url'])){// 直接编辑链接
		$tid=intval(@$_GET['url']);
	}elseif(isset($_GET['whid'])){
		$rowid=intval(@$_GET['whid']);
		$res=$db->query("select tidson from pagetag WHERE rowid=$rowid")->fetch();
		$tid=$res['0'];
		$txt='标签';
	}else{
		if(isset($_GET['bhid'])){// 博客文章
			$rowid=intval(@$_GET['bhid']);
		}elseif(isset($_GET['rowid'])){
			$rowid=intval(@$_GET['rowid']);
		}
		$res=$db->query("select tidson from pagetag WHERE rowid=$rowid")->fetch();
		$tid=$res['0'];
	}
	$actn='href';
	$sql="select rowid,tid,hid,rank from pagehref WHERE tid=$tid and hid=$hid";
	$res=$db->query($sql)->fetch();
	$tntwo=$tidfather=-1;
	$rank=$res['3'];
	$rowid=$res['0'];
}else{
	$actn='tag';
	$txt='标签';
	$rowid=intval(@$_GET['rowid']);
	$res=$db->query("select tid,tidfather,tidson,pstate,rank from pagetag where rowid=$rowid")->fetch();
	//echo"select tid,tidfather,tidson,pstate,rank from pagetag where rowid=$rowid";
	$tid=$res['0'];
	$tidfather=$res['1'];
	$tidson=$res['2'];
	$pstate=$res['3'];
	$rank=$res['4'];
	if($tidfather>1){
		$res=$db->query("select pstate from pagetag where tid=$tid and tidfather=1 and tidson=$tidfather")->fetch();
		$tidfather=1; // 表示有父类
		//if($res['0']!=2)
		//$pstate=$res['0'];
		$tntwo=1;
		
		if($res['0']==2){
			if($pstate!=2 && $pstate!=3 && $pstate!=5 && $pstate!=8){
				$pstate=2;
			}
			$strtn='本页+self内部';
		}elseif($res['0']==0){
			if($pstate!=2 && $pstate!=5 && $pstate!=8){
				$pstate=2;
			}
			$strtn='';
			$tidfather=3; // 表示有父类为0类型
		}elseif($res['0']==5 || $res['0']==8){
			$tntwo=$tidfather=-1;
		}
	}elseif($tidfather==1){// 设置是否标签
		$tn=1;
	}
}
//echo 'xxxxxxxxxxxxxxxxxx---------------------------tntwo:',$tntwo,'pstate:',$pstate,'tidfather:',$tidfather,'res0:',$res['0'];
if($act=='tag'){ 
    $ref=@$_POST['ref'];
    $rowid=intval(@$_POST['rowid']);
	$res=$db->query("select tid,tidfather,tidson from pagetag where rowid=$rowid")->fetch();
	$tid=$res['0'];$tidfather=$tidson=-1;
	$tidfatherstr=$tidsonstr=$pstatestr='';

	if(isset($_POST['fatherstate'])){
		$tidfather=intval(@$_POST['fatherstate']);
		$tidfatherstr="tidfather=$tidfather,";
	}

	if(isset($_POST['tidson'])){
		$tidson=intval(@$_POST['tidson']);
		$tidsonstr="tidson=$tidson,";
	}
	if(isset($_POST['pstate'])){
		$pstate=intval(@$_POST['pstate']);
		if($tidson==$tid)$pstate=2;
		if($tidfather==1&&$pstate!=0&&$pstate!=3&&$pstate!=5&&$pstate!=8)$pstate=2;
		$pstatestr="pstate=$pstate,";
	}
    $rank=intval(@$_POST['rank']);
	$eof=$db->exec("update pagetag set $tidfatherstr $tidsonstr $pstatestr rank=$rank where rowid=$rowid");
	//echo"update pagetag set $tidfatherstr $tidsonstr $pstatestr rank=$rank where rowid=$rowid",$tidson,'==',$tid;
	if($eof){
		$msg='保存成功';
	}
	if(!empty($ref))redir($ref);
}elseif($act=='href'){
    $ref=@$_POST['ref'];
    $rowid=intval(@$_POST['rowid']);
    $rank=intval(@$_POST['rank']);
	$eof=$db->exec("update pagehref set rank=$rank where rowid=$rowid");
	if($eof){
		$msg='保存成功';
	}
	if(!empty($ref))redir($ref);
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>编辑标签 - 后台管理 - 非常导航</title>
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
            </nav>
			<br/>
			<br/>

			<form method="post" action="pageEdit.php?act=<?php echo$actn;?>">
				<h3><a><i class="fa-bookmark"></i>编辑<?php echo$txt;?></a></h3>
		<?php if($tidfather==1 || $tidfather==3 || $tntwo==-2){ ?>
			<?php if($tntwo==-2){ ?>
			<p>
				<label>顶级标签类型</label><br/>
				<select class="form-control" name="fatherstate"style="width:50%"onChange="fatherchange(this.value);">
				<option value="0"<?php if($tidfather==0)echo'selected';?>>顶级链接(0无子类)</option>
				<option value="1"<?php if($tidfather==1)echo'selected';?>>顶级分类(1有子类)</option>
				</select>
			</p>
			<?php } ?>
			<p>
				<label><?php echo$txt;?>类型</label><br/>
				<select class="form-control" name="pstate"id="pstate"style="width:50%">
			<?php if(($tidfather!=1&&$tidfather!=3) || $tntwo==-2){ ?>
				<option value="-1"<?php if($pstate==-1)echo'selected';?> class="hide">返回上一级+self链接(-1)</option>
			<?php } ?>
			<?php if(($tidfather==1&&$pstate==0) || $tntwo==-2){ ?>
				<option value="0"<?php if($pstate==0)echo'selected';?>>外页+self链接页面(0)</option>
			<?php } ?>

			<?php if(($tidfather==1 || $tidfather==3) &&($pstate==2 || $pstate==3 || $pstate==5 || $pstate==8) || $tntwo==-2){ ?>
				<?php if($tidfather==1 || $tntwo==-2) {?><option value="3"<?php if($pstate==3)echo'selected';?> class="hide">本页+blank外部链接(3)</option><?php } ?>	
				<option value="2"<?php if($pstate==2)echo'selected';?>><?php echo$strtn;?>链接(2)</option>
				<option value="5"<?php if($pstate==5)echo'selected';?> class="hide"><?php echo$strtn;?>标签(5)</option>
				<option value="8"<?php if($pstate==8)echo'selected';?> class="hide"><?php echo$strtn;?>博客(8)</option>
			<?php } ?>

			<!--
			<?php if($pstate==5 || $pstate==2 || $pstate==3 || $pstate==10 || $pstate==11 || $tntwo==-2){ ?>
				<option value="2"<?php if($pstate==2)echo'selected';?>>本页+self内部链接(2)</option>
				<option value="3"<?php if($pstate==3)echo'selected';?> class="hide">本页+blank外部链接(3)</option>				
			<?php } ?>
			<?php if($pstate==5 || $pstate==13 || $tntwo==-2){ ?>	
				<option value="5"<?php if($pstate==5)echo'selected';?>>本页+self内部标签(5)</option>
			<?php } ?>
			-->
				</select>
			</p>
		<?php } ?>
			<p>
				<label><?php echo$txt;?>排序 <span class="c8">(数值越大越靠前)</span></label> * <br/>
				<input type="number" class="form-control" style="width:40%" name="rank"value="<?php echo$rank;?>">
			</p>
				<input type="hidden"name="ref"value="<?php echo$ref;?>">
				<input type="hidden"name="rowid"value="<?php echo$rowid;?>">
				<input type="hidden"name="hid"value="<?php echo$hid;?>">
				<button type="submit" class="btn btn-primary btn-block">编辑<?php echo$txt;?></button>
			</form>
			<p class="c2"><?php echo $msg; ?></p>

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
	 <script type="text/javascript">
	 function fatherchange(v){
		if(v<0){return;}
		if(v==0){
			$('option.hide').show();
		}else{
			$('option.hide').hide();
		}
	 }
	 fatherchange(<?php echo$tn;?>);
	 </script>
	<script src="../assets/js/fcdh.js"></script>
</body>

</html>
