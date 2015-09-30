/*
2012.05.10
ページ下部のdisco機能
*/

$(function(){
	/* ---------------------------------------------------------------------------------
		各種設定
	*/
	var _sp = 300;//各animateのspeed
	var linum = $("#result_search ul.list_article li").size();

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
	var liNum = $('ul.list_article > li').size();
	var posx = 55;//初期値
	var articleWidth = $('ul.list_article > li').width();
	var movex = articleWidth;
	var width = $(window).width();
	var moveValue = width/articleWidth;
	var articleMargin = 10;
	var articleMaxWidth = (linum*(articleWidth+articleMargin))+55;
	var articleCardLeftNo = 0;
	var articleCardAfter = linum;
	var box = 0;
	moveValueSetFunc();//画面サイズをセット

	if(articleNo > 0) articleFirstViewPos();
	
	//左右のボタンの処理
	$('li.next a img').click(function () { $('li.next a img').bind("click", articleMoveFunc('next')) });
	function articleMoveFunc(val){
		if(val === "next"){
			articleCardAfter -= moveValue;
			if( articleCardAfter > 0 ){
				posx -= ( (articleWidth * moveValue)+(articleMargin * moveValue) );
				box = articleCardAfter;
			}else{
				articleCardAfter = box;
				$('li.next a img').unbind("click", articleMoveFunc('next'));
			}
			$('ul.list_article').animate({ "margin-left" : posx+'px' },_sp);
		}else{
		}
	}

	function articleFirstViewPos(){
		articleCardAfter -= articleNo;
		if( articleCardAfter > 0 ){
			posx -= ( (articleWidth * articleNo)+(articleMargin * articleNo) );
			box = articleCardAfter;
		}else{
			articleCardAfter = box;
			$('li.next a img').unbind("click", articleMoveFunc('next'));
		}
		$('ul.list_article').css("margin-left",posx );
	}

	$('li.back a img').click(function(){
		articleCardAfter += moveValue;
		if( articleCardAfter >= linum ){
			posx = 55;
			articleCardAfter = linum;
		}else if(posx < 55){
			posx += ( (articleWidth * moveValue)+(articleMargin * moveValue) );
		}
		$('ul.list_article').animate({ "margin-left" : posx+'px' },_sp);
	});
	

	//windowサイズに応じてスクロールする数を変更
	//window幅を取得
	$(window).resize(function(){
		moveValueSetFunc();
	});
	
	function moveValueSetFunc(){
		width = $(window).width();
		moveValue = width/(articleWidth+articleMargin+55);
		if(moveValue < 3){
			moveValue = 1;
		}else{
			moveValue = Math.floor(moveValue);
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