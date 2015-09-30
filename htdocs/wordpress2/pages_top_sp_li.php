<?php
require('./wp-blog-header.php');
$posts = htmlspecialchars($_GET['posts']);
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
	$liveinfo = 0;
	$limited = 0;
	$shop_name = "";
	$shop_slug = "";
	if(isset($taxonomy_id)){
		foreach($taxonomy_id as $tax){
			$term_id = $wpdb->get_results("SELECT term_id,parent FROM wp_".$blog_id."_term_taxonomy WHERE taxonomy='category' AND term_taxonomy_id='".$tax->term_taxonomy_id."'");
			if(isset($term_id)){
				foreach($term_id as $ter){
					$slug = $wpdb->get_results("SELECT slug,name FROM wp_".$blog_id."_terms WHERE term_id='".$ter->term_id."'");
					if($parent_id[0]->term_id == $ter->parent){
						$shop_slug = $slug[0]->slug;
						$shop_name = $slug[0]->name;
					}
					if($slug[0]->slug == "live_info") $liveinfo = 1;
					if($slug[0]->slug == "limited") $limited = 1;
				}
			}
		}
	}
	$bloginfo = $wpdb->get_results("SELECT option_value FROM wp_".$nowpage[0]."_options WHERE option_name='blogname'");
?>
            	<li class="clear linkBox">
                    <div class="left"><a href="<?php echo "/".$blogpath."/?p=".$post[0]->ID; ?>"><img src="<?php echo $getfirst; ?>" width="59" height="70" alt="Family" /></a></div>
                    <div class="right">
                        <p class="txt"><a href="<?php echo "/".$blogpath."/?p=".$post[0]->ID; ?>"><?php
    echo mb_substr($post[0]->post_title,0,12);
    if(mb_strlen($post[0]->post_title) > 12) echo "…";
    ?></a></p>
                        <p class="brand jsl"><a href="<?php echo "/{$blogpath}/"; ?>"><?php echo $bloginfo[0]->option_value; ?></a><?php if($shop_name) echo "/<a href=\"".$blogpath."/?st=shop&getkey=".$shop_slug."\">".$shop_name."</a>"; ?></p>
                    </div>
            	</li>


<?php
}
?>
