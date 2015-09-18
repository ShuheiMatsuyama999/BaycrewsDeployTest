<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.6
 */

/**
 * Display navigation to next/previous post when applicable.
 *
 * @return void
 */
if ( ! function_exists( 'liner_post_nav' ) ) :
function liner_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'liner' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'liner' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'liner' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
if ( ! function_exists( 'liner_posted_on' ) ) :
function liner_posted_on() {
	global $post;
	setup_postdata( $post );
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated hidden" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'liner' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);
	?>
	<?php if ( get_the_category() ) : ?>
	<span class="entry-categories">Categories: <?php the_category( ', ' ); ?></span>
	<?php endif; ?>
	<?php if ( get_the_tags() ) : ?>
	<span class="entry-tags">Tags: <?php the_tags( '', ', ', '' ); ?></span>
	<?php endif; ?>
	<?php if ( get_the_terms( get_the_ID(), 'news-category' ) ) : ?>
	<span class="entry-categories">Categories: <?php the_terms( get_the_ID(), 'news-category', '', ', ', ''  ); ?></span>
	<?php endif; ?>
	<?php
	wp_reset_postdata( $post );
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 */
function liner_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so liner_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so liner_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in liner_categorized_blog.
 */
function liner_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'liner_category_transient_flusher' );
add_action( 'save_post',     'liner_category_transient_flusher' );

/**
 * liner_is_custom_post_type
 */
function liner_is_custom_post_type( $pt = '' ) {
	$default_post_type = get_post_types( array(
		'_builtin' => true
	) );
	$post_type = get_post_type();
	if ( !$post_type ) {
		global $wp_query;
		if ( isset( $wp_query->query['post_type'] ) ) {
			$post_type = $wp_query->query['post_type'];
		}
	}
	if ( !empty( $post_type ) && !in_array( $post_type, $default_post_type ) ) {
		if ( empty( $pt ) ) {
			return true;
		} elseif ( $pt === $post_type ) {
			return true;
		}
	}
	return false;
}

/**
 * liner_get_top_parent_id
 */
function liner_get_top_parent_id( $post ) {
	if ( empty( $post->ID ) )
		return;
	$ancectors = get_post_ancestors( $post );
	$ancestor = 0;
	if ( $ancectors )
		$ancestor = @array_pop( get_post_ancestors( $post ) );
	if ( !$ancestor )
		return $post->ID;
	return $ancestor;
}
/**
 * liner_is_blog
 */
function liner_is_blog() {
	global $post;
	$post_type = get_post_type( $post );
	if ( ( is_home() || is_archive() || is_single() ) && $post_type == 'post' ) {
		return true;
	}
	return false;
}

/**
 * liner_get_template_used_page
 * @param string $post_type
 */
function liner_get_template_used_page( $post_type ) {
	$template_pages = get_posts( array(
		'post_type' => 'page',
		'meta_query' => array(
			array(
				'key' => '_wp_page_template',
				'value' => 'template/template-' . $post_type . '.php',
			),
		),
	) );
	if ( !empty( $template_pages[0] ) ) {
		return $template_pages[0];
	}
}

/**
 * liner_the_main_visual
 */
if ( ! function_exists( 'liner_the_main_visual' ) ) :
function liner_the_main_visual() {
	echo liner_main_visual();
}
endif;
if ( ! function_exists( 'liner_main_visual' ) ) :
function liner_main_visual() {
	$post_type = get_post_type();
	if ( liner_is_custom_post_type() ) {
		$template_page = liner_get_template_used_page( $post_type );
		if ( !empty( $template_page->ID ) ) {
			$parent_id = $template_page->ID;
		}
	} elseif ( $post_type === 'post' || is_archive() && !$post_type && !is_tax() ) {
		$parent_id = get_option( 'page_for_posts' );
	} elseif ( !is_404() && !is_search() ) {
		$ancestors = get_post_ancestors( get_the_ID() );
		array_unshift( $ancestors, get_the_ID() );
		foreach ( $ancestors as $ancestor ) {
			$main_visual = liner_get_header_image( $ancestor );
			if ( $main_visual ) {
				return apply_filters( 'liner_main_visual', $main_visual );
			}
		}
	}
	if ( !empty( $parent_id ) ) {
		return apply_filters( 'liner_main_visual', liner_get_header_image( $parent_id ) );
	}
}
function liner_get_header_image( $post_id ) {
	$post_meta = get_post_meta( $post_id, 'liner-header-image', true );
	if ( !empty( $post_meta['liner_header_image_id'] ) ) {
		$header_image_id = $post_meta['liner_header_image_id'];
		return wp_get_attachment_image(
			$header_image_id,
			'liner-header-image',
			array( 'alt' => get_the_title( $post_id ), 'title' => '' )
		);
	}
}
endif;

/**
 * liner_the_pager
 */
if ( ! function_exists( 'liner_the_pager' ) ) :
function liner_the_pager() {
	global $wp_rewrite;
	global $wp_query;
	global $paged;
	$paginate_base = get_pagenum_link( 1 );
	if ( strpos( $paginate_base, '?' ) || ! $wp_rewrite->using_permalinks() ) {
		$paginate_format = '';
		$paginate_base = add_query_arg( 'paged', '%#%' );
	} else {
		$paginate_format = ( substr( $paginate_base, -1 ,1 ) == '/' ? '' : '/' ) .
		user_trailingslashit( 'page/%#%/', 'paged' );
		$paginate_base .= '%_%';
	}
	$paginate_links = paginate_links( array(
		'base'      => $paginate_base,
		'format'    => $paginate_format,
		'total'     => $wp_query->max_num_pages,
		'mid_size'  => 5,
		'current'   => ( $paged ? $paged : 1 ),
		'prev_text' => '&lt;',
		'next_text' => '&gt;',
	) );
	if ( $paginate_links ) {
		?>
		<div class="pager">
			<p>
				<?php echo $paginate_links; ?>
			</p>
		<!-- end .pager --></div>
		<?php
	}
}
endif;

/**
 * liner_archive_title
 */
if ( ! function_exists( 'liner_archive_title' ) ) :
function liner_archive_title() {
	if ( liner_is_custom_post_type() ) {
		global $wp_query;
		$post_type = $wp_query->get( 'post_type' );
		if ( !$post_type ) {
			$post_type = get_post_type();
		}
		$page = liner_get_template_used_page( $post_type );
		if ( $page ) {
			$title = esc_html( get_the_title( $page ) );
		} else {
			$post_type_object = get_post_type_object( $post_type );
			if ( $post_type_object ) {
				$title = $post_type_object->labels->singular_name;
			} else {
				$title = '';
			}
		}
	}
	elseif ( is_tax() ) {
		$taxonomy = get_query_var( 'taxonomy' );
		$taxonomy_objects = get_taxonomy( $taxonomy );
		$post_types = $taxonomy_objects->object_type;
		if ( isset( $post_types[0] ) ) {
			$post_type = $post_types[0];
			$page = liner_get_template_used_page( $post_type );
			if ( $page ) {
				$title = esc_html( get_the_title( $page ) );
			} else {
				$post_type_object = get_post_type_object( $post_type );
				if ( $post_type_object ) {
					$title = $post_type_object->labels->singular_name;
				} else {
					$title = '';
				}
			}
		} else {
			$page_for_posts = get_option( 'page_for_posts' );
			$title = esc_html( get_the_title( $page_for_posts ) );
		}
	}
	else {
		$page_for_posts = get_option( 'page_for_posts' );
		$title = esc_html( get_the_title( $page_for_posts ) );
	}
	return $title;
}
endif;

/**
 * liner_the_page_heading
 * @param bool $link リンク付きかどうか
 */
if ( ! function_exists( 'liner_the_page_heading' ) ) :
function liner_the_page_heading( $link = false ) {
	$classes = array();
	if ( get_theme_mod( 'display_page_heading' ) === 'false' ) {
		$classes[] = 'hidden';
	}
	if ( is_singular() ) {
		$post_type = get_post_type();
		$title = get_the_title();
		if ( is_page() || is_attachment() ) {
			$classes[] = 'entry-title';
		} else {
			$classes[] = 'page-title';
		}
		$post_types = get_post_types( array( 'public' => true, '_builtin' => false ) );
		$post_types += array( 'post' => 'post' );
		if ( in_array( $post_type, $post_types ) ) {
			$title = liner_archive_title();
		}
	} else {
		$title = liner_archive_title();
		$classes[] = 'page-title';
		if ( is_search() ) {
			$title = sprintf( '「%s」の検索結果', get_search_query() );
		}
		elseif ( is_404() ) {
			$title = 'ページが見つかりませんでした。';
		}
	}
	$class = implode( ' ', $classes );
	?>
	<?php if ( $link ) : ?>
	<h1 class="<?php echo esc_attr( $class ); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo esc_html( $title ); ?></a></h1>
	<?php else : ?>
	<h1 class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $title ); ?></h1>
	<?php
	endif;
}
endif;

/**
 * liner_the_bread_crumb
 */
if ( ! function_exists( 'liner_the_bread_crumb' ) ) :
function liner_the_bread_crumb( array $params = array() ) {
	if ( get_theme_mod( 'display_topic_path' ) !== 'false' ) {
		$liner_bread_crumb = new liner_bread_crumb();
		$liner_bread_crumb->display( $params );
	}
}
endif;

