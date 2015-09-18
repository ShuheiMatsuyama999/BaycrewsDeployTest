<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.2
 */

/**
 * add meta box
 */
function liner_add_voice_meta_box() {
	add_meta_box(
		'liner_voice_meta_box',
		'カスタムフィールド',
		'liner_voice_meta_box',
		'voice',
		'normal'
	);
}
add_action( 'admin_head', 'liner_add_voice_meta_box' );

function liner_voice_meta_box() {
	global $post;
	?>
	<p>
		<strong>お客様名</strong><br />
		<input type="text" name="liner_staff_name" value="<?php echo esc_attr( get_post_meta( $post->ID, 'liner_staff_name', true ) ); ?>" />
	</p>
	<p>
		<strong>URL</strong><br />
		<input type="text" name="liner_url" value="<?php echo esc_attr( get_post_meta( $post->ID, 'liner_url', true ) ); ?>" class="widefat" />
	</p>
	<?php
}

function liner_voice_save_post( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;
	if ( !isset( $_POST['post_type'] )|| 'voice' !== $_POST['post_type'] )
		return $post_id;
	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;

	if ( isset( $_POST['liner_staff_name'] ) ) {
		update_post_meta( $post_id, 'liner_staff_name', $_POST['liner_staff_name'] );
	}
	if ( isset( $_POST['liner_url'] ) ) {
		update_post_meta( $post_id, 'liner_url', $_POST['liner_url'] );
	}
}
add_action( 'save_post', 'liner_voice_save_post' );
