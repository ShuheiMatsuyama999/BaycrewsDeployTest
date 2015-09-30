/*
2012.05.10
ページ下部のdisco機能
*/

$(function(){
	/* ---------------------------------------------------------------------------------
		各種設定
	*/
	var _sp = 300;//各animateのspeed
	var linum = $("#result_search ul.list_article li").length;

	/* ---------------------------------------------------------------------------------
		open close 処理
	*/
	$('li.bt_toggle a').toggle(
		function() {
			$(this).addClass("active");
			$(this).text('open');
			$('#result_search').animate({ "bottom" : "-120px" },_sp);
		},function() {
			$(this).removeClass("active");
			$(this).text('close');
			$('#result_search').animate({ "bottom" : "0" },_sp);
		}
	);
	
	/* ---------------------------------------------------------------------------------
		画像のロールオーバー処理
	*/
	$("#result_search ul.list_article li").each(function(i){
		$(this).hover(function(){
			$(this).find("a>img").parent("a").addClass("over");
			$(this).addClass("listhover");
		},function(){
			$(this).find("a>img").parent("a").removeClass("over");
			$(this).removeClass("listhover");
		})
	})
	

	/* ---------------------------------------------------------------------------------
		article処理
	*/
//	var liNum = $('ul.list_article > li').length;
	var liNum = discodataNum;// get total number of data
	var posx = 55;//初期値
	var articleWidth = $('ul.list_article > li').width();
//	var movex = articleWidth;
	var width = 0;
	var moveValue = 0;
	var articleMargin = 10;
	var articleWidthMargin = articleWidth + articleMargin;
//	var articleCardLeftNo = 0;	
	var articleCardAfter = liNum;
	var box = 0;
	var numRight = 0;
	var numLeft = 0;
	var flagRight = true;
	var flagLeft = true;
	var articleMaxWidth = (liNum * articleWidthMargin) + 55;
	var limitWidth = articleMaxWidth;
	var lastWidth = 0;
	var extantWidth = 0;
	var flagClick = true;
	var flagClickAjax = true;
	var flagHide = false;	

	//show current border
	setTimeout(function(){
		//$("span.current_bg").show();
		$("span.current_bg").fadeIn(800);
	},2000);
	
	moveValueSetFunc();//画面サイズをセット
	
	//calculating limited width
	function limitFunc(){
		articleMaxWidth = ($('ul.list_article > li').length * articleWidthMargin) + 55 ;
		lastWidth = articleWidthMargin * (moveValue+1);
		extantWidth = width - lastWidth - (55*2);
		limitWidth = articleMaxWidth - width + extantWidth + articleWidthMargin;
	}

	// ajax function
	function ajaxFunc(vsid, vnum, vaction){
		flagClickAjax = false;
		$.ajax({
			type : 'GET',
			url : '/disco_rest.php',
			data: { sid: vsid, num: vnum, action: vaction },
			dataType : 'html',
			success : function(data) {
				if(vaction === "next"){
					$("ul.list_article").append(data);
				}else if(vaction === "back"){
					$("ul.list_article").prepend(data);
				}
				flagClickAjax = true;	
				setTimeout(function(){				
					$("span.img_loading").hide();
					$(".loading_disco").removeClass("loading_disco");
				},1000);
			}
		});
	}

	// first view
	function articleFirstViewPos(){
		articleCardAfter -= articleNo;
		if(articleCardAfter < moveValue){
			articleCardAfter = moveValue;
		}
		if(articleCardAfter > 0){
			if(articleNo <= 90){
				posx -= articleWidthMargin * articleNo;
			}else if(articleNo > 90 && articleNo < (liNum - moveValue)){
				posx -= articleWidthMargin * (articleNo - eLeft);			
			}
			if(articleNo >= (liNum - moveValue)){
				limitFunc();
				posx = -limitWidth;
				$('li.next').hide();
			}	
		}			
		$('ul.list_article').css("margin-left",posx );
	}
	
	
	// the first time call function
	if(articleNo > 0){
		articleFirstViewPos();
	}else{
		$('li.back').hide();
	}
	
	//左右のボタンの処理
	$('li.next a img').click(function () { 
		if(flagClick === true && flagClickAjax === true){
	//		$('li.next a img').bind("click", articleMoveFunc('next'));
			articleMoveFunc('next')
		}
	});
	$('li.back a img').click(function () { 
		if(flagClick === true && flagClickAjax === true){
		//	$('li.back a img').bind("click", articleMoveFunc('back'));	
			articleMoveFunc('back');
		}
	});

	function articleMoveFunc(val){
		flagClick = false;
		if(val === "next"){
			articleCardAfter -= moveValue;
			if(flagRight === true){
				numRight = eRight;
			}		
			if(eRight < liNum && articleCardAfter <= (liNum-numRight+moveValue)){									
				ajaxFunc(sid, numRight, 'next');
				numRight += 20;
				flagRight = false;	
				if(articleCardAfter >=  moveValue){
					flagHide = true;
				}
			}			
			$('li.back').show();

			posx -= articleWidthMargin * moveValue;
			if(articleCardAfter < moveValue*2){	
				limitFunc();
			}	
			if(posx < (-limitWidth)){
				posx = -limitWidth;
			} 			
			if(articleCardAfter === 0 || (articleCardAfter <= moveValue && flagHide === true && posx == -limitWidth) || (eRight === liNum && articleCardAfter <= moveValue)){
				$('li.next').hide();
			}					
			if(articleCardAfter < moveValue ){
				articleCardAfter = moveValue;
			}		
			$('ul.list_article').animate({ "margin-left" : posx+'px' },_sp, function(){ setTimeout(function(){flagClick = true;},_sp) });
		}else if(val === "back"){
			articleCardAfter += moveValue;
			if(flagLeft === true){
				numLeft = eLeft;
			}
			if(eLeft > 1 && articleCardAfter >= (liNum-numLeft-(moveValue*1))){		
				ajaxFunc(sid, numLeft, 'back');
				numLeft -= 20;	
				flagLeft = false;				
				if(numLeft >= 0){	
					posx += (articleWidthMargin * moveValue) - (articleWidthMargin * 20);
				}else{
					posx += (articleWidthMargin * moveValue) - (articleWidthMargin * (numLeft+20));
				}
				limitFunc();
			}else{
				posx += articleWidthMargin * moveValue;
			}
			if(articleCardAfter > moveValue){
				$('li.next').show();
			}
			if( articleCardAfter >= liNum){
				posx = 55;
				articleCardAfter = liNum;
				$('li.back').hide();
			}
			$('ul.list_article').animate({ "margin-left" : posx+'px' },_sp, function(){ setTimeout(function(){flagClick = true;},_sp) });
		}
	}

	//windowサイズに応じてスクロールする数を変更
	//window幅を取得
	$(window).resize(function(){
		moveValueSetFunc();
	//	limitFunc();
	});
	
	function moveValueSetFunc(){
		width = $(window).width();
		moveValue = width/(articleWidthMargin+55);
		if(moveValue < 3){
			moveValue = 1;
		}else{
			moveValue = Math.round(moveValue);
		}
		return false;
	}


	//カードのリンク先を取得
	$("ul.list_article>li").each(function(){
		$(this).hover(function(){
			//$(this).find("a>img").parent("a").addClass("over");
			$(this).addClass("bIover");
		},function(){
			//$(this).find("a>img").parent("a").removeClass("over");
			$(this).removeClass("bIover");
		});
		$(this).click(function() {
			//if( $(".aLover").size() == 0 ){
				var anchorTags = $(this).find("a>img").parent("a").attr("href");
				window.open(anchorTags, "_top");
			//}
		});
	});

});