/* 
 * IEには配列向けにindexOfが使えないため自作する
 * @param elt int,char,array
 * @return int @default -1
 */

if (!Array.prototype.indexOf){
	Array.prototype.indexOf = function(elt /*, from*/){
		var len = this.length;
		var from = Number(arguments[1]) || 0;
		from = (from < 0)
				 ? Math.ceil(from)
				 : Math.floor(from);
		if (from < 0)
			from += len;
		for (; from < len; from++){
			if (from in this &&	this[from] === elt)
				return from;
		}
		return -1;
	};
}

(function($){
	$(function(){
		// =============================== document ready
		
		/*
		 * jQuery( document.body ).data()の仕様
		 * 
		 * APIから取得したデータを整形し、データオブジェクトとして保存してある（jquery.set_weather_api.js参照）
		 * 
		 * @key:
		 *   pref        @char     都道府県のローマ字      @value : tokyo kanagawa など 仕様書を参照     @default : tokyo    [post]
		 *   weather     @char     天気のローマ字          @value : fine rain snow                       @default : fine     [post]
		 *   feeling     @char     現在気温のフィーリング  @value : ex_cold cold cool warm hot heat      @default : ex_cold  [post]
		 *   style       @char     スタイル（調整中）      @value :                                      @default :          [post]
		 *   averageTemp @float    最低と最高気温の平均    @value : 数（正、負あり ex.-10.5 0 12.5など） @default : 0
		 *   tempMin     @int      最低気温                @value : 整数（正、負あり ex.-10 0 12など）   @default : 0
		 *   tempMan     @int      最高気温                @value : 整数（正、負あり ex.-10 0 12など）   @default : 0
		 *   date        @char     jsonから取得した日付    @value : 2012/04/01など                       @default : 現在日付
		 *   day         @char     日付から変換した曜日    @value : sun mon tue wed thr fri sat          @default : sun
		 *   roundedEightCode @char 50種類以上あるAPIの天気コードを8種類に丸めたもの。A - Hの英字大文字  @default : A
		 *
		 * @use:
		 *   alert( jQuery( document.body ).data("pref") );
		 */
		 
		 //submit
		 $("#weather_submit").click(function(e) {
			e.preventDefault();
			$("#weatherForm").submit();
		});
		 	
	});
	
	/**
	 * initAllHeaderUnit
	 * ヘッダー初期化
	 */
	function initAllHeaderUnit(){
		//console.log("initAllHeaderUnit");
		initPref(); //日付情報
		initTemp(); //気温情報
		initWeather(); //天気情報
		initFeeling(); //現在気温のフィーリング
		initStyle(); //スタイル
		initDataInfoSearch(); //「Blog or ブランドから探す」
	}
	
	/**
	 * initPref
	 * 日付情報初期化
	 */
	function initPref(){
		var pref = jQuery( document.body ).data("pref");
		$("#ajaxPref").text(pref +", Japan");
		$("#pref-hidden").attr("value", pref);
	}
	
	/**
	 * initDataInfoSearch
	 * 「Blog or ブランドから探す」ラジオボタン初期化
	 */
	function initDataInfoSearch(){
		var $dataInfoSearchBlock = $("#weatherForm").find("ul.search_from");
		var $dataInfoSearchLi = $dataInfoSearchBlock.find("li");
		var $dataInfoSearchInput = $dataInfoSearchLi.find("input");
		var $dataInfoSearchBlandLi = $dataInfoSearchLi.eq(1);
		
		//ブランドページ
		if($("body").hasClass("brandpage")){
			//「ブランドから探す」ラジオボタンの活性化
			if($dataInfoSearchBlandLi.hasClass("disabled")){
				$dataInfoSearchBlandLi.removeClass("disabled");
				$dataInfoSearchBlandLi.find("input").removeAttr("disabled");
			}
			
			//ラジオボタン表示初期化
			var $checkedItem = $dataInfoSearchInput.filter(":checked");
			$checkedItem.closest("li").addClass("checked");
			var $notCheckedItem = $dataInfoSearchInput.not(":checked");
			$notCheckedItem.closest("li").removeClass("checked");
			
			//ラジオボタン切り替え
			$dataInfoSearchInput.each(function(){
				var $input = $(this);
				$input.click(function(){
					var $this = $(this);
					$checkedItem.removeAttr("checked").closest("li").removeClass("checked");
					$this.attr("checked", "checked").closest("li").addClass("checked");
					$checkedItem = $this;
				});
			});
		//ブランド以外のページ
		}else{
			$dataInfoSearchBlandLi.find("label").hover(
				function () {
					$(this).css("cursor","default");
				},
				function () {
					
				}
			);
		}
	}
	
	/**
	 * initDate
	 * 現在地情報初期化
	 */
	function initDate(){
		var date = jQuery( document.body ).data("date");
		var w = jQuery( document.body ).data("day");
		var m;
		var d;
		
		if((date) && (w)){
			hashes = date.split("/");
			m = Number(hashes[1]);
			d = Number(hashes[2]);
		}else{
			date = new Date();
			m = date.getMonth() + 1;
			d = date.getDate();
			dayIndex = date.getDay();
			dayArr = ["sun","mon","tue","wed","thu","fri","sat"]
			w = dayArr[dayIndex];
		}
		
		var $dateBlock = $("#date_info").find("p.day");
		var $dateSpan = $dateBlock.find("span");
		var $dateM10 = $dateSpan.eq(0);
		var $dateM01 = $dateSpan.eq(1);
		var $dateD10 = $dateSpan.eq(3);
		var $dateD01 = $dateSpan.eq(4);
		var $dateW = $dateSpan.eq(5);
		var defNumClassName = "num_0";
		var defDayClassName = "day_sun";
		
		var dateM01Txt = String(m % 10);
		$dateM01.removeClass(defNumClassName).addClass("num_" + dateM01Txt).text(dateM01Txt);
		
		var dateD01Txt = String(d % 10);
		$dateD01.removeClass(defNumClassName).addClass("num_" + dateD01Txt).text(dateD01Txt);
		
		if(m >= 10){
			var dateM10Txt = String(Math.floor(m / 10));
			$dateM10.removeClass(defNumClassName).addClass("num_" + dateM10Txt).text(dateM10Txt);
		}else{
			$dateM10.hide();
		}
		
		if(d >= 10){
			var dateD10Txt = String(Math.floor(d / 10));
			$dateD10.removeClass(defNumClassName).addClass("num_" + dateD10Txt).text(dateD10Txt);
		}else{
			$dateD10.hide();
		}
		
		$dateW.removeClass(defDayClassName).addClass("day_" + w).text(w.toUpperCase() + ".");
	}
	
	/**
	 * initTemp
	 * 気温情報初期化
	 */
	function initTemp(){
		var $maxTempBlock = $("#ajaxMaxC");
		var $maxTempSpan = $maxTempBlock.find("span");
		var $maxTempMinus = $maxTempSpan.filter(".mark_minus");
		var $maxTemp10 = $maxTempSpan.eq(1);
		var $maxTemp01 = $maxTempSpan.eq(2);
		
		var $minTempBlock = $("#ajaxMinC");
		var $minTempSpan = $minTempBlock.find("span");
		var $minTempMinus = $minTempSpan.filter(".mark_minus");
		var $minTemp10 = $minTempSpan.eq(1);
		var $minTemp01 = $minTempSpan.eq(2);
		
		var tempMax = Number(jQuery( document.body ).data("tempMax"));
		var tempMin = Number(jQuery( document.body ).data("tempMin"));
		var defNumClassName = "num_0";
		
		if(tempMax > -1) $maxTempMinus.hide();
		if(tempMin > -1) $minTempMinus.hide();
		
		var maxTemp01Txt = String(Math.abs(tempMax) % 10);
		$maxTemp01.removeClass(defNumClassName).addClass("num_" + maxTemp01Txt).text(maxTemp01Txt);
		
		var minTemp01Txt = String(Math.abs(tempMin) % 10);
		$minTemp01.removeClass(defNumClassName).addClass("num_" + minTemp01Txt).text(minTemp01Txt);
		
		if(Math.abs(tempMax) >= 10){
			var maxTemp10Txt = String(Math.floor(Math.abs(tempMax) / 10));
			$maxTemp10.removeClass(defNumClassName).addClass("num_" + maxTemp10Txt).text(maxTemp10Txt);
		}else{
			$maxTemp10.hide();
		}
		
		if(Math.abs(tempMin) >= 10){
			var minTemp10Txt = String(Math.floor(Math.abs(tempMin) / 10));
			$minTemp10.removeClass(defNumClassName).addClass("num_" + minTemp10Txt).text(minTemp10Txt);
		}else{
			$minTemp10.hide();
		}
	}
	
	/**
	 * initWeather
	 * 天気情報初期化
	 */
	function initWeather(){
		var $weatherUl = $("#list_weather");
		var $weatherBlock = $weatherUl.closest("div");
		var $weatherLi = $weatherUl.find("li");
		var $weatherNavLi = $weatherBlock.find("ul.nav_list");
		var $weatherHidden = $("#weather-hidden");
		
		var weatherListLength = $weatherLi.length;
		var weatherCodeArr = ["A", "B", "E", "G", "C", "F", "H", "D"];
		var currentWeatherEightCode = jQuery( document.body ).data("roundedEightCode");
		var currentIndex = weatherCodeArr.indexOf(currentWeatherEightCode);
		var maxIndex = weatherListLength - 1;
		var weatherLiHeight = $weatherLi.eq(0).height();
		var weatherUlHeight = weatherLiHeight * weatherListLength;
		var listActiveClassName = "active";
		
		//Change Weather
		var changeWeatherFunc = function(targetIndex){
			//最初の画像から最後の画像を表示する時
			if(currentIndex == 0 && targetIndex == maxIndex){
				$weatherUl.css("top", -((weatherListLength + 1) * weatherLiHeight) + "px");
			//最後の画像から最初の画像を表示する時
			}else if(currentIndex == maxIndex && targetIndex == 0){
				$weatherUl.css("top", 0);
			}
			$weatherLi.eq(currentIndex).removeClass(listActiveClassName);
			$weatherLi.eq(targetIndex).addClass(listActiveClassName);
			//カレント設定
			currentIndex = targetIndex;
			var currentTreeCode = $.translateWeatherCodeToThree(weatherCodeArr[currentIndex]);
			$weatherHidden.attr("value", currentTreeCode);
			//スライドアニメーション
			var targetX = -((targetIndex + 1) * weatherLiHeight);
			$weatherUl.stop(true, false).animate({top: targetX}, {duration:500, easing:"easeOutExpo"});
			
		};
		
		//PREV
		$('#prevWeather').click(function(e) {
			e.preventDefault();
			var index = (currentIndex == 0) ? maxIndex : currentIndex - 1;
			changeWeatherFunc(index);
		});
		
		//NEXT
		$('#nextWeather').click(function(e) {
			e.preventDefault();
			var index = (currentIndex == maxIndex) ? 0 : currentIndex + 1;
			changeWeatherFunc(index);
		});
		
		//初期動作 ---------------------------------------
		$weatherLi.removeClass(listActiveClassName);
		$weatherLi.eq(currentIndex).addClass(listActiveClassName);
		//前後にクローンを配置
		$weatherUl.prepend($weatherLi.slice(maxIndex).clone()).append($weatherLi.slice(0,1).clone());
		$weatherUl.css("height", weatherUlHeight + "px").css("top", -((currentIndex + 1) * weatherLiHeight));
		$weatherHidden.attr("value", jQuery( document.body ).data("weather"));
	}
	
	/**
	 * initFeeling
	 * 現在気温のフィーリング初期化
	 */
	function initFeeling(){
		setSelectMenu($("#block_feeling"), $("#ajaxTemperature"), $("#temperature-hidden"), jQuery( document.body ).data("feeling"));
	}
	
	/**
	 * initStyle
	 * スタイル初期化
	 */
	function initStyle(){
		setSelectMenu($("#block_style"), $("#ajaxStyle"), $("#style-hidden"), jQuery( document.body ).data("style"));
	}
	
	/**
	 * setSelectMenu
	 * セレクトメニュー初期化
	 * @param jQuery $targetBlock(セレクトメニューのラッパー)
	 * @param jQuery $trigger(セレクトメニューのトリガー)
	 * @param jQuery $hidden(hidden要素)
	 * @param String apiValue(APIから取得したデータ)
	 */
	function setSelectMenu($targetBlock, $trigger, $hidden, apiValue){
		var $changeClassTarget = $trigger.find("span");
		var $listItemUl = $targetBlock.find("ul.list_item");
		var $listItemLi = $listItemUl.find("li");
		
		var currentClass = $changeClassTarget.attr("class");
		var listActiveClassName = "active";
		
		//カレント設定
		var $currentLi = $listItemLi.filter("." + listActiveClassName);
		$listItemLi.each(function(){
			var $this = $(this);
			var $target = $this.find("a");
			
			//カレント初期化
			if(apiValue){
				if($target.hasClass(apiValue)){
					$this.addClass(listActiveClassName);
					$currentLi = $this;
					$changeClassTarget.removeClass(currentClass).addClass(apiValue).text($target.text());
					$hidden.attr("value", apiValue);
					currentClass = apiValue;
				}else{
					$this.removeClass(listActiveClassName);
				}
			}
			//カレントチェンジ
			$target.click(function(e) {
				e.preventDefault();
				var $a = $(this);
				var $li = $a.closest("li");
				var thisClass = $a.attr("class");
				$li.addClass(listActiveClassName);
				$currentLi.removeClass(listActiveClassName);
				$currentLi = $li;
				$changeClassTarget.removeClass(currentClass).addClass(thisClass).text($a.text());
				$hidden.attr("value", thisClass);
				currentClass = thisClass;
			});
		});
		
		//メニューの開閉
		$trigger.click(function(e) {
			e.preventDefault();
			var showFlg = ($listItemUl.css("display") == "none") ? false : true;
			killSelectAllMenu();
			if(!showFlg){
				$listItemUl.show();
				$(document).click(killSelectAllMenu);
			}
			return false;
		});
	}
	
	/**
	 * killSelectAllMenu
	 * セレクトメニュー非表示
	 */
	function killSelectAllMenu(){
		$(document).unbind("click", killSelectAllMenu);
		$("#block_feeling").find("ul.list_item").hide();
		$("#block_style").find("ul.list_item").hide();
	}


	// =============================== jQuery拡張関数
    $.extend({
    	beforeCallbackForHeader: function() {
    		//Ajax通信前
    		
    	},
    	successCallbackForHeader: function() {
    		//Ajax通信成功
    		initAllHeaderUnit();
    		
    	},
    	errorCallbackForHeader: function() {
    		//Ajax通信失敗
    		
    	},
    	completeCallbackForHeader: function() {
    		//Ajax通信実施後（成功・失敗に限らず実行する）
    		initDate();
    	}
		
	});//------- end $.extend --------
	
})(jQuery)