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

var __ajaxInitialFlag = true;

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
		 *   tempMax     @int      最高気温                @value : 整数（正、負あり ex.-10 0 12など）   @default : 0
		 *   date        @char     jsonから取得した日付    @value : 2012/04/01など                       @default : 現在日付
		 *   day         @char     日付から変換した曜日    @value : sun mon tue wed thr fri sat          @default : sun
		 *   roundedEightCode @char 50種類以上あるAPIの天気コードを8種類に丸めたもの。A - Hの英字大文字  @default : A
		 *
		 * @use:
		 *   alert( jQuery( document.body ).data("pref") );
		 */
		 
		 
		 //console.log( jQuery( document.body ).data() );
		 //console.log( $.translatePrefJp( jQuery( document.body ).data('pref') ) );
	});
	
	/**
	 * updateAllHeaderUnit
	 * ヘッダー初期化
	 */
	var stop_change_flag = true;
	function updateAllHeaderUnit(){
		updatePref(); //都道府県情報
		if( $('body').hasClass('weather_search_result') && stop_change_flag ){
			stop_change_flag = false;
		}else{
			updateTemp(); //気温情報
			updateWeather(); //天気情報
		}
	}
	
	/**
	 * initPref
	 * 都道府県情報初期化
	 */
	function updatePref(){
		var pref = $.translatePrefJp( jQuery( document.body ).data("pref") );
		if( __ajaxInitialFlag ){
			//初回のみ、prefのselectボックスにchangeイベントを送信する
			$('#pref').val( pref ).trigger('change');
			//都道府県セレクトボックスに変更があったら再度AJAXで天気情報を更新
			addChangePrefEvent();
			__ajaxInitialFlag = false;
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
			m = Number(date.getMonth() + 1);
			d = Number(date.getDate());
			dayIndex = date.getDay();
			dayArr = ["sun","mon","tue","wed","thu","fri","sat"]
			w = dayArr[dayIndex];
		}
		
		var $dateUl = $("#date_info");
		var _tmpLi = "";//HTML出力用の変数
		
		m = ""+m;
		d = ""+d;
		var i = 0;//一文字ずつ抜き出す時に使用
		
		//month
		for( i = 0; i < m.length; i++ ){
			_tmpLi += '<li><img width="7" height="10" alt="" src="/images/common/date_num_0' + m.charAt(i) + '.gif"></li>';
		}
		
		_tmpLi += '<li><img width="7" height="10" alt="/" src="/images/common/date_slash.gif"></li>';
		
		//date
		for( i = 0; i < d.length; i++ ){
			_tmpLi += '<li><img width="7" height="10" alt="" src="/images/common/date_num_0' + d.charAt(i) + '.gif"></li>';
		}
		
		//day
		_tmpLi += '<li><img width="28" height="10" alt="" src="/images/common/date_' + w + '.gif"></li>';
		
		//console.log(_tmpLi);
		
		//日付を入れ替え
		$dateUl.children('li').hide();
		$dateUl.append(_tmpLi);
	}
	
	/**
	 * updateTemp
	 * 気温情報更新
	 */
	function updateTemp(){
		var $tempUl = $('#temp_info');
		var _tmpLi = "";//HTML出力用の変数
		
		var tempMax = "" + jQuery( document.body ).data('tempMax');
		var tempMin = "" + jQuery( document.body ).data('tempMin');
		var i = 0;//一文字ずつ抜き出す時に使用
		
		//tempMax
		for( i = 0; i < tempMax.length; i++ ){
			_tmpLi += '<li><img width="10" height="15" alt="" src="/images/common/templeture_num_0' + tempMax.charAt(i) + '.gif"></li>';
		}
		
		_tmpLi += '<li><img src="/images/common/templeture_degree.gif" width="10" height="15" alt="度" /></li>';
		_tmpLi += '<li><img src="/images/common/templeture_slash.gif" width="10" height="15" alt="/" /></li>';
		
		
		//tempMin
		for( i = 0; i < tempMin.length; i++ ){
			_tmpLi += '<li><img width="10" height="15" alt="" src="/images/common/templeture_num_0' + tempMin.charAt(i) + '.gif"></li>';
		}
		
		_tmpLi += '<li><img src="/images/common/templeture_degree.gif" width="10" height="15" alt="度" /></li>';
		
		//console.log(_tmpLi);
		
		//気温を入れ替え
		$tempUl.children('li').hide();
		$tempUl.append(_tmpLi);
	}
	
	/**
	 * updateWeather
	 * 天気情報更新
	 */
	function updateWeather(){
		//hiddenの更新
		$('#weather-hidden').val(jQuery( document.body ).data("weather"));
		//$('#temperature-hidden').val(jQuery( document.body ).data("feeling"));
		$('#temperature').val(jQuery( document.body ).data("feeling"));
		$("span.ui-btn-text").each(function(i){
          if(i == 5){
            var getnowtxt = $("#temperature option:selected").text();
            $(this).text(getnowtxt);
          }
        });
		
		//天気アイコン更新
		var weatherCodeArr = ["A", "B", "E", "G", "C", "F", "H", "D"];
		var iconIdx = 0;
		
		for(var i = 0; i < weatherCodeArr.length; i++){
			if( jQuery( document.body ).data("roundedEightCode") == weatherCodeArr[i] ){
				iconIdx = i;
			}
		}
		iconIdx++;
		
		$('#ajaxWeather > img').attr('src','/images/common/weather_icon_0' + iconIdx + '.gif');
	}
	/**
	 * addChangePrefEvent
	 * 都道府県セレクトボックスに変更があったら再度AJAXで天気情報を更新
	 */
	function addChangePrefEvent(){
		$('#pref').change(function(){
			coval = $.translatePrefNum($(this).val()) + ",";
			$.cookie('lifecruise',coval,{ path: '/', expires: 30 });
			
			var pref = "tokyo";
			pref = $.translatePrefEn( $(this).val() );
			var suff = ".js";
			var fileName = pref + suff;
			var dataSourceUrlBase = "http://" + window.location.hostname +  "/_weather_json/";
			var dataSourceUrl = dataSourceUrlBase + fileName;
			$.doAjaxForWeather( dataSourceUrl );
		});
	}
	
	
	// =============================== jQuery拡張関数
    $.extend({
    	beforeCallbackForHeader: function() {
    		//Ajax通信前
    		
    	},
    	successCallbackForHeader: function() {
   			//Ajax通信成功
   			updateAllHeaderUnit();
    	},
    	errorCallbackForHeader: function() {
    		//Ajax通信失敗
    		
    	},
    	completeCallbackForHeader: function() {
    		//Ajax通信実施後（成功・失敗に限らず実行する）
    		initDate();
    	},
    	/* 
		* cookieに入れるための準備
		*/
		translatePrefNum: function( rawCode ) {
			var ret = "13";//default code
			var codeArr = {
				"北海道"   : "1",
				"青森県"   : "2",
				"岩手県"   : "3",
				"宮城県"   : "4",
				"秋田県"   : "5",
				"山形県"   : "6",
				"福島県"   : "7",
				"茨城県"   : "8",
				"栃木県"   : "9",
				"群馬県"   : "10",
				"埼玉県"   : "11",
				"千葉県"   : "12",
				"東京都"   : "13",
				"神奈川県" : "14",
				"新潟県"   : "15",
				"富山県"   : "16",
				"石川県"   : "17",
				"福井県"   : "18",
				"山梨県"   : "19",
				"長野県"   : "20",
				"岐阜県"   : "21",
				"静岡県"   : "22",
				"愛知県"   : "23",
				"三重県"   : "24",
				"滋賀県"   : "25",
				"京都府"   : "26",
				"大阪府"   : "27",
				"兵庫県"   : "28",
				"奈良県"   : "29",
				"和歌山県" : "30",
				"鳥取県"   : "31",
				"島根県"   : "32",
				"岡山県"   : "33",
				"広島県"   : "34",
				"山口県"   : "35",
				"徳島県"   : "36",
				"香川県"   : "37",
				"愛媛県"   : "38",
				"高知県"   : "39",
				"福岡県"   : "40",
				"佐賀県"   : "41",
				"長崎県"   : "42",
				"熊本県"   : "43",
				"大分県"   : "44",
				"宮崎県"   : "45",
				"鹿児島県" : "46",
				"沖縄県"   : "47"
			};
			
			if( codeArr[rawCode] ){
				ret = codeArr[rawCode];
			}
			return ret;
		}
		
	});//------- end $.extend --------
	
})(jQuery)