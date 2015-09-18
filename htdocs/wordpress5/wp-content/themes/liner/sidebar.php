<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */

if ( !is_front_page() ) {
	$post_type = get_post_type();
	if ( $post_type === 'post' || is_archive() && !$post_type ) {
		if ( is_active_sidebar( 'blog-sidebar' ) ) {
			dynamic_sidebar( 'blog-sidebar' );
		}
	} elseif ( liner_is_custom_post_type( 'news' ) ) {
		if ( is_active_sidebar( 'news-sidebar' ) ) {
			dynamic_sidebar( 'news-sidebar' );
		}
	} elseif ( liner_is_custom_post_type( 'faq' ) ) {
		if ( is_active_sidebar( 'faq-sidebar' ) ) {
			dynamic_sidebar( 'faq-sidebar' );
		}
	} else {
		if ( is_active_sidebar( 'sidebar' ) ) {
			?>
			<div class="row">
				<?php dynamic_sidebar( 'sidebar' ); ?>
			<!-- end .row --></div>
			<?php
		}
	}
}
