<?php
/**
 * @author NetBusinessAgent
 * @version 1.1.0
 */

/**
 * Init Liner_Eyecatch_Entrie_Widget
 */
function liner_eyecatch_entry_widget() {
	register_widget( 'Liner_Eyecatch_Entrie_Widget' );
};
add_action( 'widgets_init', 'liner_eyecatch_entry_widget' );

/**
 * Liner_Eyecatch_Entrie_Widget
 */
class Liner_Eyecatch_Entrie_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'liner_eyecatch_entry_widget',
			'アイキャッチ付きの投稿',
			array(
				'description' => 'アイキャッチ付きの投稿',
			)
		);
	}

	public function widget( $args, $instance ) {
		global $post;
		// カスタマイザーでウィジェットを設置したときにデフォルトの内容を表示するために必要
		if ( empty( $instance['post_id'] ) ) {
			$__posts = $this->get_posts();
			$__post  = array_shift( $__posts );
			$thumbnail_sizes = get_intermediate_image_sizes();
			$instance['post_id'] = $__post->ID;
			$instance['title']   = get_the_title( $instance['post_id'] );
			$instance['thumbnail_size'] = array_shift( $thumbnail_sizes );
		}
		$_posts = $this->get_posts( $instance['post_id'] );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<div class="liner-eyecatch-entry">
			<?php foreach ( $_posts as $post ) : setup_postdata( $post ); ?>
			<dl>
				<dt class="thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $instance['thumbnail_size'] ); ?></a></dt>
				<dt class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dt>
				<dd><a href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a></dd>
			</dl>
			<?php endforeach; wp_reset_postdata(); ?>
		<!-- end .liner-recent-entries --></div>
		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		global $post;

		$title = '';
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">タイトル</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		$post_id = '';
		if ( isset( $instance[ 'post_id' ] ) ) {
			$post_id = $instance[ 'post_id' ];
		}
		$_posts = $this->get_posts();
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>">投稿</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>">
				<?php foreach ( $_posts as $_post ) : ?>
				<option value="<?php echo esc_attr( $_post->ID ); ?>" <?php selected( $post_id, $_post->ID ); ?>>
					<?php echo esc_html( get_the_title( $_post->ID ) ); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
		$thumbnail_size = '';
		if ( isset( $instance[ 'thumbnail_size' ] ) ) {
			$thumbnail_size = $instance[ 'thumbnail_size' ];
		}
		$thumbnail_sizes = get_intermediate_image_sizes();
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>">サムネイルサイズ</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>">
				<?php foreach ( $thumbnail_sizes as $_thumbnail_size ) : ?>
				<option value="<?php echo esc_attr( $_thumbnail_size ); ?>" <?php selected( $thumbnail_size, $_thumbnail_size ); ?>>
					<?php echo esc_html( $_thumbnail_size . ': ' . $this->get_thumbnail_size( $_thumbnail_size ) ); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];
		$instance['post_id'] = $new_instance['post_id'];
		$instance['thumbnail_size'] = $new_instance['thumbnail_size'];

		return $instance;
	}

	private function get_posts( $post_id = '' ) {
		$args = array(
			'meta_key' => '_thumbnail_id',
			'post_type' => 'any',
			'posts_per_page' => -1,
		);
		if ( $post_id ) {
			$args['p'] = $post_id;
			$args['posts_per_page'] = 1;
		}
		return $_posts = get_posts( $args );
	}

	private function get_thumbnail_size( $thumbnail_id ) {
		global $_wp_additional_image_sizes;
		$size = array(
			'width' => '-',
			'height' => '-',
		);
		if( in_array( $thumbnail_id, array( 'thumbnail', 'medium', 'large' ) ) ){
			$size['width'] = get_option( $thumbnail_id . '_size_w' );
			$size['height'] = get_option( $thumbnail_id . '_size_h' );
		} else {
			if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[$thumbnail_id] ) ) {
 				$size['width'] = $_wp_additional_image_sizes[$thumbnail_id]['width'];
 				$size['height'] = $_wp_additional_image_sizes[$thumbnail_id]['height'];
			}
		}
		$size = join( '&times;', $size );
		return $size;
	}
}
