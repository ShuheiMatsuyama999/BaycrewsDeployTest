<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.5
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1170;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
if ( ! function_exists( 'liner_setup' ) ) :
function liner_setup() {

	// Make theme available for translation.
	load_theme_textdomain( 'liner', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'responsive-size', 740, 493, true );
	add_image_size( 'column-3', 270, 180, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'global-nav' => 'グローバルナビゲーション',
		'footer-nav' => 'フッターナビゲーション',
		'social-nav' => 'ソーシャルメディア',
	) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', ) );

	// Enable support for editor style.
	add_editor_style();
	function liner_custom_editor_settings( $initArray ){
		$class = 'entry-content';
		if ( get_theme_mod( 'font_family' ) === 'mincho' ) {
			$class .= ' mincho';
		} else {
			$class .= ' gothic';
		}
		$initArray['body_class'] = $class;
		return $initArray;
	}
	add_filter( 'tiny_mce_before_init', 'liner_custom_editor_settings' );

	function liner_excerpt_mblength( $length ) {
		if ( is_page() ) {
			return 50;
		}
		return $length;
	}
	add_filter( 'excerpt_mblength', 'liner_excerpt_mblength' );

	function liner_excerpt_more( $more ) {
		$more = '';
		if ( !has_excerpt() ) {
			$more .= '...';
		}
	}
	add_filter( 'excerpt_more', 'liner_excerpt_more' );

	function liner_wp_trim_excerpt( $excerpt ) {
		return esc_html( $excerpt ) . '<span class="read-more">' . apply_filters( 'liner_more_text', 'Read More &raquo;' ) . '</span>';
	}
	add_filter( 'wp_trim_excerpt', 'liner_wp_trim_excerpt' );

	/**
	 * fix_nav_menu_css_class
	 * nav_menuのCSSクラス付与の不具合を修正
	 */
	function fix_nav_menu_css_class( $classes, $item ) {
		global $wp_query;
		$page_template = get_post_meta( $item->object_id, '_wp_page_template', true );

		$page_for_posts = get_option( 'page_for_posts' );
		$post_type_query = $wp_query->query_vars['post_type'];
		$del_flag = true;

		if ( is_singular( 'post' ) || is_category() || is_tag() ) {
			$del_flag = false;
		} elseif ( ( is_author() || is_date() || is_author() ) ) {
			if ( in_array( $post_type_query, array ( '', 'post' ) ) ) {
				$del_flag = false;
			}
		} elseif ( is_singular() ) {
			if ( $post_type_query === 'news' && $page_template == 'template/template-news.php' ||
				 $post_type_query === 'voice' && $page_template == 'template/template-voice.php' ||
				 $post_type_query === 'faq' && $page_template == 'template/template-faq.php'
				) {
				$classes[] = 'current_page_parent';
			}
		} elseif ( is_tax() ) {
			$taxonomy = get_taxonomy( $wp_query->query_vars['taxonomy'] );
			if ( count( $taxonomy->object_type ) == 1 && $taxonomy->object_type[0] == 'post' ) {
				$del_flag = false;
			}
			if ( $taxonomy->object_type[0] == $item->object && $item->type === 'post_type_archive' ) {
				$classes[] = 'current_page_parent';
			}
			if ( $taxonomy->object_type[0] == 'news' && $page_template == 'template/template-news.php' ||
				 $taxonomy->object_type[0] == 'voice' && $page_template == 'template/template-voice.php' ||
				 $taxonomy->object_type[0] == 'faq' && $page_template == 'template/template-faq.php'
				) {
				$classes[] = 'current_page_parent';
			}
		}

		if ( $del_flag && is_numeric( $page_for_posts ) && $item->object_id == $page_for_posts && $item->object == 'page' && $key = array_search( 'current_page_parent', $classes ) ) {
			unset( $classes[$key] );
		}
		return $classes;
	}
	add_filter( 'nav_menu_css_class', 'fix_nav_menu_css_class', 10, 2 );

	add_post_type_support( 'page', 'excerpt' );
}
endif;
add_action( 'after_setup_theme', 'liner_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function liner_widgets_init() {
	register_sidebar( array(
		'name'		  => 'ブログサイドバー',
		'id'			=> 'blog-sidebar',
		'before_widget' => '<div id="%1$s" class="widget-container %2$s"><dl>',
		'after_widget'  => '</dd></dl></div>',
		'before_title'  => '<dt class="widget-title">',
		'after_title'   => '</dt><dd class="widget-content">',
	) );
	register_sidebar( array(
		'name'		  => '新着情報サイドバー',
		'id'			=> 'news-sidebar',
		'before_widget' => '<div id="%1$s" class="widget-container %2$s"><dl>',
		'after_widget'  => '</dd></dl></div>',
		'before_title'  => '<dt class="widget-title">',
		'after_title'   => '</dt><dd class="widget-content">',
	) );
	register_sidebar( array(
		'name'		  => 'よくあるご質問サイドバー',
		'id'			=> 'faq-sidebar',
		'before_widget' => '<div id="%1$s" class="widget-container %2$s"><dl>',
		'after_widget'  => '</dd></dl></div>',
		'before_title'  => '<dt class="widget-title">',
		'after_title'   => '</dt><dd class="widget-content">',
	) );
	register_sidebar( array(
		'name'		  => '固定ページ等記事下',
		'id'			=> 'sidebar',
		'before_widget' => '<div id="%1$s" class="widget-container col-4 %2$s"><dl>',
		'after_widget'  => '</dd></dl></div>',
		'before_title'  => '<dt class="widget-title">',
		'after_title'   => '</dt><dd class="widget-content">',
	) );
	register_sidebar( array(
		'name'		  => 'フッター',
		'id'			=> 'footer',
		'before_widget' => '<div id="%1$s" class="widget col-4 %2$s"><dl>',
		'after_widget'  => '</dd></dl></div>',
		'before_title'  => '<dt class="widget-title">',
		'after_title'   => '</dt><dd class="widget-content">',
	) );

	$front_page_widget_areas = get_theme_mod( 'front_page_widget_areas' );
	if ( is_array( $front_page_widget_areas ) ) {
		foreach ( $front_page_widget_areas as $front_page_widget_area ) {
			$grid_size = 'col-' . 12 / $front_page_widget_area['columns'];
			register_sidebar( array(
				'name'		  => $front_page_widget_area['name'],
				'id'			=> $front_page_widget_area['id'],
				'before_widget' => '<div id="%1$s" class="widget %2$s ' . $grid_size . '">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2>',
				'after_title'   => '</h2>',
			) );
		}
	}
}
add_action( 'widgets_init', 'liner_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function liner_scripts() {
	$template_url = get_template_directory_uri();
	wp_enqueue_style( 'liner-base', get_stylesheet_uri() );
	wp_enqueue_style( 'jquery.responsive-nav', $template_url . '/js/jquery.responsive-nav/jquery.responsive-nav.css' );
	wp_enqueue_script( 'jquery.responsive-nav', $template_url . '/js/jquery.responsive-nav/jquery.responsive-nav.js', array( 'jquery' ), false, true );

	wp_enqueue_style( 'jquery.scrollButton', $template_url . '/js/jquery.scrollButton/jquery.scrollButton.css' );
	wp_enqueue_script( 'jquery.scrollButton', $template_url . '/js/jquery.scrollButton/jquery.scrollButton.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'jquery.SmoothScroll', $template_url . '/js/jquery.SmoothScroll/jquery.smoothScroll.js', array( 'jquery' ), false, true );

	wp_enqueue_style( 'liner-style', $template_url . '/css/layout.css' );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_script( 'liner-base', $template_url . '/js/liner.js', array( 'jquery' ), 2, true );
}
add_action( 'wp_enqueue_scripts', 'liner_scripts' );

/**
 * Enqueue scripts and styles.
 */
function liner_admin_scripts() {
	add_thickbox();
	wp_enqueue_style( 'liner-admin', get_template_directory_uri() . '/css/admin.css' );
}
add_action( 'admin_enqueue_scripts', 'liner_admin_scripts' );

/**
 * Display recommend plugin message in widgets
 */
function liner_widgets_admin_page() {
	?>
	<?php if ( !is_plugin_active( 'black-studio-tinymce-widget/black-studio-tinymce-widget.php' ) ) : ?>
	<div class="liner-information">
		<ul>
			<li><a href="<?php echo admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=black-studio-tinymce-widget&amp;TB_iframe=true&amp;width=600&amp;height=550' ); ?>" class="thickbox">Black Studio TinyMCE Widget</a>をインストールするとWYSIWYGエディタがウィジェットで使用可能になります。</li>
		</ul>
	<!-- end .liner-information --></div>
	<?php endif; ?>
	<?php
}
add_action( 'widgets_admin_page', 'liner_widgets_admin_page' );

/**
 * Display recommend plugin message in nav-menus
 */
function liner_user_edit_notices() {
	?>
	<?php if( !is_plugin_active( 'simple-local-avatars/simple-local-avatars.php' ) ) : ?>
	<div class="liner-information">
		<ul>
			<li><a href="<?php echo admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=simple-local-avatars&amp;TB_iframe=true&amp;width=600&amp;height=550' ); ?>" class="thickbox">Simple Local Avatars</a>をインストールするとアバター画像をアップロードできるようになります。</li>
		</ul>
	<!-- end .liner-information --></div>
	<?php endif; ?>
	<?php
}
function liner_set_user_edit_notices() {
	add_action( 'admin_notices', 'liner_user_edit_notices' );
}
add_action( 'admin_head-user-edit.php', 'liner_set_user_edit_notices' );

/**
 * liner_youtube_responsive
 */
function liner_youtube_responsive( $html, $url, $attr, $post_ID ) {
	if ( preg_match( '/^https?:\/\/www\.youtube\.com/', $url ) ) {
		$html = '<div class="liner-video-container">' . $html . '</div>';
	}
	return $html;
}
add_filter( 'embed_oembed_html', 'liner_youtube_responsive', 10, 4 );

/**
 * liner_add_custom_image_size_select
 */
function liner_add_custom_image_size_select( $size_names ) {
	$custom_sizes = get_intermediate_image_sizes();
	foreach ( $custom_sizes as $custom_size ) {
		if ( !isset( $size_names[$custom_size] ) && $custom_size !== 'liner-header-image' ) {
			$size_names[$custom_size] = $custom_size;
		}
	}
	return $size_names;
}
add_filter( 'image_size_names_choose', 'liner_add_custom_image_size_select' );

/**
 * liner_wp_link_pages_link
 */
function liner_wp_link_pages_link( $link, $i ) {
	$link = preg_replace( '/^(<a.+>)<span.+?>(.+?)<\/span>(<\/a>)$/', '$1$2$3', $link );
	return $link;
}
add_filter( 'wp_link_pages_link', 'liner_wp_link_pages_link', 10, 2 );

/**
 * add thumbnail setting description
 * ブログの場合：column-3
 * インデックスページの場合：responsive-size
 * お客様の声の場合：responsive-size
 */
function liner_admin_post_thumbnail_html( $content ) {
	global $_wp_additional_image_sizes;
	$post_type = get_post_type();
	if ( $post_type === 'post' || $post_type === 'news' ) {
		$image_size = 'column-3';
	} elseif ( $post_type === 'page' || $post_type === 'voice' ) {
		$image_size = 'responsive-size';
	}
	if ( isset( $image_size, $_wp_additional_image_sizes[$image_size] ) ) {
		$postThumbnail = $_wp_additional_image_sizes[$image_size];
		if ( isset( $postThumbnail['height'], $postThumbnail['width'] ) ) {
			$height = $postThumbnail['height'];
			$width  = $postThumbnail['width'];
			$content .= '<p class="howto">推奨サイズ：' . $width . ' x ' . $height . '<br />※これより大きいサイズの画像を指定した場合は自動的にリサイズ&amp;トリミングされます。</p>';
		}
	}
	return $content;
}
add_filter( 'admin_post_thumbnail_html', 'liner_admin_post_thumbnail_html' ) ;

/**
 * liner_redirect_single_voice
 */
function liner_redirect_single_voice( $query ) {
	if ( is_admin() )
		return $query;
	if ( $query->is_main_query() && $query->is_singular() && liner_is_custom_post_type( 'voice' ) ) {
		wp_redirect( home_url( '/' ) );
		exit;
	}
	return $query;
}
add_action( 'pre_get_posts', 'liner_redirect_single_voice' );

/**
 * liner_redirect_single_faq
 */
function liner_redirect_single_faq( $query ) {
	if ( is_admin() )
		return $query;
	if ( $query->is_main_query() && $query->is_singular() && liner_is_custom_post_type( 'faq' ) ) {
		wp_redirect( home_url( '/' ) );
		exit;
	}
	return $query;
}
add_action( 'pre_get_posts', 'liner_redirect_single_faq' );

/**
 * liner_front_page_main_visual_default
 */
if ( ! function_exists( 'liner_front_page_main_visual_default' ) ) :
function liner_front_page_main_visual_default() {
	if ( get_header_image() ) :
		?>
		<div class="main-visual">
			<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
		<!-- end .main-visual --></div>
		<?php
	endif;
}
endif;
add_action( 'liner_front_page_main_visual', 'liner_front_page_main_visual_default' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Support page
 */
require get_template_directory() . '/inc/support.php';

/**
 * Widgets
 */
require get_template_directory() . '/inc/widgets/recent-entries.php';
require get_template_directory() . '/inc/widgets/eyecatch-entry.php';
require get_template_directory() . '/inc/widgets/taxonomy.php';

/**
 * Customize front page widget areas.
 */
require get_template_directory() . '/inc/front-page/front-page.php';

/**
 * Add header image in single or page.
 */
require get_template_directory() . '/inc/header-image/header-image.php';

/**
 * Bread crumb
 */
require get_template_directory() . '/inc/bread-crumb.php';

/**
 * Include shortcodes
 */
require get_template_directory() . '/inc/shortcodes.php';

/**
 * Post type voice
 */
require get_template_directory() . '/inc/post-type-voice.php';

/**
 * Theme updater
 */
require get_template_directory() . '/inc/theme-update.php';
$ATPU_Theme = new ATPU_Theme( 'http://www.wp-flat.com/api/' );

/**
 * Custom post type management class.
 */
require get_template_directory() . '/inc/mw-manage-custom-post-type.php';
$mw_manage_custom = new mw_manage_custom_post_type();
$mw_manage_custom->custom_post_type( '新着情報', 'news',
	array( 'title', 'editor', 'author', 'excerpt', 'comments', 'revisions', 'thumbnail' ),
	array( 'has_archive' => false )
);
$mw_manage_custom->custom_taxonomy( '新着カテゴリー', 'news-category', array( 'news' ),
	array( 'hierarchical' => true )
);

$mw_manage_custom->custom_post_type( 'お客様の声', 'voice',
	array( 'title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail' ),
	array( 'has_archive' => false )
);

$mw_manage_custom->custom_post_type( 'よくあるご質問', 'faq',
	array( 'title', 'editor', 'author', 'revisions' ),
	array( 'has_archive' => false )
);
$mw_manage_custom->custom_taxonomy( 'FAQカテゴリー', 'faq-category', array( 'faq' ),
	array( 'hierarchical' => true )
);
$mw_manage_custom->init();


