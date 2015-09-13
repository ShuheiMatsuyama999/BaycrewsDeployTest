<?php
/**
 * @author NetBusinessAgent
 * @version 1.0.3
 */
class Liner_Front_Page_Widget_Areas {

	private $fields = array();
	private $default_attr = array();

	/**
	 * __construct
	 * 本体の admin_menu フックから呼ばれる
	 */
	public function __construct() {
		$front_page_widget_areas = get_theme_mod( 'front_page_widget_areas' );
		$this->default_attr = array(
			'name' => '',
			'columns' => 1,
			'title' => '',
			'bgcolor' => '',
		);

		add_action( 'admin_menu', array( $this, 'activate' ) );
	}

	private function get_directory_uri() {
		return get_template_directory_uri() . '/inc/front-page/';
	}

	public function activate() {
		$hook = add_theme_page(
			'トップページ設定',
			'トップページ設定',
			apply_filters( 'liner_settings_page_capability', 'edit_theme_options' ),
			basename( __FILE__ ),
			array( $this, 'settings_page' )
		);
		add_action( 'load-' . $hook, array( $this, 'update' ) );

		add_action( 'admin_print_styles-' . $hook, array( $this, 'admin_style' ) );
		add_action( 'admin_print_scripts-' . $hook, array( $this, 'admin_scripts' ) );
	}

	/**
	 * admin_style
	 * CSS適用
	 */
	public function admin_style() {
		$url = $this->get_directory_uri();
		wp_register_style( 'liner-admin-front-page', $url . './css/admin.css' );
		wp_enqueue_style( 'liner-admin-front-page' );
	}

	/**
	 * admin_scripts
	 * JavaScript適用
	 */
	public function admin_scripts() {
		$url = $this->get_directory_uri();
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_register_script( 'liner-admin-front-page', $url . './js/admin.js' );
		wp_enqueue_script( 'liner-admin-front-page' );
	}

	public function update() {
		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'complete_front_page' ) ) {
			check_admin_referer( 'complete_front_page' );
			
			$_POST = stripslashes_deep( $_POST );

			if ( isset( $_POST['liner']['front_page_widget_areas'] ) ) {
				$_front_page_widget_areas = $_POST['liner']['front_page_widget_areas'];
				$front_page_widget_areas = array();
				foreach ( $_front_page_widget_areas as $widget_count => $front_page_widget_area ) {
					$front_page_widget_area = shortcode_atts( $this->default_attr, $front_page_widget_area );
					if ( empty( $front_page_widget_area['name'] ) ) {
						continue;
					}
					if ( !preg_match( '/^[\d]+$/', $front_page_widget_area['columns'] ) ) {
						$front_page_widget_area['columns'] = $this->default_attr['columns'];
					}
					if ( !preg_match( '/^([\da-fA-F]{6}|[\da-fA-F]{3})$/', $front_page_widget_area['bgcolor'] ) ) {
						$front_page_widget_area['bgcolor'] = $this->default_attr['bgcolor'];
					}
					$front_page_widget_area['id'] = 'front-page-widget-areas-' . $widget_count;
					$front_page_widget_areas[ $front_page_widget_area['id'] ] = $front_page_widget_area;
				}
				set_theme_mod( 'front_page_widget_areas', $front_page_widget_areas );
			} else {
				set_theme_mod( 'front_page_widget_areas', array() );
			}

			do_action( 'liner_front_page_update' );

			wp_redirect( admin_url( '/admin.php?page=front-page.php&complete_add_widget_area=true' ) );
			exit;
		}
	}

	public function settings_page() {
		?>
		<div class="wrap">
			<h2>トップページ設定</h2>

			<?php if ( isset( $_GET['complete_add_widget_area'] ) && $_GET['complete_add_widget_area'] === 'true' ) : ?>
			<div id="message" class="updated">
				<p>
					トップページウィジェットエリアが更新されました。
				</p>
			<!-- end #message --></div>
			<?php endif; ?>

			<form method="post" action="">
				<div class="liner-front-page-widget-areas-setting">
					<h3>ウィジェットエリア設定</h3>
					<p>
						<span class="liner-add-field button">ウィジェットエリアを追加</span>
					</p>
					<table id="liner-front-page-widget-areas" border="0" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th class="liner-delete-column">&nbsp;</th>
								<th>ウィジェットエリア名</th>
								<th>カラム数</th>
								<th>タイトル</th>
								<th>背景色</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$front_page_widget_areas = get_theme_mod( 'front_page_widget_areas' );
							if ( !$front_page_widget_areas )
								$front_page_widget_areas = array();
							array_unshift( $front_page_widget_areas, $this->default_attr );
							?>
							<?php $i = 0; foreach ( $front_page_widget_areas as $key => $front_page_widget_area ) : ?>
							<?php
							$front_page_widget_area = shortcode_atts( $this->default_attr, $front_page_widget_area );
							$id = preg_replace( '/^.+?\-(\d+)$/', '$1', $key );
							?>
							<tr <?php if ( $i === 0 ) : ?> style="display:none"<?php endif; ?>>
								<?php $name = 'liner[front_page_widget_areas][' . $id . ']'; ?>
								<td class="liner-delete-column">
									<span class="liner-delete button">×</span>
								</td>
								<td>
									<input type="text" name="<?php echo esc_attr( $name ); ?>[name]" value="<?php echo esc_attr( $front_page_widget_area['name'] ); ?>" />
								</td>
								<td>
									<select name="<?php echo esc_attr( $name ); ?>[columns]">
										<?php for ( $j = 1; $j <= 4; $j ++ ) : ?>
										<option value="<?php echo esc_attr( $j ); ?>" <?php selected( $j, $front_page_widget_area['columns'] ); ?>>
											<?php echo esc_html( $j ); ?>
										</option>
										<?php endfor; ?>
									</select>
								</td>
								<td>
									<input type="text" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $front_page_widget_area['title'] ); ?>" />
								</td>
								<td>
									#<input size="7" maxlength="6" type="text" name="<?php echo esc_attr( $name ); ?>[bgcolor]" value="<?php echo esc_attr( $front_page_widget_area['bgcolor'] ); ?>" />
								</td>
							</tr>
							<?php $i ++; endforeach; ?>
						</tbody>
					</table>
					<p>
						ドラッグアンドドロップで並び替えできます。<br />
						各ウィジェットエリアに配置するウィジェットは「<a href="./widgets.php">ウィジェット</a>」から設定できます。
					</p>
				<!-- end .liner-front-page-widget-areas-setting --></div>

				<?php do_action( 'liner_front_page_settings' ); ?>

				<p class="submit">
					<?php wp_nonce_field( 'complete_front_page' ); ?>
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'liner' ) ?>" />
				</p>
			</form>
		<!-- end .wrap --></div>
		<?php
	}
}
$Liner_Front_Page_Widget_Areas = new Liner_Front_Page_Widget_Areas();
