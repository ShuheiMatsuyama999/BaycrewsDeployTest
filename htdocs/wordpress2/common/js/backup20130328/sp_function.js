// JavaScript Document
$(function(){
	$(document).ready(function(){
		
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// add code - input area size control
		var _inputAreaSize = $(".inputArea input").css("width");
		var _inputAreaSize_selected = "200px";
		// _______________________________________________________		
		
		$('p.setup').click(function(){
			
			$('img.welcomeMsg').attr('src','/images/index/setting_midashi.gif');
			$('img.welcomeMsg').attr('width','600');
			$('img.welcomeMsg').attr('height','21');
			$('img.welcomeMsg').attr('alt','興味のある記事とお住まいの地域をお選びください。あなたに最適な情報をご提案します。');
			
			$('img.welcomeRead').attr('src','/images/index/setting_read.gif');
			$('img.welcomeRead').attr('width','492');
			$('img.welcomeRead').attr('height','13');
			$('img.welcomeRead').attr('alt','設定変更');
			
			$('input.settingBtn').attr('src','/images/index/btn_setting_on.jpg');
			$('input.settingBtn').attr('onmouseover','');
			$('input.settingBtn').attr('onmouseout','');
			
			//onmouseover="this.src='images/index/btn_join_on.jpg'
			
			
			wlOpen(4);
		});
		
		
		
		//TOP : welcome block
		if($("body").hasClass("index")){
			if($.cookie('lifecruise') == "," || $.cookie('lifecruise') == null ){
				//cookie無し
				//alert($.cookie('lifecruise'))
				//wlOpen(4);
			}else{
			//	alert("cookieあり///"+$.cookie('lifecruise'))
			}
		}
		
		//browser setting
		//mac os
	 	if (navigator.appVersion.indexOf("Mac") !=-1){
			$('#header .searchArea').css({'padding':'1px 4px 4px 4px'});
		}
		
		//blankBlock(nonText) height Setting
		$(".nonText .section").height($(".block .section:first").height());
		setInterval(function(){
			$(".nonText .section").height($(".block .section:first").height());
		},100);
		
		//checkBox Setting
		$(".navContents input:checkbox").uniform();
		$(".sortSelect select").uniform();
		
		//pageNav rotate animation
		$(".pageMenu").append('<div class="menuBG" />');		
		$(".pageMenu ul li").hover(function(){
			$(this).stop(true,true).stop(true,true).animate({"top": "+=10px", scale: '1'}, 300);
		},function(){
			$(this).stop(true,true).stop(true,true).animate({"top": "-=10px",  scale: '1'}, 300);
		})
			
		$("ul.circle_banner li").hover(function(){
			$(this).stop(true,true).stop(true,true).animate({"top": "+=10px", scale: '1'}, 300);
		},function(){
			$(this).stop(true,true).stop(true,true).animate({"top": "-=10px",  scale: '1'}, 300);
		})

		//search area
		var srcStr = "";
		$(".inputArea input").focus(function() {
			$(this).val('');
			$(".inputArea input")
			
	        $(this).animate({
				width:"160px"
			},300);
			$(this).attr("size",50);
			$(".searchArea").addClass('here');
	        if($(this).val() == srcStr){
	            $(this).val('');
			}
	    }).blur(function() {
	        $(this).animate({
				width:"120px"
			},300);
			$(this).attr("size",100);
			$(".searchArea").removeClass('here');
	        if(jQuery.trim($(this).val()) == "") {
	            $(this).val(srcStr);
	        }
	    });
		
		//gnav Menu
 		$(".gNav li, .navContents").hover(function(){
			$(this).find(".navContents").stop(true,true).delay(200).fadeIn();
		},function(){
			$(this).find(".navContents").stop(true,true).delay(200).fadeOut("fast");
		})
		
		//brand tag
		$(".logoTag").append('<div class="tagBG" />');
		$(".logoTag").each(function(){
			var tagH = $(this).children("img").addClass("tagImg").height();
			var tagPT = tagH - (tagH*2);
			$(this).find(".tagBG").css("height",tagH).css("top",tagPT);
		})
		
		//brand tag mouseon
/* 		$(".block").hover(function(){
			$(this).find(".logoTag").css("right","-15px");
			//$(this).find(".section").addClass("secHover");
		},function(){
			$(this).find(".logoTag").css("right","-12px");
			//$(this).find(".section").removeClass("secHover");
		}) */

		//wrapping block "a"
/* 		$(".blockInner").each(function(){
			urlStr = $(this).find(".articleImage a").attr("href");
			$(this).find(".articleImage a").addClass("alpha");
			$(this).wrap("<a class='alpha articleMoreLink' href='"+urlStr+"' />");
		}) */

		//wrapping block articleImage "a>img" alpha
 		$(".blockInner").each(function(){
			$(this).hover(function(){
				$(this).find(".articleImage>a>img").parent("a").addClass("over");
			},function(){
				$(this).find(".articleImage>a>img").parent("a").removeClass("over");
			})
		})
		
		//welcome block open
		function wlClose(){
			$("#welcomeWrapper, .wlContainer").fadeOut();
			$("html").css("overflow","auto");
		}
		
		//welcomeBlock Close
		function wlOpen(i,cNum){
			
			$("#welcomeWrapper, .wlContainer").fadeIn();
			$("html").css("overflow","hidden");
	
			target1 = ".welcome";
			
			winH = document.documentElement.clientHeight;
			winW = document.documentElement.clientWidth;
			
			function firstView(topNum,clickNum){

				var mainHeight = $(target1).height();
				
				winH = document.documentElement.clientHeight;
				winW = document.documentElement.clientWidth;

				$("#welcomeWrapper").height(winH).width(winW);
				var mNum = (winH-mainHeight)/2;
				$(target1).css("margin-top",mNum);
			}
			
			firstView(i,cNum);

			
			$("#welcomeWrapper").height(winH).width(winW);
			
			var resizeTimer = null;
			$(window).bind('resize', function() {
				if (resizeTimer) clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function(){
					firstView(2);0
				},10);
			});
			
		}
		
		$("#welcomeWrapper, .wlClose, .wlButton input").click(function(){
			wlClose();
		})
		
		$(".setButton").click(function(){
			wlOpen(2,1);
		})
		
// list page only setting
//-------------------------------------------------------------- //
		var herePage = $("body").hasClass("listPage");
		if(herePage){
			$("a.articleMoreLink").hover(function(){
				$(this).find("dl>dt, dl>dd>p").css("color","#666666");
			},function(){
				$(this).find("dl>dt, dl>dd>p").css("color","#383332");
			})
		}


		

// blog page function 
//-------------------------------------------------------------- //

		//form select (srot) setting
		var sortWrapper = $(".sortSelect").width();
		var optionW = $(".selector").width();
		var pW = (sortWrapper-optionW)-15;
		$(".sortSelect p").css("width",pW);
		
		//sideNav adordion
		$("#shopList h3").click(function(){
			$(this).find("img").toggle();
//			$("#shopList ul").toggleClass("close");
			$("#shopList ul").animate({
				height:"toggle"
			},100);
			$(this).toggleClass("ttlLink");
		})

		//sideNav list setting
		$(".navWrapper").each(function(){
			$(this).find("li:last").css("padding-bottom",0).css("background","none");
			
			//a(blank) setting
			$(this).find("ul li").each(function(){
				var side_a_status = $(this).find("a").attr("target");
				if(side_a_status == "_blank"){
					$(this).addClass("blank");
				}
			})
			
		})

		//main article text a(blank) setting
		$(".articleInner p a").each(function(){
			var aStatus = $(this).attr("target");
			if(aStatus == "_blank"){
				$(this).addClass("blank");
			}
		})
		$(".articleInner .linkList li a").each(function(){
			var linkStatus = $(this).attr("target");
			if(linkStatus == "_blank"){
				$(this).parent("li").addClass("blank");
			}
		})
		
		
		//blog image size setting
		var indexPage = $("body").hasClass("index");
		var listPage = $("body").hasClass("listPage")
		if(!indexPage && !listPage){
			$(".articleImage img").each(function(){
				var setSize = 440;
				if($(this).attr("height")){
					$(this).removeAttr("height");
				}
				var articleImgSize = $(this).width();
				if(articleImgSize >=setSize){
					$(this).css("width",setSize);
				}
				else if ((articleImgSize % 2) != 0) {
					var setSize = articleImgSize-1;
					$(this).css("width",setSize);
				}
				$(this).parent("div").css("width",setSize);
			})
		}
		
		//Max OS hack
		if (navigator.appVersion.indexOf("Mac") !=-1){
			$('.shopDetail h5 span').addClass("macSpan");
			$('.shopDetail .shopMoreLink').addClass("macMoreLink");
		}
		
		
		//画像のセンター
		$('img.alignnone').parent().parent().each(function(){ $(this).css('text-align','center'); });
		$('img.aligncenter').parent().parent().each(function(){ $(this).css('text-align','center'); });
		
		
		
		//画像リサイズ
	    /*
	    $('img').each(function(){
	        var max = 0;
	        if (navigator.userAgent.indexOf('iPhone') > 0) {
	            max = 280; // iPhoneの最大幅
	        }} else if (navigator.userAgent.indexOf('Android') > 0) {
	            max = 280; // Androidの最大幅
	        }
	        var w = $(this).width();
	        var h = $(this).height();
	        if (max != 0 && w > max) {
	            $(this).width(max).height(Math.round((max/w)*h));
	        }
	    });*/
   });

});
