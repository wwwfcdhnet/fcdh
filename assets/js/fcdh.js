/*!
 * Bootstrap v3.3.1 (http://getbootstrap.com)
 * Copyright 2011-2014 Twitter,
 Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */
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
			}(jQuery),

	+function(a){"use strict";
function b(b){return this.each(function(){var d=a(this),
e=d.data("bs.tooltip"),
f="object"==typeof b&&b,
g=f&&f.selector;
(e||"destroy"!=b)&&(g?(e||d.data("bs.tooltip",
e={}),
e[g]||(e[g]=new c(this,
f))):e||d.data("bs.tooltip",
e=new c(this,
f)),
"string"==typeof b&&e[b]())})}var c=function(a,
b){this.type=this.options=this.enabled=this.timeout=this.hoverState=this.$element=null,
this.init("tooltip",
a,
b)};
c.VERSION="3.3.1",
c.TRANSITION_DURATION=150,
c.DEFAULTS={animation:!0,
placement:"top",
selector:!1,
template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
trigger:"hover focus",
title:"",
delay:0,
html:!1,
container:!1,
viewport:{selector:"body",
padding:0}},
c.prototype.init=function(b,
c,
d){this.enabled=!0,
this.type=b,
this.$element=a(c),
this.options=this.getOptions(d),
this.$viewport=this.options.viewport&&a(this.options.viewport.selector||this.options.viewport);
for(var e=this.options.trigger.split(" "),
f=e.length;
f--;
){var g=e[f];
if("click"==g)this.$element.on("click."+this.type,
this.options.selector,
a.proxy(this.toggle,
this));
else if("manual"!=g){var h="hover"==g?"mouseenter":"focusin",
i="hover"==g?"mouseleave":"focusout";
this.$element.on(h+"."+this.type,
this.options.selector,
a.proxy(this.enter,
this)),
this.$element.on(i+"."+this.type,
this.options.selector,
a.proxy(this.leave,
this))}}this.options.selector?this._options=a.extend({},
this.options,
{trigger:"manual",
selector:""}):this.fixTitle()},
c.prototype.getDefaults=function(){return c.DEFAULTS},
c.prototype.getOptions=function(b){return b=a.extend({},
this.getDefaults(),
this.$element.data(),
b),
b.delay&&"number"==typeof b.delay&&(b.delay={show:b.delay,
hide:b.delay}),
b},
c.prototype.getDelegateOptions=function(){var b={},
c=this.getDefaults();
return this._options&&a.each(this._options,
function(a,
d){c[a]!=d&&(b[a]=d)}),
b},
c.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("bs."+this.type);
return c&&c.$tip&&c.$tip.is(":visible")?void(c.hoverState="in"):(c||(c=new this.constructor(b.currentTarget,
this.getDelegateOptions()),
a(b.currentTarget).data("bs."+this.type,
c)),
clearTimeout(c.timeout),
c.hoverState="in",
c.options.delay&&c.options.delay.show?void(c.timeout=setTimeout(function(){"in"==c.hoverState&&c.show()},
c.options.delay.show)):c.show())},
c.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("bs."+this.type);
return c||(c=new this.constructor(b.currentTarget,
this.getDelegateOptions()),
a(b.currentTarget).data("bs."+this.type,
c)),
clearTimeout(c.timeout),
c.hoverState="out",
c.options.delay&&c.options.delay.hide?void(c.timeout=setTimeout(function(){"out"==c.hoverState&&c.hide()},
c.options.delay.hide)):c.hide()},
c.prototype.show=function(){var b=a.Event("show.bs."+this.type);
if(this.hasContent()&&this.enabled){this.$element.trigger(b);
var d=a.contains(this.$element[0].ownerDocument.documentElement,
this.$element[0]);
if(b.isDefaultPrevented()||!d)return;
var e=this,
f=this.tip(),
g=this.getUID(this.type);
this.setContent(),
f.attr("id",
g),
this.$element.attr("aria-describedby",
g),
this.options.animation&&f.addClass("fade");
var h="function"==typeof this.options.placement?this.options.placement.call(this,
f[0],
this.$element[0]):this.options.placement,
i=/\s?auto?\s?/i,
j=i.test(h);
j&&(h=h.replace(i,
"")||"top"),
f.detach().css({top:0,
left:0,
display:"block"}).addClass(h).data("bs."+this.type,
this),
this.options.container?f.appendTo(this.options.container):f.insertAfter(this.$element);
var k=this.getPosition(),
l=f[0].offsetWidth,
m=f[0].offsetHeight;
if(j){var n=h,
o=this.options.container?a(this.options.container):this.$element.parent(),
p=this.getPosition(o);
h="bottom"==h&&k.bottom+m>p.bottom?"top":"top"==h&&k.top-m<p.top?"bottom":"right"==h&&k.right+l>p.width?"left":"left"==h&&k.left-l<p.left?"right":h,
f.removeClass(n).addClass(h)}var q=this.getCalculatedOffset(h,
k,
l,
m);
this.applyPlacement(q,
h);
var r=function(){var a=e.hoverState;
e.$element.trigger("shown.bs."+e.type),
e.hoverState=null,
"out"==a&&e.leave(e)};
a.support.transition&&this.$tip.hasClass("fade")?f.one("bsTransitionEnd",
r).emulateTransitionEnd(c.TRANSITION_DURATION):r()}},
c.prototype.applyPlacement=function(b,
c){var d=this.tip(),
e=d[0].offsetWidth,
f=d[0].offsetHeight,
g=parseInt(d.css("margin-top"),
10),
h=parseInt(d.css("margin-left"),
10);
isNaN(g)&&(g=0),
isNaN(h)&&(h=0),
b.top=b.top+g,
b.left=b.left+h,
a.offset.setOffset(d[0],
a.extend({using:function(a){d.css({top:Math.round(a.top),
left:Math.round(a.left)})}},
b),
0),
d.addClass("in");
var i=d[0].offsetWidth,
j=d[0].offsetHeight;
"top"==c&&j!=f&&(b.top=b.top+f-j);
var k=this.getViewportAdjustedDelta(c,
b,
i,
j);
k.left?b.left+=k.left:b.top+=k.top;
var l=/top|bottom/.test(c),
m=l?2*k.left-e+i:2*k.top-f+j,
n=l?"offsetWidth":"offsetHeight";
d.offset(b),
this.replaceArrow(m,
d[0][n],
l)},
c.prototype.replaceArrow=function(a,
b,
c){this.arrow().css(c?"left":"top",
50*(1-a/b)+"%").css(c?"top":"left",
"")},
c.prototype.setContent=function(){var a=this.tip(),
b=this.getTitle();
a.find(".tooltip-inner")[this.options.html?"html":"text"](b),
a.removeClass("fade in top bottom left right")},
c.prototype.hide=function(b){function d(){"in"!=e.hoverState&&f.detach(),
e.$element.removeAttr("aria-describedby").trigger("hidden.bs."+e.type),
b&&b()}var e=this,
f=this.tip(),
g=a.Event("hide.bs."+this.type);
return this.$element.trigger(g),
g.isDefaultPrevented()?void 0:(f.removeClass("in"),
a.support.transition&&this.$tip.hasClass("fade")?f.one("bsTransitionEnd",
d).emulateTransitionEnd(c.TRANSITION_DURATION):d(),
this.hoverState=null,
this)},
c.prototype.fixTitle=function(){var a=this.$element;
(a.attr("title")||"string"!=typeof a.attr("data-original-title"))&&a.attr("data-original-title",
a.attr("title")||"").attr("title",
"")},
c.prototype.hasContent=function(){return this.getTitle()},
c.prototype.getPosition=function(b){b=b||this.$element;
var c=b[0],
d="BODY"==c.tagName,
e=c.getBoundingClientRect();
null==e.width&&(e=a.extend({},
e,
{width:e.right-e.left,
height:e.bottom-e.top}));
var f=d?{top:0,
left:0}:b.offset(),
g={scroll:d?document.documentElement.scrollTop||document.body.scrollTop:b.scrollTop()},
h=d?{width:a(window).width(),
height:a(window).height()}:null;
return a.extend({},
e,
g,
h,
f)},
c.prototype.getCalculatedOffset=function(a,
b,
c,
d){return"bottom"==a?{top:b.top+b.height,
left:b.left+b.width/2-c/2}:"top"==a?{top:b.top-d,
left:b.left+b.width/2-c/2}:"left"==a?{top:b.top+b.height/2-d/2,
left:b.left-c}:{top:b.top+b.height/2-d/2,
left:b.left+b.width}},
c.prototype.getViewportAdjustedDelta=function(a,
b,
c,
d){var e={top:0,
left:0};
if(!this.$viewport)return e;
var f=this.options.viewport&&this.options.viewport.padding||0,
g=this.getPosition(this.$viewport);
if(/right|left/.test(a)){var h=b.top-f-g.scroll,
i=b.top+f-g.scroll+d;
h<g.top?e.top=g.top-h:i>g.top+g.height&&(e.top=g.top+g.height-i)}else{var j=b.left-f,
k=b.left+f+c;
j<g.left?e.left=g.left-j:k>g.width&&(e.left=g.left+g.width-k)}return e},
c.prototype.getTitle=function(){var a,
b=this.$element,
c=this.options;
return a=b.attr("data-original-title")||("function"==typeof c.title?c.title.call(b[0]):c.title)},
c.prototype.getUID=function(a){do a+=~~(1e6*Math.random());
while(document.getElementById(a));
return a},
c.prototype.tip=function(){return this.$tip=this.$tip||a(this.options.template)},
c.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},
c.prototype.enable=function(){this.enabled=!0},
c.prototype.disable=function(){this.enabled=!1},
c.prototype.toggleEnabled=function(){this.enabled=!this.enabled},
c.prototype.toggle=function(b){var c=this;
b&&(c=a(b.currentTarget).data("bs."+this.type),
c||(c=new this.constructor(b.currentTarget,
this.getDelegateOptions()),
a(b.currentTarget).data("bs."+this.type,
c))),
c.tip().hasClass("in")?c.leave(c):c.enter(c)},
c.prototype.destroy=function(){var a=this;
clearTimeout(this.timeout),
this.hide(function(){a.$element.off("."+a.type).removeData("bs."+a.type)})};
var d=a.fn.tooltip;
a.fn.tooltip=b,
a.fn.tooltip.Constructor=c,
a.fn.tooltip.noConflict=function(){return a.fn.tooltip=d,
this}}(jQuery);


var _pagename=null;
var _thisSearch='https://www.baidu.com/s?word='; // 默认搜索引擎
var now = -1;
var resLength = 0;
var _this='undefined',  // 当前左侧导航打开或关闭的节点对象
	_num=5,			// index.html页面记录和收藏各显示的最大记录数量
	_maxnum=10,		// records,edit.html 页面记录和收藏各显示的最大记录数量 超过自动删除剩余的
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
		url: 'https://www.google.com/search',
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
	}]
}

$(document).ready(function()
{
	// Main Vars
	public_vars.$body			= $("body");
	public_vars.$pageContainer	= $("#container");
	public_vars.$sidebarMenu	= $('#sidebar');
	public_vars.$tiMeng			= $('#ti-meng');	


	setup_sidebar_menu(); // 初	始化左边菜单	

	_pagename=pageName(); //获取当前页面名称

	if(_pagename=='index.html' || _pagename=='home.html' || _pagename=='edit.html'){
		loadLocalSites(); // 加载本地网址
	}
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
			$('.has-sub.expanded > ul').show()
		}
	});

	// 手机顶部按钮打开和关闭
	$('#ti-menu,#ti-meng').on('click', function(ev)
	{
		ev.preventDefault();
		public_vars.$sidebarMenu.toggleClass('mobile');
		public_vars.$tiMeng.toggleClass('ti-meng');
	});

	public_vars.lastBreakpoint=get_current_breakpoint(); // 获取当前屏幕大小devicescreen

		///鼠标悬浮提醒
	$('[data-toggle="tooltip"]').each(function(i, el)
	{
		var $this = $(el),
			placement = attrDefault($this, 'placement', 'top'),
			trigger = attrDefault($this, 'trigger', 'hover'),
			tooltip_class = $this.get(0).className.match(/(tooltip-[a-z0-9]+)/i);

		$this.tooltip({
			placement: placement,
			trigger: trigger
		});

		if(tooltip_class)
		{
			$this.removeClass(tooltip_class[1]);

			$this.on('show.bs.tooltip', function(ev)
			{
				setTimeout(function()
				{
					var $tooltip = $this.next();
					$tooltip.addClass(tooltip_class[1]);

				}, 0);
			});
		}
	});

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
			var ulh=parseInt(ulblock.css('height')); // 子栏目的高度
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

		var rowid=$(this).attr("href").substr(1);
		moreHref(rowid,true);

		$("#menu li").each(function() {
			$(this).removeClass("active");
		});
		$(this).parent("li").addClass("active");
		public_vars.$sidebarMenu.toggleClass('mobile');
		public_vars.$tiMeng.toggleClass('ti-meng');		
		public_vars.$tiMeng.toggleClass('searchs');
		$("html, body").animate({
			scrollTop: $($(this).attr("href")).offset().top - 95
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


	// 按键松开时执行
	$('#txt').keyup(function (e) {
		if (e.keyCode == 38 || e.keyCode == 40 || e.keyCode == 37) {
			return;
		}
		var word=$('#txt').val();
		var dat = {
			wd: word
		}
		var box=$('#box');
		if (word != '') {
			$.ajax({
				type: "GET",
				url: "https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su",
			//  url: "http://suggestion.baidu.com/su",
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
							$('#txt').val(soword);
							window.open(_thisSearch + soword);
							box.html('');
							box.css('display', 'none');
						});
						$('#box li i').eq(i).click(function () {
							var obj=$(this).prev();
							$('#txt').val(obj.html());
							box.html('');
							box.css('display', 'none')
							$('#ti-search input').focus();
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
			//box.html() === '' ? box.css('height','0px') : box.css('height','auto');
		}
	});

	$('#txt').keydown(function (ev) {
		if (ev.keyCode == 40) {
			now++;
			if (now > resLength - 1) {
				now = 0;
			}
			$('#box li').eq(now).addClass('bg2').siblings().removeClass('bg2');
			$('#txt').val($('#box li').eq(now).text());
		}

		if (ev.keyCode == 38) {
			ev.preventDefault();
			if (now == -1 || now == 0) {
				now = resLength;
			}
			now--;
			$('#box li').eq(now).addClass('bg2').siblings().removeClass('bg2');
			$('#txt').val($('#box li').eq(now).text());
		}
		
		if (ev.keyCode == 13) {
			var textValue = $('#txt').val();
			if (textValue != '') {
				window.open(_thisSearch + textValue)
				// $('#'#txt'').val('');
				$('#box').html('');			
				$('#box').css('display', 'none');
			} else {
				new $.zui.Messager('请输入关键字', {
					icon: 'bell', // 定义消息图标
					type: 'danger',
					placement: 'top',
					close: false
				}).show();
			}
		}
	});


	mySearch(localStorage.getItem('search'));

	for (var i = 0; i < _search.data.length; i++) {
		var addList = '<li class="'+i+'"><img src="' + _search.data[i].img + '"/></li>'
		$('#ti-engine').append(addList);
	}


	$("#ti-icon").click(function() {
		$('.'+$(this).attr("alt")).prependTo("#ti-engine");
		$("#ti-engine").attr('style','display:block');
	});

	$('#ti-engine li').click(function () {
		var _index = $(this).attr("class");
		_thisSearch = _search.data[_index].url+'?'+_search.data[_index].word+'=';
		localStorage.setItem('search',_index);
		var imge = _search.data[_index].img;
		var wd = _search.data[_index].word;
		var title=_search.data[_index].name;
		$('#ti-icon').attr('src', imge);
		$('#ti-icon').attr('alt', _index);
		$('#ti-search form').attr('action',_thisSearch);
		var soinput=$('#ti-search input');
		soinput.attr('name',wd);		
		soinput.focus();
		$('#ti-search button').html(title);

	});

	$('#txt').focus(function(){
		$("#ti-engine").attr('style','display:none');
	});

	$('#ti-search form').mousedown(function(ev){
		ev.stopPropagation();
	});

	$("body").mousedown(function(){
		$("#box").attr('style','display:none');//setTimeout('displaynone()',250);	
	});

	// Enable/Disable Resizable Event
	$(window).resize(function() {
		setTimeout('trigger_resizable()',100);
	});
	return false;

});

function mySearch(_index){// 加载最近使用的搜索
	if(_index==null)return;
    _thisSearch = _search.data[_index].url+'?'+_search.data[_index].word+'=';
	var imge = _search.data[_index].img;
	var wd = _search.data[_index].word;
	var title=_search.data[_index].name;
	$('#ti-icon').attr('src', imge);
	$('#ti-icon').attr('alt', _index);
	$('#ti-search form').attr('action',_thisSearch);
	var soinput=$('#ti-search input');
	soinput.attr('name',wd);		
	//soinput.focus();
	$('#ti-search button').html(title);
}
// 获取当前网页名称
function pageName()
 {
	 var strUrl=location.href;
	 var arrUrl=strUrl.split("/");
	 var strPage=arrUrl[3].split("#");
	 if(arrUrl[2]=='bbs.fcdh.net')return 'bbs';
	 else if(strPage=='')strPage='index.html';
	 return strPage;
 }


function trigger_resizable(){
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

}
// 打开网址页面
/*
----------------------------------
*/
function getHrefKey(url)
{
	return md5(url).substr(12,8);
}
function openHref(url,obj,opt){
	if(opt==undefined)opt=0;
	window.open(url, '_blank');
	key = opt+getHrefKey(url);
	if(null==localStorage.getItem(key)){ // 如果记录有，则不增加
		var max=getMaxLocalNum();
		var html=max+'@'+url+'@'+opt+'@'+obj.getAttribute("data-original-title")+'@'+obj.getAttribute('class')+'@'+obj.innerHTML.trim();
		localStorage.setItem(key,html);
		localStorage.setItem('max', ++max); // 记录存储数量的标记最大值
	}
	return false;
}


// 加载本地网址0 1 2 3 4 5
var cateArr = [5,2,3,4,0,1,'记 录','常 用','爱 好','专 业','工 具','其 他'];
var cateStr = ['','','','','',''];

function loadLocalSites(){
	var tn=0,
		nums=_num; // index.html页面记录显示的最大记录数量
	var array=new Array();
	if(_pagename=='home.html'){// home.html 页面
		tn=1;nums=_maxnum;
	}else if(_pagename=='edit.html'){// edit.html页面
		tn=2;nums=_maxnum;
	}

	for(var i=0,len=localStorage.length; i<len; i++){
		var key=localStorage.key(i);
		if(key=='max' || key=='search' || key=='cate')continue;
		
		
		var html=localStorage.getItem(key);
		if (html != null && key!=null){ //items存在
			var strArr=html.split('@');
				array[strArr[0]]=[strArr[1],strArr[2],strArr[3],strArr[4],strArr[5],key];
		}
	}
//	var step=array.length>>1; // 除2
	for(var i=array.length-1;i>-1;i--){
		if(array[i]==undefined ||(!tn && array[i][1]>1 ))continue;
		var temp3='';
		if(array[i][3]!='null'){// 颜色
			temp3=' class="'+array[i][3]+'"';
		}
		var openHref='openHref';
		var data=' data-toggle="tooltip" data-placement="bottom" data-original-title="'+array[i][2]+'"';
		if(tn==2){
			openHref='editHref';
			data=' title="'+array[i][2]+'"';
		}

		sites = '<li'+temp3+' onclick="'+openHref+'(\''+array[i][0]+'\',this,'+array[i][1]+')"'+data+'>'+array[i][4] +'</li>';
	
		if(array[i][1]==0){
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
	}else{
		document.getElementById('records').innerHTML=cateStr['1']+cateStr['0'];
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
/* Functions */

// Get current breakpoint cate
function get_current_breakpoint()
{ //使用window.innerWidth 解决与@media screen 宽度不一致问题
	var width = window.innerWidth,
		breakpoints = public_vars.breakpoints;
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

var _catobj=null;
// 显示被稳藏的网址
function moreHref(rowid,tn){
	if(tn==undefined)tn=false;
	var curobj=$('#'+rowid);
	var prev=curobj.prev();
	var obj=prev.children('span:first');

	// 清除默认的结构
	if(_catobj==null){
		obj2=$('.open:first');
		if(!obj2.is(obj)){
			obj2.removeClass('open');
			obj2.prev().removeClass('c0');
			$('.show:first').removeClass('show');
		}
	}else if(!curobj.is(_catobj)){
		_catobj.removeClass('show');
		var prev2=_catobj.prev();
		prev2.children('a:first').removeClass('c0');
		prev2.children('span:first').removeClass('open');
	}
	_catobj=curobj;

	if(obj.hasClass('open')){
		if(tn){
			return false;
		}
		obj.removeClass('open');
		curobj.removeClass('show');
		prev.children('a:first').removeClass('c0');
	}else{
		obj.addClass('open');
		curobj.addClass('show');
		prev.children('a:first').addClass('c0');
	}
}