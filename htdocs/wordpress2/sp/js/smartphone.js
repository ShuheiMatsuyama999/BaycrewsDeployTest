$(function(){
	setting();
	//setTimeout(doScroll, 100);
	if($("div").is(".more_btn")){
		//console.log("on")
		moreFunc();
	}

	$("div.btn_close").bind("click", function(){
		$("div.pc").remove();
		return false;
	});

	if($("div").is(".ui-select")){
		weatherSearch();
	}

});

function weatherSearch(){
	var prefselect = $("select#pref").parent().parent();
	var tempselect = $('select#temperature').parent().parent();
	var styleselect = $('select#style').parent().parent();
	prefselect.after( $('#nowloc') );

	$('#nowloc').after( '<div style="margin-top: 12px; margin-bottom: 8px; line-height:0;"><img src="/sp/images/common/bg_weather_icon.gif" width="18" height="18" alt="✘"></div>' );
	tempselect.after( '<div style="margin-top: 12px; margin-bottom: 8px; line-height:0;"><img src="/sp/images/common/bg_weather_icon.gif" width="18" height="18" alt="✘"></div>' );
	styleselect.after( '<div style="margin-top: 8px; margin-bottom: 8px; line-height:0;"><img src="/sp/images/common/bg_weather_icon_iq.gif" width="18" height="18" alt="||"></div>' );
}

function setting(){
	tilt();

	var agent = navigator.userAgent;
	if(agent.search(/iPhone/) != -1){
		$("body").addClass("iphone");
		window.onorientationchange = tilt;
	}else if(agent.search(/Android/) != -1){
		$("body").addClass("android");
		window.onresize = tilt;
	}
}

function tilt(){
	var orientation = window.orientation;
	if(orientation == 0){
		$("body").addClass("portrait");
		$("body").removeClass("landscape");
	}else{
		$("body").addClass("landscape");
		$("body").removeClass("portrait");
	}
}

function doScroll() {
	if (window.pageYOffset === 0) { window.scrollTo(0,1); }
}

/* 追加 moreをクリックで新しい記事を読み込み、リサイズ */
var moreFunc = function(){
	var thisPage = "";
	var loadFlag = false;
	var articleBaseNum = 0;
	
	var loaderFunc = function(){
		var urlVal = $("ul.alpha li:first div.more_btn a:first").attr("href");
		//if(location.search === "?mode=list"){
		if(0 < $("ul.list").size()){
			$(".alpha").before("<ul class='list clear'></ul>");
			$("ul.list:last").load(urlVal);
		}else if(0 < $("ul.gallery").size()){
			$(".alpha").before("<ul class='gallery'></ul>");
			$(".gallery:last").load(urlVal);
		}
		$("ul.alpha li:first").remove();
		$("ul.alpha li:first").css({
			display:"block"
		});
		loadFlag =false;
	}

	$('.more_btn').each(function(){
		$(this).find("a:first").bind("click", function(){
			if(!loadFlag){
				loadFlag = true;
				$("ul.alpha li:first div.more_btn a:first img:first").attr("src", "/images/ajax-loader.gif");
				loaderFunc();
			}
			return false;
		})
		$(this).find("p:first").bind("click", function(){
			if(!loadFlag){
				loadFlag = true;
				$("ul.alpha li:first div.more_btn a:first img:first").attr("src", "/images/ajax-loader.gif");
				loaderFunc();
			}
			return false;
		})
	})
}