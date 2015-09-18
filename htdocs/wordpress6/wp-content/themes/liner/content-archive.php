<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.1
 */
?>
<?php if ( get_the_content() ) : ?>
<div class="page-content">
	<?php the_content(); ?>
	<?php
	wp_link_pages( array(
		'before' => '<div class="pager"><p>',
		'after'  => '</p></div>',
	) );
	?>
<!-- end .page-content --></div>
<?php endif; ?>