<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */
?>
		<!-- end .col-12 --></div>
	<!-- end #contents --></div>
	<footer id="footer">
		<div class="row">
			<nav class="footer-nav col-12">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-nav',
					'depth' => 1
				) );
				?>
			<!-- end .footer-nav --></nav>

			<?php
			$nav_menu_locations = get_theme_mod( 'nav_menu_locations' );
			?>
			<?php if ( !empty( $nav_menu_locations['social-nav'] ) ) : ?>
			<nav class="social-nav col-12">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'social-nav',
					'depth' => 1,
				) );
				?>
			<!-- end .social-nav --></nav>
			<?php endif; ?>

			<div class="footer-widget-area col-12">
				<div class="row">
					<?php
					if ( is_active_sidebar( 'footer' ) ) {
						dynamic_sidebar( 'footer' );
					}
					?>
				<!-- end .row --></div>
			<!-- end .col-12 --></div>
		<!-- end .row --></div>

		<div class="copyright">
			<div class="row">
				<p class="col-12">
					<?php echo apply_filters( 'liner_copyright', sprintf( 'Copyright &copy; %s  All Rights Reserved.', get_bloginfo( 'name' ) ) ); ?>
					<?php echo apply_filters( 'liner_theme_by', 'Theme by <a href="http://www.wp-flat.com" target="_blank">FLAT</a> - <a href="http://www.nb-a.jp" target="_blank">Net Business Agent</a>' ); ?>
				</p>
			<!-- end .row --></div>
		<!-- end .copyright --></div>
	<!-- end #footer --></footer>
<!-- end #container --></div>
<?php wp_footer(); ?>
</body>
</html>