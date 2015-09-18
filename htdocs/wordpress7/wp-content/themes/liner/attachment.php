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
				<?php $att_image = wp_get_attachment_image_src( $post->id, "full-size"); ?>
				<p class="attachment">
					<img src="<?php echo esc_url( $att_image[0] );?>" width="<?php echo esc_attr( $att_image[1] );?>" height="<?php echo esc_attr( $att_image[2] );?>" class="attachment-full-size" alt="<?php the_title(); ?>" />
				</p>
				<?php if ( get_the_content() ) : ?>
				<?php the_content(); ?>
				<?php elseif ( get_the_excerpt() ) : ?>
				<?php the_excerpt(); ?>
				<?php endif; ?>
			<!-- end .entry-content --></div>

			<div class="entry-meta">
				<?php liner_posted_on(); ?>
			<!-- end .entry-meta --></div>
		<!-- end .hentry --></article>
		<?php endwhile; ?>
	<!-- end #main --></main>

	<aside id="sub">
		<?php get_sidebar(); ?>
	<!-- end #sub --></aside>
<?php get_footer(); ?>