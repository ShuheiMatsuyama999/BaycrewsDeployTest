<?php
require('./wp-blog-header.php');
$blog_num = htmlspecialchars($_GET['b_id']);
$post_num = htmlspecialchars($_GET['p_id']);
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
</head>
<body id="inpage">
<a name="top" id="top"></a>
<article id="container" class="detail">
	<header>
    	<h1><img src="/sp/images/common/sub_logo.gif" width="100%" alt="BAYCREW'S GROUP DAILY BLOG" /></h1>

	<section class="contents">
	<div class="cheaderText">店頭でこの画面をスタッフにお見せください。</div>
   	<section class="post">

    <section class="memo">
    	<div class="title clear">
    		<h2>お買い物メモ</h2>
        </div>
<?php
if($blog_num && $post_num){
	switch_to_blog($blog_num);
	$getposts = get_posts('numberposts=-1');
	$nowpost = array();
	foreach($getposts as $post){
		if($post->ID == $post_num){
			$content = $post->post_content;
			$nowpost = $post;
		}
	}
}

$getcategory = get_the_category($nowpost->ID);
$nowdate = strtotime($nowpost->post_date);
$nowshop = "";
foreach($getcategory as $cat){
	$parent = get_category($cat->parent);
	if($parent->slug == "shop_info") $nowshop = $cat->name;
}

preg_match_all("/<img .*?src=[\'\"]*([^ >]+?)[\'\"]*[ >]/i", $nowpost->post_content, $getimgs);

preg_match_all("/\[caption.*?\](.*?)\[\/caption\]/i", $nowpost->post_content, $captags);
echo "<!--";
echo $nowpost->post_content;
echo "-->\n";

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
	list($width, $height, $type, $attr) = getimagesize($memo);
	if($width>523) $imgwidth = " width=\"523\"";
}
?>
<div class="inner">
	<?php if($key==0){ ?><p class="date"><?php echo date('Y.m.d.D',$nowdate); ?><?php if($nowshop) echo "/ FROM {$nowshop}"; ?></p><?php } ?>
	<div class="photo"><img src="<?php echo $memo; ?>"<?php echo $imgwidth; ?> alt="" /></div>
	<p class="txt"><?php echo $nowgetmemo_cap[$key]; ?>
			</p>
			<hr class="memo_border"/>
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
$linkobj = get_term_by('slug', 'coupon', 'link_category');
$getlinks = get_bookmarks('category='.$linkobj->term_id);
foreach($getlinks as $links){
	echo "<img src=\"".$links->link_image."\" alt=\"".$links->link_name."\" /><br />\n";
}
restore_current_blog();
?>
</p>
	</div>
    </section>
	</section>

	<footer class="clear">
        <p class="copy">©BAYCREW’S CO.,LTD. ALL RIGHTS RESERVED.</p>
	</footer>
</article>
</body>
</html>