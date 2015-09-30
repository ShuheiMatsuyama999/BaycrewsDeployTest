(function($){
$(function(){
	// =============================== WWOの天気データを取得
	
	/*
	 * init setting
	 *   ajaxURLの生成
	 *   クッキーから都道府県の取得
	 */
	var pref = "tokyo";
	var suff = ".js"
	
	// クッキーから都道府県を抽出
	if( $.cookie('lifecruise') ){
		//取得
		var cookieLifeCruise = $.cookie('lifecruise');
		var cookieArr = cookieLifeCruise.split(",");
		var cookiePrefCode = cookieArr[0];
		
		if( cookiePrefCode != "" ){
			//県として代入
			pref = $.translateCookiePref(cookiePrefCode);
		}
	}
	
	// ajaxのURLを生成
	var fileName = pref + suff;
	var dataSourceUrlBase = "http://" + window.location.hostname +  "/_weather_json/";
	var dataSourceUrl = dataSourceUrlBase + fileName;
	
	
	/* 
	 * jquery data objectの初期化
	 * 
	 *   他のjsファイルからAPIの情報を流用できるようにdataオブジェクトを用意する
	 *   それぞれdefault値を持たせておく
	 */
	
	// ajaxによるデータ取得に失敗した時のことを考えてデフォルト用に現在日付の取得
	var nowDate = $.getNowDate();
	
	// 同上の理由から、それぞれdefault値を代入し、ajax成功後に取得した値を代入する
	jQuery( document.body ).data({
		pref             : pref,
		weather          : 'fine',
		feeling          : 'ex_cold',
		roundedEightCode : 'A',
		averageTemp      : 0,
		tempMin          : 0,
		tempMax          : 0,
		style            : "style01",
		date             : nowDate,
		day              : "sun"
	});
	
	//[スマホ]
	$.doAjaxForWeather( dataSourceUrl );
	

});//------- end Document.Ready() --------

	// =============================== jQuery拡張関数
    $.extend({
    	//[スマホ]
    	doAjaxForWeather : function( dataSourceUrl ){
	    	/*
			 * ajax
			 * データソースのURLからオブジェクトを生成
			 */
			$.ajax({
				url: dataSourceUrl,
				dataType: "json",
				type: "GET",
				beforeSend : function(){
					/*****************************
					 * jquery.header_enhancer.jsへ
				 	 *****************************/
					$.beforeCallbackForHeader;
				},
				success : function(json,datatype){
					// -- 取得したデータをjquery data objectに格納し、jquery.header_enhancer.jsへ
					
					//現在の天気
					jQuery( document.body ).data("roundedEightCode", $.translateWeatherCodeToEight( json.data.current_condition[0].weatherCode ));
					jQuery( document.body ).data("weather", $.translateWeatherCodeToThree( jQuery( document.body ).data("roundedEightCode") ));
					
					//今日の最低気温
					jQuery( document.body ).data("tempMin", parseInt( json.data.weather[0].tempMinC ));
					
					//今日の最高気温
					jQuery( document.body ).data("tempMax", parseInt( json.data.weather[0].tempMaxC ));
					
					//気温の平均値
					var ave = ( jQuery( document.body ).data("tempMin") + jQuery( document.body ).data("tempMax") ) / 2
					jQuery( document.body ).data("averageTemp", ave);
					jQuery( document.body ).data("feeling", $.translateTemperature( jQuery( document.body ).data("averageTemp") ));
					
					//スタイル
					jQuery( document.body ).data("style", "style01");
					
					//日付
					var tmpDate = json.data.weather[0].date;
					tmpDate = tmpDate.replace(/-/g, "/");;
					jQuery( document.body ).data("date", tmpDate );
					jQuery( document.body ).data("day", $.translateDay( tmpDate ) );
					
					/*****************************
					 * jquery.header_enhancer.jsへ
					 *****************************/
					$.successCallbackForHeader();
					
				},
				error : function(){
					// -- ajax失敗時のコールバック
					alert("Failed to load weather data. \n Please retry to access this site after.");
					
					/*****************************
					 * jquery.header_enhancer.jsへ
					 *****************************/
					$.errorCallbackForHeader();
				},
				complete : function(){
					// -- ajaxの成否に関わらず通信完了時に実行するコールバック
					/*****************************
					 * jquery.header_enhancer.jsへ
					 *****************************/
					$.completeCallbackForHeader();
				}
			});
    	},
    	//[スマホ]
    	translatePrefJp: function( key ) {
    		var ret = "北海道";//default
			var codeArr = {
				"hokkaido": "北海道",
				"aomori" : "青森県",
				"iwate" : "岩手県",
				"miyagi" : "宮城県",
				"akita" : "秋田県",
				"yamagata" : "山形県",
				"fukushima" : "福島県",
				"ibaraki" : "茨城県",
				"tochigi" : "栃木県",
				"gunma" : "群馬県",
				"saitama" : "埼玉県",
				"chiba" : "千葉県",
				"tokyo" : "東京都",
				"kanagawa" : "神奈川県",
				"niigata" : "新潟県",
				"toyama" : "富山県",
				"ishikawa" : "石川県",
				"fukui" : "福井県",
				"yamanashi" : "山梨県",
				"nagano" : "長野県",
				"gifu" : "岐阜県",
				"shizuoka" : "静岡県",
				"aichi" : "愛知県",
				"mie" : "三重県",
				"shiga" : "滋賀県",
				"kyoto" : "京都府",
				"oosaka" : "大阪府",
				"hyougo" : "兵庫県",
				"nara" : "奈良県",
				"wakayama" : "和歌山県",
				"tottori" : "鳥取県",
				"shimane" : "鳥取県",
				"okayama" : "岡山県",
				"hiroshima" : "広島県",
				"yamaguchi" : "山口県",
				"tokushima" : "徳島県",
				"kagawa" : "香川県",
				"ehime" : "愛媛県",
				"kouchi" : "高知県",
				"fukuoka" : "福岡県",
				"saga" : "佐賀県",
				"nagasaki" : "長崎県",
				"kumamoto" : "熊本県",
				"ooita" : "大分県",
				"miyazaki" : "宮崎県",
				"kagoshima" : "鹿児島県",
				"okinawa" : "沖縄県"
			};
			
			if( codeArr[key] ){
				ret = codeArr[key];
			}
			return ret;
    	},
    	//[スマホ]
    	translatePrefEn: function( key ) {
    		var ret = "hokkaido";//default
			var codeArr = {
				"北海道" : "hokkaido",
				"青森県" : "aomori",
				"岩手県" : "iwate",
				"宮城県" : "miyagi",
				"秋田県" : "akita",
				"山形県" : "yamagata",
				"福島県" : "fukushima",
				"茨城県" : "ibaraki",
				"栃木県" : "tochigi",
				"群馬県" : "gunma",
				"埼玉県" : "saitama",
				"千葉県" : "chiba",
				"東京都" : "tokyo",
				"神奈川県" : "kanagawa",
				"新潟県" : "niigata",
				"富山県" : "toyama",
				"石川県" : "ishikawa",
				"福井県" : "fukui",
				"山梨県" : "yamanashi",
				"長野県" : "nagano",
				"岐阜県" : "gifu",
				"静岡県" : "shizuoka",
				"愛知県" : "aichi",
				"三重県" : "mie",
				"滋賀県" : "shiga",
				"京都府" : "kyoto",
				"大阪府" : "oosaka",
				"兵庫県" : "hyougo",
				"奈良県" : "nara",
				"和歌山県" : "wakayama",
				"鳥取県" : "tottori",
				"鳥取県" : "shimane",
				"岡山県" : "okayama",
				"広島県" : "hiroshima",
				"山口県" : "yamaguchi",
				"徳島県" : "tokushima",
				"香川県" : "kagawa",
				"愛媛県" : "ehime",
				"高知県" : "kouchi",
				"福岡県" : "fukuoka",
				"佐賀県" : "saga",
				"長崎県" : "nagasaki",
				"熊本県" : "kumamoto",
				"大分県" : "ooita",
				"宮崎県" : "miyazaki",
				"鹿児島県" : "kagoshima",
				"沖縄県" : "okinawa"
			};
			
			if( codeArr[key] ){
				ret = codeArr[key];
			}
			return ret;
    	},
    	
    	/* 
    	 * WWO-APIの天気コードを表示用に8種類へ丸める
    	 *   @param
    	 *    rawCode : char ajaxで取得したWWOの天気コード
    	 *   @return
    	 *    ret     : char 8種類に丸めた結果を文字列で返す
    	 */
		translateWeatherCodeToEight: function( rawCode ) {
			var ret = "A";//default code
			var codeArr = {
        		"113" : "A",
				"260" : "B",
				"248" : "B",
				"143" : "B",
				"122" : "B",
				"119" : "B",
				"377" : "C",
				"374" : "C",
				"359" : "C",
				"356" : "C",
				"353" : "C",
				"350" : "C",
				"314" : "C",
				"311" : "C",
				"308" : "C",
				"302" : "C",
				"296" : "C",
				"284" : "C",
				"281" : "C",
				"395" : "D",
				"371" : "D",
				"368" : "D",
				"365" : "D",
				"362" : "D",
				"338" : "D",
				"332" : "D",
				"326" : "D",
				"320" : "D",
				"317" : "D",
				"230" : "D",
				"227" : "D",
				"116" : "E",
				"392" : "F",
				"386" : "F",
				"305" : "F",
				"299" : "F",
				"293" : "F",
				"266" : "F",
				"263" : "F",
				"176" : "F",
				"335" : "G",
				"329" : "G",
				"323" : "G",
				"185" : "G",
				"182" : "G",
				"179" : "G",
				"389" : "H",
				"200" : "H"
			};
			
			if( codeArr[parseInt(rawCode)] ){
				ret = codeArr[rawCode];
			}
			return ret;
		},
		/* 
		 * 表示用8種類の天気コードをPOST用に3種類に変換
    	 *   @param
    	 *    rawCode : char 8種類に丸めた天気コード
    	 *   @return
    	 *    ret     : char POST用に３種類に丸めた結果
		 */
		translateWeatherCodeToThree: function( rawCode ) {
			var ret = "fine";//default code
			var codeArr = {
        		"A" : "fine",
				"B" : "fine",
				"C" : "rain",
				"D" : "snow",
				"E" : "fine",
				"F" : "rain",
				"G" : "snow",
				"H" : "rain"
			};
			
			if( codeArr[rawCode] ){
				ret = codeArr[rawCode];
			}
			return ret;
		},
		/* 
		 * 現在気温からフィーリングに変換する
		 *   @param
    	 *    averageTemp : int 最低気温と最高気温の平均値
    	 *   @return
    	 *    ret         : char 平均気温と対応するフィーリング
		 * 
		 *   とても寒い時（0度以下）      ex_cold
		 *   寒い時（10度以下）           cold
		 *   涼しい時（11度～15度）       cool
		 *   暖かい時（16度～20度）      	warm
		 *   暑い時（21度～25度）         hot
		 *   とても暑い時（26度～）      	heat
		 * 
		 */
		translateTemperature: function( averageTemp ) {
			var ret = "ex_cold";//default temperature feeling
			
			if( averageTemp < 0 ){
				ret = "ex_cold";
			}
			else if( averageTemp < 10 ){
				ret = "cold";
			}
			else if( averageTemp < 15 ){
				ret = "cool";
			}
			else if( averageTemp < 20 ){
				ret = "warm";
			}
			else if( averageTemp < 25 ){
				ret = "hot";
			}
			else{
				ret = "heat";
			}
			return ret;
		},
		/* 
		 * cookieコードから都道府県を返す
		 */
		translateCookiePref: function( rawCode ) {
			var ret = "13";//default code
			var codeArr = {
				"1" : "hokkaido",
				"2" : "aomori",
				"3" : "iwate",
				"4" : "miyagi",
				"5" : "akita",
				"6" : "yamagata",
				"7" : "fukushima",
				"8" : "ibaraki",
				"9" : "tochigi",
				"10" : "gunma",
				"11" : "saitama",
				"12" : "chiba",
				"13" : "tokyo",
				"14" : "kanagawa",
				"15" : "niigata",
				"16" : "toyama",
				"17" : "ishikawa",
				"18" : "fukui",
				"19" : "yamanashi",
				"20" : "nagano",
				"21" : "gifu",
				"22" : "shizuoka",
				"23" : "aichi",
				"24" : "mie",
				"25" : "shiga",
				"26" : "kyoto",
				"27" : "oosaka",
				"28" : "hyougo",
				"29" : "nara",
				"30" : "wakayama",
				"31" : "tottori",
				"32" : "shimane",
				"33" : "okayama",
				"34" : "hiroshima",
				"35" : "yamaguchi",
				"36" : "tokushima",
				"37" : "kagawa",
				"38" : "ehime",
				"39" : "kouchi",
				"40" : "fukuoka",
				"41" : "saga",
				"42" : "nagasaki",
				"43" : "kumamoto",
				"44" : "ooita",
				"45" : "miyazaki",
				"46" : "kagoshima",
				"47" : "okinawa"
			};
			
			if( codeArr[rawCode] ){
				ret = codeArr[rawCode];
			}
			return ret;
		},
		/* 
		 * 現在日時の取得
		 *   @return
    	 *    strDate : date yyyy/mm/dd
		 */
		getNowDate: function() {
			var date = new Date();
			var year = date.getYear();
			var year4 = (year < 2000) ? year+1900 : year;
			var month = date.getMonth() + 1;
			var date = date.getDate();
			if (month < 10) {
				month = "0" + month;
			}
			if (date < 10) {
				date = "0" + date;
			}
			var strDate = year4 + "/" + month + "/" + date;
			return strDate;
		},
		/* 
		 * 日付文字列から曜日を返す
		 *   @param
    	 *    date : char yyyy/mm/dd
    	 *   @return
    	 *    ret  : char 曜日
		 */
		translateDay: function( date ) {
			var d = new Date(date);
			var w = ["sun","mon","tue","wed","thu","fri","sat"];
			var ret = "sun";
			
			if( w[d.getDay()] ){
				ret = w[d.getDay()];
			}
			
			return ret;
		}
	});//------- end $.extend --------
})(jQuery)