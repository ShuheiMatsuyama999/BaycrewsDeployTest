/*aoki 追加*********************************************/

var tmpWidth;
function getTmpWidth(){
	return tmpWidth;
}
function getObj(){
	return this;
}
function resizeStartGlobal(obj, width) {
		var _self = obj;		
		rObj = new ResizeController();
		rObj.resizeColumnSize(width, "animate");
}
/*aoki*********************************************/


var Illust;
var mainContentsW;//hino
Illust = (function() {
  function Illust() {
    this.illustInputSize = 140;
    this.leftWidth = 165;
    this.subPadding = 10;
    this.leftPadding = 22;
    this.selectedIllust = [];
  }
  
  Illust.prototype.put = function(data) {
    var padding;
    if (data.count < this.pattern.length) {
/*       padding = Math.floor(data.height / 2) - 60;
      data.block.append("<div class=\"illustAfterRender\" style=\"display:none;position: absolute;left:" + (data.left + 10) + "px;top:" + (data.top + padding) + "px;z-index:-1;\">\n\                <img src=\"" + this.pattern[this.getNextIllustIndex()] + "\" />\n\                </div>");
 */    }
    return data.count + 1;
  };
  Illust.prototype.putOne = function(data) {
/*     var padding;
    padding = Math.floor(data.height / 2) - 60;
    data.block.append("<div class=\"illustAfterRender\" style=\"display:none;position: absolute;left:" + (data.left + 10) + "px;top:" + (data.top + padding) + "px;z-index:-1;\">\n\            <img src=\"" + this.pattern[this.getNextIllustIndex()] + "\" />\n\            </div>");
 */    return data.count + 1;
  };
  Illust.prototype.getNextIllustIndex = function() {
    var does, i, low, ok, rand, up, _ref;
    does = true;
    while (does) {
      low = 0;
      up = this.pattern.length - 1;
      rand = Math.floor((up - low + 1) * Math.random() + low);
      ok = true;
      for (i = 0, _ref = this.selectedIllust.length; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
        if (this.selectedIllust[i] === rand) {
          ok = false;
        }
      }
      if (ok) {
        this.selectedIllust.push(rand);
        does = false;
      }
    }
    return rand;
  };
  Illust.prototype.pint = function(string) {
    if (string) {
      string = string.replace("px", "");
    }
    return parseInt(string, 10);
  };
  return Illust;
})();

function ResizeController() {
    var _self = this;

    _self.header = 34;
    _self.heightPadding = 10;
    _self.bottomHeightPadding = 22;

    _self.pagePadding = 100;
    _self.subPadding = 0;
    _self.leftPadding = 0;
    _self.subWidth = 0;
    _self.leftWidth = 0;
    _self.block = 245;

    _self.minBlockSize = 4;

    _self.blockSpeed = 0;
    _self.speed = 0;
    _self.speedWrapp = 0;

    _self.windowSizeChanging = false;
    _self.targetWidth = 0;

    _self.firstResize = function(width) {
        var _self = this;
        _self.resizeColumnSize(width);
    }


/*aoki 追加*********************************************/

		_self.resize = function(width) {
			tmpWidth = width;
			resizeStart(this, width);
		}
		function resizeStart(obj, width) {
			var _self = obj;
			_self.resizeColumnSize(width, "animate");
			
			
			
		}

/*aoki*********************************************/


    _self.resizeColumnSize = function(width, type){
        var _self = this;

        if(_self.windowSizeChanging==false){
            _self.windowSizeChanging=true;

            var blockCnt = _self.getInnerColumnSize(width);
            var nextContainerWidth = _self.block * blockCnt + _self.leftWidth + _self.subWidth + _self.leftPadding + _self.subPadding -20 +"px";

            if(type=="quick"){
                $('#container').css(
                    new Object({"width":nextContainerWidth}));
                _self.resizeInnerColumns(blockCnt,"first");
                _self.windowSizeChanging = false;

            }else{
                $('#container').animate(
                    new Object({"width":nextContainerWidth}),_self.speed+_self.speedWrapp,function(){
                    _self.resizeInnerColumns(blockCnt,"other");
                    _self.windowSizeChanging = false;
                    setTimeout(putIllust,_self.blockSpeed);
                });
            }
        }
    }


    _self.getInnerColumnSize = function(width){
        var _self = this;

        var blockArea = (width-_self.pagePadding) - _self.subPadding - _self.leftPadding - _self.subWidth - _self.leftWidth;
        var blockCnt = Math.floor(blockArea / _self.block);
        if (blockCnt < _self.minBlockSize) {
            blockCnt = _self.minBlockSize;
        }

        return blockCnt;
    }

    _self.addPointsSortByHeight = function(points){
        var working;
        var rs = new Array();
        for(var i=0 ; i < points.length ; i++){
            if(points[i] == "unset"){
            }else{
                rs.push(points[i]);
            }
        }

        for(i=0 ; i < rs.length - 1 ; i++){
            for(var j=rs.length-1;j>i;j--){
                if(rs[j].height < rs[j-1].height){
                    working = rs[j];
                    rs[j] = rs[j-1];
                    rs[j-1] = working;
                }else if(rs[j].height == rs[j-1].height && rs[j].column < rs[j-1].column){
                    working = rs[j];
                    rs[j] = rs[j-1];
                    rs[j-1] = working;
                }
            }
        }


/* catName 幅可変 shimoyamada 追記 */

		//alert( ($("#contents").width()) - ($("#sub").width()+10));
		var mainW = $("#contents").width();
		var blockNum = Math.floor(mainW/245);
		//alert(blockNum);
		
/* 		var t1 = ".catName"
		$(t1).css("width",(245*blockNum) + 20*(blockNum-1));
		var ww = $(t1).width();
 */

 		$(".listPage .block .section").css("width",mainW-5);
		$(".moreLink .section").css("width",mainW-4);
		$(".smallblock .moreLink .section").css("width",mainW-5);
		$(".footerInner").css("width",mainW-5);
		
/* 追記 */

        return rs;
    }

    _self.getMaxHeightOfAddPoints = function(points){
        var max = 0;
        for(var i=0 ; i < points.length ; i++){
            if(points[i].height > max){
                max = points[i].height;
            }
        }

        return max;
    }
    _self.getMaxPoint = function(points){
        var max = 0;
        for(var i=0 ; i < points.length ; i++){
            if(points[i].height > max){
                max = i;
            }
        }

        return max;
    }

    _self.getMaxRightPoint = function(points){
        var max = _self.getMaxPoint(points);
        for(var i=0 ; i < points.length ; i++){
            if(points[i].column > points[max].column &&
                    points[i].height > 0){
//                if(points[i].height > points[max].height){
                    max = i;
//                }
            }
        }

        return max;
    }

    _self.resizeInnerColumns = function(size,type){
        var _self = this;
        var addPoints = new Array(new Object({column:1,height:0,size:2}));

        var mainBlock = $("#mainBlock");
        var blockOrder1 = mainBlock.find(".block.order1");
        var blockOrder2 = mainBlock.find(".block.order2");

        addPoints = _self.addPointsSortByHeight(addPoints);

        var card;
        var mySize;
        var working;
        var compAddPoint;
        var compColumn;
        var compHeight;

        var eachBlocks = mainBlock.find(".block").not(".order1,.order2,.pagenate");
mainContentsW = $("#contents").width();

        var l = eachBlocks.length;
        for(var i=0; i< l; i++){

            card = eachBlocks.get(i);
            mySize = _self.mySize(card);

            for(var pointIndex = 0 ; pointIndex < addPoints.length ; pointIndex++){

                myAddPoint=addPoints[pointIndex];
                myColumn = myAddPoint.column;
                myHeight = myAddPoint.height;

                for(var pointIndex2 = pointIndex ; pointIndex2 < addPoints.length ; pointIndex2++){
                    compAddPoint = addPoints[pointIndex2];
                    compColumn = compAddPoint.column;
                    compHeight = compAddPoint.height;

                    if( compColumn==myColumn+1 && compHeight > myHeight){

                        addPoints[pointIndex].size=1;
                        if(mySize >= 2){
                            working = addPoints[pointIndex];
                            working.height = compHeight;
                            working.size=2;

                            addPoints[pointIndex2] = working;
                            addPoints[pointIndex] = "unset";
                        }
                    }
                }
                if(addPoints[pointIndex]!="unset" && (myColumn - 1) + mySize <= size ){
					/* アーギュメントを追加 現在の記事数を送る */
                    addPoints = _self.moveElement(card, size, addPoints, pointIndex, i);
                    break;
                }
            }
            addPoints = _self.addPointsSortByHeight(addPoints);
        }

        var blockPagenate = mainBlock.find(".pagenate.block");

        var height = _self.getMaxHeightOfAddPoints(addPoints);
        if(height > $("#leftBlockArea").height()){
			$("#main").height(height);
		} else {
			$("#main").height($("#leftBlockArea").height());
		}

		/* 追加 現在の記事数を記録する */
		articleNum = l;
		
	}
	/* end resizeInnerColumns */


    _self.moveElement = function(card, size, addPoints, pointIndex, index){
        var _self = this;

        var myAddPoint=addPoints[pointIndex];
        var myColumn = myAddPoint.column;
        var myHeight = myAddPoint.height;
        var mySize = _self.mySize(card);
        
		var leftVal = ((myColumn-1)*_self.block);
		var previewVal = 0;
		

		if(leftVal < (mainContentsW/2)){
			previewVal = parseInt(leftVal + 6);
		}else {
			previewVal = leftVal - 6;
		}

/*
		if(leftVal < 480){
			previewVal = parseInt(leftVal + 30);
		}else {
			previewVal = leftVal - 30;
		}
*/
		
		if(index > articleNum - 2){
			//console.log("index:" + index + " articleNum:" + articleNum)
			//console.log(200 * (index - articleNum + 2))
			
			$(card).css({
				opacity:0,
				left:previewVal,
				top:parseInt(myHeight + 40)
			}).delay(200 * (index - articleNum + 2)).animate(new Object({
				"left":((myColumn-1)*_self.block)+"px",
				"top":myHeight+"px",
				"opacity":1
			},"easeOutQuint"),500, function(){
				//console.log("index:" + index + " articleNum:" + articleNum)
			});
		}else{
			$(card).css({
				opacity:1
			}).animate(new Object({
				"left":((myColumn-1)*_self.block)+"px",
				"top":myHeight+"px",
				"opacity":1
			},"easeOutQuint"),800, function(){
			});
		}


        var nextRight = new Object({
            column:myColumn+mySize,
            height:myHeight,
            size:2
        });

        var nextBottom = new Object({
            column : myColumn,
            height : myHeight + $(card).height() + _self.heightPadding,
            size:2
        });

        var nextHeight = myHeight + $(card).height() + _self.heightPadding;
        var nextColumn = myColumn + mySize -1;
        for(var pointIndex2 = 0 ; pointIndex2 < addPoints.length ; pointIndex2++){

            var compAddPoint = addPoints[pointIndex2];
            var compColumn = compAddPoint.column;
            var compHeight = compAddPoint.height;

            if((
                    pointIndex2 != pointIndex &&
                    compColumn >= myColumn &&
                    compColumn <= nextColumn

                )&& (( compHeight < myHeight) || (
                        compHeight >= myHeight &&
                        compHeight < nextHeight
                ))
            ){
                addPoints[pointIndex2] = "unset";
            }

            if( compColumn==nextRight.column && compHeight >= nextRight.height){
                nextRight = "unset";
            }

            if(compColumn == nextBottom.column && compHeight == nextBottom.height){
                nextBottom = "unset";
            }

        }

        if(nextRight.column > size){
            addPoints[pointIndex] = "unset";
            nextBottom.size=1;
        }else{
            addPoints[pointIndex] = nextRight;
        }
        addPoints.push(nextBottom);

        return addPoints;
    }


    _self.mySize = function(element){
        var _self = this;

        return Math.round($(element).width()/_self.block);
    }

}

/* 追加 blockの数を記録する */
var articleNum = 0;

var myResize = new ResizeController();
var oldWidth = 0;
var illust = new Illust();
var loadCounter = 0;


function resize(){
    var width = $('html').outerWidth();
    var _self = window;

    if(_self.oldWidth != width){
        _self.oldWidth = width;
        _self.myResize.resize(width);
	}
}

function putIllust(){
    var _self = window;
}

/* 追加 moreをクリックで新しい記事を読み込み、リサイズ */
var moreFunc = function(){
	var thisPage = "";
	var loadFlag = false;
	var articleBaseNum = 0;
	var $btns = $(".moreLink:first ul.alpha li");

	
	$('.moreLink:first ul.alpha li').click(function() {
		if(!loadFlag){
			loadFlag = true;
			$btns.eq(0).find(".blockInner:first a:first img:first").attr("src", "/images/ajax-loader.gif");
			
			var loaderFunc = function(){
				var urlVal = $btns.eq(0).find(".blockInner:first a:first").attr("href");
				$('.moreLink:first').before("<div id='wrapDiv'></div>")
				$("#wrapDiv").load(urlVal,"",function(){

					$('.moreLink:first').before($("#wrapDiv .block"));
					$("#wrapDiv").remove();

					if(parseInt($(".block").length) - 1 >= 100){
						$('.moreLink:first').remove();
					}
					
					var width = $('html').outerWidth();
					window.myResize.resize(width);

					$btns.eq(0).remove();
					$btns = $(".moreLink:first ul.alpha li");
					if($btns.length > 0){
						$btns.eq(0).css({
							display:"block"
						})
					}


                    $(".block>.logoTag").hover(function(){
                        $(this).parent().find(".logoTag").css("right","-14px");
                    },function(){
                        $(this).parent().find(".logoTag").css("right","-13px");
                    })

                    $(".block").find(".brandName").hover(function(){
                        $(this).parents(".block").find(".logoTag").css("right","-14px");
                    },function(){
                        $(this).parents(".block").find(".logoTag").css("right","-13px");
                    })

					//brand tag mouseon
					/* $(".block").hover(function(){
						$(this).find(".logoTag").css("right","-14px");
						//$(this).find(".section").addClass("secHover");
					},function(){
						$(this).find(".logoTag").css("right","-13px");
						//$(this).find(".section").removeClass("secHover");
					})*/

					 $(".blockInner").each(function(){
						$(this).hover(function(){
							$(this).find(".articleImage>a>img").parent("a").addClass("over");
						},function(){
							$(this).find(".articleImage>a>img").parent("a").removeClass("over");
						})
					})

					loadFlag =false;
				});
			}
			loaderFunc();
		}
		return false;
	});
}

$(document).ready(function() {
    var images = $("#mainBlock .block .pic img");
    var _self = window;
	var ww = $(window).width() / 2 - 240; 
	
	/* 追加 最初はblockを表示させない */
	$(".block").css({
		opacity:0
	})

    try {
        var l = images.length;
        for(var i=0; i< l; i++){
            var img = images[i];

            img.onload = function(){
                _self.loadCounter++;
                if(images.length-1 <= _self.loadCounter)
                    _self.endLoading();
            };
            $(img).load(function(){
                _self.loadCounter++;
                if(images.length-1 <= _self.loadCounter)
                    _self.endLoading();
            });
            $(img).error(function(){
                _self.loadCounter++;
                if(images.length-1 <= _self.loadCounter)
                    _self.endLoading();
            });
        }
    }catch(e) {
        _self.endLoading();
    }
	
	/* more部分の動き */
	moreFunc();


//    setTimeout(function(){
//        _self.endLoading();
//    }, 600);

    //_self.myResize.firstResize($('html').outerWidth());
    $(".moreLink").hide();
	
	setTimeout(_self.resize, 10);
	setTimeout(function(){$(".moreLink").fadeIn()}, 50);
    setInterval(_self.resize, 1000);
    
    tag_over();
});

function tag_over(){
	$("div.logoTag").unbind("mouseenter").unbind("mouseleave");
	$("div.logoTag").hover(
		function(){
            $(this).parent().find(".logoTag").css("right","-14px");
			var index = $("div.logoTag").index(this);
			var brandname = $("div.logoTag").eq(index).parent().find('span.brandName');
			brandname.append('<div style="width:200px;height:14px;background-color:#fff;position:absolute;top:303px;left:0;opacity:0.25"></div>');
		},
		function(){
            $(this).parent().find(".logoTag").css("right","-13px");
			var index = $("div.logoTag").index(this);
			var brandname = $("div.logoTag").eq(index).parent().find('span.brandName div');
			brandname.remove();
		}
	);
}