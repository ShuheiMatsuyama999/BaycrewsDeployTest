<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */
get_header(); ?>

	<main id="main" role="main">
		<header class="page-header">
			<?php liner_the_page_heading(); ?>
			<?php liner_the_bread_crumb(); ?>
		<!-- end .page-header --></header>
		<?php get_template_part( 'content', 'none' ); ?>
	<!-- end #main --></main>

	<aside id="sub">
		<?php get_sidebar(); ?>
	<!-- end #sub --></aside>
<?php get_footer(); ?>