<?php
include 'admin.php';

$wd=@$_GET['wd'];
$tidson=$tidfather=$res=array();
$tid=$i=0;$tname='NONE';
if(isset($_GET['tid'])){
	$tid=intval($_GET['tid']);if($tid<0)$tid=0;
	$res=$db->query("select tindex,tname from tag WHERE tid = $tid")->fetch();
	$wd=$res[0];
	$tname=$res[1];
	$res=$db->query("SELECT pagetag.rowid,pagetag.tid,pagetag.tidfather,pagetag.tidson,pagetag.pstate,pagetag.rank,tag.tindex,tag.tname,tag.ttitle FROM pagetag INNER JOIN tag ON pagetag.tidson=tag.tid WHERE pagetag.tid=$tid ORDER BY pagetag.rank DESC")->fetchAll();
	//echo"SELECT pagetag.rowid,pagetag.tid,pagetag.tidfather,pagetag.tidson,pagetag.pstate,pagetag.rank,tag.tindex,tag.tname,tag.ttitle FROM pagetag INNER JOIN tag ON pagetag.tidson=tag.tid WHERE pagetag.tid=$tid ORDER BY pagetag.rank DESC";
	foreach($res as $r){
		if($r['tidfather']<2){
			if($r['tidfather']==1){
				$tidfather[$r['tidson'].'-'.$r['rowid']]['rowid']=$r['rowid'];
				$tidfather[$r['tidson'].'-'.$r['rowid']]['tindex']=$r['tindex'];
				$tidfather[$r['tidson'].'-'.$r['rowid']]['tname']=$r['tname'];
				$tidfather[$r['tidson'].'-'.$r['rowid']]['ttitle']=$r['ttitle'];
				$tidfather[$r['tidson'].'-'.$r['rowid']]['rank']=$r['rank'];
				$tidfather[$r['tidson'].'-'.$r['rowid']]['pstate']=$r['pstate'];
			}else{
				$tidfather['n'.$r['tidson'].'-'.$r['rowid']]['rowid']=$r['rowid'];
				$tidfather['n'.$r['tidson'].'-'.$r['rowid']]['tindex']=$r['tindex'];
				$tidfather['n'.$r['tidson'].'-'.$r['rowid']]['tname']=$r['tname'];
				$tidfather['n'.$r['tidson'].'-'.$r['rowid']]['ttitle']=$r['ttitle'];
				$tidfather['n'.$r['tidson'].'-'.$r['rowid']]['rank']=$r['rank'];
				$tidfather['n'.$r['tidson'].'-'.$r['rowid']]['pstate']=$r['pstate'];
			}
		}else{
			$tidson[$r['tidfather']][$i]['rowid']=$r['rowid'];
			$tidson[$r['tidfather']][$i]['tid']=$r['tid'];
			$tidson[$r['tidfather']][$i]['tidson']=$r['tidson'];
			$tidson[$r['tidfather']][$i]['tindex']=$r['tindex'];
			$tidson[$r['tidfather']][$i]['tname']=$r['tname'];
			$tidson[$r['tidfather']][$i]['ttitle']=$r['ttitle'];
			$tidson[$r['tidfather']][$i]['rank']=$r['rank'];
			$tidson[$r['tidfather']][$i]['pstate']=$r['pstate'];
			++$i;
		}
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
    <title>页面列表 - 后台管理 - 非常导航</title>
    <meta name="keywords" content="留言列表，后台管理，非常导航">
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
                            <form action="tag.php" method="get">
								<div class="input-group">
									<input type="text" style="padding-left:15px;"class="form-control" name="wd" placeholder="搜索标签" maxlength="63">
									<span class="input-group-btn">
										<button type="submit"class="btn btn-primary fa-search"> 标签</button>
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

<h3><a class="c10" href="tag.php?wd=<?php echo$wd;?>"><i class="fa-reply"></i> 返回上一级</a>&nbsp; <a href="tag.php?<?php echo'tid=',$tid,'&wd2=',$wd;?>"><i class="fa-plus"></i>增加顶类</a></h3>
<h3><i class="fa-long-arrow-right"></i><?php echo'《',$tname,'》';?><i class="fa-long-arrow-right"></i><a class="c4"href="pageHref.php?<?php echo'hid=',$tid,'&wd=',$wd;?>"> <?php echo'网址';?></a> <a class="c4"href="pageHref.php?<?php echo'tid=',$tid,'&wd=',$wd;?>"> <?php echo'标签';?></a> <a class="c4"href="pageHref.php?<?php echo'bid=',$tid,'&wd=',$wd;?>"> <?php echo'博客';?></a></h3>
<?php
	$pstate=0;
	foreach($tidfather as $i=>$v){
		$url="";$tntwo='rowid';
		if(substr($i,0,1)=='n'){ // 区分顶级分类0和1，n表示没有父类，只有本类
			$i=substr(substr($i,1),0,strpos($i, '-')-1);
			$tn=0;
			switch($v['pstate']){
				case 5:
					$tnstr='fa-tag';
					$tntwo='whid';
					break;
				case 2:
					$tnstr='fa-compress';
					break;
				case 3:
					$tnstr='fa-external-link-square';
					break;
				case 4:
					$tnstr='fa-external-link';
					break;
				case 8:
					$tnstr='fa-bitcoin';
					$tntwo='bhid';
					break;
				default:
					$tnstr='fa-paper-plane';
			}

			$url=' href="pageHref.php?'.$tntwo.'='.$v['rowid'].'&wd='.$wd.'"';
			$v['tname']=$v['ttitle'];
			if($v['pstate']<2){
				$temp='target="_blank"';
				$url=' href="../'.$v['tindex'].'.html"'.$temp;
				$tnstr='fa-link';
				if($v['pstate']==-1){
					$v['tname']='返回上一级';
				}
			}
		}else{
			$tn=1;
			$pstate=$v['pstate'];
			$tnstr='fa-chevron-down';
			$temp='target="_blank"';
			$url=' href="page.php?tid='.$i.'&wd='.$v['tindex'].'"'.$temp;
			if($pstate==2){// 表示子类是直达本页面
				$tnstr='fa-send-o';
			}
		}
?>
<h3>
<a<?php echo$url;?>><i class="<?php echo$tnstr;?>"></i> <?php echo$v['tname'],'#',$v['rank'],'#';?></a>
<?php
	if($tn)
	{
?>
<a class="c10" href="tag.php?rowid=<?php echo $v['rowid'],'&wd2=',$wd;?>"><i class="fa-plus"></i>增加</a>
<?php 
	}
?>
<a class="c10" href="pageEdit.php?rowid=<?php echo $v['rowid'];?>"><i class="fa-edit"></i>修改</a>
<a class="c10" href="pageDel.php?rowid=<?php echo $v['rowid'];?>" onclick="return window.confirm('是否删除？')"><i class="fa-trash"></i>删除</a>
<?php
	if(!$tn)
	{
?>
<a class="c10" href="page.php?tid=<?php echo $i,'&wd=',$v['tindex'];?>"target="_blank">[本类]</a>
<?php 
	}
?>
</h3>
	<div>
	<?php
		$i=substr($i,0,strpos($i, '-'));
		if(isset($tidson[$i])){
			foreach($tidson[$i] as $arr){
		?>
			<dl>
				<dd><?php 
						if($v['pstate']<2){
							if($v['pstate']==0){
								if($arr['pstate']==8) echo'<i class="fa-bitcoin"></i>';
								elseif($arr['pstate']==5) echo'<i class="fa-tags"></i>';
								else echo'<i class="fa-link"></i>';
							}else{
								echo'<i class="fa-link"></i>';
							}
						}elseif($v['pstate']==2){ // v 表示父类状态 arr 表示子类状态
							if($arr['pstate']==3) echo'<i class="fa-external-link-square"></i>';
							elseif($arr['pstate']==4) echo'<i class="fa-external-link"></i>';
							elseif($arr['pstate']==5) echo'<i class="fa-tags"></i>';
							elseif($arr['pstate']==8) echo'<i class="fa-bitcoin"></i>';
							else echo'<i class="fa-compress"></i>';
						}

						if($arr['pstate']==5){ // 标签
							$tntwo='whid';
						}elseif($arr['pstate']==8){ // 博客
							$tntwo='bhid';
						}else{ // 链接
							$tntwo='rowid';
						}

						/*elseif($arr['pstate']==8){
							echo'<i class="fa-bitcoin"></i>';
						}else{
							if($arr['pstate']==5)echo'<i class="fa-tag"></i>';
							else echo'<i class="fa-tags"></i>';
						}
						*/
						echo $arr['rank'];?><strong><a href="pageHref.php?<?php echo $tntwo,'=',$arr['rowid'],'&wd=',$wd;?>"class="c5"><?php echo'【',$arr['tname'],'】';?></a></strong>
				<?php $pstate>1?$tn=1:$tn=0;?>
					<a href="pageEdit.php?rowid=<?php echo $arr['rowid'];?>">[修改]</a>
					<a href="pageDel.php?rowid=<?php echo $arr['rowid'];?>" onclick="return window.confirm('是否删除？')">[删除]</a>
					<a href="page.php?tid=<?php echo$arr['tidson'],'&wd=',$arr['tindex'];?>"target="_blank">[本类]</a>
				</dd>
			</dl>
		<?php
			}
		}
	?>
	</div>  
                <?php
	}
?>
 
    </div>

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
