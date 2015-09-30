$( function() {
	
	// search area
	// 参考 : http://blog.baycrews.co.jp/common/js/function.js
	var srcStr = "DAILY BLOGから検索";
	$(".inputArea input").focus(function() {
		$(this).animate({ width:"160px" }, 300);
		$(this).attr("size",50);
		$(".searchArea").addClass('here');
		if($(this).val() == srcStr){ $(this).val(''); }
	}).blur(function() {
		$(this).animate({ width:"120px" }, 300);
		$(this).attr("size",100);
		$(".searchArea").removeClass('here');
		if(jQuery.trim($(this).val()) == "") $(this).val(srcStr);
	});

});
