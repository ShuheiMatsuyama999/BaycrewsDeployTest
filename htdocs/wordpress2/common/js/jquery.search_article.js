(function($){
	$(function(){
		// =============================== document ready
		 //「記事を探す」初期化
		 initSearchArticleBlock();
		 //submit
		 $("#article_submit").click(function(e) {
			e.preventDefault();
			$("#articleForm").submit();
		 });
		 	
	});
	
	/**
	 * initSearchArticleBlock
	 * 「記事を探す」初期化
	 */
	function initSearchArticleBlock(){
		var $openBtn = $("#search_article_open_bt");
		var $closeBtn = $("#search_article_close_bt");
		var $conents = $("#search_article_box");
		
		$conents.hide();
		//show
		$openBtn.click(function(e){
			e.preventDefault();
			//resetSearchArticleBlock();
			if($openBtn.hasClass("active")){
				$conents.stop()
				.animate({opacity:0}, 1200, 'easeOutExpo', function(){$conents.hide();});
				$openBtn.removeClass("active");
			}else{
				$conents.show();
				$conents.css({marginTop:-10, opacity:0})
				.stop()
				.animate({marginTop:0, opacity:1}, 1200, 'easeOutExpo');
				$openBtn.addClass("active");
			}
		});
		//hide
		$closeBtn.click(function(e){
			e.preventDefault();
			$openBtn.removeClass("active");
			$conents.stop()
			.animate({opacity:0}, 1200, 'easeOutExpo', function(){$conents.hide();});
		});
		
		//チェックボックス初期化
		initCheckBoxSync($("#searchList_category"));
		initCheckBoxSync($("#searchList_brand"));
		initCheckBoxSync($("#searchList_local"));
	}
	
	/**
	 * initCheckBoxSync
	 * チェックボックス同期設定初期化
	 * @param jQuery $targetBlock(チェックボックスのラッパー)
	 */
	function initCheckBoxSync($targetBlock){
		var $input = $targetBlock.find("input");
		var $parent = $input.filter(":eq(0)");
		var $children = $input.not(":eq(0)");
		var inputWrapperTagName = $parent.parent().parent().get(0).tagName;
		var nameSpace = "articleCheckBoxSync";
		
		//チェックボックス表示初期化
		$input.filter(":checked").closest(inputWrapperTagName).addClass("checked");
		$input.not(":checked").closest(inputWrapperTagName).removeClass("checked");
		
		//親にデータ保持
		$parent.data(nameSpace, { children: $children });
		
		//子にデータ保持
		$children.data(nameSpace, { parent: $parent });
		
		//イベント設定
		var ua = navigator.userAgent.toLowerCase();
		//IE6、7、8の場合、ラベルクリック
		if((ua.indexOf("msie 6") >= 0) || (ua.indexOf("msie 7") >= 0) || (ua.indexOf("msie 8") >= 0)){
			$parent.parent("label").click(function(){
				onParentsUpdate($(this).children(), inputWrapperTagName, nameSpace);
			});
			$children.parent("label").click(function(){
				onChildrenUpdate($(this).children(), inputWrapperTagName, nameSpace);
			});
		//IE6、7、8以外の場合、inputクリック
		}else{
			$parent.click(function(){
				onParentsUpdate($(this), inputWrapperTagName, nameSpace);
			});
			$children.click(function(){
				onChildrenUpdate($(this), inputWrapperTagName, nameSpace);
			});
		}
	}
	
	/**
	 * onParentsUpdate
	 * 親が変更された際の更新処理
	 * @param jQuery $parents(親のチェックボックス)
	 * @param inputWrapperTagName(ラッパーのタグ名)
	 * @param nameSpace(格納時のnamespace)
	 */
	function onParentsUpdate($parents, inputWrapperTagName, nameSpace){
		//親を走査
		$parents.each(
			function(index){
				//親
				var $parent = $(this);
				//子
				var $children = $parent.data(nameSpace).children;
				
				//親のチェックを外す
				if($parent.closest(inputWrapperTagName).hasClass("checked")){
					$parent.removeAttr("checked").closest(inputWrapperTagName).removeClass("checked");
				}
				//親をチェック
				else{
					$parent.attr("checked", "checked").closest(inputWrapperTagName).addClass("checked");
				}
				//親がチェックされていれば
				if($parent.filter(":checked").size()){
					//子チェック
					$children.attr("checked", "checked").closest(inputWrapperTagName).addClass("checked");
				}
				//親が未チェックであれば
				else{
					//子チェック解除
					$children.removeAttr("checked").closest(inputWrapperTagName).removeClass("checked");
				}
			}
		);
	}
	
	/**
	 * onChildrenUpdate
	 * 子が変更された際の更新処理
	 * @param jQuery $parents(子のチェックボックス)
	 * @param inputWrapperTagName(ラッパーのタグ名)
	 * @param nameSpace(格納時のnamespace)
	 */
	function onChildrenUpdate($children, inputWrapperTagName, nameSpace){
		//子を走査
		$children.each(
			function(index){
				//子
				var $child = $(this);
				//親
				var $parent = $child.data(nameSpace).parent;
				//親の子チェック群
				var $children = $parent.data(nameSpace).children;
				
				//子のチェックを外す
				if($child.closest(inputWrapperTagName).hasClass("checked")){
					$child.removeAttr("checked").closest(inputWrapperTagName).removeClass("checked");
				}
				//子をチェック
				else{
					$child.attr("checked", "checked").closest(inputWrapperTagName).addClass("checked");
				}
				//子チェックの中で未チェックが存在すれば
				if($children.not(":checked").size()){
					//親チェック解除
					$parent.removeAttr("checked").closest(inputWrapperTagName).removeClass("checked");
				}
				//子チェックの中で未チェックが存在しなければ（全てチェック）
				else{
					//親チェック
					$parent.attr("checked", "checked").closest(inputWrapperTagName).addClass("checked");
				}
			}
		);
	}
	
	
	/**
	 * resetSearchArticleBlock
	 * 「記事を探す」チェックボックスリセット
	 */
	function resetSearchArticleBlock(){
		var $form = $("#articleForm");
		$form.find("input").removeAttr("checked").parent().parent().removeClass("checked");
		$form.trigger("reset");
	}
	
})(jQuery)