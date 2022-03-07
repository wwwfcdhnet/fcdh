<?php

function load_config($key){
  global $db;
  $data=$db->query("select value from config where key='$key'")->fetch();
  //var_dump($data);
  if($data)
    return $data['value'];
  else
    return false;
}

function save_config($key,$value){
  global $db;
  $eof=$db->exec("update config set value='$value' where key='$key'");
 // if($key=='showhref')echo"update config set value='$value' where key='$key'";
  if(!$eof){
    $db->exec("insert into config(key,value) values('$key','$value')");
  }
}
// 生成静态html页面
function hrefhtml($hid,$tn=0,$key=''){// 0:表示生成href 1：表示生成套图 2：表示生成blog
	global $db;
	include '../config.php';
	$type=$pre=$next=$hrelate=$htmlfile='';
	$encodestr='';
//	$vip=array('免费','VIP1','VIP2','VIP3','VIP4','VIP5','VIP6','VIP7','VIP8','VIP9','VIP10');
	if($tn==2){
		$res=$db->query("select MAX(bid) as maxid from blog")->fetch();
		$maxid=$res['maxid'];
		$preid=$hid-1;if($preid<800001)$preid=$maxid;
		$nextid=$hid+1;if($nextid>$maxid)$nextid=800001;
		
		
		$template='tempBlog.html';
		$res=$db->query("select bindex as hindex,btitle as htitle,bkey as hkey,bdesc as hdesc,btag as htag,bview as hview,btime as htime,bcolor as hcolor,bstrong as hstrong,bads as hads,bvip as pvip from blog where bid=$hid limit 1")->fetch(); 
		$pvip=$res['pvip'];
		$res2=$db->query("select bid,bindex,btitle from blog where bid=$preid OR bid=$nextid limit 2")->fetchAll(); 
		foreach($res2 as $r){
			if($r['bid']==$preid){
				$pre='<a href="'.$r['bindex'].'.html">【上一篇】'.$r['btitle'].'</a>';
			}else{
				$next='<a href="'.$r['bindex'].'.html">【下一篇】'.$r['btitle'].'</a>';
			}
		}
		$res3=$db->query("SELECT tagword.word,eye FROM blogso INNER JOIN tagword on blogso.wid=tagword.wid WHERE blogso.bid=$hid")->fetchAll(); 

	}elseif($tn==1){ // 套图
		$type='p';
		$res=$db->query("select MAX(pid) as maxid from photo")->fetch();
		$maxid=$res['maxid'];
		$preid=$hid-1;if($preid<2000001)$preid=$maxid;
		$nextid=$hid+1;if($nextid>$maxid)$nextid=2000001;
		
		$template='tempPhoto.html';
		$res=$db->query("select pindex as hindex,pnum,ptitle as htitle,pdesc as hdesc,pkey as hkey,ptag as htag,pdown as hview,pdate as htime,pcolor as hcolor,pstrong as hstrong,pads as hads,year,ptip,plink,pcode,pvip,pshow,prel,psize,point,pcoin from photo where pid=$hid limit 1")->fetch(); 
		$plinkarr=explode("{|}", $res['plink']);
		$pcodearr=explode("{|}", $res['pcode']);
		$pnum=$res['pnum'];
		$pindex=$res['hindex'];
		$year=$res['year'];
		$psize=$res['psize'];
		$pvip=$res['pvip'];
		$point=$res['point'];
		$pcoin=$res['pcoin'];

		$unit=array('张','套');
		$ptip=$unit[$res['ptip']];

		$encodeuser=array('url'=>'https://www.fcdh.net/'.$pindex.'p.html','link'=>$plinkarr['0'],'link1'=>$plinkarr['1'],'code'=>$pcodearr['0'],'code1'=>$pcodearr['1'],'imgnum'=>$pnum,'pindex'=>$pindex,'pid'=>$hid,'vip'=>$pvip,'point'=>$point,'pcoin'=>$pcoin);
		$encode=serialize($encodeuser);// 被加密的字符串
		$encodestr=authcode($encode,'ENCODE',$key);
//		print_r($encodeuser);exit;
		$res2=$db->query("select pid,pindex,ptitle from photo where pid=$preid OR pid=$nextid limit 2")->fetchAll(); 
		//echo$maxid,':',"select pid,pindex,ptitle from photo where pid=$preid OR pid=$nextid limit 2";exit;
		foreach($res2 as $r){
			if($r['pid']==$preid){
				$pre='<a href="'.$r['pindex'].'p.html">【上一篇】'.$r['ptitle'].'</a>';
			}else{
				$next='<a href="'.$r['pindex'].'p.html">【下一篇】'.$r['ptitle'].'</a>';
			}
		}
		$res3=$db->query("SELECT tagword.word,eye FROM photoso INNER JOIN tagword on photoso.wid=tagword.wid WHERE photoso.pid=$hid")->fetchAll(); 
		
	//	echo"select pindex,pthumb,year,ptitle as hname,pcolor as hcolor,pstrong as hstrong,pvip from photo where pid IN($res[prel])";exit;
		
		$relatearr=$db->query("select pindex,pdate,pthumb,year,ptitle as hname,pcolor as hcolor,pstrong as hstrong,pvip from photo where pid IN($res[prel])")->fetchAll(); 
		$hrelate='<h4><a href="#related"onclick="moreHref(\'related\',this);"><i class="fa-codepen"></i> 类似美人图片<span class="more"></span></a></h4><div class="ti-ulbg"><ul class="rowp"id="related">';
		$items='';
		foreach($relatearr as $arr){
			$color=' class="bg'.$arr['hcolor'].'"';

			$name=$arr['hname']=strtr($arr['hname'],array(' '=>''));
			if($arr['hstrong']){
				$name='<strong>'.$arr['hname'].'</strong>';
			}else{
				$name=$arr['hname'];
			}
			$pdate=date("Y-m-d",$arr['pdate']);
			$items.='<li><a href="'.$arr['pindex'].'p.html"><img src="'.$_imgaddr.'thumb/'.$arr['year'].'/'.$arr['pindex'].'-'.$arr['pthumb'].'-t-.jpg"><span'.$color.'>'.$name.'</span><i class="pix bg8">'.$pdate.'</i><i class="vip bg'.$arr['pvip'].'">VIP'.$arr['pvip'].'</i></a></li>';
		}
		if(!empty($items))$hrelate.=$items.'</ul></div>';
		else $hrelate='';
	}else{
		$template='tempHref.html';
		$res=$db->query("select hindex,hname,hurl,htitle,hkey,hdesc,htag,hstate,hview,preview,nowview,htime,hcolor,hstrong,hico,hrelate,hads from href where hid=$hid limit 1")->fetch(); 
		// 获取相关网站
		//echo"select hindex,hname,hurl,htitle from href where hid IN($res[hrelate])";exit;
		$relatearr=$db->query("select hindex,hname,hurl,htitle,hstate,hcolor,hstrong,hico from href where hid IN($res[hrelate])")->fetchAll(); 
		$hrelate='<h4><a href="#related"onclick="moreHref(\'related\',this);"><i class="fa-codepen"></i> 类似'.$res['hname'].'<span class="more"></span></a></h4><div class="ti-ulbg"><ul class="row"id="related">';
		$items='';
		foreach($relatearr as $arr){
			$de=$img=$color='';
			if(isset($arr['hico'])){
				if($arr['hico']==1){ // 已经上传到七牛云
					$img='<img src="assets/logos/'.$arr['hindex'].'.png">';
				}elseif(file_exists($imgsrc)){
					$img='<img src="assets/logos/'.$arr['hindex'].'.png">';
				}
			}
			if($arr['hstate']==0){ // 表示死链
				$color=' class="de"';
				$de=' class="de subti"';
			}else{
				$de=' class="subti"';
			}

			if($arr['hcolor']){
				$color=' class="c'.$arr['hcolor'].'"';
			}
			$name=$arr['hname']=strtr($arr['hname'],array(' '=>''));
			if($arr['hstrong']){
				$name='<strong>'.$arr['hname'].'</strong>';
			}else{
				$name=$arr['hname'];
			}
			
			$urlarr=explode("{|}", $arr['hurl']);
			$arr['hurl']=$urlarr['0'];
			if(isset($urlarr['1'])){
				$arr['hurl']=$urlarr['1'];
			}
			$base64=base64_encode($arr['hurl']);
			$len=strlen($base64);
			$at=substr($base64,1);

			$items.='<li><a href="'.$arr['hindex'].'.html"><span'.$color.' title="'.$arr['htitle'].'">'.$img.$name.'</span></a><i class="fa-lock"onclick="openHref(\''.base64_encode($arr['hurl']).'\',this,-1)"></i><p'.$de.'>'.$arr['htitle'].'</p></li>';
		}
		if(!empty($items))$hrelate.=$items.'</ul></div>';
		else $hrelate='';

		$res3=$db->query("SELECT tagword.word,eye FROM hrefso INNER JOIN tagword on hrefso.wid=tagword.wid WHERE hrefso.hid=$hid")->fetchAll(); 
	}
	
	$hindex=$res['hindex'];
    $htitle=$res['htitle'];
    $hads=$res['hads'];
    $hkey=$res['hkey'];
    $hdesc=html_entity_decode($res['hdesc']);
    $hstrong=$res['hstrong'];
    $hcolor=$res['hcolor'];
	$hview=$res['hview'];
	$htime=$res['htime']; 
	$htag=$res['htag'];
	$rank='0';
	$pshow=$pnum=0;
	if($tn==1){
		$pnum=$res['pnum'];
		$rank=$res['pvip'];
		//$year=intval($hdesc);
		if(!empty($hdesc)){
			$hdesc='<p>'.$hdesc.'</p><div id="imges">';
		}else{
			$hdesc='<div id="imges">';
		}
		$pshow=$res['pshow'];
		if(empty($pshow))$pshow=$pnum;
	}elseif($tn==2){
		$rank=$res['pvip'];
	}

	if($tn==0){ // href 静态页面生成
		$hurl=$res['hurl'];
		$htxtname=$hname=$res['hname'];
		$hstate=$res['hstate'];
		$hico=$res['hico'];
		$preview=$res['preview'];
		$nowview=$res['nowview'];
		if($hstate==0)$hstate=9;
		elseif($hstate==1)$hstate=5;
		elseif($hstate==2)$hstate=10;
		elseif($hstate==4)$hstate=1;
		elseif($hstate==5)$hstate=7;
		elseif($hstate==6)$hstate=6;
		elseif($hstate==7)$hstate=8;
		elseif($hstate==8)$hstate=3;
		else $hstate=2;
		$statearr=array('9'=>'死链','5'=>'正常','10'=>'异常','2'=>'改版','1'=>'跳转','7'=>'已赞助','6'=>'已快审','8'=>'可赞助引流','3'=>'301重定向');
	}elseif($tn==1){
		$photo=$db->query("SELECT pmid,psize,pwidth,pheight,psuf,pdesc FROM photoimg WHERE pindex=$hindex")->fetchAll(); 
		$i=0;
		$desc=$size='';
		//$sizekb=0;
		foreach($photo as $img){
			$size=formatBytes($img['psize']);
			//$sizekb+=$img['psize'];
			if($i++ < $pshow){
				$hdesc .= '<p><img src="'.$_imgaddr.'thumb/'.$year.'/'.$hindex.'-'.$img['pmid'].'-m-'.$img['psuf'].'">〖'.$img['pdesc'].' &nbsp;<a href="downpic.php?id='.$hindex.'-'.$img['pmid'].'-'.$year.'-'.$img['psuf'].'"target="_blank">[查看原图]</a> &nbsp;原图像素：'.$img['pwidth'].'*'.$img['pheight'].'px，原图大小：'.$size.'〗</p>';
			}
			$desc .= '【'.$img['pdesc'].$img['psuf'].', 像素: '.$img['pwidth'].'*'.$img['pheight'].'px, 大小: <span class="c5">'.$size.'</span>】,';
			if($i==10)break;
		}
		$hdesc.='</div>';
		if($pshow>0 && $pshow<$i){
			$hdesc .= '<p><button onclick="loadMoreImges('.$hindex.','.$pshow.','.$year.')"  type="submit"id="loadmore"value="'.$pshow.'"class="btn btn-primary fa-paper-plane"style="width:100%"> 加载更多美人照片</button></p>';
		}
		
		$bbsurl=load_config('bbsurl');
		$hdesc .= '<p>本页美人图片一共有<b>'.$pnum.$ptip.'</b>美女照片，共<b>'.formatBytes($psize,'MB').'</b>。图片风格类型名称有'.trim($desc,',').'等美女图片。</p>';
		$hdesc .= '<p>套图包下载统一为<b>[7-zip]</b>压缩包格式，统一解压码为<strong>fenmeiren.net</strong>或<strong>yago.cc</strong>，美人套图上传至<a href="https://pan.baidu.com/"rel="nofollow"target="_blank">[百度网盘]</a>或<a href="https://pages.aliyundrive.com/mobile-page/web/beinvited.html?code=4dda42c"rel="nofollow"target="_blank">[阿里云盘]</a>。</p>';
		$hdesc .= '<p>本套美女图片为<b>VIP</b>套图, 等级<strong>VIP'.$rank.'会员</strong>可以下载，下载需<b>'.$res['point'].'积分</b>和<strong class="c10">'.$res['pcoin'].'美币</strong>。到目前为止，本套图片总计被下载<b id="pdown">'.$res['hview'].'</b>次。</p>';
		$hdesc .= '<p><b>注：</b>"重复获取"不扣积分和美币。 <a href="./vip/?ref=1"id="vip">[登录/注册会员]</a></p>';
		$hdesc .= '<p><button onclick="loadRarImges(\''.$encodestr.'\',this)"  type="submit"id="down"class="btn btn-primary fa-paper-plane"value=""> 获取地址</button>&nbsp;&nbsp;<button data-toggle="modal"data-target="#error" id="report" class="btn btn-primary fa-exclamation-triangle"> 报错</button> <a href="'.$bbsurl.'?url='.$pindex.'p"rel="nofollow"target="_blank">[留言]</a>
		<p id="downurl">要求<strong>VIP'.$rank.'会员</strong>，扣除<b>'.$res['point'].'积分</b>和<strong class="c10">'.$res['pcoin'].'美币</strong></p>';
	}

	$tag=$db->query("select tid,tindex,html,tico,tname,ttitle from tag where tid in($htag)")->fetchAll(); 
	$tags='';
	$sider='<li><a href="./"class="smooth"><span>返回首页</span><i class="fa-home"title="返回首页"></i></a></li>';
	foreach($tag as $r){
		$ttitle=$r['ttitle'];
		$subtiarr=array('','');
		$subtiarr=explode("#",$ttitle);	
		$ttitle=$subtiarr[0];
		$htmlfile='../'.$r['tindex'].'.html';
		if(!empty($r['tid']) && $r['html']){
			if(is_numeric($ttitle))$r['tname'].=$subtiarr[1];
			else $r['tname']=$subtiarr[0];
			$r['tname']=strtr($r['tname'],array(' '=>''));
			$tags.='<a href="'.$r['tindex'].'.html">['.$r['tname'].']</a> ';
			$sider.='<li><a href="'.$r['tindex'].'.html"class="smooth"><span>'.$r['tname'].'</span><i class="'.$r['tico'].'"title="'.$r['tname'].'"></i></a></li>';
		}
	}
//	if($tags=='')$tags='';
//	else $tags.='';

	$htmlfile=$hindex.$type.'.html';
	$html_fp = fopen('../html/'.$htmlfile, 'w');
    $handle = fopen($template, "r");//读取二进制文件时，需要将第二个参数设置成'rb'

	$pagetn=array('href','photo','href');
    $tagso=''; // 标签搜索
	foreach($res3 as $arr3){
		if(!preg_match("/[\x7f-\xff]/",$arr3['word']) && $arr3['eye']==0){
			continue;
		//	if(strpos($arr3['word'],'@')===false)continue; // 过滤非汉字字符
		//	else $arr3['word']=substr($arr3['word'],1);
		}
		$tagso.='<a href="search.html?'.$pagetn[$tn].'='.$arr3['word'].'">['.$arr3['word'].']</a> ';

	}

    //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
    $contents = fread($handle, filesize ($template));

	//$sider=file_get_contents('tempSider.html');
	if($tn && !empty($tags)){
		$tags='<p>'.$tags.'</p>';
	}
	$subtitle=' - '.load_config('title');
	if($tn==0){
		$imgsrc='../assets/logos/'.$hindex.'.png';
		$img='';

		if($hico==1){ // 已经上传到七牛云
			$img='<img src="assets/logos/'.$hindex.'.png"id="ico">';
		}elseif(file_exists($imgsrc)){
			$img='<img src="assets/logos/'.$hindex.'.png"id="ico">';
		}
		$del=$hstate==9?' de':'';
		//$url=parse_url($hurl);
		$urlarr=explode("{|}", $hurl);
		$hurl=$gohurl=$urlarr['0'];
	//	$dingxurl=get_url_index($gohurl);
		if(isset($urlarr['1'])){
			$gohurl=$urlarr['1'];
	//		$dingxurl=get_url_index($gohurl);
		}
	//	$dingx='';
	//	if($hstate==3 && $dingxurl!=$hindex)$dingx='<meta http-equiv="refresh" content="0;url='.$dingxurl.'.html">';

		$opt=$hcolor.'#'.$hstrong.'#'.$hname;
		$hname=$img.'<strong class="deline c'.$hcolor.$del.'">'.$hname.'</strong>';
		$contents = str_replace('{{hrelate}}', $hrelate, $contents);
		$contents = str_replace('{{opt}}', $opt, $contents);
		$hurlhtml='<li><span>【网址】</span><i class="fa-close deline c9'.$del.'">删除</i></li>';
		if($hads==1){$hurlhtml='<li><span>【官方首页】</span><i class="fa-university deline c5'.$del.'"title="官方首页">'.$hurl.'</i></li>';}
		elseif($hads==2){$hurlhtml='<li><span>【绿色网址】</span><i class="fa-shield deline c2'.$del.'"title="绿色认证">'.$hurl.'</i></li>';}
		elseif($hads==3){$hurlhtml='<li><span>【网址】</span><i class="fa-warning deline c10'.$del.'"title="非认证">'.$hurl.'</i></li>';}

		$contents = str_replace('{{hurlhtml}}', $hurlhtml, $contents);
		$contents = str_replace('{{hstate}}', '<strong id="state"class="c'.$hstate.'">'.$statearr[$hstate].'</strong>', $contents);
		$gohurl=base64_encode($gohurl);
		$contents = str_replace('{{hindex}}', $hindex, $contents);
		$contents = str_replace('{{hname}}', $hname, $contents);
		$contents = str_replace('{{htxtname}}', $htxtname, $contents);
		$contents = str_replace('{{hurl}}', $hurl, $contents);
//		$contents = str_replace('{{dingx}}', $dingx, $contents);
		$contents = str_replace('{{gohurl}}', $gohurl, $contents);
	}elseif($tn==1){// 图片套图
		$contents = str_replace('{{vip}}', $rank, $contents);
		$contents = str_replace('{{hrelate}}', $hrelate, $contents);
		$contents = str_replace('{{pre}}', $pre, $contents);
		$contents = str_replace('{{next}}', $next, $contents);
		$contents = str_replace('{{hindex}}', $hindex, $contents);
		//$htime=date("Y-m-d H:i:s",$htime);
		$htime=date("Y-m-d",$htime);
	}else{// 是博客文章
		$contents = str_replace('{{vip}}', $rank, $contents);
		$contents = str_replace('{{pre}}', $pre, $contents);
		$contents = str_replace('{{next}}', $next, $contents);
	}
	$contents = str_replace('{{subtitle}}', $subtitle, $contents);
	$contents = str_replace('{{hid}}', $hid, $contents);
	$contents = str_replace('{{htitle}}', $htitle, $contents);
	$contents = str_replace('{{hkey}}', $hkey, $contents);
	$contents = str_replace('{{tagso}}', $tagso, $contents);
	$contents = str_replace('{{hdescmeta}}', mb_strcut(strip_tags($hdesc),0,200), $contents);
	$contents = str_replace('{{hdesc}}', $hdesc, $contents);

	$contents = str_replace('{{hview}}', $hview, $contents);
	$contents = str_replace('{{preview}}', $preview, $contents);
	$contents = str_replace('{{nowview}}', $nowview, $contents);
	$contents = str_replace('{{htime}}', $htime, $contents);
	$contents = str_replace('{{htag}}', $tags, $contents);
	$contents = str_replace('{{sider}}', $sider, $contents);
	$ads1='';
	if($hads>0){
		$ads1='<div>'.file_get_contents('../assets/money/1.txt').'</div>';
	}
	$contents = str_replace('{{hads}}', $ads1, $contents);
   
	fwrite($html_fp, $contents);//4.将内容写入静态文件
	
	//5.文件必须关闭
	fclose($html_fp);
	fclose($handle);
	if($tn==0){
		$db->exec("update href set html=1 where hid=$hid");
	}elseif($tn==2){
		$db->exec("update blog set html=1 where bid=$hid");
	}elseif($tn==1){
		$db->exec("update photo set html=1 where pid=$hid");
	}
	copy('../html/'.$htmlfile,'../'.$htmlfile);
}

function htmlpage($tid){
	global $db;
	include '../config.php';
	//$tid=intval(@$_GET['tid']);
	$tidfather=array();
	$ad=$fenlei=$count=0;
	$pagestr=$tempsider=$hrefall='';
	$tagarr=Array();
	$tag=$db->query("select tindex,seotitle,tkey,tdesc from tag where tid=$tid")->fetch(); 
	if($tag['0']=='index'){
		//$count=1;
		$tempsider=' <li><a href="./index.html"class="smooth"><span>返回首页</span><i class="fa-mail-reply"title="返回首页"></i></a></li>';
	}

	$res=$db->query("SELECT pagetag.rowid,pagetag.tid,pagetag.tidfather,pagetag.tidson,pagetag.pstate,pagetag.rank,pagetag.navstate,tag.tindex,tag.html,tag.tico,tag.tname,tag.ttitle,tag.tcolor,tag.tstrong FROM pagetag INNER JOIN tag ON pagetag.tidson=tag.tid WHERE pagetag.tid=$tid ORDER BY pagetag.rank DESC")->fetchAll();
	//echo"SELECT pagetag.rowid,pagetag.tid,pagetag.tidfather,pagetag.tidson,pagetag.pstate,pagetag.rank,tag.tindex,tag.html,tag.tico,tag.tname,tag.ttitle,tag.tcolor,tag.tstrong FROM pagetag INNER JOIN tag ON pagetag.tidson=tag.tid WHERE pagetag.tid=$tid ORDER BY pagetag.rank DESC";
	$i=0;
	$showhref=intval(load_config('showhref'));
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
			$tidfather[$tn.$r['tidson'].'-'.$r['rowid']]['navstate']=$r['navstate'];
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
			$tidson[$r['tidfather']][$i]['navstate']=$r['navstate'];
			$tidson[$r['tidfather']][$i]['tidson']=$r['tidson'];
			$tidson[$r['tidfather']][$i]['html']=$r['html'];
			$tidson[$r['tidfather']][$i]['tico']=$r['tico'];
			$tidson[$r['tidfather']][$i]['tcolor']=$r['tcolor'];
			$tidson[$r['tidfather']][$i]['tstrong']=$r['tstrong'];
			++$i;
		}
	}

	ob_start();
	$k=true;$pages=0;
	foreach($tidfather as $i=>$v){
		$crow='row';
		if($v['pstate']==8)$crow='rowx'; // 博客文章
		if($v['pstate']==6)$crow='rowp'; // 美女套图
		if($v['pstate']>0){
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
				$navstate=$v['navstate'];// 0:自动，1：简称，2：全程，3：标题+尾词，4：标题+尾词+序号，4：自动+全显示
				if($count==0 && ($tag[0]==$v['index'] || $navstate<0)){
					$active=' class="active"';
				}
				if($v['pstate']==-1){ // 返回上一个类别
					$v['ttitle']='上一级分类';
					$v['tico']='fa-mail-reply';
				}
				$target='';
				if($v['pstate']==1){
					//$target='target="blank"';
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
				$subtiarr=array('','');
				$subtiarr=explode("#",$ttitle);	
				$v['ttitle']=$subtiarr[0];
				if(is_numeric($subtiarr[0])){
					if($navstate==1){
						$v['ttitle']=$v['tname'];
					}elseif($navstate==2){
						$v['ttitle']=$subtiarr[1];
					}elseif($navstate==3){
						$v['ttitle']=$v['tname'].$subtiarr[1];
					}else{
						$pages=1;
						$v['ttitle']=$v['tname'].$subtiarr[1].' '.$subtiarr[0].'页';
						$temp='btn-primary';
						if($count==0 && ($tag[0]==$v['index'] || $navstate<0))$temp='btn-gray';
						$pagestr.='<a href="'.$v['index'].'.html"class="btn '.$temp.'" id="prev">第'.$subtiarr[0].'页</a> ';
					}
					$ttitle=$v['tname'].$subtiarr[1].$subtiarr[0].'页';
				}elseif($navstate>0){
						if($navstate==1){$v['ttitle']=$v['tname'];}
						elseif($navstate==2){$v['ttitle']=$subtiarr[0].$subtiarr[1];}
						elseif($navstate==3){$v['ttitle']=$v['tname'].$subtiarr[1];}
				}

				$v['pstate']==8?$tntitle='文章':$tntitle='';
				$tntitle='';
				if($v['tstrong']){
					$v['ttitle']='<strong'.$color.'>'.$v['ttitle'].$tntitle.'</strong>';
				}else{
					$v['ttitle']='<span'.$color.'>'.$v['ttitle'].$tntitle.'</span>';
				}

				echo'<li',$active,'>
					<a href="',$tna,$v['tindex'],$tnb,'"'.$target.'class="',$tnc,'smooth">
						',$v['ttitle'],'<i class="',$v['tico'],'"title="',$tntitle,$ttitle,'"></i>
					</a>
                 </li>'; 
				if($v['pstate']<1 && $tag[0]=='index'){ // 分类为跳转链接
					$tempsider.='<li>
						<a href="'.$tna.$v['tindex'].$tnb.'"'.$target.'class="'.$tnc.'smooth">
							'.$v['ttitle'].'<i class="'.$v['tico'].'"title="'.$ttitle.'"></i>
						</a>
					 </li>';
				}
			}
			if($v['tidfather']==0 && $v['pstate']>0 && !in_array($v['tindex'], $tagarr)){ // 只生成本页的链接
				$ads2=$info=$class=$open=$show=$url='';
				$tagarr[$v['tindex']]=$v['tindex'];
				if($count==0 && ($tag[0]==$v['index'] || $navstate<0)){
					$open=' class="open"';
					$show=' show';
					$count++;
				}
				if($ad==0){
					$class=' class="c10"';
					$info='&nbsp;&nbsp;<strong id="info"class="c9"></strong>';
					$ads2='<script src="assets/money/pagetop.js"></script>';
					$ad=1;
				}

			//	if($v['html'] && $v['tidson']!=$v['tid']){
			//		$url='<i><a href="'.$v['index'].'.html" class="ti-more"> More &raquo;</a></i>';
			//	}
				$limit='';
				if($v['tidson']!=$v['tid']){ // 自身添加
					$temp=18;
					if($v['pstate']==6)$temp=6;
					$limit=' limit '.$temp;
					if($v['html']){
						$url='<i><a href="'.$v['index'].'.html" class="ti-more"> More &raquo;</a></i>';
					}
					if($navstate<0){
						$limit='';
					}
				}
				$hrefall.='<h4><a href="#'.$v['tindex'].'"'.$open.' onclick="moreHref(\''.$v['tindex'].'\',this);"><i class="'.$v['tico'].'"></i> '.$ttitle.$tntitle.'<span class="more"></span></a>'.$info.$url.'</h4><div class="ti-ulbg"><ul class="'.$crow.''.$show.'"id="'.$v['tindex'].'">';
				
				if($v['pstate']==5){ // hid 表示的是标签类
					$res=$db->query("SELECT pagehref.rank,tag.tid as hid,tag.tindex as hindex,tag.tico,tag.tname as hname,tag.seotitle as htitle,tag.tstrong as hstrong,tag.tcolor as hcolor FROM pagehref INNER JOIN tag ON pagehref.hid=tag.tid WHERE pagehref.tid=$i AND tag.html>0 ORDER BY pagehref.rank DESC")->fetchAll();
				}elseif($v['pstate']==8){ // hid 表示的是博客
					$res=$db->query("SELECT pagehref.rank,blog.bid as hid,blog.bindex as hindex,blog.btitle as hname,blog.bcolor as hcolor,blog.bstrong as hstrong FROM pagehref INNER JOIN blog ON pagehref.hid=blog.bid WHERE pagehref.tid=$i ORDER BY pagehref.rank DESC")->fetchAll();
				}elseif($v['pstate']==6){ // hid 表示的是套图
					$res=$db->query("SELECT pagehref.rank,photo.pid as hid,photo.pindex as hindex,photo.ptitle as hname,photo.pcolor as hcolor,photo.pstrong as hstrong,photo.pnum,photo.pthumb,photo.pvip,photo.year,photo.pdate FROM pagehref INNER JOIN photo ON pagehref.hid=photo.pid WHERE pagehref.tid=$i ORDER BY pagehref.rank DESC$limit")->fetchAll();
				}else{
					$res=$db->query("SELECT pagehref.rank,href.hid,href.hindex,href.hname,href.hurl,href.htitle,href.hstate,href.hcolor,href.hstrong,href.hico FROM pagehref INNER JOIN href ON pagehref.hid=href.hid WHERE pagehref.tid=$i ORDER BY pagehref.rank DESC$limit")->fetchAll();
					
				}
				$hreflist='';
				foreach($res as $href){
					$delwd=$de=$img=$color='';
					$subti='subti';
					if($v['pstate']!=8){
						$imgsrc="../assets/logos/".$href['hindex'].".png";
					}elseif($v['pstate']==6){
						$imgsrc="";
					}else{
						$href['hname']='※ '.$href['hname'];
					}
					if(isset($href['hurl'])){
						$urlarr=explode("{|}", $href['hurl']);
						$href['hurl']=$urlarr['0'];
						if(isset($urlarr['1'])){
							$href['hurl']=$urlarr['1'];
						}
					}
					
					if($v['pstate']==2 || $v['pstate']==1){
						if($href['hstate']==0){ // 表示死链
							$delwd=' de';
							$color=$de=' class="de"';
							$subti='subti de';
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
							$img='<img src="assets/logos/'.$href['hindex'].'.png">';
						}elseif(file_exists($imgsrc)){
							$img='<img src="assets/logos/'.$href['hindex'].'.png">';
						}
					}

					if($v['pstate']==1 || $v['pstate']==2 || $v['pstate']==5 || $v['pstate']==8 || $v['pstate']==6){ // 强制内部链接

						if($v['pstate']==5){
							if(empty($color)){
								$color=' class="'.$href['tico'].'"';
							}else{
								$color=' class="c'.$href['hcolor'].' '.$href['tico'].'"';
							}
						}elseif($v['pstate']==6){
							$color=' class="bg'.$href['hcolor'].'"';
						}

						if($href['hstrong']){
							$hname='<strong>'.$href['hname'].'</strong>';
						}else{
							$hname=$href['hname'];
						}
						if($v['pstate']==8){
							$hreflist.='<li><a href="'.$href['hindex'].'.html"><span'.$color.'>'.$hname.'</span></a></li>';
						}elseif($v['pstate']==6){//  套图
							//$temp=strtotime($href['pdate']);
							$pdate=date("Y-m-d",$href['pdate']);
							if($href['pnum']<1)$href['pnum']='N-';
							$hreflist.='<li><a href="'.$href['hindex'].'p.html"><img src="'.$_imgaddr.'thumb/'.$href['year'].'/'.$href['hindex'].'-'.$href['pthumb'].'-t-.jpg"><span'.$color.'>'.$hname.'</span><i class="pix bg8">'.$pdate.'</i><i class="vip bg'.$href['pvip'].'">VIP'.$href['pvip'].'</i></a></li>';
						}elseif($v['pstate']==5){
							$hreflist.='<li title="'.$href['htitle'].'"><a href="'.$href['hindex'].'.html"><span'.$color.'>'.$img.$hname.'</span></a><i class="fa-unlock-alt"></i><p><a href="'.$href['hindex'].'.html"target="website">'.$href['htitle'].'</a></p></li>';
						}else{
							//$hreflist.='<li><a href="'.$href['hindex'].'.html"><span'.$color.' title="'.$href['htitle'].'">'.$img.$hname.'</span></a><p'.$de.' onclick="openHref(\''.$href['hurl'].'\',this,-1)">'.$href['htitle'].'</p></li>';
							$hreflist.='<li><a href="'.$href['hindex'].'.html" title="'.$href['htitle'].'"><span'.$color.'>'.$img.$hname.'</span></a><i class="fa-lock"onclick="openHref(\''.base64_encode($href['hurl']).'\',this,-1)"></i><p class="'.$subti.'">'.$href['htitle'].'</p></li>';
						}	
					}else{
						if($v['pstate']==4){//  友情链接
							$hreflist.='<li title="'.$href['htitle'].'"><a href="'.$href['hurl'].'"target="_blank"class="link"><span'.$color.'>'.$img.$hname.'</span></a><i class="fa-unlock-alt"></i><p><a href="'.$href['hindex'].'.html">'.$href['htitle'].'</a></p></li>';
						}else{ // 强制外部链接
							$hreflist.='<li><span'.$color.' onclick="openHref(\''.base64_encode($href['hurl']).'\',this)" title="'.$href['htitle'].'">'.$img.$hname.'</span><i class="fa-unlock-alt"></i><p'.$de.'><a href="'.$href['hindex'].'.html">'.$href['htitle'].'</a></p></li>';
						}
					}
				}
				if(empty($hreflist)){
					$hrefall.='<li class="c8">【暂无数据】</li>';
				}
				$hrefall.=$hreflist.'</ul></div>';
				if($ads2!=''){
					$hrefall=$ads2.$hrefall;
				}
			}
		}else{ // 分类下有子类的情况
			$style=$expanded='';
			//if($fenlei==0 && $tna=='#'){ // '#'表示直接跳到本页
			if($fenlei==0){ // '#'表示直接跳到本页
				$style=' style="display:block"';
				$expanded=' class="has-sub expanded"';
				++$fenlei;
			}
			//if($tna=='#')$cusor='class="ti-arrow"';
			
			$subtiarr=array('','');
			$subtiarr=explode("#",$v['ttitle']);	
			$v['ttitle']=$subtiarr[0];
			$navstate=$v['navstate'];// 0:自动，1：简称，2：全程，3：标题+尾词，4：标题+尾词+序号
			
			if(is_numeric($subtiarr[0])){
				if($navstate==1){
					$v['ttitle']=$v['tname'];
				}elseif($navstate==2){
					$v['ttitle']=$subtiarr[1];
				}elseif($navstate==3){
					$v['ttitle']=$v['tname'].$subtiarr[1];
				}else{
					$pages=1;
					$v['ttitle']=$v['tname'].$subtiarr[1].' '.$subtiarr[0].'页';
					$temp='btn-primary';
					if($count==0 && ($tag[0]==$v['index'] || $navstate<0))$temp='btn-gray';
					$pagestr.='<a href="'.$v['index'].'.html"class="btn '.$temp.'" id="prev">第'.$subtiarr[0].'页</a> ';
				}
				$ttitle=$v['tname'].$subtiarr[1].$subtiarr[0].'页';
			}elseif($navstate>0){
					if($navstate==1){$v['ttitle']=$v['tname'];}
					elseif($navstate==2){$v['ttitle']=$subtiarr[0].$subtiarr[1];}
					elseif($navstate==3){$v['ttitle']=$v['tname'].$subtiarr[1];}
			}

			if($v['pstate']!=1){
				//$fenlei++;
				echo'<li',$expanded,'>
						<a class="ti-arrow">
							<span>',strtr($v['ttitle'],array(' '=>'')),'</span><i class="',$v['tico'],'"></i>
						</a>  ';
				if($tna!='#' && $tag[0]=='index'){	// 表示跳转链接
					if($fenlei==1){
						//$style=' style="display:block"';
						//$expanded=' class="has-sub expanded"';
					}
					$tempsider.='<li'.$expanded.'>
						<a class="ti-arrow">
							<span>'.strtr($v['ttitle'],array(' '=>'')).'</span><i class="'.$v['tico'].'"></i>
						</a>  ';
				}
			}else{
				$fenlei--;
			}

			$pages=$j=0;
			$li='';
			$i=substr($i,0,strpos($i, '-'));
			if(isset($tidson[$i])){
				foreach($tidson[$i] as $arr){
					$num=1;
					$crow='row';
					if($arr['pstate']==8)$crow='rowx'; // 博客文章
					if($arr['pstate']==6)$crow='rowp'; // 博客文章
					$color=$active='';
					if(!$j && $v['pstate']!=1){
						++$j;
						$v['pstate']==0?$temp='ti-hand':$temp='ti-arrow';
						if($tna=='#'||$fenlei==1){echo'<ul class="'.$temp.'"'.$style.'>';$fenlei++;}
						else echo'<ul>';
						//$tempsider.='<ul>';
					}
					$navstate=$arr['navstate'];// 0:自动，1：简称，2：全程，3：标题+尾词，4：标题+尾词+序号
					if($tag['0']=='index'&&$v['pstate']>0&&$k&&$showhref){
						//++$j==0;
						$k=false;
						echo'<li class="active">
								<a href="#records" class="smooth">
									<span>自定义</span>
								</a>
							 </li>';
					}else{
						if($count==0 && ($tag['0']==$arr['pindex'] || $navstate<0)){
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

					$subtiarr=array('','');
					$subtiarr=explode("#",$arr['ttitle']);	
					$arr['ttitle']=$subtiarr[0];
					if(is_numeric($arr['ttitle'])){
						//if($tag[0]!='index')$ttitle.=$subtiarr[0].' '.$subtiarr[1].'页';
						//$pages=1;
						//$ttitle=$arr['tname'].'第'.$arr['ttitle'].'页';
						//$arr['tname']='第'.$arr['ttitle'].'页';
						//$temp='btn-primary';
						//if($tag[0]==$arr['index'])$temp='btn-gray';
						//$pagestr.='<a href="'.$arr['index'].'.html"class="btn '.$temp.'">第'.$arr['ttitle'].'页</a> ';
						if($navstate==1){
							$ttitle=$arr['tname'];
						}elseif($navstate==2){
							$ttitle=$subtiarr[1];
						}elseif($navstate==3){
							$ttitle=$arr['tname'].$subtiarr[1];
						}
						$ttitle=$arr['tname'].$subtiarr[1].$subtiarr[0].'页';
						$pagestr='';
					}else{
						$ttitle=$arr['ttitle'];
					}
					if($v['pstate']!=1){
						echo'<li',$active,'>
								<a href="',$tna,$arr['tindex'],$tnb,'" class="smooth">
									',$arr['tname'],'
								</a>
							 </li>';
						if($v['pstate']<1 && $tag[0]=='index'){
							$li.='<li>
								<a href="'.$tna.$arr['tindex'].$tnb.'" class="smooth">
									'.$arr['tname'].'
								</a>
							 </li>';
						}
					}

				if($v['pstate']>0 && !in_array($arr['tindex'], $tagarr)){ // 只生成本页的链接
					
					$info=$open=$class=$show=$url='';					
					$tagarr[$arr['tindex']]=$arr['tindex'];
					if($count==0 && ($tag[0]==$arr['index'] || $navstate<0)){
						$open=' class="open"';
						$show=' show';
						$count++;
					}
					if($ad==0){
						$class=' class="c10"';
						$info='&nbsp;&nbsp;<strong id="info"class="c9"></strong>';
						$ad=1;
					}
					$limit='';
					if($arr['tid']!=$arr['tidson']){
						$temp=18;
						if($arr['pstate']==6)$temp=6;
						$limit=' limit '.$temp;
						if($arr['html']){
							$url='<i><a href="'.$arr['index'].'.html" class="ti-more"> More &raquo;</a></i>';
						}
						if($navstate<0){
							$limit='';
						}
					}
					$hrefall.='<h4><a href="#'.$arr['tindex'].'"'.$open.' onclick="moreHref(\''.$arr['tindex'].'\',this);"><i class="'.$arr['tico'].'"></i> '.$ttitle.'<span class="more"></span></a>'.$info.$url.'</h4><div class="ti-ulbg"><ul class="'.$crow.''.$show.'"id="'.$arr['tindex'].'">';
					
					if($arr['pstate']==5){ // hid 表示的是标签类
						$res=$db->query("SELECT pagehref.rank,tag.tid as hid,tag.tindex as hindex,tag.tico,tag.tname as hname,tag.seotitle as htitle,tag.tstrong as hstrong,tag.tcolor as hcolor FROM pagehref INNER JOIN tag ON pagehref.hid=tag.tid AND tag.html>0 WHERE pagehref.tid=$arr[tidson] ORDER BY pagehref.rank DESC")->fetchAll();
					}elseif($arr['pstate']==8){ // hid 表示的是博客文章
						$res=$db->query("SELECT pagehref.rank,blog.bid as hid,blog.bindex as hindex,blog.btitle as hname,blog.bcolor as hcolor,blog.bstrong as hstrong FROM pagehref INNER JOIN blog ON pagehref.hid=blog.bid WHERE pagehref.tid=$arr[tidson] ORDER BY pagehref.rank DESC")->fetchAll();
					}elseif($arr['pstate']==6){ // pid 表示的是套图文章
						$res=$db->query("SELECT pagehref.rank,photo.pid as hid,photo.pindex as hindex,photo.ptitle as hname,photo.pcolor as hcolor,photo.pstrong as hstrong,photo.pnum,photo.pthumb,photo.pvip,photo.year,photo.pdate FROM pagehref INNER JOIN photo ON pagehref.hid=photo.pid WHERE pagehref.tid=$arr[tidson] ORDER BY pagehref.rank DESC$limit")->fetchAll();
					}else{
						$res=$db->query("SELECT pagehref.rank,href.hid,href.hindex,href.hname,href.hurl,href.htitle,href.hdesc,href.hstate,href.hcolor,href.hstrong,href.hico FROM pagehref INNER JOIN href ON pagehref.hid=href.hid WHERE pagehref.tid=$arr[tidson] ORDER BY pagehref.rank DESC$limit")->fetchAll();
					}
					$hreflist='';
					foreach($res as $href){
						$delwd=$de=$img=$color='';
						$subti='subti';
						if($arr['pstate']!=8){
							$imgsrc="../assets/logos/".$href['hindex'].".png";
						}elseif($arr['pstate']==6){
							$imgsrc="";
						}else{
							$href['hname']='※ '.$href['hname'];
						}
						if(isset($href['hurl'])){
							$urlarr=explode("{|}", $href['hurl']);
							$href['hurl']=$urlarr['0'];
							if(isset($urlarr['1'])){
								$href['hurl']=$urlarr['1'];
							}
						}

						$tname=$href['hname'];	
						if($arr['pstate']==2 || $arr['pstate']==1){
							if($href['hstate']==0){ // 表示死链
								$delwd=' de';
								$color=$de=' class="de"';
								$subti='subti de';
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
								$img='<img src="assets/logos/'.$href['hindex'].'.png">';
							}elseif(file_exists($imgsrc)){
								$img='<img src="assets/logos/'.$href['hindex'].'.png">';
							}
						}
						if($arr['pstate']==1 || $arr['pstate']==2 || $arr['pstate']==5 || $arr['pstate']==6 || $arr['pstate']==8){ // 强制内部链接
							if($arr['pstate']==5){// 标签类型
								if(empty($color)){
									$color=' class="'.$href['tico'].'"';
								}else{
									$color=' class="c'.$href['hcolor'].' '.$href['tico'].'"';
								}
							}elseif($arr['pstate']==6){
								$color=' class="bg'.$href['hcolor'].'"';
							}
							if($href['hstrong']){
								$hname='<strong>'.$href['hname'].'</strong>';
							}else{
								$hname=$href['hname'];
							}
							if($arr['pstate']==8){
								$hreflist.='<li><a href="'.$href['hindex'].'.html"><span'.$color.'>'.$hname.'<span></a></li>';
							}elseif($arr['pstate']==6){
								$pdate=date("Y-m-d",$href['pdate']);
								$hreflist.='<li><a href="'.$href['hindex'].'p.html"><img src="'.$_imgaddr.'thumb/'.$href['year'].'/'.$href['hindex'].'-'.$href['pthumb'].'-t-.jpg"><span'.$color.'>'.$hname.'</span><i class="pix bg8">'.$pdate.'</i><i class="vip bg'.$href['pvip'].'">VIP'.$href['pvip'].'</i></a></li>';
							}elseif($arr['pstate']==5){
								$hreflist.='<li title="'.$href['htitle'].'"><a href="'.$href['hindex'].'.html"><span'.$color.'>'.$img.$hname.'</span></a><i class="fa-unlock-alt"></i><p><a href="'.$href['hindex'].'.html"target="website">'.$href['htitle'].'</a></p></li>';
							}else{
								//$hreflist.='<li><a href="'.$href['hindex'].'.html"><span'.$color.' title="'.$href['htitle'].'">'.$img.$hname.'</span></a><p'.$de.' onclick="openHref(\''.$href['hurl'].'\',this,-1)">'.$href['htitle'].'</p></li>';
								$hreflist.='<li><a href="'.$href['hindex'].'.html" title="'.$href['htitle'].'"><span'.$color.'>'.$img.$hname.'</span></a><i class="fa-lock"onclick="openHref(\''.base64_encode($href['hurl']).'\',this,-1)"></i><p class="'.$subti.'">'.$href['htitle'].'</p></li>';
							}
						}else{
							if($arr['pstate']==4){ // 外部友情链接
								$hreflist.='<li title="'.$href['htitle'].'"><a href="'.$href['hurl'].'"target="_blank"class="link"><span'.$color.'>'.$img.$hname.'</span></a><i class="fa-unlock-alt"></i><p><a href="'.$href['hindex'].'.html">'.$href['htitle'].'</a></p></li>';
							}else{ // 强制外部链接
								$hreflist.='<li><span'.$color.' onclick="openHref(\''.base64_encode($href['hurl']).'\',this)" title="'.$href['htitle'].'">'.$img.$hname.'</span><i class="fa-unlock-alt"></i><p'.$de.'><a href="'.$href['hindex'].'.html">'.$href['htitle'].'</a></p></li>';
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
			if($v['pstate']!=1)echo'</li>';
		}
	}
//	echo'xxxxxxxxxxx',$hrefall;
	if(!empty($hrefall)){ // 如果链接为空则不生成
		if($tag[0]=='index'){
			$bbsurl=load_config('bbsurl');
			echo'<li>
					<a href="'.$bbsurl.'"class="smooth">
						<span>留 言 板</span><i class="fa-weixin"title="留 言 板"></i>
					</a>
				 </li><li>
					<a href="vip/login.php"class="smooth">
						<span>[登录/注册]</span><i class="fa-user"title="登录/注册"></i>
					</a>
				 </li>';
		}
		$sider = ob_get_contents();//取得php页面输出的全部内容
		$contents=file_get_contents('tempPage.html');
		$contents = str_replace('{{sider}}', $sider, $contents);
		$contents = str_replace('{{hrefall}}', $hrefall, $contents);
		if(!empty($pagestr))$pagestr='<nav id="pages">'.$pagestr.'</nav>';
		$contents = str_replace('{{pagestr}}', $pagestr, $contents);
		$subtitle=$simi='';
		if($tag['0']=='index'){
			if($showhref){
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
		}else{
			//$subtitle=' - '.$tag['seotitle'];
			$subtitle=' - '.load_config('title');
		}
		$contents = str_replace('{{subtitle}}', $subtitle, $contents);
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
