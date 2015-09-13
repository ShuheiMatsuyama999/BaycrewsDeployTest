<?php
/**
 * Template Name: お客様の声アーカイブ
 *
 * @author NetBusinessAgent
 * @version 1.0.1
 */
get_header(); ?>

	<header class="page-header">
		<?php liner_the_page_heading(); ?>
		<?php liner_the_bread_crumb(); ?>
	<!-- end .page-header --></header>

	<main id="main" role="main">
		<?php
		while ( have_posts() ) {
			the_post();
			get_template_part( 'content', 'archive' );
		}
				
		$args = array(
			'post_type' => 'voice',
			'paged' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
		);
		if ( is_tax() ) {
			$args = array_merge( $wp_query->query, $args );
		}
		query_posts( $args );
		?>

		<div class="entries">
			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-thumbnail">
					<?php the_post_thumbnail( 'responsive' ); ?>
				<!-- end .entry-thumbnail --></div>
				<?php endif; ?>

				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="voice-customer">
						<?php echo esc_html( get_post_meta( get_the_ID(), 'liner_staff_name', true ) ); ?>
						<?php if ( $url = get_post_meta( get_the_ID(), 'liner_url', true ) ) : ?>
							&nbsp;<a href="<?php echo esc_url( $url ); ?>" target="_blank"><?php echo esc_url( $url ); ?></a>
						<?php endif; ?>
					<!-- end .voice-customer --></div>
				<!-- end .entry-header --></header>

				<div class="entry-content">
					<?php the_content(); ?>
				<!-- end .entry-content --></div>
			<!-- end .hentry --></article>
			<?php endwhile; ?>
			<?php liner_the_pager(); ?>
		<!-- end .entries --></div>
	<!-- end #main --></main>

	<aside id="sub">
		<?php get_sidebar(); ?>
	<!-- end #sub --></aside>
<?php get_footer(); ?>