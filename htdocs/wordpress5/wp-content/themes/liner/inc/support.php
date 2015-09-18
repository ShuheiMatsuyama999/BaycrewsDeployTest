<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */

function liner_add_support_page() {
	add_menu_page(
		'サポートについて',
		'サポートについて',
		'edit_pages',
		'liner-support',
		'liner_display_support_page',
		'dashicons-admin-users',
		98
	);
}
function liner_display_support_page() {
	?>
	<div class="wrap">
		<h2>サポートについて</h2>
		<table class="form-table">
			<tr>
				<th>Flat 公式サイト</th>
				<td><a href="http://www.wp-flat.com" target="_blank">http://www.wp-flat.com</a></td>
			</tr>
			<tr>
				<th>サポート</th>
				<td>
					Flat では、Flat のテーマを使ったサイトのサポートを承っております。<br />
					<a href="http://www.wp-flat.com/service" target="_blank">http://www.wp-flat.com/service/</a>
				</td>
			</tr>
		</table>
	<!-- end .wrap --></div>
	<?php
}
add_action( 'admin_menu', 'liner_add_support_page' );
