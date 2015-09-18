<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.1
 */

/**
 * Init Liner_Recent_Entries_Widget
 */
function liner_recent_entries_widget() {
	register_widget( 'Liner_Recent_Entries_Widget' );
};
add_action( 'widgets_init', 'liner_recent_entries_widget' );

/**
 * Liner_Recent_Entries_Widget
 */
class Liner_Recent_Entries_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'liner_recent_entries_widget',
			'最近の投稿（任意の投稿タイプ）',
			array(
				'description' => '任意の投稿タイプの最近の投稿',
			)
		);
	}

	public function widget( $args, $instance ) {
		global $post;
		$post_type      = ( isset( $instance['post_type'] ) )      ? $instance['post_type']      : 'post';
		$posts_per_page = ( isset( $instance['posts_per_page'] ) ) ? $instance['posts_per_page'] : 5;
		$title          = ( isset( $instance['title'] ) )          ? $instance['title']          : '';
		$_posts = get_posts( array(
			'post_type' => $post_type,
			'posts_per_page' => $posts_per_page,
		) );
		$title = apply_filters( 'widget_title', $title );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<div class="liner-recent-entries">
			<ul>
				<?php foreach ( $_posts as $post ) : setup_postdata( $post ); ?>
				<li>
					<dl>
						<dt class="published"><?php echo get_the_date(); ?></dt>
						<dd><a href="<?php the_permalink(); ?>"><?php the_title(); ?><span class="read-more">Read More »</span></a></dd>
					</dl>
				</li>
				<?php endforeach; wp_reset_postdata(); ?>
			</ul>
		<!-- end .liner-recent-entries --></div>
		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ) {
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
		$post_type = 'post';
		if ( isset( $instance[ 'post_type' ] ) ) {
			$post_type = $instance[ 'post_type' ];
		}
		$post_types = $this->get_post_types();
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>">投稿タイプ</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
				<?php foreach ( $post_types as $_post_type ) : ?>
				<option value="<?php echo esc_attr( $_post_type->name ); ?>" <?php selected( $post_type, $_post_type->name ); ?>>
					<?php echo esc_html( $_post_type->labels->singular_name ); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
		$posts_per_page = 5;
		if ( isset( $instance[ 'posts_per_page' ] ) ) {
			$posts_per_page = $instance[ 'posts_per_page' ];
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>">投稿件数</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>">
				<?php for ( $i = 0; $i <= 10; $i ++ ) : ?>
				<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $i, $posts_per_page ); ?>>
					<?php echo esc_html( $i ); ?>件
				</option>
				<?php endfor; ?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];

		$post_types = $this->get_post_types();
		if ( array_key_exists( $new_instance['post_type'], (array)$post_types ) ) {
			$instance['post_type'] = $new_instance['post_type'];
		}

		if ( preg_match( '/^\d+$/', $new_instance['posts_per_page'] ) ) {
			$instance['posts_per_page'] = $new_instance['posts_per_page'];
		}

		return $instance;
	}

	private function get_post_types() {
		return $post_types = get_post_types( array(
			'public' => true,
		), 'objects' );
	}
}
