<?php
include'../functionOpen.php';
?><!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0 user-scalable=0" />
    <meta name="author" content="viggo" />
    <title>会员用户仅限 - 粉美人</title>
    <link rel="shortcut icon" href="../assets/images/favicon.png">
	<link rel="stylesheet" href="https://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/fcdh.css">
    <link rel="stylesheet" href="./css/admin.css">
	<script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
	<style>
	#customers {
	  font-family: Arial, Helvetica, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	  font-size:16px;
	}

	#customers td, #customers th {
	  border: 1px solid #ddd;
	  padding: 8px;
	  text-align: center;
	}

	#customers tr:nth-child(even){background-color: #f2f2f2;}

	#customers tr:hover {background-color: #ddd;}

	#customers th {
	  padding-top: 12px;
	  padding-bottom: 12px;
	  background-color: #4CAF50;
	  color: white;
	}
	</style>
</head>

<body>
    <!-- 最外层容器 -->
    <div id="container">
        <div id="sidebar"class="toggle-others">
		<div class="ti-fix">
                <header class="logo-env">
                    <!-- logo -->
                    <div class="logo">
                        <a href="../" class="logo-expanded">
                            <img src="../assets/images/logo@2x.png"alt="非常导航之电脑版本logo" />
                        </a>
                        <a href="../" class="logo-collapsed">
                            <img src="../assets/images/logo-collapsed@2x.png"alt="非常导航之手机版本logo" />
                        </a>
                    </div>
                </header>
                <ul id="menu"> 
                    <li>
                        <a href="./" class="smooth">
                            <span>后台首页</span><i class="fa-home"></i>
                        </a>
                    </li>
                    <li>
                        <a href="login.php" class="smooth">
                            <span>会员登录</span><i class="fa-user"></i>
                        </a>
                    </li>
                    <li>
                        <a href="register.php" class="smooth">
                            <span>会员注册</span><i class="fa-flag-checkered"></i>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo$_FAKA;?>"target="_blank" class="smooth">
                            <span>获取邀请码</span><i class="fa-credit-card"></i>
                        </a>
                    </li>
                </ul>
        </div>
        </div>
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
                        <a href="../">
                            <i class="fa-home"></i>
                        </a>
                    </li>
            </nav>
			<br/>
			<br/>
			<div id="main">
				<h3><a><i class="fa-bookmark"></i> 会员用户权限</a></h3><br/>
				<table id="customers">
					<tr>
						<th>等级</th>
						<th>头衔</th>
						<th>美币</th>
						<th>有效期</th>
						<th>价格</th>
					</tr>

					<?php 
						for($i=1;$i<8;$i++){
							$color=$i+3;
							$vip=viprank($i);
							if($vip['point']<0)$vip['point']='无限';
							if($vip['month']==0)$vip['month']='<details><summary><b>已过期</b></summary><span class="c2">过期会员自动降为<b>VIP0</b></span></details>';
							elseif($vip['month']<0)$vip['month']='<details><summary>永久</summary><span class="c2">需要<b>'.$vip['day'].'天</b>内至少登录一次，否则过期降为<b>VIP0</b>会员</span></details>';
							else $vip['month']='<details><summary>'.$vip['month'].'个月</summary><span class="c2">过期降为<b>VIP0</b>会员</span></details>';

							$vip['day']<0?$vip['day']='——':$vip['day'].='天';;
							if($vip['money']<0)$vip['money']='0';
							echo'<tr class="c',$color,'">
								 <td>VIP',$i,'</td>
								 <td>',$vip['label'],'</td>
								 <td>',$vip['coin'],'枚</td>
								 <td>',$vip['month'],'</td>
								 <td>￥',$vip['money'],'元</td>
								 </tr>';
						}
						$vip3=viprank(3);
					?>
				</table>
			</div>
			<h3><a href="<?php echo$_FAKA;?>"target="_blank" class="c2"><span>【立即获取邀请码】</span></a></h3>
			<p class="c8">注：会员任意下载本站图片资源。</p>
			<!--
			注①：<strong class="c9">“永久”</strong>：需要<b>90天</b>内至少登录一次，否则过期降为<strong>VIP0</strong>会员。<br/>
			注②：<strong class="c9">“永久”</strong>：需要<b>180天</b>内至少登录一次，否则过期降为<strong>VIP0</strong>会员。<br/>
			注③：<strong class="c9">“永久”</strong>：需要<b>365天</b>内至少登录一次，否则过期降为<strong>VIP0</strong>会员。<br/>
			
			注④：<strong class="c9">“期限”</strong>：1月按照30天计算，理论上所有VIP用户都是<b>永久会员</b>，只是VIP等级不一样，延长期限不一样。<br/>
			注⑤：<strong class="c9">“等级”</strong>：VIP的等级是按照<strong>累计充值金额</strong>计算，如累计充值金额达<b><?php echo$vip3['money'];?>元</b>，那自动升级为<b>VIP3会员</b>，<strong>如果会员到期则降为VIP0级别会员</strong>。<br/>
			注⑥：<strong class="c9">“价格”</strong>：每一档充值价格对应相应的权益，如<b>首次充值<?php echo$vip3['money'];?>元</b>，获取到<b>VIP3会员</b>对应权益，之后再充值<?php echo$vip3['money'];?>元，那额外获取<b>美币<?php echo$vip3['coin'];?>枚</b>，会员期限额外<b>延长<?php echo$vip3['month'];?>个月</b>，其他权益随VIP等级变化而变化。<br/>
			条款1：<strong>VIP1</strong>及以上会员到期后降为<b>VIP0</b>级会员，每天只提供<b>1积分</b>，美币为上一天剩余的数量<br/>
			条款2：本网站因意志以外的原因被关闭，<b> 不退款，但可以超额补偿相应图片包</b>。<br/>
			条款3：会员不能用第三方工具采集本站图片，一经发现降为 <b>VIP0</b> 级会员。<br/>
			条款4：查看原图，不扣积分数，获取打包地址则扣除 <b>积分</b>和<b>美币</b><br/>
			条款5：<strong class="c9">注册本站会员，则表示同意上述所有条款</strong>。<br/>-->
			<p class="c2">————上术条款从2021年9月5日0时生效</p>
			
           <?php include'footer.php';?>
			<div id="ti-meng"></div>
		</div>
    </div> <!-- end 最处层容器 -->

		<!-- 确定信息 --> 
	<div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog"> 
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
				<h3 class="modal-title" id="myModalLabel"> 提示信息 </h3> 
			</div>
			<div class="modal-body"><p class="modal-body"><strong id="msg"></strong></p></div>
			<div class="modal-footer"> 
				<button id="cancel"type="submit" class="btn btn-primary" data-dismiss="modal"> 确 定 </button> 
			</div> 
		</div><!-- end 模态 -->
	</div>
 <script src="../assets/js/fcdh.js"></script>
	<script>
$(function(){

	$('#scodeimg').bind('click',function(){this.src='scode.php?rand='+Math.random();});
	
    $('#myform').bind('submit',function(){
		var oldname=newname=email=qq=phone=newpsw=oldpsw='';
		var oldname=$('#oldname').val().trim();
		if(oldname==''){
			$('#msg').html('请填写用户名');
			$('#warning').modal('show');
			return false;
		}else{
			newname=$('#newname').val().trim();
			email=$('#email').val().trim();
			phone=$('#phone').val().trim();
			qq=$('#qq').val().trim();
			newpsw=$('#newpsw').val().trim();
			oldpsw=$('#oldpsw').val().trim();
		}
		if($('#scode').val()==''){
			$('#msg').html('请填写验证码');
			$('#warning').modal('show');
			return false;
		}
		var info=document.getElementById('info');
		info.innerHTML='<img src="assets/images/loading.gif">';


		$.ajax({
            //请求方式
            type : "POST",
            //请求的媒体类型
			data: {'oldname':oldname,'newname':newname,'email':email,'qq':qq,'phone':phone,'newpsw':newpsw,'oldpsw':oldpsw,'scode':$('#scode').val(),'rand':Math.random()},
			//crossDomain: true,
			xhrFields:{
				withCredentials:true
			},
			async:true,
            //请求地址
            url : "ajax.php",
            //数据，json字符串
           // data : JSON.stringify(list),
            //请求成功
            success : function(data) {
               var strArr = JSON.parse(data);
				if(strArr[0]==false){
					$('#scode').val('');
					if(strArr[2]=='scode'){
						$('#msg').html('验证码有误');
						$('#warning').modal('show');
					}else if(strArr[2]=='pswtip'){
						if(strArr['3']!='')$('#email').attr("placeholder",strArr['3']);
						if(strArr['4']!='')$('#phone').attr("placeholder",strArr['4']);
						if(strArr['5']!='')$('#qq').attr("placeholder",strArr['5']);
					}else if(strArr[2]=='forbid'){ //  操作频繁过多
						var subedit=$('#subedit');
						subedit.attr("disabled",true);
						subedit.removeClass('btn-primary');
						subedit.addClass('btn-gray');
					}
					info.innerHTML=strArr['1'];
				}else{
					if(strArr['3']!='')$('#email').val(strArr['3'])
					if(strArr['4']!='')$('#phone').val(strArr['4'])
					if(strArr['5']!='')$('#qq').val(strArr['5'])
					$('#newpsw').val('');
					info.innerHTML=strArr['1'];
				}
				$('#scodeimg').attr('src','//bbs.fcdh.net/scode.php?rand='+Math.random());
            }
        });

		return false;
	});

});
</script>
</body>

</html>
