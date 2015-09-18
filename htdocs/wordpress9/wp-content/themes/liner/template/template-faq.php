<?php
/**
 * Template Name: FAQアーカイブ
 *
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
			<?php
			if ( is_singular() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'content', 'archive' );
				}
			}
			
			$args = array(
				'post_type' => 'faq',
				'posts_per_page' => 50,
				'paged' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
			);
			if ( is_tax() ) {
				$args = array_merge( $wp_query->query, $args );
			}
			query_posts( $args );
			?>
			<div class="faq-list">
				<?php while ( have_posts() ) : the_post(); ?>
				<dl>
					<dt><span class="question">質問</span><?php the_title(); ?>
					<dd>
						<span class="answer">回答</span>
						<?php the_content(); ?>
					</dd>
				</dl>
				<?php endwhile ?>
				<?php liner_the_pager(); ?>
			<!-- end .faq-list --></div>
		<!-- end #main --></main>
		<aside id="sub" class="col-3">
			<?php get_sidebar(); ?>
		<!-- end #sub --></aside>
	<!-- end .row --></div>
	
<?php get_footer(); ?>