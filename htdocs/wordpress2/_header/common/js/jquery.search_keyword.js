(function($){
	$(function(){
		// =============================== document ready
		 //「全体検索、ブランド内検索」トグルボタン初期化
		 initSearchTypeBtn();
		 //submit
		 $("#keyword_submit").click(function(e) {
			e.preventDefault();
			$("#keywordForm").submit();
		});
		 	
	});
	
	/**
	 * initSearchType
	 * 「全体検索、ブランド内検索」トグルボタン初期化
	 */
	function initSearchTypeBtn(){
		var $searchTypeBlock = $("#search_keyword").find("ul.search_from");
		var $searchTypeLi = $searchTypeBlock.find("li");
		var $searchTypeInput = $searchTypeLi.find("input");
		var $searchTypeBlandLi = $searchTypeLi.eq(1);
		
		//ブランドページ
		if($("body").hasClass("brandpage")){
			//「ブランド内検索」ボタンの活性化
			if($searchTypeBlandLi.hasClass("disabled")){
				$searchTypeBlandLi.removeClass("disabled");
				$searchTypeBlandLi.find("input").removeAttr("disabled");
			}
			
			//ボタン表示初期化
			var $checkedItem = $searchTypeInput.filter(":checked");
			$checkedItem.closest("li").addClass("checked");
			var $notCheckedItem = $searchTypeInput.not(":checked");
			$notCheckedItem.closest("li").removeClass("checked");
			
			//ボタン切り替え
			$searchTypeInput.each(function(){
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
			$searchTypeBlandLi.find("label").hover(
				function () {
					$(this).css("cursor","default");
				},
				function () {
					
				}
			);
		}
	}
	
})(jQuery)