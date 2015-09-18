<?php
/**
 * Template Name: インデックスページ
 *
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
				
			<?php
			$children = get_children( array(
				'post_parent' => get_the_ID(),
				'post_type' => 'page',
				'post_status' => 'publish',
			) );
			?>
			<?php if ( $children ) : ?>
			<div class="sub-pages row">
				<?php foreach ( $children as $post ) : setup_postdata( $post ); ?>
				<div class="sub-page col-4">
					<div class="liner-eyecatch-entry">
						<dl>
							<?php if ( has_post_thumbnail() ) : ?>
							<dt class="thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'column-4' ); ?></a></dt>
							<?php endif; ?>
							<dt class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dt>
							<dd><a href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a></dd>
						</dl>
					<!-- end .liner-eyecatch-entry --></div>
				<!-- end .col-3 --></div>
				<?php endforeach; wp_reset_postdata(); ?>
			<!-- end .sub-pages --></div>
			<?php endif; ?>

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
