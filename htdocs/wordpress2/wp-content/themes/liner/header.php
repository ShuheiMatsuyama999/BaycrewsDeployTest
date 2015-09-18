<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head prefix="og: http://ogp.me/ns# <?php echo ( is_single() || is_page() ) ? 'fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#' : 'fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#' ?>">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>
<body <?php body_class( get_theme_mod( 'font_family' ) ); ?>>
<div id="container" class="hfeed">
	<header id="header">
		<div class="row">
			<div class="col-12">
				<div class="site-branding">
					<h1 class="site-title">
						<?php
						$header_logo = get_theme_mod( 'header_logo' );
						if ( !$header_logo ) {
							$header_logo = get_template_directory_uri() . '/images/common/header-logo.png';
						}
						?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php if ( $header_logo ) : ?>
							<img src="<?php echo esc_url( $header_logo ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
							<?php else : ?>
							<?php bloginfo( 'name' ); ?>
							<?php endif; ?>
						</a>
					</h1>
					<span id="responsive-btn">MENU</span>
				<!-- end .site-branding --></div>

				<nav class="global-nav">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'global-nav',
					) );
					?>
				<!-- end .global-nav --></nav>
			<!-- end .col-12 --></div>
		<!-- end .row --></div>
	<!-- end #header --></header>

	<div id="contents" class="row">
		<?php if ( is_front_page() ) : ?>
			<?php do_action( 'liner_front_page_main_visual' ); ?>
		<?php else : ?>
			<?php if ( $main_visual = liner_main_visual() ) : ?>
			<div class="main-visual">
				<?php echo $main_visual; ?>
			<!-- end .main-visual --></div>
			<?php endif; ?>
		<?php endif; ?>

		<div class="col-12">
