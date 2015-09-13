<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
	<!-- end .entry-header --></header>
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="entry-thumbnail">
		<?php the_post_thumbnail( 'column-3' ); ?>
	<!-- end .entry-thumbnail --></div>
	<?php endif; ?>
	<div class="entry-summary">
		<a href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a>
	<!-- end .entry-summary --></div>
	
	<div class="entry-meta">
		<?php liner_posted_on(); ?>
	<!-- end .entry-meta --></div>
<!-- end .hentry --></article>
