<?php
/**
 * Liner Theme Customizer
 */

/**
 * liner_customize_register
 */
function liner_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'liner_design', array(
		'title'    => 'デザイン・設定',
		'priority' => 100,
	) );

	$wp_customize->add_setting( 'header_logo', array(
		'default'   => get_template_directory_uri() . '/images/common/header-logo.png',
	) );
	class Liner_Header_Logo_Control extends WP_Customize_Image_Control {
		public function __construct( $manager, $id, $args ) {
			parent::__construct( $manager, $id, $args );
			$this->add_tab( 'tab_defaults', 'デフォルト', array( $this, 'tab_defaults' ) );
		}
		public function tab_defaults() {
			?>
			<div class="uploaded-target"></div>
			<?php
			$defaults = array(
				get_template_directory_uri() . '/images/common/header-logo.png',
			);
			foreach ( $defaults as $default ) {
				$this->print_tab_image( esc_url_raw( $default ) );
			}
		}
		public function render_content() {
			parent::render_content();
			?>
			<p class="description" style="clear: both;">
				高さ 60px を推奨します。
			</p>
			<?php
		}
	}
	$wp_customize->add_control( new Liner_Header_Logo_Control( $wp_customize, 'header_logo', array(
		'label'    => 'ヘッダーロゴ',
		'section'  => 'liner_design',
		'settings' => 'header_logo',
	) ) );

	$wp_customize->add_setting( 'font_family' , array(
		'default'     => 'gothic',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'font_family', array(
		'label'      => 'フォント',
		'section'    => 'liner_design',
		'settings'   => 'font_family',
		'type'       => 'radio',
		'choices'    => array(
			'gothic' => 'ゴシック',
			'mincho' => '明朝',
		),
	) ) );

	$wp_customize->add_setting( 'display_page_heading' , array(
		'default'     => 'true',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'display_page_heading', array(
		'label'      => 'ページタイトル',
		'section'    => 'liner_design',
		'settings'   => 'display_page_heading',
		'type'       => 'radio',
		'choices'    => array(
			'true' => '表示する',
			'false' => '表示しない',
		),
	) ) );

	$wp_customize->add_setting( 'display_topic_path' , array(
		'default'     => 'true',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'display_topic_path', array(
		'label'      => 'パンくずリスト',
		'section'    => 'liner_design',
		'settings'   => 'display_topic_path',
		'type'       => 'radio',
		'choices'    => array(
			'true' => '表示する',
			'false' => '表示しない',
		),
	) ) );

	$wp_customize->add_setting( 'font_color' , array(
		'default'     => '#000',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'font_color', array(
		'label'      => '文字色',
		'section'    => 'colors',
		'settings'   => 'font_color',
	) ) );

	$wp_customize->add_setting( 'excerpt_link_hover_color' , array(
		'default'     => '#999',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'excerpt_link_hover_color', array(
		'label'      => '抜粋マウスオーバー時リンク色',
		'section'    => 'colors',
		'settings'   => 'excerpt_link_hover_color',
	) ) );

	$wp_customize->add_setting( 'link_color' , array(
		'default'     => '#000',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'      => 'リンク文字色',
		'section'    => 'colors',
		'settings'   => 'link_color',
	) ) );
}
add_action( 'customize_register', 'liner_customize_register' );

function liner_customize_css() {
	$link_color = get_theme_mod( 'link_color' );
	$link_color_hex = array(
		'red'   => hexdec( substr( $link_color, 1, 2 ) ),
		'green' => hexdec( substr( $link_color, 3, 2 ) ),
		'blue'  => hexdec( substr( $link_color, 5, 2 ) ),
	);
	?>
	<style>
	<?php if ( get_theme_mod( 'link_color' ) ) : ?>
	.entry-content a:link, .entry-content a:visited, .entry-content a:hover, .entry-content a:active,
	.page-content a:link, .page-content a:visited, .page-content a:hover, .page-content a:active {
		color: <?php echo get_theme_mod( 'link_color' ); ?>;
	}
	<?php endif; ?>
	<?php if ( get_theme_mod( 'font_color' ) ) : ?>
	body,
	.entries .hentry .entry-summary a,
	.entry-title a, .page-title a,
	.liner-eyecatch-entry dl dd a {
		color: <?php echo get_theme_mod( 'font_color' ); ?>;
	}
	<?php endif; ?>
	<?php if ( get_theme_mod( 'excerpt_link_hover_color' ) ) : ?>
	.entries .hentry .entry-summary a:hover,
	.entries .hentry .entry-summary a:active,
	.liner-eyecatch-entry dl dd a:hover,
	.liner-eyecatch-entry dl dd a:active,
	.liner-recent-entries ul li dl dd a:hover,
	.liner-recent-entries ul li dl dd a:active,
	.dl-top-image dl dd a:hover,
	.dl-top-image dl dd a:active {
		color: <?php echo get_theme_mod( 'excerpt_link_hover_color' ); ?>;
	}
	<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'liner_customize_css' );
