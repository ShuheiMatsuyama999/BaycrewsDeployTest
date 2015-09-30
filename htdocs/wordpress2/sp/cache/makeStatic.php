<?php
/*
簡易表示用テンプレート
*/

$hostName = 'http://'.$_SERVER['SERVER_NAME'].'/';
$docRoot = $_SERVER['DOCUMENT_ROOT'].'/';

require('../../wp-load.php');

global $wpdb;
global $post;

if(isset($_GET['brand']) && isset($_GET['id'])){
	$getBrand = $wpdb->escape($_GET['brand']);
	$getId    = $wpdb->escape($_GET['id']);
}else{
	header('Location: http://'.$_SERVER['SERVER_NAME'].'/');
}

//テンプレートファイル
$tmpl = "./cacheTemplate.php";

//画像幅
$imageWidth = 296;

//取得記事数
$maxPostNum = 50;

//html用ディレクトリパス
$htmlDir = dirname(__FILE__)."/tmp/";

//html用ディレクトリなければ作る
if(!is_dir($htmlDir)){
	$rc = mkdir($htmlDir, 0777);
	chmod($htmlDir, 0777);
}


//全マルチサイト情報
$allBlogs = wponw::get_blogs( $args );

//blog_id取得
$blogCount = 0;
foreach($allBlogs as $blogs){
	$brandPath = str_replace("/", "", $blogs->path);
	if($getBrand == $brandPath){
		$nowBlogId = $blogs->blog_id;
		break;
	}
	$blogCount++;
}

//マルチサイト記事取得
$posts = wponw::get_posts('numberposts='.$maxPostNum.'&affect_wp_query=true&exclude_blog_ids=1&blog_ids='.$nowBlogId);

//記事の数ループ
if ( ! empty ( $posts ) ){
	foreach ( $posts as $post ){
		wponw::setup_blog_and_postdata( $post );

		if($post->ID == $getId){
			//ブランドname
			$brandPath = str_replace("/", "", $allBlogs[$blogCount]->path);

			$nowBlogInfo = $wpdb->get_results("SELECT option_value FROM wp_".$nowBlogId ."_options ");
			$brandInfo['name'] = $nowBlogInfo[1]->option_value;

			//以降コンテンツ表示WpTouchの記述内容

			$pnum = (isset($_GET['pnum'])) ? htmlspecialchars($_GET['pnum']) : 1;
			$nowshop = array();
			$limited_flag = 0;
			$shop_num = 0;
			$taxonomy_id = $wpdb->get_results("SELECT term_taxonomy_id FROM wp_".$nowBlogId."_term_relationships WHERE object_id='".$post->ID."'");
			$shopinfo_id = $wpdb->get_results("SELECT term_id FROM wp_".$nowBlogId."_terms WHERE slug='shop_info'");
			if(count($shopinfo_id)) $shopinfo_id = $shopinfo_id[0]->term_id;
			$limited_id = $wpdb->get_results("SELECT term_id FROM wp_".$nowBlogId."_terms WHERE slug='limited'");
			if(count($limited_id)) $limited_id = $limited_id[0]->term_id;
			foreach($taxonomy_id as $tid){
				$parent_id = $wpdb->get_results("SELECT parent FROM wp_".$nowBlogId."_term_taxonomy WHERE term_taxonomy_id='".$tid->term_taxonomy_id."'");
				if($shopinfo_id == $parent_id[0]->parent){
						$shop_slug = $wpdb->get_results("SELECT name,slug FROM wp_".$nowBlogId."_terms WHERE term_id='".$tid->term_taxonomy_id."'");
					$nowshop[$shop_num]['name'] = $shop_slug[0]->name;
					$nowshop[$shop_num]['slug'] = $shop_slug[0]->slug;
					$shop_num += 1;
				}
			}



	        $content = $post->post_content;
	        $content = apply_filters('the_content', $content);
	        $content = str_replace(']]>', ']]&gt;', $content);

	//商品IDが見つかった場合ECへリンク
	//処理が長いので開発次コメントアウト
			$iteminfo = array();
			$p_num = 0;
			preg_match_all("/<p class=\"wp-caption-text\">(.*?)<\/p>/u",$content,$pickcaps);
			$itemstock = array();
			foreach($pickcaps[0] as $onecap){
				$picknums = array();
				preg_match_all("/([０-９0-9]+([\-−]|[０-９0-9])*[０-９0-9])/u",$onecap,$onenum);
				$nowcaption = $onecap;
				foreach($onenum[1] as $pn){
					$getex = $pn;
					$getex = str_replace("-","",$getex);
					$getex = str_replace("ー","",$getex);
					$getex = str_replace("−","",$getex);
					$getex = mb_convert_kana($getex,n,"UTF-8");
					if(preg_match("/[0-9]{14}/i",$getex)){
						if(in_array($getex,$itemstock)){
							continue;
						}else{
							array_push($itemstock,$getex);
						}

						//$itemlist = simplexml_load_file("http://style-cruise.jp/ec/c_api/getItemData.php?id=".$getex."&detaildiv=1");
						$scLink = "http://style-cruise.jp/ec/c_api/getItemData.php?id=".$getex."&detaildiv=1";
						$scLinkCheck = get_headers($scLink);
						if(preg_match("/OK$/",$scLinkCheck[0])){
							$getcontents = @file_get_contents($scLink);
							$getcontents = mb_convert_encoding($getcontents,"UTF-8");
							$itemlist = simplexml_load_string( $getcontents, 'SimpleXMLElement', LIBXML_NOCDATA );
							$itemlink = $itemlist->itemlist->item->sitelinkurl;
							if($itemlink){
								$itemlink = "{$pn} <a href=\"{$itemlink}\" class=\"blank\" target=\"_blank\">詳細</a>";
								$onecap = str_replace($pn,$itemlink,$onecap);
								$iteminfo[$p_num]->image = $itemlist->itemlist->item->mainthumnail;
								$iteminfo[$p_num]->link = $itemlist->itemlist->item->sitelinkurl;
								$iteminfo[$p_num]->name = $itemlist->itemlist->item->itemname;
								$price = intval($itemlist->itemlist->item->price);
								$iteminfo[$p_num]->price = number_format($price);
								$iteminfo[$p_num]->color = $itemlist->itemlist->item->skulist->sku->colorname;
								$p_num += 1;
							}
						}
					}
				}
				$content = str_replace($nowcaption,$onecap,$content);
			}
//

			//画像拡大表示
			preg_match_all("/href=\"((.*?)\.(jpg|JPG|jpeg|gif|png))\"/",$content,$hrefmatch);
			//$attaches = get_posts('post_type=attachment&posts_per_page=-1');
			$hrefmatch_all = $hrefmatch[0];
			foreach($hrefmatch[1] as $key => $hf){
				$imglink = "href=\"".$hf."\"";
				$content = str_replace($hrefmatch_all[$key],$imglink,$content);
			}
			preg_match_all("/href=\".*?attachment_id=([0-9]+)\"/",$content,$atcmatch);
			$atcmatch_all = $atcmatch[0];
			foreach($atcmatch[1] as $key => $am){
				$image_src = wp_get_attachment_image_src( $am, 'medium' );
				$imglink = "href=\"".$image_src[0]."\"";
				$content = str_replace($atcmatch_all[$key],$imglink,$content);
			}

			//商品枠拡大
			$content = preg_replace("/style=\"width:\s([0-9]+)px\">/","style=\"width:100%;text-align:center;\">",$content);
			$content = preg_replace("/<p><a(.*?)><img/i","<p style=\"text-align:center;\"><a\\1><img",$content);

			//画像センター寄せ「<dt><a><img>」バージョン
			preg_match_all("/<dt>(<a.*?><img)/",$content,$getimgs);
			for($i=0;$i<count($getimgs[0]);$i++){
				$replace_img = "<dt style=\"text-align:center;\">".$getimgs[1][$i];
				$content = str_replace($getimgs[0][$i],$replace_img,$content);
			}

			//画像幅調整
			preg_match_all("/width=\"([0-9]+)\" height=\"([0-9]+)\"/",$content,$content_size);
			foreach($content_size[0] as $key => $cs){
				$width = intval($content_size[1][$key]);
				$height = intval($content_size[2][$key]);
				if($width>$imageWidth){
					$height = $imageWidth * ( $height / $width);
					$height = floor($height);
					$width = $imageWidth;
					$content = str_replace($cs,"width=\"{$width}\" height=\"{$height}\"",$content);
				}
			}

			// 改行タグへ変換{br}
			$content = str_replace("{br}","<br />",$content);

			//コンテンツ表示WpTouchの記述内容終わり

			//記事内の改行コード削除
			$content = str_replace(array("\r\n","\r","\n"), '', $content);

			//静的詳細ファイル生成
			ob_start();
			$mergeContent = @include($tmpl);
		    $mergeContent = ob_get_contents();
		    ob_clean();

			//キャッシュ用ディレクトリパス
			$cacheDir = $htmlDir.$brandPath."/";
			//ブランドディレクトリなければ作る
			$newDir = $htmlDir.$brandPath;

			//ディレクトリ削除　ファイル洗い替え
//			system("rm -rf {$newDir}");

			if(!is_dir($cacheDir)){
				$rc = mkdir($newDir, 0777);
				chmod($newDir, 0777);
			}
			//生成ファイル
			$makeFile = $cacheDir.$post->ID.".html";
			$makedID = $post->ID;

			$handle = fopen( $makeFile, 'w+');
			fwrite( $handle, $mergeContent);
			fclose( $handle );
			chmod($makeFile,0755);
		}

			wponw::restore_blog_and_postdata();
	}
}
wp_reset_query();

if(isset($mergeContent)){
	header('Location: http://'.$_SERVER['SERVER_NAME'].'/sp/cache/tmp/'.$brandPath.'/'.$makedID.'.html');
}else{
	header('Location: http://'.$_SERVER['SERVER_NAME'].'/');
}