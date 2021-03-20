<?php
include 'admin.php';
use Qiniu\Config;
use Qiniu\Qiniu;
require dirname(__FILE__) . '/autoload.php';

$cate=array('0'=>'','2'=>'网站','3'=>'网站','5'=>'','8'=>'文章');
$ref=@$_SERVER['HTTP_REFERER'];
if(isset($_GET['hid'])){// 静态化网站详细页面
	$hid=intval(@$_GET['hid']);
	hrefhtml($hid);
}elseif(isset($_GET['bid'])){ // 生成静态博客文章
	$bid=intval(@$_GET['bid']);
	hrefhtml($bid,2);
}elseif(isset($_GET['tid'])){// // 静态化网页目录页面
	$tid=intval(@$_GET['tid']);
	$tidfather=array();
	$fenlei=$count=0;
	$tempsider=$hrefall='';
	$tagarr=Array();
	$tag=$db->query("select tindex,seotitle,tkey,tdesc from tag where tid=$tid")->fetch(); 
	if($tag['0']=='index'){
		$count=1;
		$tempsider=' <li><a href="./index.html"class="smooth"><span>返回首页</span><i class="fa-mail-reply"title="返回首页"></i></a></li>';
	}

	$res=$db->query("SELECT pagetag.rowid,pagetag.tid,pagetag.tidfather,pagetag.tidson,pagetag.pstate,pagetag.rank,tag.tindex,tag.html,tag.tico,tag.tname,tag.ttitle,tag.tcolor,tag.tstrong FROM pagetag INNER JOIN tag ON pagetag.tidson=tag.tid WHERE pagetag.tid=$tid ORDER BY pagetag.rank DESC")->fetchAll();
	//echo"SELECT pagetag.rowid,pagetag.tid,pagetag.tidfather,pagetag.tidson,pagetag.pstate,pagetag.rank,tag.tindex,tag.html,tag.tico,tag.tname,tag.ttitle,tag.tcolor,tag.tstrong FROM pagetag INNER JOIN tag ON pagetag.tidson=tag.tid WHERE pagetag.tid=$tid ORDER BY pagetag.rank DESC";
	$i=0;
	foreach($res as $r){
		$r['pstate']==0?$tnstr='':$tnstr=$r['pstate'];
		if($r['tidfather']<2){
			$r['tidfather']==1?$tn='':$tn='n';
			
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['tid']=$r['tid'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['index']=$r['tindex'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['tindex']=$r['tindex'].$tnstr;
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['tname']=$r['tname'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['ttitle']=$r['ttitle'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['pstate']=$r['pstate'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['tidfather']=$r['tidfather'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['html']=$r['html'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['tico']=$r['tico'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['tidson']=$r['tidson'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['tcolor']=$r['tcolor'];
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['tstrong']=$r['tstrong'];
		}else{
			$tidson[$r['tidfather']][$i]['tid']=$r['tid'];
			$tidson[$r['tidfather']][$i]['tindex']=$r['tindex'].$tnstr;
			$tidson[$r['tidfather']][$i]['index']=$r['tindex'];
			$tidson[$r['tidfather']][$i]['tname']=$r['tname'];
			$tidson[$r['tidfather']][$i]['ttitle']=$r['ttitle'];
			$tidson[$r['tidfather']][$i]['pstate']=$r['pstate'];
			$tidson[$r['tidfather']][$i]['tidson']=$r['tidson'];
			$tidson[$r['tidfather']][$i]['html']=$r['html'];
			$tidson[$r['tidfather']][$i]['tico']=$r['tico'];
			$tidson[$r['tidfather']][$i]['tcolor']=$r['tcolor'];
			$tidson[$r['tidfather']][$i]['tstrong']=$r['tstrong'];
			++$i;
		}
	}

	ob_start();
	$k=true;
	foreach($tidfather as $i=>$v){
		$crow='row';
		if($v['pstate']==8)$crow='rowx'; // 博客文章
		if($v['pstate']>1){
			$tna='#';
			$tnb='';
			$tnc='ti-arrow ';

		}else{
			$tna='';
			$tnb='.html';
			$tnc='';
			$v['tindex']=$v['index'];
		}
		if(substr($i,0,1)=='n'){ // 区分顶级分类,n (none)表示没有子类
			//$i=substr($i,1);  // 该分类tidson
			$i=substr(substr($i,1),0,strpos($i, '-')-1);
			if($tna=='#' || $v['html']){ // 如果静态页面存在则生成该导航
				$color=$active='';
				if($count==0 && $tna=='#'){
					$active=' class="active"';
				}
				if($v['pstate']==-1){ // 返回上一个类别
					$v['ttitle']='返回上一级';
					$v['tico']='fa-mail-reply';
				}
				$target='';
				if($v['pstate']==1){
					$target='target="blank"';
				}
				if($v['tid']==$i && $tna!='#'){
					$tna='#';
					$v['tindex']='this';
					$tnb='';
					//continue;
				}
				if($v['tcolor']){
					$color=' class="c'.$v['tcolor'].'"';
				}
				$ttitle=$v['ttitle'];
				if($v['tstrong']){
					$v['ttitle']='<strong'.$color.'>'.$v['ttitle'].'</strong>';
				}else{
					$v['ttitle']='<span'.$color.'>'.$v['ttitle'].'</span>';
				}

				echo'<li',$active,'>
					<a href="',$tna,$v['tindex'],$tnb,'"'.$target.'class="',$tnc,'smooth">
						',$v['ttitle'],'<i class="',$v['tico'],'"title="',$ttitle,'"></i>
					</a>
                 </li>'; 
				if($v['pstate']<2 && $tag[0]=='index'){ // 分类为跳转链接
					$tempsider.='<li>
						<a href="'.$tna.$v['tindex'].$tnb.'"'.$target.'class="'.$tnc.'smooth">
							'.$v['ttitle'].'<i class="'.$v['tico'].'"title="'.$ttitle.'"></i>
						</a>
					 </li>';
				}
			}
			if($v['tidfather']==0 && $v['pstate']>1 && !in_array($v['tindex'], $tagarr)){ // 只生成本页的链接
				$info=$class=$open=$show=$url='';
				$tagarr[$v['tindex']]=$v['tindex'];
				if($count==0){
					$count++;
					$open=' class="open"';
					$show=' show';
					$class=' class="c10"';
					$info='&nbsp;&nbsp;<strong id="info"class="c9"></strong>';
				}

			//	if($v['html'] && $v['tidson']!=$v['tid']){
			//		$url='<i><a href="'.$v['index'].'.html" class="ti-more"> More &raquo;</a></i>';
			//	}
				$limit='';
				if($v['tidson']!=$v['tid']){ // 自身添加
					$limit=' limit 18';
					if($v['html']){
						$url='<i><a href="'.$v['index'].'.html" class="ti-more"> More &raquo;</a></i>';
					}
				}
				$hrefall.='<h4><a'.$open.' onclick="moreHref(\''.$v['tindex'].'\',this);"><i class="'.$v['tico'].'"></i> '.$ttitle.'<span class="more"></span></a>'.$info.$url.'</h4><div class="ti-ulbg"><ul class="'.$crow.''.$show.'"id="'.$v['tindex'].'">';
				
				if($v['pstate']==5){ // hid 表示的是标签类
					$res=$db->query("SELECT pagehref.rank,tag.tid as hid,tag.tindex as hindex,tag.tico,tag.tname as hname,tag.ttitle as htitle,tag.tstrong as hstrong,tag.tcolor as hcolor FROM pagehref INNER JOIN tag ON pagehref.hid=tag.tid WHERE pagehref.tid=$i AND tag.html>0 ORDER BY pagehref.rank DESC")->fetchAll();
				}elseif($v['pstate']==8){ // hid 表示的是博客文章
					$res=$db->query("SELECT pagehref.rank,blog.bid as hid,blog.bindex as hindex,blog.btitle as hname,blog.bcolor as hcolor,blog.bstrong as hstrong FROM pagehref INNER JOIN blog ON pagehref.hid=blog.bid WHERE pagehref.tid=$i ORDER BY pagehref.rank DESC")->fetchAll();
				}else{
					$res=$db->query("SELECT pagehref.rank,href.hid,href.hindex,href.hname,href.hurl,href.htitle,href.hstate,href.hcolor,href.hstrong,href.hico FROM pagehref INNER JOIN href ON pagehref.hid=href.hid WHERE pagehref.tid=$i ORDER BY pagehref.rank DESC$limit")->fetchAll();
					
				}
				$hreflist='';
				foreach($res as $href){
					$delwd=$de=$img=$color='';
					if($v['pstate']!=8){
						$imgsrc="../assets/logos/".$href['hindex'].".png";
					}else{
						$href['hname']='※ '.$href['hname'];
					}
					
					if($v['pstate']==2){
						if($href['hstate']==0){ // 表示死链
							$delwd=' de';
							$color=$de=' class="de"';
						}
					}		

					$tname=$href['hname'];
					if($href['hcolor']){
						$color=' class="c'.$href['hcolor'].$delwd.'"';
					}
					if($href['hstrong']){
						$hname='<strong>'.$tname.'</strong>';
					}else{
						$hname=$tname;
					}
					
					if(isset($href['hico'])){
						if($href['hico']==1){ // 已经上传到七牛云
							$img='<img src="//www.fcdh.net/assets/logos/'.$href['hindex'].'.png">';
						}elseif(file_exists($imgsrc)){
							$img='<img src="../assets/logos/'.$href['hindex'].'.png">';
						}
					}

					if($v['pstate']==2 || $v['pstate']==5 || $v['pstate']==8){ // 强制内部链接

						if($v['pstate']==5){
							if(empty($color)){
								$color=' class="'.$href['tico'].'"';
							}else{
								$color=' class="c'.$href['hcolor'].' '.$href['tico'].'"';
							}
						}
						if($href['hstrong']){
							$hname='<strong>'.$href['hname'].'</strong>';
						}else{
							$hname=$href['hname'];
						}
						if($v['pstate']==8){
							$hreflist.='<li><a href="'.$href['hindex'].'.html"><span'.$color.'>'.$hname.'</span></a></li>';
						}elseif($v['pstate']==5){
							$hreflist.='<li title="'.$href['htitle'].'"><a href="'.$href['hindex'].'.html"><span'.$color.'>'.$img.$hname.'</span></a><p><a href="'.$href['hindex'].'.html"target="website">'.$href['htitle'].'</a></p></li>';
						}else{
							$hreflist.='<li><a href="'.$href['hindex'].'.html"><span'.$color.' title="'.$href['htitle'].'">'.$img.$hname.'</span></a><p'.$de.' onclick="openHref(\''.$href['hurl'].'\',this,-1)">'.$href['htitle'].'</p></li>';
							//$hreflist.='<li title="'.$href['htitle'].'"><a href="'.$href['hindex'].'.html"><span'.$color.'>'.$img.$hname.'</span><p>'.$href['htitle'].'</p></a></li>';
						}	
					}else{
						if($v['pstate']==4){//  友情链接
							$hreflist.='<li title="'.$href['htitle'].'"><a href="'.$href['hurl'].'"target="_blank"class="link"><span'.$color.'>'.$img.$hname.'</span></a><p><a href="'.$href['hindex'].'.html">'.$href['htitle'].'</a></p></li>';
						}else{ // 强制外部链接
							$hreflist.='<li><span'.$color.' onclick="openHref(\''.$href['hurl'].'\',this)" title="'.$href['htitle'].'">'.$img.$hname.'</span><p'.$de.'><a href="'.$href['hindex'].'.html">'.$href['htitle'].'</a></p></li>';
						}
					}
				}
				if(empty($hreflist)){
					$hrefall.='<li class="c8">【暂无数据】</li>';
				}
				$hrefall.=$hreflist.'</ul></div>';
			}
		}else{ // 分类下有子类的情况
			$style=$expanded='';
			if($fenlei==0 && $tna=='#'){ // '#'表示直接跳到本页
				$style=' style="display:block"';
				$expanded=' class="has-sub expanded"';
				++$fenlei;
			}
			echo'<li',$expanded,'>
					<a class="ti-hand">
						<span>',strtr($v['ttitle'],array(' '=>'')),'</span><i class="',$v['tico'],'"></i>
					</a>  ';
			if($tna!='#' && $tag[0]=='index'){	// 表示跳转链接
				if($fenlei==1){
					$style=' style="display:block"';
					$expanded=' class="has-sub expanded"';
				}
				$tempsider.='<li'.$expanded.'>
					<a class="ti-hand">
						<span>'.strtr($v['ttitle'],array(' '=>'')).'</span><i class="'.$v['tico'].'"></i>
					</a>  ';
			}

			$j=0;
			$li='';
			$i=substr($i,0,strpos($i, '-'));
			if(isset($tidson[$i])){
				foreach($tidson[$i] as $arr){
					$num=1;
					$crow='row';
					if($arr['pstate']==8)$crow='rowx'; // 博客文章
					$color=$active='';
					if(!$j){
						++$j;
						if($tna=='#')echo'<ul class="ti-arrow"'.$style.'>';
						else echo'<ul>';
						//$tempsider.='<ul>';
					}
					if($tag['0']=='index'&&$v['pstate']>1&&$k){
						//++$j==0;
						$k=false;
						echo'<li class="active">
								<a href="#records" class="smooth">
									<span>自定义</span>
								</a>
							 </li>';
					}else{
						if($count==0){
							$active=' class="active"';
						}
					}

					if($arr['tcolor']){
						$color=' class="c'.$arr['tcolor'].'"';
					}
					$ttitle=$arr['tname']=strtr($arr['tname'],array(' '=>''));
					if($arr['tstrong']){
						$arr['tname']='<strong'.$color.'>'.$arr['tname'].'</strong>';
					}else{
						$arr['tname']=$arr['tname'];
					}
					if($tnb=='.html'){
						$arr['tindex']=$arr['index'];
					}
					echo'<li',$active,'>
							<a href="',$tna,$arr['tindex'],$tnb,'" class="smooth">
								',$arr['tname'],'
							</a>
						 </li>';
					if($v['pstate']<2 && $tag[0]=='index'){
						$li.='<li>
							<a href="'.$tna.$arr['tindex'].$tnb.'" class="smooth">
								'.$arr['tname'].'
							</a>
						 </li>';
					}
				if($v['pstate']>1 && !in_array($arr['tindex'], $tagarr)){ // 只生成本页的链接
					$info=$open=$class=$show=$url='';					
					$tagarr[$arr['tindex']]=$arr['tindex'];
					if($count==0){
						$count++;
						$open=' class="open"';$show=' show';$class=' class="c10"';
						$info='&nbsp;&nbsp;<strong id="info"class="c9"></strong>';
					}
					$limit='';
					if($arr['tid']!=$arr['tidson']){						
						$limit=' limit 18';
						if($arr['html']){
							$url='<i><a href="'.$arr['index'].'.html" class="ti-more"> More &raquo;</a></i>';
						}
					}
					$hrefall.='<h4><a'.$open.' onclick="moreHref(\''.$arr['tindex'].'\',this);"><i class="'.$arr['tico'].'"></i> '.$ttitle.'<span class="more"></span></a>'.$info.$url.'</h4><div class="ti-ulbg"><ul class="'.$crow.''.$show.'"id="'.$arr['tindex'].'">';
					
					if($arr['pstate']==5){ // hid 表示的是标签类
						$res=$db->query("SELECT pagehref.rank,tag.tid as hid,tag.tindex as hindex,tag.tico,tag.tname as hname,tag.seotitle as htitle,tag.tstrong as hstrong,tag.tcolor as hcolor FROM pagehref INNER JOIN tag ON pagehref.hid=tag.tid AND tag.html>0 WHERE pagehref.tid=$arr[tidson] ORDER BY pagehref.rank DESC")->fetchAll();
					}elseif($arr['pstate']==8){ // hid 表示的是博客文章
						$res=$db->query("SELECT pagehref.rank,blog.bid as hid,blog.bindex as hindex,blog.btitle as hname,blog.bcolor as hcolor,blog.bstrong as hstrong FROM pagehref INNER JOIN blog ON pagehref.hid=blog.bid WHERE pagehref.tid=$arr[tidson] ORDER BY pagehref.rank DESC")->fetchAll();
					}else{
						$res=$db->query("SELECT pagehref.rank,href.hid,href.hindex,href.hname,href.hurl,href.htitle,href.hdesc,href.hstate,href.hcolor,href.hstrong,href.hico FROM pagehref INNER JOIN href ON pagehref.hid=href.hid WHERE pagehref.tid=$arr[tidson] ORDER BY pagehref.rank DESC$limit")->fetchAll();
					}
					$hreflist='';
					foreach($res as $href){
						$delwd=$de=$img=$color='';
						if($arr['pstate']!=8){
							$imgsrc="../assets/logos/".$href['hindex'].".png";
						}else{
							$href['hname']='※ '.$href['hname'];
						}

						$tname=$href['hname'];	
						if($arr['pstate']==2){
							if($href['hstate']==0){ // 表示死链
								$delwd=' de';
								$color=$de=' class="de"';
							}
						}		
						
						if($href['hcolor']){
							$color=' class="c'.$href['hcolor'].$delwd.'"';
						}
						if($href['hstrong']){
							$hname='<strong'.$color.'>'.$tname.'</strong>';
						}else{
							$hname=$tname;
						}
						if(isset($href['hico'])){
							if($href['hico']==1){ // 已经上传到七牛云
								$img='<img src="//www.fcdh.net/assets/logos/'.$href['hindex'].'.png">';
							}elseif(file_exists($imgsrc)){
								$img='<img src="../assets/logos/'.$href['hindex'].'.png">';
							}
						}
						if($arr['pstate']==2 || $arr['pstate']==5 || $arr['pstate']==8){ // 强制内部链接
							if($arr['pstate']==5){// 标签类型
								if(empty($color)){
									$color=' class="'.$href['tico'].'"';
								}else{
									$color=' class="c'.$href['hcolor'].' '.$href['tico'].'"';
								}
							}
							if($href['hstrong']){
								$hname='<strong>'.$href['hname'].'</strong>';
							}else{
								$hname=$href['hname'];
							}
							if($arr['pstate']==8){
								$hreflist.='<li><a href="'.$href['hindex'].'.html"><span'.$color.'>'.$hname.'<span></a></li>';
							}elseif($arr['pstate']==5){
								$hreflist.='<li title="'.$href['htitle'].'"><a href="'.$href['hindex'].'.html"><span'.$color.'>'.$img.$hname.'</span></a><p><a href="'.$href['hindex'].'.html"target="website">'.$href['htitle'].'</a></p></li>';
							}else{
								$hreflist.='<li><a href="'.$href['hindex'].'.html"><span'.$color.' title="'.$href['htitle'].'">'.$img.$hname.'</span></a><p'.$de.' onclick="openHref(\''.$href['hurl'].'\',this,-1)">'.$href['htitle'].'</p></li>';
							}
						}else{
							if($arr['pstate']==4){ // 外部友情链接
								$hreflist.='<li title="'.$href['htitle'].'"><a href="'.$href['hurl'].'"target="_blank"class="link"><span'.$color.'>'.$img.$hname.'</span></a><p><a href="'.$href['hindex'].'.html">'.$href['htitle'].'</a></p></li>';
							}else{ // 强制外部链接
								$hreflist.='<li><span'.$color.' onclick="openHref(\''.$href['hurl'].'\',this)" title="'.$href['htitle'].'">'.$img.$hname.'</span><p'.$de.'><a href="'.$href['hindex'].'.html">'.$href['htitle'].'</a></p></li>';
							}
						}
					}
					if(empty($hreflist))$hrefall.='<li class="c8">【暂无数据】</li>';
					$hrefall.=$hreflist.'</ul></div>';
				}

				}
				if($j){
					echo'</ul>';
					if($li!='' && $tag[0]=='index'){
						if($fenlei==1){
							++$fenlei;
						}
						$tempsider.='<ul'.$style.'>'.$li.'</ul>';
					}
				}
			}
			echo'</li>';
		}
	}
	if(!empty($hrefall)){ // 如果链接为空则不生成
		if($tag[0]=='index'){
			echo'<li>
					<a href="bbs.php"class="smooth">
						<span>留 言 板</span><i class="fa-weixin"title="留 言 板"></i>
					</a>
				 </li>';
		}
		$sider = ob_get_contents();//取得php页面输出的全部内容
		$contents=file_get_contents('tempPage.html');
		$contents = str_replace('{{sider}}', $sider, $contents);
		$contents = str_replace('{{hrefall}}', $hrefall, $contents);
		$simi='';
		if($tag['0']=='index'){
			$simi='<div id="simi">
					<!-- 浏览记录 -->
					<h4 id="scroll">
						<ul class="ti-scroll">
						  <li><a class="open" onclick="moreHref(\'records\',this,1);">常 用<span class="more"></span></a></li>
						  <li><a onclick="moreHref(\'records\',this,0);">记 录<span class="more"></span></a></li>
						  <li><a onclick="moreHref(\'records\',this,2);">爱 好<span class="more"></span></a></li>
						  <li><a onclick="moreHref(\'records\',this,3);">专 业<span class="more"></span></a></li>
						  <li><a onclick="moreHref(\'records\',this,4);">工 具<span class="more"></span></a></li>
						  <li><a onclick="moreHref(\'records\',this,5);">其 他<span class="more"></span></a></li>
						  <li><a href="edit.html"><i class="fa-edit"></i></a> <strong id="info"class="c9"></strong></li>
						</ul>
					</h4>
					<div class="ti-ulbg">
					<ul class="row show"id="records">
					</ul>
					</div>
				<!-- end 浏览记录 -->
				</div>';
			
			$fp = fopen('tempSider.html', "w"); //  单独输出左侧导航栏目
			fwrite($fp, $tempsider);
			fclose($fp);
		}
		$indexjs=$indexday='';
		$contents = str_replace('{{simi}}', $simi, $contents);
		$contents = str_replace('{{ttitle}}', $tag['seotitle'], $contents);
		$contents = str_replace('{{tkey}}', $tag['tkey'], $contents);
		$contents = str_replace('{{tdesc}}', $tag['tdesc'], $contents);
		if($tag[0]=='index'){
			$year=load_config('year');
			$mon=load_config('mon');
			$day=load_config('day');
			$indexday='<i class="c2">本站已运行 <strong id="years"class="c8">00</strong> 年 <strong id="days"class="c8">000</strong> 天</i>&nbsp;&nbsp;';
			$indexjs="	<script>
				var nowtime = new Date();
				var oyear=".$year.",omon=".$mon.",oday=".$day."; // 起始时间
				var nyear=parseInt(nowtime.getFullYear()),nmon=parseInt(nowtime.getMonth())+1,nday=parseInt(nowtime.getDate()); // 当前时间

				var dyear=nyear-oyear,dmon=nmon-omon,dday=nday-oday; // 相差的时间
				var dateDiff=dateBegin=year=month=day=0;
				var diff=86400000;
				if(dmon>0){
					year=oyear+dyear;
					month=omon;
					day=oday;
					dateBegin = new Date(year+'/'+month+'/'+day+' 00:00:00');
					dateDiff = nowtime.getTime() - dateBegin.getTime();//时间差的毫秒数
					dday = Math.floor(dateDiff / diff);//计算出相差天数(24 * 3600 * 1000)

				}else if(dmon<0){
					dyear--;
					year=oyear+dyear;
					month=omon;
					day=oday;
					dateBegin = new Date(year+'/'+month+'/'+day+' 00:00:00');
					dateDiff = nowtime.getTime() - dateBegin.getTime();//时间差的毫秒数
					dday = Math.floor(dateDiff / diff);//计算出相差天数(24 * 3600 * 1000)
				}else{ // 月相等
					if(dday>0){
						dday++;
					}else if(dday<0){
						dyear--;
						year=oyear+dyear;
						month=omon;
						day=oday;
						dateBegin = new Date(year+'/'+month+'/'+day+' 00:00:00');
						dateDiff = nowtime.getTime() - dateBegin.getTime();//时间差的毫秒数
						dday = Math.floor(dateDiff / diff);//计算出相差天数(24 * 3600 * 1000)
					}else{
						dday=1;					
					}			
				}
				if(dday>=0)$('#days').html(dday);
				if(dyear>=0)$('#years').html((dyear));
			</script>";
		}
		$contents = str_replace('{{indexday}}', $indexday, $contents);
		$contents = str_replace('{{indexjs}}', $indexjs, $contents);
		$htmlfile = "$tag[0].html";
		$fp = fopen('../html/tag/'.$htmlfile, "w");
		ob_end_clean();
		fwrite($fp, $contents);
		fclose($fp);
		$db->exec("update tag set html=1 where tid=$tid");
		copy('../html/tag/'.$htmlfile,'../'.$htmlfile);
	}else{
		$db->exec("update tag set html=0 where tid=$tid");
	}
}


/*  上传到牛云，并删除本地数据
if(file_exists('../'.$htmlfile)){
	$qiniu = new Qiniu($accessKey, $secretKey);
	$file = $qiniu->file('video', $htmlfile);
	$response = $file->put('../'.$htmlfile);
	if((bool)$response){// 如果上传成功 ，并删除本地数据
		unlink('../'.$htmlfile);
	}
}
*/
	if(!empty($ref))redir($ref);