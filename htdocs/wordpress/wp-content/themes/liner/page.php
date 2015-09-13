<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */
get_header(); ?>

	<main id="main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php liner_the_page_heading(); ?>
					<?php liner_the_bread_crumb(); ?>
				<!-- end .entry-header --></header>

				<div class="entry-content">
					<?php the_content(); ?>
					<?php
					wp_link_pages( array(
						'before' => '<div class="pager"><p>',
						'after'  => '</p></div>',
					) );
					?>
				<!-- end .entry-content --></div>
					
				<div class="entry-meta hidden">
					<?php liner_posted_on(); ?>
				<!-- end .entry-meta --></div>
			<!-- end .hentry --></article>
			<?php
			if ( comments_open() || '0' != get_comments_number() ) {
				comments_template();
			}
			?>
		<?php endwhile; ?>
	<!-- end #main --></main>

	<aside id="sub">
		<?php get_sidebar(); ?>
	<!-- end #sub --></aside>
<?php get_footer(); ?>
