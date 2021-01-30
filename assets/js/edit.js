// 对记录或收藏的网址进行操作
var _ajaxurl='https://bbs.fcdh.net/ajax.php';
//var _ajaxurl='https://127.0.0.1/ajax.php';
var _editmodal=$('#edithref');
function editHref(url,obj,opt){
	$('input:radio[name="cate"][value="'+opt+'"]').prop('checked',true);
	$('#url').val(url);
	var keys=getHrefKey(url),
		key=opt+keys,
		html=localStorage.getItem(key),
		del=$('#delete'),
		ok=$('#move');
	if(html!=null){
		ok.unbind('click').click(function(){
			optHref(keys,obj,opt); // 传递单击事件参数
		});
		del.unbind('click').click(function(){
			localStorage.removeItem(key);
			var ulobj=$(obj).parent();
			obj.remove(); // 删除
			var ulhtml=ulobj.html();
			if(ulhtml==''){
				ulobj.html('<li><span class="c8">【暂无数据】</span></li>');
			}

		});
	}
	_editmodal.modal('show');
}
$('#upsite').on('show.bs.modal', function () {
  $('input[name=upname]').val(localStorage.getItem('uname'));
})
$('#downsite').on('show.bs.modal', function () {
  $('#downsite input').val(localStorage.getItem('uname'));
})
function optHref(keys,obj,opt){
	var curopt=$('input:radio[name="cate"]:checked').val(),
		key=opt+keys,
		html=localStorage.getItem(key),
		arr=html.split('@'),
		max=getMaxLocalNum(),
		newurl=$('#url').val();
	var	newkey=getHrefKey(newurl);
	key1=curopt+newkey;
	html=localStorage.getItem(key1);
	//alert(newurl);
	if(html==null){ // key值不存在
		html=max+'@'+newurl+'@'+curopt+'@'+arr[3]+'@'+arr[4]+'@'+arr[5];
		arr=html.split('@')
		localStorage.setItem(key1,html);
		localStorage.setItem('max',++max);
		localStorage.removeItem(key);
		//var temp456=temp7='';
		//alert(html);
		var sites=getHtmlDiv(arr,curopt);
		obj.remove();
		$('#records'+curopt).prepend(sites);

	}else if(key1!=key){ // key值存在
		arr=html.split('@');
		html=max+'@'+arr[1]+'@'+curopt+'@'+arr[3]+'@'+arr[4]+'@'+arr[5];
		localStorage.setItem(key1,html);
		localStorage.setItem('max',++max);
		var warning=$('#warning');
		warning.find('.modal-body p').html('<strong>网站键值与 '+key1+' 重复!</strong> <br/>按F5刷新查看');
		warning.modal('show');
		$("#cancel").prop("hidden",true);
		$("#ok").attr('value','10');
		$("#ok").html(' 确定 ');
	}else{
		html=max+'@'+arr[1]+'@'+curopt+'@'+arr[3]+'@'+arr[4]+'@'+arr[5];
		localStorage.setItem(key,html);
		localStorage.setItem('max',++max);
		var sites=getHtmlDiv(arr,curopt);
		obj.remove();
		$('#records'+curopt).prepend(sites);
	}
}

function getHtmlDiv(arr,curopt){
	var color='';
	if(arr[4]!='null'){// 颜色
		color=' class="'+arr[4]+'"';
	}
	var sites = '<li'+color+' onclick="editHref(\''+arr[1]+'\',this,\''+curopt+'\')" title="'+arr[3]+'">'+arr[5] +'</li>';
	return sites;
}

function optSite(obj){
	var tn=obj.getAttribute('value');
	var warning=$('#warning');
	switch(tn){
		case '0': // 覆盖操作
		case '1': // 增加收藏网站
			var title=$("input[name='title']").val().replace(/^\s*|\s*$/g,"").replace(/@/g,"#"),
				url=$("input[name='url']").val().replace(/^\s*|\s*$/g,"").replace(/@/g,"#"),
				desc=$("input[name='desc']").val().replace(/^\s*|\s*$/g,"").replace(/@/g,"#"),
				cname=color=$('input:radio[name="color"]:checked').val(),
				opt=$('input:radio[name="opt"]:checked').val();
			if(title==''){
				$("input[name='title']").focus();
				return;
			}

			if(opt==undefined)opt='1';
			
			var key1 = null;
			if(url==''){
				$("input[name='url']").focus();
				return;
			}
			key1 = opt+getHrefKey(url);
			if(color!='null'){//字体非默认颜色
				color=' class="'+cname+'"';
			}else{
				color='';
			}

			var html1 = localStorage.getItem(key1),
				max = getMaxLocalNum();

			if(tn=='0'){// 覆盖操作
				var html=max+'@'+url+'@'+opt+'@'+desc+'@'+cname+'@'+title;
				localStorage.setItem(key1,html);
				localStorage.setItem('max', ++max); // 记录存储数量的标记最大值
				if(html1==null){
					$('#records1').prepend('<li'+color+' onclick="editHref(\''+url+'\',this,1)" title="'+desc+'">'+title +'</li>');	
				}else{
					// 刷新网址
				}
				warning.modal('hide');
			}else{
				if(html1 != null){// 表示有重复的网址
					var arr1=html1.split('@');
					warning.find('.modal-body p').html('网站键值 '+key1+' 重复!，是否覆盖？<br> <strong>（'+arr1[5]+'）<br>'+arr1[1]+'</strong>');
					$('#addsite').modal('hide');
					$("#cancel").prop("hidden",false);
					$("#ok").attr('value','0');
					$("#ok").html(' 覆盖 ');
					var html=max+'@'+arr1[1]+'@'+arr1[2]+'@'+arr1[3]+'@'+arr1[4]+'@'+arr1[5];
						localStorage.setItem(key1,html); // 置顶网址
						localStorage.setItem('max', ++max); // 记录存储数量的标记最大值
					warning.modal('show');
				}else{ // 新增网址
					var	html=max+'@'+url+'@'+opt+'@'+desc+'@'+cname+'@'+title;
						localStorage.setItem(key1,html);
						localStorage.setItem('max', ++max); // 记录存储数量的标记最大值
					var sites = '<li'+color+' onclick="editHref(\''+url+'\',this,'+opt+')" title="'+desc+'">'+title +'</li>';

					$('#records'+opt).prepend(sites);	
					
				}
				$('#addsite').modal('hide');
			}
			break;
		case '3': // 从云端下载收藏网址
			var dwname=$("input[name='dwname']").val().replace(/^\s*|\s*$/g,"");
			var all=Number($('#allurl').is(':checked'));
			if(dwname==''){
				$("input[name='dwname']").focus();
				return;
			}
			get_userself_hrefs(dwname,'info',all);
			break;
		case '4': // 上传收藏网址到云端
			var upname=$("input[name='upname']").val().replace(/^\s*|\s*$/g,""),
				psw=$("input[name='psw']").val();
			
			if(upname==''){
				$("input[name='upname']").focus();
				return;
			}
			if(psw==''){
				$("input[name='psw']").focus();
				return;
			}
			var strArr=[],first='0';

			strArr.push(upname);
			strArr.push(psw);
			for(var i=0,len=localStorage.length; i<len; i++){
				var key=localStorage.key(i);
					first=key.charAt(0);
				if(key=='max' || key=='search' || key=='cate' || first=='0')continue;
				strArr.push(localStorage.getItem(key).replace(/^\s*|\s*$/g,"")+'@'+key);
			}
			var jsonstr=JSON.stringify(strArr);
			var fData = new FormData();
			fData.append("upstr",jsonstr);
			var xhr = new XMLHttpRequest();
			var info=document.getElementById('info');
			xhr.onreadystatechange=function(){
				info.innerHTML='<img src="assets/images/loading.gif">';
				if(xhr.readyState == 4 && xhr.status==200){
					//alert(xhr.responseText);
					var strArr = JSON.parse(xhr.responseText);
					if(strArr['0']){
						info.innerHTML=strArr['1'];
						localStorage.setItem('uname', upname);
						//window.location.href="home.html";
					}else{
						info.innerHTML=strArr['2'];
						if(strArr['1']=='forbid'){
							var subup=$('#subup');
							subup.attr("disabled",true);
							subup.removeClass('btn-primary');
							subup.addClass('btn-gray');
						}
					}
				}
				if(xhr.status==500 || xhr.status==502 ||xhr.status==503 ||xhr.status==504){
					info.innerHTML='上传失败!';
				}
			}
			xhr.open("POST",_ajaxurl);
			xhr.send(fData);
			$('#upsite').modal('hide');
			break;
		case '9': //清除记录网址
			localStorage.clear();
			document.getElementById('records0').innerHTML=document.getElementById('records1').innerHTML=document.getElementById('records2').innerHTML=document.getElementById('records3').innerHTML=document.getElementById('records4').innerHTML=document.getElementById('records5').innerHTML='<li><span class="c8">【暂无数据】</span></a></li>';
			warning.modal('hide');
			break;
		default:
			warning.modal('hide');
	}
}


/* 清除浏览记录*/
function clearRecords(){ // 清除浏览记录
	var warning=$('#warning');
	warning.find('.modal-body p').html('“确定”清除记录。“取消”返回。');
	$("#ok").html(' 确定 ');
	$("#ok").attr('value','9');
	warning.modal('show');
	return;
}
