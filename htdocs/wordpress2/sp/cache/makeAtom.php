<?php
/*
Template Name: 簡易表示用テンプレート
*/
require('../../wp-load.php');

global $wpdb;
global $post;

//Atomテンプレートファイル
$atomTmpl = "./atomTemplate.php";

//atom用ブログID初期化
$atomData = array();

//取得記事数
$maxPostNum = 10;

//全マルチサイト情報
$allBlogs = wponw::get_blogs( $args );

//全マルチサイト数
$countAllBlogs = count($allBlogs);


/*
負荷対策の為、マルチサイトを分割して実行する
*/
//分割実行分割数
$splitNo = 4;

//分割実行用
$sp_id = $_GET['sp_id'];
if(is_numeric($sp_id) && $sp_id <= $splitNo){
    $marginBolgId = floor($countAllBlogs / $splitNo);
    $startBolgId = floor($marginBolgId * ($sp_id - 1));

    if($sp_id == $splitNo){
        $endBolgId = $countAllBlogs;
    }else{
        $endBolgId = floor($marginBolgId * $sp_id) - 1;
    }
}
else{
    header('Location: http://'.$_SERVER['SERVER_NAME'].'/');
}

for($i = 0; $i < $countAllBlogs; $i++){
	if($startBolgId <= $i && $i <= $endBolgId){
		$setBlogs[$i] = $allBlogs[($i)];
	}
}


foreach($setBlogs as $blog){

	$postNo = 0;

	//マルチサイト記事取得
	$posts = wponw::get_posts('numberposts='.$maxPostNum.'&affect_wp_query=true&exclude_blog_ids=1&blog_ids='.$blog->blog_id);

	//記事の数ループ
	if ( ! empty ( $posts ) ){
		foreach ( $posts as $post ){
			wponw::setup_blog_and_postdata( $post );

			//記事の所属するブランドを検索
			$blogCount = 0;
			$nowBlogId = '';
			foreach($allBlogs as $blogs){
				if($blogs->blog_id == $post->blog_id){
					$nowBlogId = $blogs->blog_id;
					break;
				}
				$blogCount++;
			}

			//ブランドname
			$brandPath = str_replace("/", "", $blog->path);

			$nowBlogInfo = $wpdb->get_results("SELECT option_value FROM wp_".$nowBlogId ."_options ");
			$brandInfo['name'] = $nowBlogInfo[1]->option_value;

			//店舗slug取得
			$getcategory = get_the_category();
			$nowshop = array();
			$limited_flag = 0;
			$shop_num = 0;
			foreach($getcategory as $cat){
				$parent = get_category($cat->parent);
				if($parent->slug == "shop_info"){
					$nowshop[$shop_num]['name'] = $cat->name;
					$nowshop[$shop_num]['slug'] = $cat->slug;
					$shop_num += 1;
				}
				if($cat->slug == "limited") $limited_flag += 1;
			}
			$nowshop_slug = $nowshop[$shop_num]['slug'];

			//店舗情報
			$getuser = get_user_by('login',$slug);
			$shop_id = get_the_author_meta('user_firstname',$getuser->data->ID);
			if($shop_id){
				$shoplist = simplexml_load_file("http://www.baycrews.co.jp/shop_list/xml/shoplist.xml");
				$nowShopInfo = "";
				foreach($shoplist->shoplist->shop as $shop){
					if($shop->shopid == $shop_id){
						$nowShopInfo = $shop;
						break;
					}
				}
			}


	//以降コンテンツ表示WpTouchの記述内容
	        $content = $post->post_content;
	        $content = apply_filters('the_content', $content);
	        $content = str_replace(']]>', ']]&gt;', $content);

	// 改行タグへ変換{br}
			$content = str_replace("{br}","<br />",$content);

	//コンテンツ表示WpTouchの記述内容終わり
	//Atom用
			if(isset($nowshop_slug)){
				$option_value = $wpdb->get_results("SELECT option_value FROM wp_".$nowBlogId."_options WHERE option_name='blogname'");
				$place = $option_value[0]->option_value." ".$nowshop[0]['name'];
				$user_id = $wpdb->get_results("SELECT ID FROM wp_users WHERE user_login='".$nowshop_slug."'");
				$meta_value = $wpdb->get_results("SELECT meta_value FROM wp_usermeta WHERE user_id='".$user_id[0]->ID."' AND meta_key='first_name'");
				$shop_id = $meta_value[0]->meta_value;
				if(isset($shop_id)){
					$atomNowShop = loadShoplist($shop_id);
					$address = $atomNowShop->address;
					$coverage = $atomNowShop->longitude.",".$atomNowShop->latitude;
				}
			}

			$path = $wpdb->get_results("SELECT path FROM wp_blogs WHERE blog_id='".$nowBlogId."'");
			$path = str_replace("/","",$path[0]->path);
			$guidPath = $path."_".$post->ID;

			//画像判定
			$url  = '';
			$type = '';

			$postcontent = apply_filters('the_content', $post->post_content);
			$postcontent = str_replace(']]>', ']]&gt;', $postcontent);
			
			$postcontent = preg_replace("/<p class=\"wp-caption-text\">(.*?)<\/p>/","<p class=\"wp-caption-text\"><br /><br />\\1<br /><br /></p>",$postcontent);
			$description = $postcontent;
			$pattern1 = "/<a.*?=\"(.*?)\">\n*<img.*?src=\"(.*?)\"/i";
			$pattern2 = "/<img.*?src=\"(.*?)\"/i";
			preg_match_all($pattern1, $postcontent, $getsrc);
			preg_match_all($pattern2, $postcontent, $getattach);
			if(count($getattach)) $img = $getattach[1][0];

			if(count($getsrc[1][0])){
				if(preg_match("/attachment_id=[0-9]+/",$getsrc[1][0])){
					preg_match_all("/attachment_id=([0-9]+)/", $getsrc[1][0], $attachid);
					$attach_id = $attachid[1][0];
					$guid = $wpdb->get_results("SELECT guid FROM wp_".$site->blog_id."_posts WHERE ID='".$attach_id."'");
					if(count($guid)) $img = $guid[0]->guid;
				}else{
					$img = $getsrc[1][0];
				}
			}
			
			if(isset($img)){
				if(strstr($img,".jpg") || strstr($img,".jpeg") || strstr($img,".JPG")){
					$url  = $img;
					$type = "image/jpg";
				}elseif(strstr($img,".png")){
					$url  = $img;
					$type = "image/png";
				}else{
					$url  = "http://".$_SERVER["HTTP_HOST"]."/images/noimage/".$path.".jpg";
					$type = "image/jpg";
				}
			}
			$guidUrl = "http://".$_SERVER["SERVER_NAME"]."/sp/cache/tmp/".$brandPath."/".$post->ID.".html";
	//Atom用


	//記事内の改行コード削除
            $content = preg_replace('/[\x00-\x1F\x7F]/', '', $content);

			$content = str_replace(array("\r\n","\r","\n"), '', $content);

			$atomData[$postNo]['path'] = $guidPath;
			//$atomData[$postNo]['pubDate'] = date(DATE_RFC822,strtotime($post->post_date) - 9 * 3600);
			$atomData[$postNo]['pubDate'] = strtotime($post->post_date);
			$atomData[$postNo]['title'] = the_title("","",FALSE);
            $atomData[$postNo]['title'] = preg_replace('/[\x00-\x1F\x7F]/', '', $atomData[$postNo]['title']);
			$atomData[$postNo]['content'] = $content;
			$atomData[$postNo]['url'] = $url;
			$atomData[$postNo]['guidUrl'] = $guidUrl;
			$atomData[$postNo]['place'] = $place;
			$atomData[$postNo]['address'] = $address;
			$atomData[$postNo]['coverage'] = $coverage;
			$atomData[$postNo]['chgDate'] = date(strtotime(get_the_modified_date('y-m-d H:i:s')));
			$postNo++;
			wponw::restore_blog_and_postdata();
		}
		//キャッシュ用ディレクトリパス
		$atomDir = dirname(__FILE__)."/atom/";

		//ブランドディレクトリなければ作る
		if(!is_dir($atomDir)){
			$rc = mkdir($atomDir, 0777);
			chmod($atomDir, 0777);
		}

		//Atom作成
		ob_start();
		echo '<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/"'."\n";
		echo 'xmlns:sekaicamera="http://new.sekaicamera.com/xml-elements/1.0/">'."\n";
		echo '<channel>'."\n";
		foreach($atomData as $brandAtomData => $dataValue){
			$atomMergeContent = @include($atomTmpl);
			$atomMergeContent = ob_get_contents();
		}
		ob_clean();

		$atomMergeContent .='</channel></rss>';



		//生成ファイル
		$makeFile = dirname(__FILE__)."/atom/".$brandPath.".xml";

		$handle = fopen( $makeFile, 'w+');
		fwrite( $handle, $atomMergeContent);
		fclose( $handle );
		chmod($makeFile,0755);

		$atomData = array();
	}
}
wp_reset_query();
