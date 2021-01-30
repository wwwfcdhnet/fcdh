function getRemoteUrl(obj,edit=0){
	var hurl=$('#'+obj.getAttribute("id")).val(); 
	if(edit==-1){//blogadd 页面
		$('#hkey').val(hurl);
		return;
	}
	$('#href').attr("href","https://bbs.fcdh.net/adminhou/ajaxAdmin.php?url="+hurl);
	var strArr=[];
	strArr.push(hurl);
	strArr.push(edit);
	var jsonstr=JSON.stringify(strArr);
	var fData = new FormData();
	fData.append("add",jsonstr);
	var xhr = new XMLHttpRequest();
	$('#info').html('<img src="../../assets/images/loading.gif">');
	xhr.onreadystatechange=function(){
		if(xhr.readyState == 4 && xhr.status==200){
			//alert(xhr.responseText);
			var strArr = JSON.parse(xhr.responseText);
			if(strArr['0']){
				if(strArr['title']!='' || edit)$('#hname').val(strArr['title']);
				if(strArr['title']!='' || edit)$('#bname').val(strArr['title']);
				if(strArr['title']!='' || edit)$('#htitle').val(strArr['title']);
				if(strArr['key']!='' || edit)$('#hkey').val(strArr['key']);
				if(strArr['desc']!='' || edit)$('#hdesc').val(strArr['desc']);
				if(strArr['index']!='' || edit){
					if(!$("#hindex").prop("disabled")){
						$('#hindex').val(strArr['index']);
					}
				}
				$('#info').html('');
				if(strArr['have']){
					$('#info').html('<strong class="c9">'+strArr['index']+' 索引已存在</strong>');
					$('#wd').val(strArr['domain']);
				}else{
					$('#info').html('<strong class="c2">成功获取数据</strong>');
				}
			}else{
				alert(strArr['2']);
			}
		}
		if(xhr.status==500 || xhr.status==502 ||xhr.status==503 ||xhr.status==504){
			$('#info').html('');
		}
	}
	xhr.open("POST",'ajaxAdmin.php');
	xhr.send(fData);
}