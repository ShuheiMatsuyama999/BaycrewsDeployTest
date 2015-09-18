<?php
/**
 * @author NetBusinessAgent
 * @version 1.2.1
 */
class liner_bread_crumb {
	private $bread_crumb = array();

	public function __construct() {
	}

	private function set( $title, $link = false ) {
		$this->bread_crumb[] = array(
			'title' => $title,
			'link' => $link,
		);
	}

	private function get_post_type_archive_link( $post_type ) {
		$post_type_archive_link = get_post_type_archive_link( $post_type );
		if ( !$post_type_archive_link ) {
			$template_page = liner_get_template_used_page( $post_type );
			if ( !empty( $template_page->ID ) ) {
				$post_type_archive_link = get_permalink( $template_page->ID );
			}
		}
		return $post_type_archive_link;
	}

	public function display( $params ) {
		global $wp_query;

		$page_on_front = get_option( 'page_on_front' );
		$home_label = 'home';
		if ( $page_on_front ) {
			$home_label = get_the_title( $page_on_front );
		}

		$defaults = array(
			'home_label' => $home_label,
		);
		$params = shortcode_atts( $defaults, $params );

		if ( is_404() ) {
			$this->set( 'ページが見つかりませんでした。' );
		}
		elseif ( is_search() ) {
			$this->set( '「' . get_search_query() . '」の検索結果' );
		}
		elseif ( is_tax() ) {
			$taxonomy = get_query_var( 'taxonomy' );
			$term = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );

			$taxonomy_objects = get_taxonomy( $taxonomy );
			$post_types = $taxonomy_objects->object_type;
			foreach ( $post_types as $post_type ) {
				$template_page = liner_get_template_used_page( $post_type );
				if ( !empty( $template_page->ID ) ) {
					$ancestors = get_ancestors( $template_page->ID, 'page' );
					krsort( $ancestors );
					foreach ( $ancestors as $ancestor_id ) {
						$this->set( get_the_title( $ancestor_id ), get_permalink( $ancestor_id ) );
					}
					$label = get_the_title( $template_page->ID );
				} else {
					$post_type_object = get_post_type_object( $post_type );
					$label = $post_type_object->labels->singular_name;
				}
				$this->set( $label, $this->get_post_type_archive_link( $post_type ) );
				break;
			}

			if ( is_taxonomy_hierarchical( $taxonomy ) && $term->parent ) {
				$ancestors = get_ancestors( $term->term_id, $taxonomy );
				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, $taxonomy );
					$this->set( $ancestor->name, get_term_link( $ancestor ) );
				}
			}
			$this->set( $term->name );
		}
		elseif ( is_attachment() ) {
			$this->set( get_the_title() );
		}
		elseif ( is_page() && !is_front_page() ) {
			$ancestors = get_ancestors( get_the_ID(), 'page' );
			krsort( $ancestors );
			foreach ( $ancestors as $ancestor_id ) {
				$this->set( get_the_title( $ancestor_id ), get_permalink( $ancestor_id ) );
			}
			$this->set( get_the_title() );
		}
		elseif ( is_single() ) {
			$post_type = $wp_query->get( 'post_type' );
			if ( $post_type ) {
				$post_type_object = get_post_type_object( $post_type );
				$template_page = liner_get_template_used_page( $post_type );
				if ( !empty( $template_page->ID ) ) {
					$ancestors = get_ancestors( $template_page->ID, 'page' );
					krsort( $ancestors );
					foreach ( $ancestors as $ancestor_id ) {
						$this->set( get_the_title( $ancestor_id ), get_permalink( $ancestor_id ) );
					}
					$label = get_the_title( $template_page->ID );
				} else {
					$label = $post_type_object->labels->singular_name;
				}
				$this->set( $label, $this->get_post_type_archive_link( $post_type ));
				$taxonomies = $post_type_object->taxonomies;
				if ( $taxonomies ) {
					foreach ( $taxonomies as $taxonomy ) {
						$terms = get_the_terms( get_the_ID(), $taxonomy );
						break;
					}
					if ( $terms ) {
						$term =  array_shift( $terms );
						$ancestors = get_ancestors( $term->term_id, $taxonomy );
						$ancestors[] = $term;
						foreach ( $ancestors as $ancestor_id ) {
							$ancestor = get_term( $ancestor_id, $taxonomy );
							$this->set( $ancestor->name, get_term_link( $ancestor ) );
							break;
						}
					}
				}
			}
			else {
				$categories = get_the_category( get_the_ID() );
				if ( $categories ) {
					$category = array_shift( $categories );
					$ancestors = get_ancestors( $category->term_id, 'category' );
					$ancestors[] = $category;
					foreach ( $ancestors as $ancestor_id ) {
						$ancestor = get_term( $ancestor_id, 'category' );
						$this->set( $ancestor->name, get_term_link( $ancestor ) );
					}
				}
			}
			$this->set( get_the_title() );
		}
		elseif ( is_category() ) {
			$category_name = single_cat_title( '', false );
			$category_id = get_cat_ID( $category_name );
			$ancestors = get_ancestors( $category_id, 'category' );
			foreach ( $ancestors as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, 'category' );
				$this->set( $ancestor->name, get_term_link( $ancestor ) );
			}
			$this->set( $category_name );
		}
		elseif ( is_tag() ) {
			$this->set( single_tag_title( '', false ) );
		}
		elseif ( is_author() ) {
			$author_id = get_query_var( 'author' );
			$this->set( get_the_author_meta( 'display_name', $author_id ) );
		}
		elseif ( is_day() ) {
			$year = get_query_var( 'year' );
			if ( !$year ) {
				$m = get_query_var( 'm' );
				$year = substr( $m, 0, 4 );
				$month = substr( $m, 4, 2 );
				$day = substr( $m, -2 );
			} else {
				$month = get_query_var( 'monthnum' );
				$day = get_query_var( 'day' );
			}
			$this->set( $year . '年', get_year_link( $year ) );
			$this->set( $month . '月', get_month_link( $year, $month ) );
			$this->set( $day . '日' );
		}
		elseif ( is_month() ) {
			$year = get_query_var( 'year' );
			if ( !$year ) {
				$m = get_query_var( 'm' );
				$year = substr( $m, 0, 4 );
				$month = substr( $m, -2 );
			} else {
				$month = get_query_var( 'monthnum' );
			}
			$this->set( $year . '年', get_year_link( $year ) );
			$this->set( $month . '月' );
		}
		elseif ( is_year() ) {
			$year = get_query_var( 'year' );
			if ( !$year ) {
				$m = get_query_var( 'm' );
				$year = $m;
			}
			$this->set( $year . '年' );
		}
		else {
			if ( !is_front_page() ) {
				$this->set( wp_title( '', false, '' ) );
			}
		}

		$bread_crumb = array();
		$bread_crumb[] = sprintf( '<a href="%s">%s</a>',
			esc_url( home_url() ),
			esc_html( $params['home_label'] )
		);
		$post_type = $wp_query->get( 'post_type' );
		if ( !$post_type ) {
			$post_type = get_post_type();
		}
		if ( ( is_category() || is_tag() || is_date() || is_author() ) ||
			 ( is_archive() && !$post_type && !is_tax() ) ||
			 ( is_single() && $post_type === 'post' ) ) {
			if ( $page_for_posts = get_option( 'page_for_posts' ) ) {
				$bread_crumb[] = sprintf( '<a href="%s">%s</a>',
					esc_url( get_permalink( $page_for_posts ) ),
					esc_html( get_the_title( $page_for_posts ) )
				);
			}
		}
		foreach ( $this->bread_crumb as $_bread_crumb ) {
			if ( !empty( $_bread_crumb['link'] ) ) {
				$bread_crumb[] = sprintf( '<a href="%s">%s</a>',
					esc_url( $_bread_crumb['link'] ),
					esc_html( $_bread_crumb['title'] )
				);
			} else {
				$bread_crumb[] = sprintf( '%s',
					esc_html( $_bread_crumb['title'] )
				);
			}
		}
		echo '<div class="liner-bread-crumb">' . implode( ' &gt; ', $bread_crumb ) . '</div>';
	}
}
