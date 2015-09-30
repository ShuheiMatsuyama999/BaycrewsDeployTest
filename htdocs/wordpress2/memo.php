<?php
require('./wp-blog-header.php');
global $wpdb;
$blog_num = htmlspecialchars($_GET['b_id']);
$post_num = htmlspecialchars($_GET['p_id']);
$sites = $wpdb->get_results("SELECT path FROM wp_blogs WHERE blog_id='".$blog_num."'");
$blogoption = $wpdb->get_results("SELECT option_value FROM wp_".$blog_num."_options WHERE option_name='blogname'");
$blogpath = str_replace("/","",$sites[0]->path);
$blogname = $blogoption[0]->option_value;
$parent_id = $wpdb->get_results("SELECT term_id FROM wp_".$blog_num."_terms WHERE slug='shop_info'");

$nowshop = "";
if($blog_num && $post_num){
	$getposts = $wpdb->get_results("SELECT * FROM wp_".$blog_num."_posts WHERE ID='".$post_num."'");
	$nowpost = array();
	$content = $getposts[0]->post_content;
	$nowpost = $getposts[0];
	$nowdate = strtotime($getposts[0]->post_date);

	$taxonomy_id = $wpdb->get_results("SELECT term_taxonomy_id FROM wp_".$blog_num."_term_relationships WHERE object_id='".$post_num."'");
	if(isset($taxonomy_id)){
		foreach($taxonomy_id as $tax){
			$term_id = $wpdb->get_results("SELECT term_id,parent FROM wp_".$blog_num."_term_taxonomy WHERE taxonomy='category' AND term_taxonomy_id='".$tax->term_taxonomy_id."'");
			if(isset($term_id)){
				foreach($term_id as $ter){
					if($parent_id[0]->term_id == $ter->parent){
						$shop_name = $wpdb->get_results("SELECT name FROM wp_".$blog_num."_terms WHERE term_id='".$ter->term_id."'");
						$nowshop = $shop_name[0]->name;
					}
				}
			}
		}
	}

	$coupon_id = $wpdb->get_results("SELECT term_id FROM wp_".$blog_num."_terms WHERE slug='coupon'");
	$object_id = $wpdb->get_results("SELECT object_id FROM wp_".$blog_num."_term_relationships WHERE term_taxonomy_id='".$coupon_id[0]->term_id."'");
	$coupons = array();
	foreach($object_id as $oj){
		$getcoupon = $wpdb->get_results("SELECT * FROM wp_".$blog_num."_links WHERE link_id='".$oj->object_id."'");
		array_push($coupons,$getcoupon[0]);
	}
}

$user_meta = $wpdb->get_results("SELECT meta_key,meta_value FROM wp_usermeta WHERE meta_key='wp_".$blog_num."_capabilities' AND user_id='".$getposts[0]->post_author."'");
$addcaption = "";
foreach($user_meta as $um){
	if(strstr($um->meta_value,"shopeditor")){
		$addcaption = " / FROM:".$blogname." ".$nowshop;
	}else{
		$addcaption = " / FROM:".$blogname;
	}
}

$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match("/^DoCoMo/i", $agent) || preg_match("/^(J\-PHONE|Vodafone|MOT\-[CV]|SoftBank)/i", $agent) || preg_match("/^KDDI\-/i", $agent) || preg_match("/UP\.Browser/i", $agent)){

$brandslist = array(
2=>"http://js-m.jp/m/",
3=>"http://js-f.jp/l/",
4=>"http://js-f.jp/x/",
5=>"http://js-f.jp/g/",
6=>"http://js-m.jp/r/",
7=>"http://js-f.jp/r/",
8=>"http://js-m.jp/h/",
9=>"http://js-m.jp/v/",
10=>"http://spick.jp/s/",
11=>"http://spick.jp/f/",
12=>"http://spick.jp/n/",
13=>"http://m.iena.jp/i/",
14=>"http://m.iena.jp/s/",
15=>"http://m-edifice.jp/e/",
16=>"http://m-edifice.jp/417/",
17=>"http://m.deuxieme-classe.jp/dc/",
18=>"http://m.deuxieme-classe.jp/ap/",
19=>"http://m.editforlulu.jp/",
20=>"http://m.figaro-paris.jp/",
21=>"http://m.la-totalite.jp/",
22=>"http://m.limitless-luxury.jp/",
23=>"http://m.bcstock.jp/",
24=>"http://m.ivory-court.jp/",
25=>"http://m.hirob.jp/",
26=>"http://m.letalon.jp/",
27=>"http://m.acme.co.jp/",
28=>"http://m.js-furniture.jp/",
29=>"http://m.journal-cafe.jp/b/",
30=>"http://m.journal-cafe.jp/p/",
31=>"http://m.baycrews.co.jp/curry/",
32=>"http://m.baycrews.co.jp/cafe-ville/",
33=>"http://m.bodybyvital.jp/",
34=>"http://m.baycrews.co.jp/reformworks/",
36=>"",
37=>"",
38=>"",
39=>"",
40=>"");

$getbrandurl = $brandslist[$blog_num];
$template = @file_get_contents($getbrandurl, false, stream_context_create(array("http"=>array("header"=>"User-Agent: DoCoMo/2.0 P903i"))));
$template = mb_convert_encoding($template,"UTF-8","SJIS");
preg_match_all('/<body text="#([0-9a-zA-Z]{6})" bgcolor="#([0-9a-zA-Z]{6})" link="#([0-9a-zA-Z]{6})" alink="#([0-9a-zA-Z]{6})" vlink="#([0-9a-zA-Z]{6})">/',$template,$setcolors);
preg_match_all('/<div style="background-color:#([0-9a-zA-Z]{6});">/',$template,$bgcolor);
if( $getbrandurl == "" ){
	$template = @file_get_contents('http://concent:VGTDCYOo@'.$_SERVER["HTTP_HOST"].'/mb/'.$blogpath.'/index.html');
	$template = mb_convert_encoding($template,"UTF-8","SJIS");
	preg_match_all('/background-color:#([0-9a-zA-Z]{6});color:#333;/',$template,$background);
	preg_match_all('/background:#([0-9a-zA-Z]{6});color:#fff;/',$template,$titlecolor);
	$setcolors[2][0] = $background[1][0];
	$setcolors[1][0] = "333";
}

// $GA_PIXEL = get_bloginfo('wpurl') . '/ga.php'; // when ga.php is under WP directory
function googleAnalyticsGetImageUrl() {
	global $GA_ACCOUNT,$GA_PIXEL;
	$GA_ACCOUNT = "MO-2740917-26";
	$GA_PIXEL = "ga.php";
	$url = "http://blog.baycrews.co.jp/";
	$url .= $GA_PIXEL . "?";
	$url .= "utmac=" . $GA_ACCOUNT;
	$url .= "&utmn=" . rand(0, 0x7fffffff);
	$referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
	$query = isset($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : '';
	$path = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
	if (empty($referer)) {
		$referer = "-";
	}
	$url .= "&utmr=" . urlencode($referer);
	if (!empty($path)) {
		$url .= "&utmp=" . urlencode($path);
	}
	$url .= "&guid=ON";
	return str_replace("&", "&amp;", $url);
}

ob_start('mb_output_handler');
header("Content-type: application/xhtml+xml;charset=Shift_JIS");
echo '<?xml version="1.0" encoding="Shift_JIS"?>'."\n";
?>
<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/1.0) 1.0//EN" "i-xhtml_4ja_10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=Shift_JIS" />
<title>BAYCREW'S GROUP DAILY BLOG</title>
<meta name="Description" content="" />
<meta name="keywords" content="" />
<meta http-equiv="Cache-Control" content="no-cache" />
<style type="text/css">
<![CDATA[
a:link {color:#fff; ?>;}
a:visited {color:#fff; ?>;}
]]>
</style>
</head>
<?php echo "<body style=\"background-color:#".$setcolors[2][0].";color:#".$setcolors[1][0].";\" text = \"#".$setcolors[1][0].'" bgcolor="#'.$setcolors[2][0].'">'; ?>
<a name="top" id="top"></a>
<!-- header -->
<div style="background-color:#<?php echo $bgcolor[1][0]; ?>;font-size:xx-small;">
<div style="background:#fff;text-align:center;">
	<img src="/mb/img/logo.gif" alt="BAYCREW'S GROUP DAILY BLOG" />
</div>
<img src="/mb/img/sp.gif" alt="" width="1" height="5" /><br />
<div><?php echo mb_convert_encoding("店頭でこの画面をｽﾀｯﾌにお見せください。","sjis-win","UTF-8"); ?></div>
<img src="/mb/img/sp.gif" alt="" width="1" height="5" /><br />
<!-- /header -->

<!-- contents -->

<div style="background:#787878;color:#fff;">
<?php echo mb_convert_encoding("お買い物ﾒﾓ","sjis-win","UTF-8"); ?><br />
</div>

<?php
	preg_match_all("/<img .*?src=[\'\"]*([^ >]+?)[\'\"]*[ >]/i", $nowpost->post_content, $getimgs);
	preg_match_all("/\[caption.*?\](.*?)\[\/caption\]/i", $nowpost->post_content, $captags);

	$nowgetmemo_src = array();
	$nowgetmemo_cap = array();
	foreach($getimgs[1] as $key => $mi){
		$nowgetmemo_src[$key] = $mi;
		$nowgetmemo_cap[$key] = "";
		foreach($captags[0] as $tags){
			if(strstr($tags,$mi)){
				preg_match_all("/caption=[\'\"]*([^]]+?)[\'\"]*[]]/i", $tags, $memo_cap);
				$nowgetmemo_cap[$key] = $memo_cap[1][0];
			}
		}
	}

foreach($nowgetmemo_src as $key => $memo){

	$resultimg = $memo;
	preg_match_all("/\.jpg|\.jpeg|\.JPG|\.gif|\.png/",$resultimg,$kakucho);
	$resultimg = preg_replace("/-[0-9]{2,}x[0-9]{2,}\./",".",$resultimg);

	$geturl_arr = explode("/",$resultimg);
	$geturl_max = count($geturl_arr) - 1;
	$imgdir = $_SERVER['DOCUMENT_ROOT']."/wp-content/blogs.dir/".$blog_num."/files/";
	$file_name = $geturl_arr[$geturl_max];
	$file_url = $imgdir.$file_name;
	$file_name = preg_replace("/\\".$kakucho[0][0]."/","",$file_name);
	$res_dir = @opendir($imgdir);
	while( $filelist = @readdir( $res_dir ) ){
		$filelist_check = $filelist;
		preg_match_all("/\.jpg|\.jpeg|\.JPG|\.gif|\.png/",$filelist_check,$list_kakucho);
		$filelist_check = preg_replace("/-[0-9]{2,}x[0-9]{2,}\./",".",$filelist_check);
		$filelist_check = preg_replace("/\\".$list_kakucho[0][0]."/","",$filelist_check);
		if($filelist_check == $file_name && $list_kakucho[0][0] == $kakucho[0][0]){
			if(preg_match("/-150x/",$filelist)){
				$memo = "http://".$_SERVER["HTTP_HOST"]."/".$blogpath."/files/".$filelist;
				break;
			}else{
				$memo = "http://".$_SERVER["HTTP_HOST"]."/".$blogpath."/files/".$filelist;
			}
		}
	}

	$ua = $_SERVER['HTTP_USER_AGENT'];
	if((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false)){
		$imgwidth = " width=\"100%\"";
	}else{
		list($width, $height, $type, $attr) = @getimagesize($memo);
		if($width>523) $imgwidth = " width=\"523\"";
	}
	if(!$imgwidth) $imgwidth = " width=\"150\"";
	
	
?>
	<?php if($key == 0){ ?>
	<img src="/mb/img/sp.gif" alt="" width="1" height="5" /><br />
	<div style="font-size:xx-small;"><?php echo date('Y.m.d.D.',$nowdate); ?><?php $addcaption = mb_convert_kana($addcaption,k,"UTF-8"); echo mb_convert_encoding($addcaption,"sjis-win","UTF-8"); ?><br /></div>
	<img src="/mb/img/sp.gif" alt="" width="1" height="5" /><br />
	<?php } ?>
	<div style="text-align:center;"><img src="<?php echo $memo; ?>"<?php echo $imgwidth; ?> /><br /></div>
	<div><img src="/mb/img/sp.gif" alt="" width="1" height="3" /><br />
	<?php
		$caption_txt = mb_convert_kana($nowgetmemo_cap[$key],k,"UTF-8");
		$caption_txt = mb_convert_encoding($caption_txt,"sjis-win","UTF-8");
		$caption_txt = str_replace("{br}","<br />",$caption_txt);
		echo $caption_txt; ?>
	</div>
	<div style="text-align:center;"><img src="/mb/img/sp.gif" alt="" width="1" height="10" /><br /><img src="/mb/img/dot.gif" alt="" /><br /><img src="/mb/img/sp.gif" alt="" width="1" height="10" /></div>
	<?php } ?>

<img src="/mb/img/sp.gif" alt="" width="1" height="15" /><br />
<div style="text-align:center;">
<?php
//$linkobj = get_term_by('slug', 'coupon', 'link_category');
//$getlinks = get_bookmarks('category='.$linkobj->term_id);
foreach($coupons as $links){
	echo "<img src=\"".$links->link_image."\" alt=\"".$links->link_name."\" width=\"100%\" /><br />\n";
}
if(count($coupons)){
?>
<img src="/mb/img/dot.gif" alt="" /><br />
<img src="/mb/img/sp.gif" alt="" width="1" height="5" /><br />
<?php
}
?>
</div>

<!-- footer -->
<div style="background-color:#333;color:#fff;">
<img src="/mb/img/sp.gif" alt="" width="1" height="5" /><br />
<img src="/mb/img/arr_white.gif" alt="" width="11" height="11" style="margin-top:5px;margin-right:5px;margin-bottom:3px;margin-left:3px;" /><a href="/" style="color:#fff;"><span color="#fff">DAILY BLOG</span></a><br />
<img src="/mb/img/arr_white.gif" alt="" width="11" height="11" style="margin-top:5px;margin-right:5px;margin-bottom:3px;margin-left:3px;" /><a href="<?php echo "/".$blogpath."/"; ?>" style="color:#fff;"><span color="#fff"><?php echo mb_convert_encoding($blogname."の記事一覧","sjis-win","UTF-8"); ?></span></a><br />
<img width="1" height="3" alt="" src="/mb/img/sp.gif">
</div>
<!-- /footer -->

</div>
<!-- /contents -->
<?php
$googleAnalyticsImageUrl = googleAnalyticsGetImageUrl();
echo '<img src="' . $googleAnalyticsImageUrl . '" />';?>
</body>
</html>
<?php
}else{
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="content-script-type" content="text/javascript">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title>BAYCREW'S GROUP DAILY BLOG</title>
<link rel="stylesheet" href="/sp/css/layout.css" type="text/css">
<link rel="stylesheet" href="/sp/css/general.css" type="text/css">
<link rel="stylesheet" href="/sp/css/jquery.mobile-1.0.1.css" type="text/css">
<link rel="stylesheet" href="/sp/css/main.css" type="text/css">
<link rel="stylesheet" href="/sp/css/press.css" type="text/css">
<script type="text/javascript" src="/sp/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript">
jQuery(document).bind("mobileinit", function(){
    jQuery.mobile.ajaxEnabled = false;
});
</script>
<script type="text/javascript" src="/sp/js/jquery.mobile-1.0.1/jquery.mobile-1.0.1.js"></script>
<script type="text/javascript" src="/sp/js/smartphone.js"></script>
<script type="text/javascript" src="/sp/js/smoothScroll.js"></script>
<script type="text/javascript">  
	function doScroll() { if (window.pageYOffset === 0) { window.scrollTo(0,1); } }  window.onload = function() { setTimeout(doScroll, 100); }  
</script>
<script type="text/javascript"> 
$(function(){
     $(".linkBox").click(function(){
         window.location=$(this).find("a").attr("href");
         return false;
    });
});
</script>

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

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-N5WZ4B"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N5WZ4B');</script>
<!-- End Google Tag Manager -->

</head>
<body id="inpage">
<a name="top" id="top"></a>
<article id="container" class="detail">
	<header>
    	<h1><img src="/sp/images/common/sub_logo.gif" width="100%" alt="BAYCREW'S GROUP DAILY BLOG" /></h1>

	<section class="contents">
	<div class="cheaderText" style="margin: 20px 20px 20px;">店頭でこの画面をスタッフにお見せください。</div>
   	<section class="post">

    <section class="memo">
    	<div class="title clear">
    		<h2>お買い物メモ</h2>
        </div>
<?php
if($blog_num && $post_num){
	switch_to_blog($blog_num);
}


preg_match_all("/<img .*?src=[\'\"]*([^ >]+?)[\'\"]*[ >]/i", $nowpost->post_content, $getimgs);
preg_match_all("/\[caption.*?\](.*?)\[\/caption\]/i", $nowpost->post_content, $captags);

$nowgetmemo_src = array();
$nowgetmemo_cap = array();
foreach($getimgs[1] as $key => $mi){
	$nowgetmemo_src[$key] = $mi;
	$nowgetmemo_cap[$key] = "";
	foreach($captags[0] as $tags){
		if(strstr($tags,$mi)){
			preg_match_all("/caption=[\'\"]*([^]]+?)[\'\"]*[]]/i", $tags, $memo_cap);
			$nowgetmemo_cap[$key] = $memo_cap[1][0];
		}
	}
}

foreach($nowgetmemo_src as $key => $memo){
$ua = $_SERVER['HTTP_USER_AGENT'];
if((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false)){
	$imgwidth = " width=\"100%\"";
}else{
	list($width, $height, $type, $attr) = @getimagesize($memo);
	if($width>523) $imgwidth = " width=\"523\"";
}
?>
<div class="inner">
	<?php if($key==0){ ?><p class="date"><?php echo date('Y.m.d.D.',$nowdate); ?><?php echo $addcaption; ?></p><?php } ?>
	<div class="photo"><img src="<?php echo $memo; ?>"<?php echo $imgwidth; ?> alt="" /></div>
	<p class="txt"><?php
	$nowgetmemo_cap[$key] = str_replace("{br}","<br />",$nowgetmemo_cap[$key]);
	echo $nowgetmemo_cap[$key];
?>
			</p>
			<?php if(count($nowgetmemo_src)-1 != $key){ ?><hr class="memo_border"/><?php } ?>
</div>
<?php
}
?>
    </section>
	</section>

    <section class="memo_coupon">
    <div class="mamo_coupon">
	<p class="date">
<?php
//$linkobj = get_term_by('slug', 'coupon', 'link_category');
//$getlinks = get_bookmarks('category='.$linkobj->term_id);
foreach($coupons as $links){
	echo "<img src=\"".$links->link_image."\" alt=\"".$links->link_name."\" /><br />\n";
}
?>
</p>
	</div>
    </section>
	</section>
<?php
//スマートフォンの場合ここから
$ua = $_SERVER['HTTP_USER_AGENT'];
if((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false)) {
?>
<!-- Only Smartphone Start -->
<p class="back linkBox"><a href="/" class="ui-link"><img src="/sp/images/common/home_img_01.gif" width="125" height="15" alt="Home"></a></p><hr class="fLink" />
<p class="back linkBox linkBoxList"><a href="<?php echo "/".$blogpath."/"; ?>" class="ui-link"><?php echo $blogname; ?>の記事一覧</a></p>
<!-- Only Smartphone End -->
<?php
}
//スマートフォンの場合ここまで
restore_current_blog();
?>
	<footer class="clear">
        <p class="copy">&copy;BAYCREW’S CO.,LTD. ALL RIGHTS RESERVED.</p>
	</footer>
</article>
</body>
</html>
<?php
}
?>