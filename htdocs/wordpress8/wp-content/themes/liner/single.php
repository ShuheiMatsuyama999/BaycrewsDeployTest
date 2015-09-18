<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */
get_header(); ?>
	<header class="entry-header">
		<?php liner_the_page_heading(); ?>
		<?php liner_the_bread_crumb(); ?>
	<!-- end .entry-header --></header>

	<div class="row">
		<main id="main" role="main" class="col-9">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php
						wp_link_pages( array(
							'before' => '<div class="pager"><p>',
							'after'  => '</p></div>',
							'pagelink' => '<span class="current">%</span>',
						) );
						?>
					<!-- end .entry-content --></div>

					<div class="entry-meta">
						<?php liner_posted_on(); ?>
					<!-- end .entry-meta --></div>
				<!-- end .hentry --></article>
				<?php
				liner_post_nav();

				if ( comments_open() || '0' != get_comments_number() ) {
					comments_template();
				}
				?>
			<?php endwhile; ?>
		<!-- end #main --></main>
		<aside id="sub" class="col-3">
			<?php get_sidebar(); ?>
		<!-- end #sub --></aside>
	<!-- end .row --></div>
	
<?php get_footer(); ?>