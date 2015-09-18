<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */
get_header(); ?>

	<header class="page-header">
		<?php liner_the_page_heading(); ?>
		<?php liner_the_bread_crumb(); ?>
	<!-- end .page-header --></header>

	<div class="row">
		<main id="main" role="main" class="col-9">
			<?php if ( have_posts() ) : ?>
				<div class="entries">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content' ); ?>
					<?php endwhile; ?>
					<?php liner_the_pager(); ?>
				<!-- end .entries --></div>
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		<!-- end #main --></main>
		<aside id="sub" class="col-3">
			<?php get_sidebar(); ?>
		<!-- end #sub --></aside>
	<!-- end .row --></div>

<?php get_footer(); ?>