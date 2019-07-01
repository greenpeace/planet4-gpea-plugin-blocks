<?php
/**
 * Articles list block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

use P4EABKS\Views\View;
use WP_Query;

if ( ! class_exists( 'Articles_List_Controller' ) ) {
	/**
	 * Class Articles_List_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Articles_List_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'articles_list';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'tag_filters';

		/**
		 * The number of posts per page.
		 *
		 * @const int POSTS_PER_PAGE
		 */
		const POSTS_PER_PAGE = 4;

		/**
		 * The nonce string.
		 *
		 * @const string NONCE_STRING
		 */
		const NONCE_STRING = 'articles_list';

		/**
		 * The list of allowed layouts to be set upon init.
		 *
		 * @var array $allowed_layouts
		 */
		private $allowed_layouts;

		/**
		 * Articles_List_Controller constructor.
		 *
		 * @param View $view The view instance.
		 */
		public function __construct( View $view ) {
			parent::__construct( $view );
			$this->allowed_layouts = [
				[
					'value' => 'tag_filters',
					'label' => __( 'Tag filters', 'planet4-gpea-blocks-backend' ),
					'desc'  => 'Tag filters',
					'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
				],
				[
					'value' => 'dropdown_filters',
					'label' => __( 'Dropdown filters', 'planet4-gpea-blocks-backend' ),
					'desc'  => 'Dropdown filters',
					'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
				],
			];
			add_action( 'wp_ajax_gpea_blocks_articles_load_more', [ $this, 'articles_load_more' ] );
			add_action( 'wp_ajax_nopriv_gpea_blocks_articles_load_more', [ $this, 'articles_load_more' ] );
		}

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label'       => 'Select the layout',
					'description' => 'Select the layout',
					'attr'        => 'layout',
					'type'        => 'radio',
					'options'     => $this->allowed_layouts,
				],
				[
					'label' => __( 'Title', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'       => 'Post type',
					'description' => 'Articles to be shown',
					'attr'        => 'article_post_type',
					'type'        => 'term_select',
					'taxonomy'    => 'p4-page-type',
					'multiple'    => false,
					'meta'        => [
						'select2_options' => [
							'allowClear'             => true,
							'placeholder'            => __( 'Select post type', 'planet4-gpea-blocks-backend' ),
							'closeOnSelect'          => true,
							'minimumInputLength'     => 0,
							'maximumSelectionLength' => 10,
						],
					],
				],
				[
					'label'    => __( 'Select tags', 'planet4-gpea-blocks-backend' ),
					'attr'     => 'tag_ids',
					'type'     => 'term_select',
					'taxonomy' => 'post_tag',
					'multiple' => true,
					'meta'     => [
						'select2_options' => [
							'allowClear'             => true,
							'placeholder'            => __( 'Select tags', 'planet4-gpea-blocks-backend' ),
							'closeOnSelect'          => true,
							'minimumInputLength'     => 0,
							'maximumSelectionLength' => 10,
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Articles List', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4EABKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

		}

		/**
		 * Get all the data that will be needed to render the block correctly.
		 *
		 * @param array  $attributes This is the array of fields of this block.
		 * @param string $content This is the post content.
		 * @param string $shortcode_tag The shortcode tag of this block.
		 *
		 * @return array The data to be passed in the View.
		 */
		public function prepare_data( $attributes, $content = '', $shortcode_tag = 'shortcake_' . self::BLOCK_NAME ) : array {

			$formatted_posts = [];

			$options = array(
				'post_type'      => array( 'post', 'page' ),
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'posts_per_page' => self::POSTS_PER_PAGE,
			);

			if ( ! isset( $attributes['_paged'] ) ) {
				$attributes['wp_nonce'] = wp_nonce_field( self::NONCE_STRING );
			}
			if ( isset( $attributes['_paged'] ) ) {
				$options['paged'] = $attributes['_paged'];
			}
			if ( isset( $attributes['_main_issue_id'] ) ) {
				$options['cat'] = $attributes['_main_issue_id'];
			}

			if ( isset( $attributes['tag_ids'] ) ) {
				$tag_ids = array_map( 'intval', explode( ',', $attributes['tag_ids'] ) );
				$options['tag__in'] = $tag_ids;
			}

			if ( isset( $attributes['article_post_type'] ) ) {
				$options['tax_query'] = array(
					array(
						'taxonomy' => 'p4-page-type',
						'field'    => 'id',
						'terms'    => $attributes['article_post_type'],
					),
				);
			}

			$query = new WP_Query( $options );

			if ( $query->posts ) {
				foreach ( $query->posts as $post ) {
					$post->link = get_permalink( $post->ID );
					if ( has_post_thumbnail( $post->ID ) ) {
						$img_id = get_post_thumbnail_id( $post->ID );
						$img_data = wp_get_attachment_image_src( $img_id, 'medium_large' );
						$post->img_url = $img_data[0];
					}

					$news_type = wp_get_post_terms( $post->ID, 'p4-page-type' );
					if ( $news_type ) {
						$post->news_type = $news_type[0]->name;
					}

					// get related main issues!
					$planet4_options = get_option( 'planet4_options' );
					$main_issues_category_id = isset( $planet4_options['issues_parent_category'] ) ? $planet4_options['issues_parent_category'] : false;
					if ( ! $main_issues_category_id ) {
						$main_issues_category = get_term_by( 'slug', 'issues', 'category' );
						if ( $main_issues_category ) {
							$main_issues_category_id = $main_issues_category->term_id;
						}
					}

					if ( $main_issues_category_id ) {
						$categories = get_the_category( $post->ID );
						if ( ! empty( $categories ) ) {
							$categories = array_filter(
								$categories, function( $cat ) use ( $main_issues_category_id ) {
									return intval( $main_issues_category_id ) === $cat->category_parent;
								}
							);
							if ( ! empty( $categories ) ) {
								$first_category = array_values( $categories )[0];
								$post->main_issue = $first_category->name;
								$post->main_issue_slug = $first_category->slug;
							}
						}
					}

					$formatted_posts[] = $post;
				}
			}

			wp_reset_postdata();

			$attributes['posts'] = $formatted_posts;
			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

			// Layout-specific queries.
			if ( 'tag_filters' === $attributes['layout'] ) {

				if ( isset( $attributes['tag_ids'] ) ) {
					$tag_names = array();
					foreach ( $tag_ids as $tag_id ) {
						$tag = get_term( $tag_id );
						if ( $tag ) {
							$tag_names[] = $tag->name;
						} else {
							$tag_names[] = '';
						}
					}

					$attributes['tags'] = array_combine( $tag_names, $tag_ids );
				}
			} elseif ( 'dropdown_filters' === $attributes['layout'] ) {

				$options = array(
					'post_type'      => array( 'post', 'page' ),
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'posts_per_page' => 1,
					'order'          => 'ASC',
				);
				if ( isset( $attributes['tag_ids'] ) ) {
					$options['tag__in'] = explode( ',', $attributes['tag_ids'] );
				}
				$query = new WP_Query( $options );
				if ( $query->posts ) {
					$attributes['year_oldest'] = date( 'Y' , strtotime( $query->posts[0]->post_date ) );
					$attributes['year_now'] = date( 'Y' );
				}
				wp_reset_postdata();

				$issues = get_category_by_slug( 'issues' );
				if ( $issues ) {
					$main_issues_array = array();
					$main_issues = get_terms(
						'category',
						array(
							'parent' => $issues->term_id,
						)
					);
					foreach ( $main_issues as $main_issue ) {
						$main_issues_array[ $main_issue->name ] = $main_issue->term_id;
					}
					$attributes['main_issues'] = $main_issues_array;
				}
			}

			$lexicon = [
				'load_more' => __( 'Load more posts', 'planet4-gpea-blocks' ),
			];

			return [
				'fields'  => $attributes,
				'lexicon' => $lexicon,
			];

		}

		/**
		 * Callback for the shortcake_noindex shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields        Array of fields that are to be used in the template.
		 * @param string $content       The content of the post.
		 * @param string $shortcode_tag The shortcode tag (shortcake_blockname).
		 *
		 * @return string The complete html of the block
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			// echo '<pre>' . print_r( $data, true ) . '</pre>';
			return ob_get_clean();
		}

		/**
		 * Get post results for AJAX autocomplete.
		 */
		public function articles_load_more() {
			if ( 'POST' === filter_input( INPUT_SERVER, 'REQUEST_METHOD' ) ) {
				$query = $this->validate_input();
				if ( $query ) {
					$fields = [
						'layout'            => $query['l'] ?? self::DEFAULT_LAYOUT,
						'article_post_type' => $query['apt'],
						'tag_ids'           => $query['tid'],
						'_main_issue_id'    => $query['miid'],
						'_paged'            => 2,
					];
					$fields = array_filter(
						$fields, function( $field ) {
							return $field;
						}
					);
					$data = $this->prepare_data( $fields );
					$this->safe_echo( wp_json_encode( $data['fields']['posts'] ), false );
				} else {
					$this->safe_echo( 'Something\'s wrong with the request...', false );
				}
			}
		}

		/**
		 * Validate input AJAX data.
		 *
		 * @return array|bool The query data, or false if unsafe.
		 */
		function validate_input() {
			$args = [
				'query' => [
					'filter' => FILTER_SANITIZE_STRING,
					'flags'  => FILTER_REQUIRE_ARRAY,
				],
			];
			$query = filter_input_array( INPUT_POST , $args , false )['query'];
			if ( ! $query ) {
				return false;
			}
			if ( ! wp_verify_nonce( $query['_wpnonce'], self::NONCE_STRING ) ) {
				return false;
			}
			$allowed_layouts = array_map(
				function( $l ) {
					return $l['value'];
				}, $this->allowed_layouts
			);
			if ( ! in_array( $query['l'], $allowed_layouts, true ) ) {
				return false;
			}
			if ( ! preg_match( '/^\d*$/', $query['apt'] ) ) {
				return false;
			}
			if ( ! preg_match( '/^\d*$/', $query['miid'] ) ) {
				return false;
			}
			if ( ! preg_match( '/^(\d+,?)*$/', $query['tid'] ) ) {
				return false;
			}
			return $query;
		}

		/**
		 * Echo escaped response and stop processing the request.
		 *
		 * @param string $string The message to be sent.
		 * @param bool   $escape Whether to esc_html $string.
		 */
		private function safe_echo( $string, $escape = true ) {
			echo $escape ? esc_html( $string ) : $string;
			wp_die();
		}

	}
}
