<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */
?>
<div class="page-content no-results not-found">
	<?php if ( is_search() ) : ?>

		<p>
			申し訳ありません、関連するページが見つかりませんでした。別のキーワードでの検索をお試しください。
		</p>
		<?php get_search_form(); ?>
		
	<?php else : ?>
	
		<p>
			ページが見つかりませんでした。
		</p>
		<?php get_search_form(); ?>

	<?php endif; ?>
<!-- .end page-content --></div>
