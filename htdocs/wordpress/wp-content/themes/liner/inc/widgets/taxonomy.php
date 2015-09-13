<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.0
 */

/**
 * liner_override_widget_categories
 */
function liner_override_widget_categories() {
	class Liner_Widget_Categories_Taxonomy extends WP_Widget_Categories {
		private $taxonomy = 'category';

		public function widget( $args, $instance ) {
			if ( !empty( $instance['taxonomy'] ) ) {
				$this->taxonomy = $instance['taxonomy'];
			}

			add_filter( 'widget_categories_dropdown_args', array( $this, 'add_taxonomy_dropdown_args' ), 10 );
			add_filter( 'widget_categories_args', array( $this, 'add_taxonomy_dropdown_args' ), 10 );
			parent::widget( $args, $instance );
		}

		public function update( $new_instance, $old_instance ) {
			$instance = parent::update( $new_instance, $old_instance );
			$taxonomies = $this->get_taxonomies();
			$instance['taxonomy'] = 'category';
			if ( in_array( $new_instance['taxonomy'], $taxonomies ) ) {
				$instance['taxonomy'] = $new_instance['taxonomy'];
			}
			return $instance;
		}

		public function form( $instance ) {
			parent::form( $instance );
			$taxonomy = 'category';
			if ( !empty( $instance['taxonomy'] ) ) {
				$taxonomy = $instance['taxonomy'];
			}
			$taxonomies = $this->get_taxonomies();
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php _e( 'Taxonomy:', 'liner' ); ?></label><br />
				<select id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">
					<?php foreach ( $taxonomies as $value ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $taxonomy, $value ); ?>><?php echo esc_attr( $value ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php
		}

		public function add_taxonomy_dropdown_args( $cat_args ) {
			$cat_args['taxonomy'] = $this->taxonomy;
			return $cat_args;
		}

		private function get_taxonomies() {
			$taxonomies = get_taxonomies( array(
				'public' => true,
			) );
			return $taxonomies;
		}
	}
	unregister_widget( 'WP_Widget_Categories' );
	register_widget( 'Liner_Widget_Categories_Taxonomy' );
}
add_action( 'widgets_init', 'liner_override_widget_categories' );
