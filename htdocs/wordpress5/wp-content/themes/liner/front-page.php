<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.2
 */
get_header(); ?>

	<main id="main" role="main">
		<?php
		$front_page_widget_areas = get_theme_mod( 'front_page_widget_areas' );
		if ( is_array( $front_page_widget_areas ) ) {
			foreach ( $front_page_widget_areas as $front_page_widget_area ) {
				if ( is_active_sidebar( $front_page_widget_area['id'] ) ) {
					$background = 'transparent';
					if ( $front_page_widget_area['bgcolor'] ) {
						$background = '#' . $front_page_widget_area['bgcolor'];
					}
					printf( '<section class="full-back" style="background: %s">', esc_attr( $background ) );
					if ( !empty( $front_page_widget_area['title'] ) ) {
						$for_col_12_style = '';
						if ( $front_page_widget_area['columns'] === 12 ) {
							$for_col_12_style = 'margin-bottom: 80px';
						}
						printf(
							'<h1 style="%s">%s</h1>',
							esc_attr( $for_col_12_style ),
							esc_html( $front_page_widget_area['title'] )
						);
					}
					echo '<div class="front-page-widget-column row">';
					dynamic_sidebar( $front_page_widget_area['id'] );
					echo '</div>';
					echo '</section>';
				}
			}
		}
		?>

		<?php while ( have_posts() ) : the_post(); ?>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
			wp_link_pages( array(
				'before' => '<div class="pager"><p>',
				'after'  => '</p></div>',
			) );
			?>
		<!-- end .entry-content --></div>
		<?php endwhile; ?>
	<!-- end #main --></main>

	<aside id="sub">
		<?php get_sidebar(); ?>
	<!-- end #sub --></aside>
<?php get_footer(); ?>
