<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta charset="UTF-8">
<meta http-equiv="content-script-type" content="text/javascript">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="format-detection" content="telephone=no">
<title><?php the_title() ?> | <?php echo $brandInfo['name']; ?> 公式ブログ</title>
<link rel="stylesheet" href="/sp/css/layout.css" type="text/css">
<link rel="stylesheet" href="/sp/css/general.css" type="text/css">
<link rel="stylesheet" href="/sp/css/jquery.mobile-1.0.1.css" type="text/css">
<link rel="stylesheet" href="/sp/css/main.css" type="text/css">
<link rel="stylesheet" href="/sp/css/static/static.css" type="text/css">
<!-- Google Analytics -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-2740917-1']);
  _gaq.push(['_setDomainName', 'baycrews.co.jp']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<!-- //Google Analytics -->
</head>
<body id="inpage" class=" brandpage">
<a name="top" id="top"></a>
<div id="map_canvas"></div>
<article id="container" class="detail">
<section class="contents">
<section class="post">
<section class="news">
<div class="">
<div class="title clear"><h2><?php the_title() ?></h2></div>
<div class="inner">
<p class="date"><?php echo date('Y.m.d.D.',strtotime($post->post_date)); ?></p>
<p class="txt"><?php echo $content; ?></p>
<?php
if(count($nowshop) > 1){
	echo '<p class="from">From '.get_bloginfo('name').'</p>';
}elseif(count($nowshop) == 1){
	echo '<p class="from">From <a href="'.$allBlogs[$blogCount]->path.'?st=shop&getkey='.$nowshop[0]['slug'].'">'.$nowshop[0]['name'].'</a></p>';
}
?>
</div>
</div>
</section>
<?php

if(count($nowshop) == 1){
		$nowshop_slug = $nowshop[0]['slug'];
		$getuser = get_user_by('login',$nowshop_slug);
		$shop_id = get_the_author_meta('user_firstname',$getuser->data->ID);
		if($shop_id){
			$shoplist = simplexml_load_file("http://www.baycrews.co.jp/shop_list/xml/shoplist.xml");
			$nowshop = "";
			foreach($shoplist->shoplist->shop as $shop){
				if($shop->shopid == $shop_id){
					$nowshop = $shop;
					break;
				}
			}
		}
	}
	if($nowshop->open_hours != "" && $nowshop->holiday != ""){
?>
<section class="hiLight">
<div class="shopDetail">
<h5><span><a href="<?php echo $allBlogs[$blogCount]->path; ?>?st=shop&getkey=<?php echo $nowshop_slug; ?>"><?php echo $nowshop->title; ?></a></span></h5>
<div class="shopInfo clearfix">
<dl>
<dt>営業時間</dt>
<dd><?php echo $nowshop->open_hours; ?><br />
<?php echo preg_replace("/<\/+p>/","",$nowshop->open_hours_detail); ?></dd>
<dt>定休日</dt>
<dd><?php echo $nowshop->holiday; ?></dd>
<dt>電話番号</dt>
<dd><?php if($nowshop->tel) echo preg_replace("/(0\d{1,4}-\d{1,4}-\d{4})/", "<a href=\"tel:\\1\">\\1</a>", $nowshop->tel); ?></a></dd>
<dt>住所</dt>
<dd><?php echo $nowshop->address; ?></dd>
</dl>
</div>
<p class="shopMoreLink"><span><a href="<?php echo $allBlogs[$blogCount]->path; ?>?st=shop&getkey=<?php echo $nowshop_slug; ?>&at=1"><?php echo $nowshop->title; ?>の記事一覧</a></span></p>
<p class="otherShops"><span><a href="<?php echo $allBlogs[$blogCount]->path; ?>?shop_list=on">このブランドの他の店舗情報</a></span></p>
</div>
</section>
<?php
	}
?>
</section>
</section>
</article>
</body>
</html>