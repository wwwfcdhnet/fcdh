

/*!
 * Bootstrap v3.3.1 (https://getbootstrap.com)
 * Copyright 2011-2014 Twitter,
 Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */
 // 全局变量，可以自己配置
var _ajaxsearch='ajax_search.php'; // 搜索远程数据
var _ajaxurl='ajax.php';  // 下载或上传到远程数据网址
var _ajaxstate='ajax_state.php'; // 设置url状态
var _name='admin'; // 默认下载admin用户的网址
var _gohref='';
if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");

	+function(a){
		"use strict";

		function b(b,d){
			return this.each(function(){
				var e=a(this),
					f=e.data("bs.modal"),
					g=a.extend({},
					c.DEFAULTS,
					e.data(),
					"object"==typeof b&&b
			);
			f||e.data("bs.modal",
			f=new c(this,g)),
			"string"==typeof b?f[b](d):g.show&&f.show(d)})
		}
		var c=function(b,c){
			this.options=c,
			this.$body=a(document.body),
			this.$element=a(b),
			this.$backdrop=this.isShown=null,
			this.scrollbarWidth=0,
			this.options.remote&&this.$element.find(".modal-content").load(this.options.remote,a.proxy(function(){
				this.$element.trigger("loaded.bs.modal")},this))
			};
			c.VERSION="3.3.1",
			c.TRANSITION_DURATION=300,
			c.BACKDROP_TRANSITION_DURATION=150,
			c.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},
			c.prototype.toggle=function(a){return this.isShown?this.hide():this.show(a)},
			c.prototype.show=function(b){
				var d=this,
					e=a.Event("show.bs.modal",{relatedTarget:b});
				this.$element.trigger(e),
				this.isShown||e.isDefaultPrevented()||(
				this.isShown=!0,
				this.checkScrollbar(),
				this.setScrollbar(),
				this.$body.addClass("modal-open"),
				this.escape(),
				this.resize(),
				this.$element.on(
					"click.dismiss.bs.modal",
					'[data-dismiss="modal"]',
					a.proxy(this.hide,this)),
					this.backdrop(function(){
						var e=a.support.transition&&d.$element.hasClass("fade");
						d.$element.parent().length||d.$element.appendTo(d.$body),
						d.$element.show().scrollTop(0),
						d.options.backdrop&&d.adjustBackdrop(),
						d.adjustDialog(),
						e&&d.$element[0].offsetWidth,
						d.$element.addClass("in").attr("aria-hidden",!1),
						d.enforceFocus();
					var f=a.Event("shown.bs.modal",{relatedTarget:b});
					e?d.$element.find(".modal-dialog").one(
						"bsTransitionEnd",
						function(){
							d.$element.trigger("focus").trigger(f)
						}).emulateTransitionEnd(c.TRANSITION_DURATION):d.$element.trigger("focus").trigger(f)}))
			},
					c.prototype.hide=function(b){
						b&&b.preventDefault(),
						b=a.Event("hide.bs.modal"),
						this.$element.trigger(b),
						this.isShown&&!b.isDefaultPrevented()&&(this.isShown=!1,
						this.escape(),
						this.resize(),
						a(document).off("focusin.bs.modal"),
						this.$element.removeClass("in").attr("aria-hidden",!0).off("click.dismiss.bs.modal"),
						a.support.transition&&this.$element.hasClass("fade")?this.$element.one("bsTransitionEnd",
						a.proxy(this.hideModal,this)).emulateTransitionEnd(c.TRANSITION_DURATION):this.hideModal())
					},
					c.prototype.enforceFocus=function(){
						a(document).off("focusin.bs.modal").on("focusin.bs.modal",
						a.proxy(function(a){
							this.$element[0]===a.target||this.$element.has(a.target).length||this.$element.trigger("focus")
						},this))
					},
					c.prototype.escape=function(){
						this.isShown&&this.options.keyboard?this.$element.on("keydown.dismiss.bs.modal",
						a.proxy(function(a){27==a.which&&this.hide()},this)):this.isShown||this.$element.off("keydown.dismiss.bs.modal")
					},
					c.prototype.resize=function(){this.isShown?a(window).on(
						"resize.bs.modal",
						a.proxy(this.handleUpdate,this)):a(window).off("resize.bs.modal")
					},
					c.prototype.hideModal=function(){
						var a=this;
						this.$element.hide(),
						this.backdrop(function(){a.$body.removeClass("modal-open"),
						a.resetAdjustments(),
						a.resetScrollbar(),
						a.$element.trigger("hidden.bs.modal")})
					},
					c.prototype.removeBackdrop=function(){
						this.$backdrop&&this.$backdrop.remove(),
						this.$backdrop=null
					},
					c.prototype.backdrop=function(b){
						var d=this,
							e=this.$element.hasClass("fade")?"fade":"";
						if(this.isShown&&this.options.backdrop){
							var f=a.support.transition&&e;
							if(this.$backdrop=a('<div class="modal-backdrop '+e+'" />').prependTo(this.$element).on(
								"click.dismiss.bs.modal",
								a.proxy(function(a){
									a.target===a.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus.call(this.$element[0]):this.hide.call(this))
								},this)
							),
							f&&this.$backdrop[0].offsetWidth,
							this.$backdrop.addClass("in"),!b)return;
							f?this.$backdrop.one("bsTransitionEnd",b).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):b()
						}else if(!this.isShown&&this.$backdrop){
							this.$backdrop.removeClass("in");
							var g=function(){d.removeBackdrop(),b&&b()};
							a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one("bsTransitionEnd",g).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):g()
						}else b&&b()
					},
					c.prototype.handleUpdate=function(){
						this.options.backdrop&&this.adjustBackdrop(),
						this.adjustDialog()
					},
					c.prototype.adjustBackdrop=function(){
							this.$backdrop.css("height",0).css("height",this.$element[0].scrollHeight)
					},
					c.prototype.adjustDialog=function(){
							var a=this.$element[0].scrollHeight>document.documentElement.clientHeight;
							this.$element.css(
								{
									paddingLeft:!this.bodyIsOverflowing&&a?this.scrollbarWidth:"",							paddingRight:this.bodyIsOverflowing&&!a?this.scrollbarWidth:""
								}
							)
					},
					c.prototype.resetAdjustments=function(){
						this.$element.css({paddingLeft:"",paddingRight:""})},
					c.prototype.checkScrollbar=function(){
						this.bodyIsOverflowing=document.body.scrollHeight>document.documentElement.clientHeight,
						this.scrollbarWidth=this.measureScrollbar()
					},
					c.prototype.setScrollbar=function(){var a=parseInt(this.$body.css("padding-right")||0,10);
					this.bodyIsOverflowing&&this.$body.css("padding-right",a+this.scrollbarWidth)},
					c.prototype.resetScrollbar=function(){this.$body.css("padding-right","")},
					c.prototype.measureScrollbar=function(){var a=document.createElement("div");
					a.className="modal-scrollbar-measure",
					this.$body.append(a);
					var b=a.offsetWidth-a.clientWidth;
					return this.$body[0].removeChild(a),b};
					var d=a.fn.modal;
					a.fn.modal=b,
					a.fn.modal.Constructor=c,
					a.fn.modal.noConflict=function(){
						return a.fn.modal=d,
						this
					},
					a(document).on(
							"click.bs.modal.data-api",
							'[data-toggle="modal"]',
							function(c){
								var d=a(this),
								e=d.attr("href"),
								f=a(d.attr("data-target")||e&&e.replace(/.*(?=#[^\s]+$)/,"")),
								g=f.data("bs.modal")?"toggle":a.extend({remote:!/#/.test(e)&&e},f.data(),d.data());
								d.is("a")&&c.preventDefault(),
								f.one(
									"show.bs.modal",
									function(a){
										a.isDefaultPrevented()||f.one(
											"hidden.bs.modal",
											function(){
												d.is(":visible")&&d.trigger("focus")
											}
										)
									}
								),
								b.call(f,g,this)
							}
					)
			}(jQuery);


var _pagename=null;
var _thisSearch='search.html?photo='; // 默认搜索引擎
var now = -1;
var resLength = 0;
var _this='undefined',  // 当前左侧导航打开或关闭的节点对象
	_num=30,			// index.html页面记录显示的最大记录数量
	_maxnum=120,		// records,edit.html 页面记录和收藏各显示的最大记录数量 超过自动删除剩余的
	_logo='assets/logos/';			// logo图片保存路径

var public_vars = public_vars || {};
jQuery.extend(public_vars, {
	breakpoints: {
		largescreen: 	[991, -1],
		tabletscreen: 	[768, 990],
		devicescreen: 	[0, 767]
	},

	lastBreakpoint: null
});
var _search = {
	data: [{
		name: '百度',
		img: 'assets/images/baidu.png',
		url: 'https://www.baidu.com/s',
		word:'word'
	}, {
		name: '谷歌',
		img: 'assets/images/google.png',
		url: 'https://www.google.co.jp/search',
		word:'q'
	}, {
		name: '必应',
		img: 'assets/images/bing.png',
		url: 'https://cn.bing.com/search',
		word:'q'
	}, {
		name: '好搜',
		img: 'assets/images/so.png',
		url: 'https://www.so.com/s',
		word:'q'
	}, {
		name: '搜狗',
		img: 'assets/images/sogou.png',
		url: 'https://www.sogou.com/web',
		word:'query'
	}
	/*
	, {
		name: '天猫',
		img: 'assets/images/tmall.png',
		url: 'https://list.tmall.com/search_product.htm',
		word:'q'
	}, {
		name: '京东',
		img: 'assets/images/jd.png',
		url: 'https://search.jd.com/Search',
		word:'keyword'
	}*/
	
	, { // 必须放最后
		name: '站内',
		img: 'assets/images/fcdh.png',
		url: 'search.html',
		word:'photo'
	}]
}
var rowph=0,
	rowp=$(".rowp"),
	phone=0, //手机方向 1 坚屏，2 横屏
	_hidbox=false; // 是否隐藏#box 节点
$(document).ready(function()
{
	// Main Vars
	public_vars.$body			= $("body");
	public_vars.$pageContainer	= $("#container");
	public_vars.$sidebarMenu	= $('#sidebar');
	public_vars.$tiMeng			= $('#ti-meng');	

	phone=orient();
	setup_sidebar_menu(); // 初	始化左边菜单	

	_pagename=pageName(); //获取当前页面名称

	if(_pagename=='index.html' || _pagename=='home.html' || _pagename=='edit.html'){
		loadLocalSites(); // 加载本地网址
	}

	
	/* 在用户变化屏幕显示方向的时候调用*/
	$(window).bind('orientationchange', function(e){
		phone=orient();setTimeout('trigger_resizable()',250);
	});


	// Enable/Disable Resizable Event
	var resizeTimer=null;
	$(window).resize(function() { // 解决执行两次变化
		if (resizeTimer) clearTimeout(resizeTimer);
		if(!phone){
			resizeTimer = setTimeout('trigger_resizable()',100);
		}
	});

	// Sidebar Toggle
	//$('a[data-toggle="sidebar"]')
	$('#ti-side').on('click',function(ev)
	{
		ev.preventDefault();
		
		if(public_vars.$sidebarMenu.hasClass('collapsed'))
		{
			localStorage.setItem('cate','largescreen');
			public_vars.$sidebarMenu.removeClass('collapsed');
		}else{
			public_vars.$sidebarMenu.addClass('collapsed');
			localStorage.setItem('cate','tabletscreen');
		}
		// collapsed  下隐藏expanded
		if($('#sidebar').hasClass('collapsed')) {
			$('.has-sub.expanded > ul').attr("style","");
			if(_this!='undefined' && _this.hasClass('expanded')){
				_this.removeClass('expanded');
			}
		} else {
			if(_this!='undefined' && !_this.hasClass('expanded')){
				_this.addClass('expanded');
			}
			$('.has-sub.expanded > ul').show();
		}
		fixpics();
	});
	//$('.has-sub.expanded > ul').show();

	// 手机顶部按钮打开和关闭
	$('#ti-menu,#ti-meng').on('click', function(ev)
	{
		ev.preventDefault();
		public_vars.$sidebarMenu.toggleClass('mobile');
		public_vars.$tiMeng.toggleClass('ti-meng');
	});

	public_vars.lastBreakpoint=get_current_breakpoint(); // 获取当前屏幕大小devicescreen

		///鼠标悬浮提醒

	var isTouch = ("ontouchstart" in window) ? true : false; //是否触摸屏
	$(document).on('click', '#menu .has-sub', function(){
		_this=$(this);
		var tn=public_vars.$sidebarMenu.hasClass('collapsed');
		if(isTouch && tn){
			var win=$(window),
				upht=Math.round(_this.offset().top-win.scrollTop()),
				dwht=win.height()-upht-50,
				ulblock=_this.children('ul');
			var ulh=parseInt(ulblock.css('height'))-25; // 子栏目的高度
			ulblock.css('left','80px');
			if(ulh>dwht){
				if(dwht<0)dwht=0;
				upht-=(ulh-dwht-(ulh-dwht)%50);
			}
			ulblock.css('top',upht+'px');
		}
		if(tn){return false;}

		if(!_this.hasClass('expanded')) {

		   setTimeout(function(){
				_this.find('ul').attr("style","")
		   }, 150);
		  
		} else {
			$('.has-sub ul').each(function(id,ele){
				var _that = $(this)
				if(_this.find('ul')[0] != ele) {
					setTimeout(function(){
						_that.attr("style","")
					}, 150);
				}
			})
		}
		return false;
	});

	if(!isTouch){		
		$('#menu .has-sub').mouseenter(function(){
			if(!public_vars.$sidebarMenu.hasClass('collapsed'))return;
			_this=$(this);
			var win=$(window),
				upht=Math.round(_this.offset().top-win.scrollTop()),
				dwht=win.height()-upht-50,
				ulblock=_this.children('ul');
			var ulh=parseInt(ulblock.css('height'))-25; // 子栏目的高度
			ulblock.css('left','80px');
			if(ulh>dwht){
				if(dwht<0)dwht=0;
				upht-=(ulh-dwht-(ulh-dwht)%50);
			}
			ulblock.css('top',upht+'px');
		});
	}
	
	$("#menu a.smooth").click(function(ev) {
		ev.stopPropagation();
		var rowid=$(this).attr("href");
		if('#'==rowid.charAt(0)){
			moreHref(rowid.substr(1),true);
		}
		$("#menu li").each(function() {
			$(this).removeClass("active");
		});
		$(this).parent("li").addClass("active");
		public_vars.$sidebarMenu.toggleClass('mobile');
		public_vars.$tiMeng.toggleClass('ti-meng');	
		$("html, body").animate({
			scrollTop: $($(this).attr("href")).offset().top - 107
		}, {
			duration: 150,
			easing: "swing"
		});
		if(public_vars.$sidebarMenu.hasClass('collapsed')){
			_this.children('ul').css('left','-1080px');
		}
		return false;
	});



	// 置顶操作
	var obj = document.getElementById('go-up');
	if(obj!=null){
		obj.onclick = function() {window.scrollBy(0, -50);}
		window.onscroll = function() {
			var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
			obj.style.display = (scrollTop >= 250) ? "block" : "none";
		}
	}

	// txt 值有变化
	var txt=$('#txt');
	var delwd=$('#del');
	var box=$('#box');
	var soinput=$('#txt');

	// 删除则隐藏box提示
	delwd.click(function() { // 点击隐藏box提示
		box.html('');	
		txt.val('');
		delwd.css('display', 'none');
		soinput.focus();
	});

	$("#txt").bind("input propertychange",function(event){   //当输入时，立刻隐藏下拉列表
        var word=txt.val();
		var dat = {
			wd: word
		}
		if (word != '') {			
			delwd.css('display', 'block'); //显示删除标志
			if(_hidbox){return;}
			$.ajax({
				type: "GET",
				url: "https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su",
			//  url: "https://suggestion.baidu.com/su",
				async: true,
				data: dat,
				dataType: 'jsonp',
				jsonp: 'cb',
				success: function (res) {
					box.text('');				
					box.css('display', 'block');
					resLength = res.s.length-5;
					if(resLength==0){				
						box.css('display', 'none');
						return;
					}
					now=-1;
					for (var i = 0; i < resLength; i++) {
						var oli_i = '<li><span>' + res.s[i] + '</span><i class="fa-external-link"></i></li>';
						box.append(oli_i);

						$('#box li span').eq(i).click(function () {
							var soword=this.innerHTML;
							txt.val(soword);
							window.open(_thisSearch + soword);
							box.html('');
							box.css('display', 'none');
						});
						$('#box li i').eq(i).click(function () {
							var obj=$(this).prev();
							txt.val(obj.html());
							//box.html('');
							//box.css('display', 'none')
							txt.trigger("input");
							txt.trigger("propertychange");
							soinput.focus();
						});
					}
					//box.html() === '' ? box.css('height','0px') : box.css('height','auto');
				},
				error: function (res) {
					console.log(res)
				}
			});
		} else {
			box.html('');
			box.css('display', 'none');		
			delwd.css('display', 'none');
			//box.html() === '' ? box.css('height','0px') : box.css('height','auto');
		}
    });
	// 向下选择提示列表
	txt.keydown(function (ev) {
		if(_hidbox)return;
		if (ev.keyCode == 40) { // 13表示enter键
			now++;
			if (now > resLength - 1) {
				now = 0;
			}
			$('#box li').eq(now).addClass('bg2').siblings().removeClass('bg2');
			txt.val($('#box li').eq(now).text());
		}
		
		if (ev.keyCode == 38) {
			ev.preventDefault();
			if (now == -1 || now == 0) {
				now = resLength;
			}
			now--;
			$('#box li').eq(now).addClass('bg2').siblings().removeClass('bg2');
			txt.val($('#box li').eq(now).text());
		}
		
		if (ev.keyCode == 13) {
			var textValue = txt.val();
			if (textValue != '') {
				//window.open(_thisSearch + textValue)
				// $('#'#txt'').val('');
				box.html('');		
				box.css('display', 'none');
			}
		}
	});
	btnsub=$('#ti-search button');
	btnsub.click(function() { // 点击隐藏box提示
		box.html('');		
		box.css('display', 'none');
	});
	mySearch(localStorage.getItem('search'));
	var engine=$('#ti-engine');
	for (var i = 0; i < _search.data.length; i++) {
		var addList = '<li class="'+i+'"><img src="' + _search.data[i].img + '"title="'+_search.data[i].name+'"/></li>'
		engine.append(addList);
	}

	var icon=$('#ti-icon');
	icon.click(function() {
		$('.'+$(this).attr("alt")).prependTo("#ti-engine");
		engine.attr('style','display:block');
	});

	var subform=$('#ti-search form');
	$('#ti-engine li').click(function () {
		var _index = $(this).attr("class");
		_thisSearch = _search.data[_index].url+'?'+_search.data[_index].word+'=';
		localStorage.setItem('search',_index);
		var imge = _search.data[_index].img;
		var wd = _search.data[_index].word;
		var title=_search.data[_index].name;
			icon.attr('src', imge);
			icon.attr('alt', _index);
			icon.attr('title', title);
			subform.attr('action',_thisSearch);
		//var soinput=$('#ti-search input');
			soinput.attr('name',wd);		
			soinput.focus();
		//var btnsub=$('#ti-search button');
			btnsub.html(title);
		if(title=='站内'){
			box.css('display','none');
			_hidbox=true;
		}else{
			//subform.attr('target','_blank');
			_hidbox=false;
			//btnsub.unbind('click');
		}
	});

	txt.focus(function(){
		engine.attr('style','display:none');
		box.attr('style','display:block');
	});

	subform.mousedown(function(ev){
		ev.stopPropagation();
	});
	
	$("body").mousedown(function(){
		engine.attr('style','display:none');
		box.attr('style','display:none');//setTimeout('displaynone()',250);	
	});


	
	return false;

});
function mySearch(_index){// 加载最近使用的搜索
	if(_index!=null){
		_thisSearch = _search.data[_index].url+'?'+_search.data[_index].word+'=';
		var imge = _search.data[_index].img;
		var wd = _search.data[_index].word;
		var title=_search.data[_index].name;
		var icon=$('#ti-icon');
			icon.attr('src', imge);
			icon.attr('alt', _index);
			icon.attr('title', title);
		var subform=$('#ti-search form');
			subform.attr('action',_thisSearch);
		var inputso=$('#ti-search input');
		//inputso.focus();
		//var btnsub=$('#ti-search button');
			inputso.attr('name',wd);
			btnsub.html(title);
		if(title=='站内'){
			subform.attr('target','_self');
			$('#box').css('display','none');
			_hidbox=true;
		}else{		
			subform.attr('target','_blank');
			_hidbox=false;
		}
	}
	//加载搜索页面
	if(_pagename=='search.html'){
		type='href';
		wd='fcdh';
		var arr=getQueryVariable(title);
		if(arr!=false){
			type=arr[0],wd=decodeURIComponent(arr[1]);
		}
		var tagso=$('#tag');
		var blogso=$('#blog');
		var hrefso=$('#href');
		var photoso=$('#photo');
		tagso.attr('href','?tag='+wd);
		blogso.attr('href','?blog='+wd);
		hrefso.attr('href','?href='+wd);
		photoso.attr('href','?photo='+wd);
		if(wd!='' && arr)getHrefList(wd,type);	
	}
}

function getQueryVariable(title)
{
	var url = window.location.search;
	if (url.indexOf("?") != -1){
	   var query=url.substring(1);
	   var vars = query.split("&");
	   for (var i=0;i<vars.length;i++) {
			   var pair = vars[i].split("=");
			   if(pair[0] == 'href' || pair[0] == 'tag' || pair[0] == 'blog' || pair[0] == 'photo'){
					var soinput=$('#txt');
					soinput.attr('name',pair[0]);	
					if(title=='站内'){
						if(pair[0] == 'blog'){
							btnsub.html('博客');
						}else if(pair[0] == 'tag'){
							btnsub.html('标签');
						}else if(pair[0] == 'photo'){
							btnsub.html('套图');
						}else{
							btnsub.html('站内');
						}
					}
					return pair;
				}
				return(false);
	   }
	}
	return(false);
}
// 搜索站内网站
function getHrefList(wd='',type='href',page=1){
	if(wd==''){
		//window.location.href='';
		return;
	}
	$('#txt').val(wd);

	var strArr=[];
		strArr.push(wd);
		strArr.push(type);
		strArr.push(page);
		var jsonstr=JSON.stringify(strArr);
		var fData = new FormData();
		fData.append("wd",jsonstr);
		var xhr = new XMLHttpRequest();

		var mainid='main';
		var simi='';
		if(_pagename=='home.html'||_pagename=='edit.html'){
			mainid='simi';
		}else if(_pagename=='index.html'){
			simi='simi';
		}else if(_pagename=='bbs'){
			simi='myform';
		}
		var info=document.getElementById('info');
		var main=document.getElementById(mainid);
		var items=hreflist='';
		xhr.onreadystatechange=function(){
			info.innerHTML='<img src="assets/images/loading.gif">';
			if(xhr.readyState == 4 && xhr.status==200){
				//alert(xhr.responseText);
				var strArr = JSON.parse(xhr.responseText);
				if(strArr['0']){
					var type=strArr['3'];
					var count=0;
					var de='';
					var targ='target="_blank"';
					if(type=='photo'){
						hreflist='<h4><a><i class="fa-bookmark"></i> '+wd+'</a> &nbsp; </h4><div class="ti-ulbg"><ul class="rowp show">';
					}
					for(var i=4,len=strArr.length;i<len;){
						if(type=='href'){
							de='';
							if(strArr[i+3]==0){ //死链
								de=' class="de"';
							}
							var gourl=strArr[i+2];
							hreflist+='<h4><a><i class="fa-bookmark"></i> '+strArr[i]+'</a> &nbsp; </h4><div class="ti-ulbg"><ul class="rowso">';
							hreflist+='<li><a onclick="openHref(\''+gourl+'\',0,\'0#0#'+strArr[i]+'\')"><span class="fa-external-link c6"></span></a>&nbsp;<a href="'+strArr[i+1]+'.html"'+targ+'><strong'+de+'>'+strArr[i+4]+'</strong></a>';
							i+=5;
						}else if(type=='tag'){
							hreflist+='<h4><a><i class="fa-bookmark"></i> '+strArr[i++]+'</a> &nbsp; </h4><div class="ti-ulbg"><ul class="rowso">';
							hreflist+='<li><span class="fa-flag c6"></span>&nbsp;<a href="'+strArr[i++]+'.html"'+targ+'><strong>'+strArr[i++]+'</strong></a>';
						}else if(type=='photo'){ // 套图
							//hreflist+='<li><span class="fa-flag c6"></span>&nbsp;<a href="'+strArr[i++]+'.html"'+targ+'><strong>'+strArr[i++]+'</strong></a>';
							if(strArr[i+3]<1)strArr[i+3]='N-';
							hreflist+='<li><a href="'+strArr[i+1]+'p.html"><img src="'+strArr[i+7]+'thumb/'+strArr[i+2]+'/'+strArr[i+1]+'-'+strArr[i+4]+'-t-.jpg"><span class="bg'+strArr[i+6]+'">'+strArr[i]+'</span><i class="pix bg8">'+strArr[i+3]+'</i><i class="vip bg'+strArr[i+5]+'">VIP'+strArr[i+5]+'</i></a></li>';
							i+=8;
						}else{// 博客
							hreflist+='<h4><a><i class="fa-bookmark"></i> 文章</a> &nbsp; </h4><div class="ti-ulbg"><ul class="rowso">';
							hreflist+='<li><span class="fa-flag c6"></span>&nbsp;<a href="'+strArr[++i]+'.html"'+targ+'><strong>'+strArr[i-1]+'</strong></a>';
							++i;
						}
						if(type=='href' || type=='blog'){
							hreflist+='<br><i class="fa-calendar"> '+strArr[i++]+'</i>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-eye"> '+strArr[i++]+'</i></li>';
						}
						if(type !='photo')hreflist+='</ul></div>';
					}
					if(type=='photo'){
						hreflist+='</ul></div>';
					}
					var pre=strArr['2']-1;
					var next=strArr['2']+1;
					var nav='<nav id="pages">';
					if(pre>0){
						nav+='<a onclick="getHrefList(\''+wd+'\',\''+type+'\','+pre+')" class="btn btn-primary" id="prev">第 '+pre+' 页</a> ';
					}
					if(strArr['2']>0){
						nav+='<a class="btn btn-gray" id="prev">第 '+strArr['2']+' 页</a> ';
					}
					if(strArr['1']){
						nav+=' <a onclick="getHrefList(\''+wd+'\',\''+type+'\','+next+')" class="btn btn-primary" id="next">第 '+next+' 页</a>';
					}
					
					nav+=' <span id="info"class="c9"></span></nav>';
					if(strArr['1'] || pre>0){
						hreflist+=nav;
					}

					if(i==3){
						info.innerHTML='';
					}else{
						if(simi!=''){
							document.getElementById(simi).innerHTML='';
						}
						main.innerHTML=hreflist;
					}
					imgHover();
				}else{
					info.innerHTML=strArr['2'];
				}
				if(xhr.status==500 || xhr.status==502 ||xhr.status==503 ||xhr.status==504){
					info.innerHTML='下载失败!';
				}
			}
		}
		xhr.open("POST",_ajaxsearch);
		xhr.send(fData);
}

function imgHover(){
	$(".rowp li").hover(
	  function () {
		var obj=$(this).children("a").children("span");
		obj.addClass("bgspan");
	  },
	  function () {
		var obj=$(this).children("a").children("span");
		obj.removeClass("bgspan");
	  }
	); 
}
function orient() { // 手机横屏竖屏判断
    if (window.orientation == 0 || window.orientation == 180) { // 竖屏
        return 1;
    }
    else if (window.orientation == 90 || window.orientation == -90) { //横屏
        return 2;
    }else{// 未定义
		return 0;
	}
}



// 获取当前网页名称
function pageName(){				
	var page = window.location.pathname;
	var host=window.location.host;
	var lastindex=page.indexOf('/')+1;
	//var lastindex=page.lastIndexOf('/')+1;
	page=page.substring(lastindex);

	if(page=='index.php' || page=='sitesub.php' || page=='bbs.php'){
		return 'bbs';
	}
	if(page=='')page='index.html';
	return page;
}
function fixpics(){ // 修复图片的最大高度值
	var tn=false;if($('ul').is('.rowp'))tn=true;
	upimgh=$(".uplist:last").innerWidth()-10;
	if(tn)rowph=($(".rowp li:first").innerWidth()-8)*1.4+18;
	if(phone){//表示触摸屏
		if(tn)rowph+=2;
		upimgh+=2;	
	}
	if(tn){
		$(".rowp").css("max-height",rowph+"px");
		$(".show:first").css("max-height","none");
	}
	$(".uploadImg").css("height",upimgh+"px");
//	$(".add_imgBox").css("height",upimgh+"px");
	$(".file_progress").css("max-width",(upimgh-2)+"px");	
}


function trigger_resizable(){
	var width = window.innerWidth;
	if(width>330){		
		var search=$('#ti-search');
		var display=search.css('display');
		if(display=='none'){
			search.css('display','inherit');
		}
	}

	var	screen_label = get_current_breakpoint();
	var tn=public_vars.$sidebarMenu.hasClass('collapsed');
	if(screen_label=='devicescreen'){
		if(tn)$('#sidebar').removeClass('collapsed');
	}else{
		if(localStorage.getItem('cate')!=null){
			screen_label=localStorage.getItem('cate');
		}
		if(screen_label=='tabletscreen'){
			if(!tn){
				$('.has-sub.expanded > ul').attr("style","");
				if(_this!='undefined' && _this.hasClass('expanded')){
					_this.removeClass('expanded');
				}
				public_vars.$sidebarMenu.addClass('collapsed');
			}
		}else if(screen_label=='largescreen'){
			if(tn)public_vars.$sidebarMenu.removeClass('collapsed');
		}
	}
	fixpics();

}
// js  解密后台php base64_encode
function base64_decode(data) {
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    dec = "",
    tmp_arr = [];
    if (!data) {
        return data;
    }
    data += '';
    do { // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));
        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;
        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;
        if (h3 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1);
        } else if (h4 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1, o2);
        } else {
            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
        }
    } while (i < data.length);
    dec = tmp_arr.join('');
    return dec;
}
// 打开网址页面
/*
----------------------------------
*/
function getHrefKey(url)
{
	return md5(url.split(':')[1]).substr(12,8);
}
function heartHref(url,opt=null){// 收藏网址
	if(opt!=null){
		url=base64_decode(url);
	}
	if(url==0 || url=='' || url==null)return;
	var urlkey = getHrefKey(url); // 浏览记录
	var key1 = "1"+urlkey;
	var key2 = "2"+urlkey;
	var key3 = "3"+urlkey;
	var key4 = "4"+urlkey;
	var key5 = "5"+urlkey;
	key1 = localStorage.getItem(key1);
	key2 = localStorage.getItem(key2);
	key3 = localStorage.getItem(key3);
	key4 = localStorage.getItem(key4);
	key5 = localStorage.getItem(key5);
	urlkey=key1||key2||key3||key4||key5;
	if(urlkey!=null){
		forbidButton('#heart',' 已收藏');
		return;
	}else if(opt!=null){
		openHref(url,2,opt); // 收藏网址
		forbidButton('#heart',' 已收藏');
	}
}
function openHref(enurl,obj,opt){
	url=base64_decode(enurl);
	if(opt>=0)var url=enurl;

	blank='_blank';
	if(opt==undefined){
		opt=0;
	}else if(opt==-1){
		obj=obj.previousSibling.firstChild;
		opt=0;
	}else{
		url=_gohref || url;
	}

	if(obj==undefined)blank='_self';
	if(obj!=2){
		window.open(url, blank);
	}

	var max=getMaxLocalNum();	
	var key=html='';
	if(obj==0 || obj==2){ // href 页面的点击
		key = obj+getHrefKey(url); // 浏览记录
		var htitle=$('#htitle').text();
		var ico=$('#ico').attr('src');
		var img='';
		if(ico!=undefined){
			img='<img src="'+ico+'">';
		};
		var strarr = opt.split("#");
		var inner=img+strarr[2];
		if(strarr[1]>0){ //is strong
			inner=img+'<strong>'+strarr[2]+'</strong>';
		}
		var color='';
		if(strarr[0]>0){ //is color
			color='c'+strarr[0];
		}
		html=max+'@'+url+'@'+obj+'@'+htitle+'@'+color+'@'+inner;	
	//	alert(html)
	}else if(opt==0){ // 浏览记录
		key = opt+getHrefKey(url);
		html=max+'@'+url+'@'+opt+'@'+obj.getAttribute("title")+'@'+obj.getAttribute('class')+'@'+obj.innerHTML.trim();
	}else{
		return;
	}
	localStorage.setItem(key,html);
	localStorage.setItem('max', ++max); // 记录存储数量的标记最大值
	return false;
}

// 加载自定义网址
function loadLocalSites(cate=1){
	var cateStr = ['','','','','',''];
	var tn=0,
		nums=_num; // index.html页面记录显示的最大记录数量
	var array=new Array();
	if(_pagename=='home.html'){// home.html 页面
		tn=1;nums=_maxnum;
	}else if(_pagename=='edit.html'){// edit.html页面
		tn=2;nums=_maxnum;
	}
	var len=length=localStorage.length;
	for(var i=0; i<len; i++){
		var key=localStorage.key(i);
		if(key=='max' || key=='search' || key=='uname'){
			--length;
			continue;
		}
		var html=localStorage.getItem(key);
		if (html != null && key!=null){ //items存在
			var strArr=html.split('@');
			array[strArr[0]]=[strArr[1],strArr[2],strArr[3],strArr[4],strArr[5],key];
			if(strArr[1]==undefined || strArr[1]==''){
				--length;
			}
		}
	}
//	var step=array.length>>1; // 除2
	for(var i=array.length-1;i>-1;i--){
		if(array[i]==undefined || (!tn && array[i][1]!=cate ))continue;
		//alert(array[i][1]);
		var temp3='';
		if(array[i][3]!='null'){// 颜色
			temp3=' class="'+array[i][3]+'"';
		}
		var openHref='openHref';
		//var data=' data-toggle="tooltip" data-placement="bottom" data-original-title="'+array[i][2]+'"';
		data=' title="'+array[i][2]+'"';
		if(tn==2){
			openHref='editHref';
		}
		//else if(tn==0){
		//	data=' title="'+array[i][2]+'"';
		//}
		sites = '<li onclick="'+openHref+'(\''+array[i][0]+'\',this,'+array[i][1]+')"'+data+'><span'+temp3+'>'+array[i][4] +'</span></li>';
		
		if(array[i][1]==0){ // 记录数
			if(--nums<0)continue;
			cateStr[array[i][1]]+=sites;
		}else{
			cateStr[array[i][1]]+=sites;
		}
	}
	if(tn){ 
		for(var i=0,len=cateStr.length;i<len;i++){
			if(cateStr[i]=='')continue;
			document.getElementById('records'+i).innerHTML=cateStr[i];
		}
	}else{ // index 首页
		var records=document.getElementById('records');
		//records.innerHTML='';
		if(records==null)return;
		if(cateStr[cate]==''){
			records.innerHTML='<li title="没有相应数据"><span class="c8">【暂无数据】</span></li>';
		}else{
			records.innerHTML=cateStr[cate];
		}
		localStorage.getItem('max');
		if(length<1){ // 如果本地没有网址，从云端下载
			get_userself_hrefs(_name);
		};
		//document.getElementById('records0').innerHTML=cateStr['0'];
	}
}

// 通过用户名获取云端网址
function get_userself_hrefs(dwname,tn='records',all=0){
	localStorage.clear();
	//var _ajaxurl='https://127.0.0.1/ajax.php';
	var fData = new FormData();
	fData.append("uname",dwname);
	fData.append("all",all);
	var xhr = new XMLHttpRequest();
	var records=document.getElementById(tn);
	xhr.onreadystatechange=function(){
		records.innerHTML='<li><img src="assets/images/loading.gif"></li>';
		if(xhr.readyState == 4 && xhr.status==200){
			var strArr = JSON.parse(xhr.responseText);
			//alert(strArr['1']);
			if(strArr['0']){
				var max = getMaxLocalNum();
				var temp3=sites='';
				for(var i=strArr.length-1;i>2;i--){
					var arr=strArr[i].split('@');
					var key=arr[1]+getHrefKey(arr[0]);
					if(localStorage.getItem(key)!=null){ // 本地存在则跳过
						continue;
					}
					temp3='';
					if(arr[3]!='null'){// 字体颜色
						temp3=' class="'+arr[3]+'"';
					}
					localStorage.setItem(key,max+'@'+strArr[i]);
					++max;

					if(arr[1]==1 && tn=='records'){
						sites = '<li><span'+temp3+' onclick="editHref(\''+arr[0]+'\',this,'+arr[1]+')" title="'+arr[2]+'">'+arr[4] +'</span></li>'+sites;
					}
				}
				localStorage.setItem('max',max);
				localStorage.setItem('uname',dwname);
				if(tn=='records'){
					records.innerHTML=sites;
				}else{
					window.location.href="home.html";
				}
			}else{
				records.innerHTML=strArr['2'];
			}
		}
		if(xhr.status==500 || xhr.status==502 ||xhr.status==503 ||xhr.status==504){
			records.innerHTML='下载失败!';
		}
	}
	xhr.open("POST",_ajaxurl);
	xhr.send(fData);
	if(tn=='info'){
		$('#downsite').modal('hide');
	}
}
// 获取本地存储最大数据值（虚拟值）
function getMaxLocalNum(){
	var max = localStorage.getItem('max'),min=0,nums=9999;
	if(max==null || max>nums){
		if(max>nums){
			//localStorage.clear(); // 一个循环的终结
			max=0;
			var array=new Array();
			for(var i=0,len=localStorage.length; i<len; i++){// 重新分配生成索引
				var key=localStorage.key(i);
				if(key=='max' || key=='search' || key=='cate')continue;	
				
				var html=localStorage.getItem(key);
				if (html != null && key!=null){ //items存在
					var strArr=html.split('@');
						array[strArr[0]]=[strArr[0],html,key];
				}
			}
			array.forEach(function(arr){ 
				localStorage.setItem(arr[2],arr[1].replace(arr[0]+'@',min+'@'));
				++max;
				++min;
			});
			localStorage.setItem('max', max); 
		}else{
			max=0;
			localStorage.setItem('max', max); 
		}
	}
	return max;
}
// Get current breakpoint cate
function get_current_breakpoint()
{ //使用window.innerWidth 解决与@media screen 宽度不一致问题
	var width = window.innerWidth,
		breakpoints = public_vars.breakpoints;
	if(width>330){		
		var search=$('#ti-search');
		var display=search.css('display');
		if(display=='none'){
			search.css('display','inherit');
		}
	}

	for(var breakpont_label in breakpoints)
	{
		var bp_arr = breakpoints[breakpont_label],
			min = bp_arr[0],
			max = bp_arr[1];

		if(max == -1)max = width;
		if(min <= width && max >= width)
		{
			return breakpont_label;
		}
	}

	return null;
}


function setup_sidebar_menu()
{		

	if(public_vars.$sidebarMenu.length)
	{
		var $items_with_subs = public_vars.$sidebarMenu.find('li:has(> ul)'),
			toggle_others = public_vars.$sidebarMenu.hasClass('toggle-others');

		$items_with_subs.filter('.active').addClass('expanded');

		// On larger screens collapse sidebar when the window is tablet screen
		var	screen_label = get_current_breakpoint();
		if(localStorage.getItem('cate')!=null && screen_label!='devicescreen'){
			screen_label=localStorage.getItem('cate');
		}
		var tn=public_vars.$sidebarMenu.hasClass('collapsed');
		if(screen_label=='tabletscreen' && !tn){
			public_vars.$sidebarMenu.addClass('collapsed');
			$('.has-sub.expanded > ul').attr("style","");
			$('.has-sub.expanded').removeClass("has-sub expanded");
		}else if(screen_label=='largescreen' && tn){
			public_vars.$sidebarMenu.removeClass('collapsed');
		}

		$items_with_subs.each(function(i, el)
		{
			var $li = jQuery(el),
				$a = $li.children('a'),
				$sub = $li.children('ul');

			$li.addClass('has-sub');

			$a.on('click', function(ev)
			{
				if(public_vars.$sidebarMenu.hasClass('collapsed')){
					return;
				}
				ev.preventDefault();

				if(toggle_others)
				{
					sidebar_menu_close_items_siblings($li);
				}

				if($li.hasClass('expanded')){
					sidebar_menu_item_collapse($li, $sub);
				}else{
					sidebar_menu_item_expand($li, $sub);
				
				}
			});
		});
	}
	fixpics();
}

// 左侧导航展开
function sidebar_menu_item_expand($li, $sub)
{
	if($li.data('is-busy') || ($("#ti-side").is(':visible') && $li.parent('#menu').length && public_vars.$sidebarMenu.hasClass('collapsed')))
	{
		return;
	}
	$li.addClass('expanded').data('is-busy', true);
	$sub.show();
	$li.data('is-busy', false);
}

// 左侧导航关闭
function sidebar_menu_item_collapse($li, $sub)
{
	if($li.data('is-busy')){
		return;
	}
	var $sub_items = $sub.children();

	$li.removeClass('expanded').data('is-busy', true);

	$sub.attr('style', '').hide();

	$li.data('is-busy', false);
}

function sidebar_menu_close_items_siblings($li)
{
	$li.siblings().not($li).filter('.expanded').each(function(i, el)
	{
		var $_li = jQuery(el),
			$_sub = $_li.children('ul');

		sidebar_menu_item_collapse($_li, $_sub);
	});
}


// Element Attribute Helper
function attrDefault($el, data_var, default_val)
{
	if(typeof $el.data(data_var) != 'undefined')
	{
		return $el.data(data_var);
	}

	return default_val;
}

//var _catobj=_catstr=null;
// 显示被稳藏的网址
function moreHref(rowid,_this,cate=-1){
	var tn=_this;
	var curobj=$('#'+rowid);
	var open=$('.open:first');
	var show=$('.show:first');
	if(cate>-1){
		var left=cate;
		switch(left){
			case 2:
				left=40;
				break;
			case 3:
				left=130;
				break;
			case 4:
			case 5:
				left=230;
				break;
			default:
				left=0;
		}
		$('.ti-scroll').prop("scrollLeft",left);
		if(cate!=1){
			$('#records').css('border-top-left-radius','10px');
		}else{
			$('#records').css('border-top-left-radius','0');
		}
		loadLocalSites(cate);
	}
	if(_this==true){ //   从侧栏点击过来
		if(rowid=='records'||rowid=='records1'){
			_this=$('.tablink:first');
			if(!_this.length){
				_this=open;
			} 
		}else{
			_this=curobj.parent().prev().children("a:first-child");
		}
	}
	//alert(open.is(_this));
	var rowh=0;
	if(curobj.hasClass('rowp')){
		rowh=rowph;
	}
	var showp=0;
	if(show.hasClass('rowp')){
		showp=rowph;
	}
	if(open.is(_this)){
		if(curobj.hasClass('show')){
			curobj.removeClass('show');
			$(_this).removeClass('open');
			if(rowid=='records' || rowid=='records1'){
				$(_this).addClass('tablink');
			}
			if(rowh)curobj.css("max-height",rowh+"px");
			fixpics();
		}else{
			curobj.addClass('show');
			$(_this).addClass('open');
			if(rowh)curobj.css("max-height",rowh+"px");
		}
		
	}else{
		open.removeClass('open');
		show.removeClass('show');
		if(rowid!='records' || (rowid!='records1' && tn==true)){ 
			open.addClass('tablink');
		}else{
			var tablink=$('.tablink:first');
			tablink.removeClass('tablink');
		}
		$(_this).addClass('open');
		curobj.addClass('show');
		if(rowh){
			curobj.css("max-height","none");
		}
		if(showp){
			show.css("max-height",showp+"px");
		}
	}
	if(tn!=true){
		event.preventDefault();
		$("html, body").animate({
			scrollTop: curobj.offset().top - 107
		}, {
			duration: 150,
			easing: "swing"
		});
	}
	return false;
}
// 显示搜索模块
function showsoso(tn){
	var search=$('#ti-search');
	if(tn==1){
		var display=search.css('display');
		if(display=='none'){
			search.css('display','inherit');
		}else{
			search.css('display','none');
		}
	}
}
function loadRarImges(encode,_this){
	var _ajaxurl='downpic.php';
	var self=$(_this);
	self.html('  获取中…');
				$(_this).attr("disabled",true);
				self.removeClass('btn-primary');
				self.addClass('btn-gray');
	//alert(start);
	$.ajax({
		//请求方式
		type : "POST",
		//请求的媒体类型
		data: {'encode':encode},
		//crossDomain: true,
		xhrFields:{ // 设置跨域使用的参数
			withCredentials:true
		},
		timeout : 12000, //超时时间设置，单位毫秒
		async:true,
		//请求地址
		url : _ajaxurl,
		//数据，json字符串
	   // data : JSON.stringify(list),
		//请求成功
		success : function(data) {
		   var strArr = JSON.parse(data);
		//	alert(strArr);
			if(strArr['0']){
				$(_this).html('  成功获取');
				var urlcode='';
				if(strArr['2']!=null){
					urlcode='<a href="'+strArr['2']+'"target="_blank">[网盘地址一]</a> 提取码: <b>'+strArr['3']+'</b>';
				}
				if(strArr['4']!=null){
					urlcode+='<br><a href="'+strArr['4']+'"target="_blank">[网盘地址二]</a> 提取码: <b>'+strArr['5']+'</b>';
				}
				if(strArr['1']!=''){
					urlcode+='<br><i class="c5">'+strArr['1']+'</i>';
				}
				$('#downurl').html(urlcode);
				var pdown=$('#pdown');
					pdown.html(parseInt(pdown.html())+1);
			}else{
				$(_this).html(strArr['3']);
				$('#msg').html(strArr['2']);
				$('#warning').modal('show');
			}
		},
	　　complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
	　　　　if(status=='timeout'){//超时,status还有success,error等值的情况
				$(_this).attr("disabled",false);
				self.removeClass('btn-gray');
				self.addClass('btn-primary');
	　　　　}
	　　}
	});
}
function loadMoreImges(pindex,pshow,year){
	var _ajaxurl='ajax_search.php';
	/*
	var fData = new FormData();
	fData.append("pindex",pindex);
	fData.append("pshow",pshow);
	fData.append("year",year);
	var xhr = new XMLHttpRequest();
	var records=document.getElementById('loadmore');
	xhr.onreadystatechange=function(){
		records.innerHTML=' 加载更多美人照片...';
		if(xhr.readyState == 4 && xhr.status==200){
			var strArr = JSON.parse(xhr.responseText);
			if(strArr['0']){
				$("#imges").append(strArr['2']);
				if('over'==strArr['1']){
				}
			}else{
				records.innerHTML=strArr['2'];
			}
		}
		if(xhr.status==500 || xhr.status==502 ||xhr.status==503 ||xhr.status==504){
			records.innerHTML='下载失败!';
		}
	}
	xhr.open("POST",_ajaxurl);
	xhr.send(fData);	*/
	var loadmore=$('#loadmore');
	forbidButton('#loadmore',' 正在加载美人照片...');
	var start=loadmore.attr('value');
	//alert(start);
	$.ajax({
		//请求方式
		type : "POST",
		//请求的媒体类型
		data: {'pindex':pindex,'pshow':pshow,'year':year,'start':start},
		//crossDomain: true,
		xhrFields:{ // 设置跨域使用的参数
			withCredentials:true
		},
		timeout: 12000, //（毫秒） 
		async:true,
		//请求地址
		url : _ajaxurl,
		//数据，json字符串
	   // data : JSON.stringify(list),
		//请求成功
		success : function(data) {
			//alert(data);
		   var strArr = JSON.parse(data);
			//alert(data);
			if(strArr['0']){
				$("#imges").append(strArr['2']);
				if('over'==strArr['1']){
					forbidButton('#loadmore',' 全部加载完成!');
				}else{
					loadmore.attr('value',strArr['1']);
				//	loadmore.html(' 加载更多美人照片');
					forbidButton('#loadmore',' 加载更多美人照片',1);
				}
			}else{
				document.getElementById('err').innerHTML=strArr['1'];
			}
		},
	　　complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
	　　　　if(status=='timeout'){//超时,status还有success,error等值的情况
	 　　　　　forbidButton('#loadmore',' 加载更多美人照片',1);
	　　　　}
	　　}
	});
}
// 增加点击数量
function addHrefView(hid,hindex='',vip=0){// tn=1 则是用户在报错误信息 opt=1 表示正常访问
	var opt=1;
	if(hindex!='' && hindex!='0' && hindex !='3'){// 链接索引不为空表示用户在报错 2表示来源博客
		if(hindex>2000000){ // 1表示来源美女套图报错
			//var statearr = ["网盘地址无效", "正常", "提取码无效"];
			//var state=document.getElementById('state').innerHTML;
			opt=$("input[name='err']:checked").val();
			forbidButton('#report',' 已报错');
		}else{
			var statearr = ["死链", "正常", "异常", "改版"];
			var state=document.getElementById('state').innerHTML;
			opt=$("input[name='err']:checked").val();
			if(state!=statearr[opt]){// 所选是否和原状态一致
				forbidButton('#report',' 已报错');
			}else{
				return;
			}
		}
	}else{
		var i=Math.floor(Math.random()*400); // 0-399之间的随机数字
		if(i<360){
			i=360;
			window.scrollBy(100, i); 
		}
	}
	$.ajax({
		//请求方式
		type : "POST",
		//请求的媒体类型
		data: {'hid':hid,'hindex':hindex,'opt':opt,'vip':vip},
		//crossDomain: true,
		xhrFields:{ // 设置跨域使用的参数
			withCredentials:true
		},
		timeout: 12000,//（毫秒） 
		async:true,
		//请求地址
		url : _ajaxsearch,
		//数据，json字符串
	   // data : JSON.stringify(list),
		//请求成功
		success : function(data) {
		   var strArr = JSON.parse(data);
			if(strArr['0']){
				if(strArr['2']){ // 后台已经被提交过一次了
					forbidButton('#report',' 已报错');
				}
				if(hindex==0 || hindex==''){ // 增加view量
					var statearr=['死链','正常','异常','改版','跳转', "已赞助", "已快审", "可赞助引流", "301重定向"];
					var colorarr=['9','5','10','2','1','7','6','8','3'];
					$('#view').html(strArr['3']);
					$('#preview').html(strArr['4']);
					$('#nowview').html(strArr['5']);
					_gohref=strArr['6'];
					var stateobj=$('#state');
					if(strArr['1']==8){
						window.location.href = _gohref;
						return;
					}else{
						var state=statearr[strArr['1']];
						var color='c'+colorarr[strArr['1']];
						if(strArr['1']==0||strArr['1']==7)state='<a href="57392782.html"><span class="'+color+'">'+state+' &raquo;</span></a>';
						stateobj.html(state);
						stateobj.removeClass().addClass(color);
						if(strArr['1']==0){
							$('.deline').addClass('de');
						}else{
							$('.deline').removeClass('de');
						}
						heartHref(_gohref);
					}
				}else if(hindex==3){ //套图
					$('#view').html(strArr['3']);
					$('#pdown').html(strArr['6']);
					var down= $('#down');
					var downarr=[' 会员登录',' 首次获取',' 重复获取',' 过期获取',' 会员升级'];
					down.html(downarr[strArr['4']]);
					if(strArr['4']>0){
						$('#vip').html(strArr['5']);
						//if(strArr['4']==4)forbidButton('#down');
					}
				}
			}else{
				$('err').html(strArr['1']);
			}
		},
	　　complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
	　　　　if(status=='timeout'){//超时,status还有success,error等值的情况
	 　　　　　 forbidButton('#down',' 重新获取',1);
	　　　　}
	　　}
	});

	$.ajax({
		//请求方式
		type : "POST",
		//请求的媒体类型
		data: {'hid':hid},
		//crossDomain: true,
		xhrFields:{ // 设置跨域使用的参数
			withCredentials:true
		},
		timeout: 12000,//（毫秒） 
		async:true,
		//请求地址
		url : _ajaxstate,
		//数据，json字符串
	   // data : JSON.stringify(list),
		//请求成功
		success : function(data) {
			//alert(data);
		},
	　　complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
	　　}
	});
}

function forbidButton(name,txt='',tn=0){// 让按钮变灰色不可用或恢复
	obj=$(name);
	if(tn){
		obj.attr("disabled",false);
		obj.removeClass('btn-gray');
		obj.addClass('btn-primary');
	}else{// disabled
		obj.attr("disabled",true);
		obj.removeClass('btn-primary');
		obj.addClass('btn-gray');
	}
	if(txt!='')obj.html(txt);
}