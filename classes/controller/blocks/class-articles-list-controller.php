<?php
/**
 * Articles list block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

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
					'options' => [
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
					],
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
				'posts_per_page' => 4,
			);

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

			$query = new \WP_Query( $options );

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
						if ( $main_issues_category ) $main_issues_category_id = $main_issues_category->term_id;
					}

					if ( $main_issues_category_id ) {
						$categories = get_the_category( $post->ID );
						if ( ! empty( $categories ) ) {
							$categories = array_filter( $categories, function( $cat ) use ( $main_issues_category_id ) {
								return $cat->category_parent === intval( $main_issues_category_id );
							});
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

				// $tags = get_terms(
				// 	'post_tag',
				// 	array(
				// 		'include' => $tag_ids,
				// 	)
				// );

				if ( isset( $attributes['tag_ids'] ) ) {
					$tag_names = array();
					foreach ( $tag_ids as $tag_id ) {
						$tag = get_term($tag_id);
						if ($tag) $tag_names[] = $tag->name;
						else $tag_names[] = '';
					}

					// $tag_names = array_map(
					// 	function( $tag ) {
					// 		return $tag->name;
					// 	}, $tags
					// );

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
				$query = new \WP_Query( $options );
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
			} else {
				// Do nothing.
			}			

			return [
				'fields' => $attributes,
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
	}
}
