// JavaScript Document
$(function(){
	$(document).ready(function(){
		
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// add code - input area size control
		var _inputAreaSize = $(".inputArea input").css("width");
		var _inputAreaSize_selected = "200px";
		// _______________________________________________________		
		
		
		
		//TOP : welcome block
		if($("body").hasClass("index")){
			wlOpen(4);
//			var testH = document.documentElement.clientHeight;
//			alert(testH);
		}
		
		//browser setting
		//mac os
	 	if (navigator.appVersion.indexOf("Mac") !=-1){
			$('#header .searchArea').css({'padding':'1px 4px 4px 4px'});
		}
		
		//blankBlock(nonText) height Setting
		setInterval(function(){
			$(".nonText .section").height($(".block .section:first").height());
		},100);
		
		//checkBox Setting
		$(".navContents input:checkbox").uniform();
		$(".sortSelect select").uniform();
		
		//pageNav rotate animation
		$(".pageMenu").append('<div class="menuBG" />');		
		$(".pageMenu ul li").hover(function(){
			$(this).stop(true,true).stop(true,true).animate({"top": "+=10px", rotate: '+=20deg', scale: '1'}, 300);
		},function(){
			$(this).stop(true,true).stop(true,true).animate({"top": "-=10px", rotate: '-=20deg', scale: '1'}, 300);
		})

		//search area
		var srcStr = $(".inputArea input").val(); // "Free Word" ****************************************
		$(".inputArea input").focus(function() {
	        $(this).animate({
				width: _inputAreaSize_selected // "160px" ****************************************
			},300);
			$(this).attr("size",50);
			$(".inputArea").addClass("here"); // $(".searchArea").addClass('here'); ****************************************
	        if($(this).val() == srcStr){
	            $(this).val('');
			}
	    }).blur(function() {
	        $(this).animate({
				width: _inputAreaSize // "120px ****************************************
			},300);
			$(this).attr("size",100);
			$(".inputArea").removeClass("here"); // $(".searchArea").removeClass('here'); ****************************************
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
		$(".block").hover(function(){
			$(this).find(".logoTag").css("right","-15px");
			//$(this).find(".section").addClass("secHover");
		},function(){
			$(this).find(".logoTag").css("right","-12px");
			//$(this).find(".section").removeClass("secHover");
		})

		//wrapping block "a"
		$(".blockInner").each(function(){
			urlStr = $(this).find(".articleImage a").attr("href");
			$(this).find(".articleImage a").addClass("alpha");
			$(this).wrap("<a class='alpha' href='"+urlStr+"' />");
			
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
//				alert($(target1).css("height"));
			}
			
			firstView(i,cNum);

			
			$("#welcomeWrapper").height(winH).width(winW);
			
			var resizeTimer = null;
			$(window).bind('resize', function() {
				if (resizeTimer) clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function(){
					firstView(2);
				},10);
			});
			
		}
		
		$("#welcomeWrapper, .wlClose, .wlButton input").click(function(){
			wlClose();
		})
		
		$(".setButton").click(function(){
			wlOpen(2,1);
		})
		
		
//blog page function 
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
		
		
		//image size setting
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
		
		//Max OS hack
		if (navigator.appVersion.indexOf("Mac") !=-1){
			$('.shopDetail h5 span').addClass("macSpan");
			$('.shopDetail .shopMoreLink').addClass("macMoreLink");
		}
		
   });

});
