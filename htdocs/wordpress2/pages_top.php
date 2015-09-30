<?php
require('./wp-blog-header.php');
global $wpdb;
$posts = htmlspecialchars($_GET['posts']);

if($_GET['mode']) $mode = htmlspecialchars($_GET['mode']);
if(isset($_GET['st'])) $st = htmlspecialchars($_GET['st']);
if(isset($_GET['weather'])) $post_weather = htmlspecialchars($_GET['weather']);
if(isset($_GET['temperature'])) $post_temperature = htmlspecialchars($_GET['temperature']);
if(isset($_GET['style'])) $post_style = htmlspecialchars($_GET['style']);

$get_sets = "";
if($st) $get_sets .= "&st={$st}";
if($post_weather) $get_sets .= "&weather={$post_weather}";
if($post_temperature) $get_sets .= "&temperature={$post_temperature}";
if($post_style) $get_sets .= "&style={$post_style}";

if(isset($_GET['sid'])) $sid = htmlspecialchars($_GET['sid']);
if($sid) $disco_sid = "&sid=".$sid;

?>
<script type="text/javascript">
tag_over();
</script>
<?php

$getpage = explode(",",$posts);
foreach($getpage as $gp){
	$nowpage = explode("-",$gp);
	$blog_id = intval($nowpage[0]);
	$post_id = intval($nowpage[1]);
	switch_to_blog($nowpage[0]);
	$blogpath = trim( get_blog_status( $blog_id, 'path' ), '/' );
	$post = $wpdb->get_results("SELECT ID,post_date,post_content,post_title,guid FROM wp_".$blog_id."_posts WHERE ID='".$post_id."' ORDER BY post_date DESC");
	$parent_id = $wpdb->get_results("SELECT term_id FROM wp_".$blog_id."_terms WHERE slug='shop_info'");
	$postdate = strtotime($post[0]->post_date);
	$blognum = $blog_id;
	if($blognum < 10) $blognum = "0".$blognum;
	$getfirst = getfirstimg($nowpage[0], $post[0]->post_content);
	if(!$getfirst) $getfirst = "/images/noimage/".$blogpath.".jpg";
	$taxonomy_id = $wpdb->get_results("SELECT term_taxonomy_id FROM wp_".$blog_id."_term_relationships WHERE object_id='".$post[0]->ID."'");
	//$getcategory = get_the_category($post[0]->ID);
	$liveinfo = 0;
	$limited = 0;
	$shop_name = "";
	$shop_slug = "";
	if(isset($taxonomy_id)){
		foreach($taxonomy_id as $tax){
			$term_id = $wpdb->get_results("SELECT term_id,parent FROM wp_".$blog_id."_term_taxonomy WHERE taxonomy='category' AND term_taxonomy_id='".$tax->term_taxonomy_id."'");
			//$getparent = get_category($cat->parent);
			if(isset($term_id)){
				foreach($term_id as $ter){
					$slug = $wpdb->get_results("SELECT slug,name FROM wp_".$blog_id."_terms WHERE term_id='".$ter->term_id."'");
					if($parent_id[0]->term_id == $ter->parent){
						$shop_slug = $slug[0]->slug;
						$shop_name = $slug[0]->name;
					}
					if($slug[0]->slug == "live_info") $liveinfo = 1;
					if($slug[0]->slug == "limited") $limited = 1;
					/*if($getparent->slug == "shop_info"){
						$shop_slug = $cat->slug;
						$shop_name = $cat->name;
					}
					if($cat->slug == "live_info") $liveinfo = 1;
					if($cat->slug == "limited") $limited = 1;*/
				}
			}
		}
	}
	$bloginfo = $wpdb->get_results("SELECT option_value FROM wp_".$nowpage[0]."_options WHERE option_name='blogname'");
?>
					<div class="block">
						<div class="logoTag"><a href="<?php echo "/{$blogpath}/"; ?>"><img src="/images/tag/logotag_<?php echo $blognum; ?>.jpg" alt="#" /></a></div>
<?php
$newtime = time() - 24 * 60 * 60;
if($newtime < $postdate){
?>
						<div class="icn_new"><img src="/images/icon_new.png" width="33" height="34" alt="#" /></div>
<?php
}
?>
<?php if($liveinfo == 1){ ?>
<div class="icn_new"><img src="/images/icon_live.png" width="33" height="34" alt="#" /></div>
<?php } ?>
						<div class="section">
							<div class="blockInner">
								<div class="articleImage"><a href="<?php echo "/".$blogpath."/?p=".$post[0]->ID.$get_sets.$disco_sid; ?>"><img src="<?php echo $getfirst; ?>" alt="" /></a></div>
								<dl>
									<dt>
										<span class="date"><?php if($postdate) echo date('Y.m.d',$postdate); ?></span>
										<a href="<?php echo "/".$blogpath."/?p=".$post[0]->ID.$get_sets.$disco_sid; ?>"><?php
							echo mb_substr($post[0]->post_title,0,15);
							if(mb_strlen($post[0]->post_title)>15) echo "…";
							?></a></dt>
									<dd>
										<span class="brandName bcolor_<?php echo $blogpath; ?>"><a href="<?php echo "/{$blogpath}/"; ?>"><?php echo $bloginfo[0]->option_value; ?></a></span><span class="part">/</span>
								<?php
								if($shop_name){
									echo "<a href=\"/".$blogpath."/?st=shop&getkey=".$shop_slug."\">".$shop_name."</a>";
								}else{
									echo "<br />";
								}
								?></dd>
								</dl>
							</div>
						</div>
					</div>
<?php
	restore_current_blog();
}
?>
