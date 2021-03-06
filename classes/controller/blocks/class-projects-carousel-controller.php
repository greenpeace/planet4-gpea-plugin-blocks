<?php
/**
 * Projects carousel block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Projects_Carousel_Controller' ) ) {
	/**
	 * Class Projects_Carousel_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Projects_Carousel_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'projects_carousel';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'dark';

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
							'value' => 'dark',
							'label' => __( 'Dark background, light text', 'planet4-gpea-blocks-backend' ),
							'desc'  => 'Dark background, light text',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'light',
							'label' => __( 'Light background, dark text', 'planet4-gpea-blocks-backend' ),
							'desc'  => 'Light background, dark text',
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
					'label' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'paragraph',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'    => __( 'Filter by Main Issue', 'planet4-gpea-blocks-backend' ),
					'attr'     => 'main_issue',
					'type'     => 'term_select',
					'taxonomy' => 'category',
					'multiple' => false,
					'meta'     => [
						'select2_options' => [
							'allowClear'         => true,
							'placeholder'        => __( 'Select Main Issue', 'planet4-gpea-blocks-backend' ),
							'closeOnSelect'      => true,
							'minimumInputLength' => 0,
						],
					],
				],
				[
					'label'    => __( 'Filter by Topic', 'planet4-gpea-blocks-backend' ),
					'attr'     => 'topic',
					'type'     => 'term_select',
					'taxonomy' => 'post_tag',
					'multiple' => false,
					'meta'     => [
						'select2_options' => [
							'allowClear'         => true,
							'placeholder'        => __( 'Select Topic', 'planet4-gpea-blocks-backend' ),
							'closeOnSelect'      => true,
							'minimumInputLength' => 0,
						],
					],
				],
				[
					'label'       => __( 'Carousel Items (max 8)', 'planet4-gpea-blocks-backend' ),
					'attr'     => 'carousel_item_ids',
					'type'     => 'post_select',
					'multiple' => 'multiple',
					'query'    => [
						'post_type'   => array( 'page' ),
						'post_status' => 'publish',
						'orderby'     => 'post_title',
						'order'           => 'ASC',
						'meta_key'    => '_wp_page_template',
						'meta_value'  => 'page-templates/project.php',
					],
					'meta'     => [
						'select2_options' => [
							'allowClear'             => true,
							'placeholder'            => __( 'Select carousel items (max 8)', 'planet4-gpea-blocks-backend' ),
							'closeOnSelect'          => false,
							'minimumInputLength'     => 0,
							'multiple'               => true,
							'maximumSelectionLength' => 8,
							'width'                  => '80%',
						],
					],
				],
				[
					'label' => __( 'Link label "See more"', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'see_more_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Link label "See more"', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Link URL "See more"', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'see_more_link',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Link URL "See more"', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Show vertical list', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'show_vertical',
					'type'  => 'checkbox',
					'meta'  => [
						'placeholder' => __( 'Show vertical list', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Projects Carousel', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/projects_block.png' ) . '" />',
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

			// Check if result needs to be filtered by category.
			$cat_id = $attributes['main_issue'] ?? '';

			$tag_id = $attributes['topic'] ?? '';

			if ( isset( $attributes['carousel_item_ids'] ) ) {

				$options = array(
					'post_type'   => array( 'post', 'page' ),
					'post_status' => 'publish',
					'post__in'    => explode( ',', $attributes['carousel_item_ids'] ),
					'orderby'     => 'post__in',
					'posts_per_page' => 8,
				);
			} else {
				// Project block default text setting.
				$options = array(
					'order'       => 'desc',
					'orderby'     => 'date',
					'post_type'   => 'page',
					'posts_per_page' => 20,
					'meta_key'    => '_wp_page_template',
					'meta_value'  => 'page-templates/project.php',
				);

				if ( '' !== $cat_id ) {
					$options['tax_query'] = array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'category',
							'field' => 'term_id',
							'terms' => $cat_id,
						),
					);
				}
				if ( '' !== $tag_id ) {
					$options['tax_query'][] = array(
						'taxonomy' => 'post_tag',
						'field' => 'term_id',
						'terms' => $tag_id,
					);
				}
			}

			$query = new \WP_Query( $options );

			if ( $query->posts ) {
				foreach ( $query->posts as $post ) {
					if ( has_post_thumbnail( $post->ID ) ) {
						$img_id = get_post_thumbnail_id( $post->ID );
						$img_data = wp_get_attachment_image_src( $img_id, 'medium_large' );
					}
					$project_percent = get_post_meta( $post->ID, 'p4-gpea_project_percentage', true );
					$post->link = get_permalink( $post->ID );
					$post->img_url = $img_data[0] ?? '';
					$post->project_percentage = (int) $project_percent;
					$post->stroke_dashoffset = $project_percent ? 697.433 * ( ( 100 - $project_percent ) / 100 ) : 0;
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
					// count associated posts!
					$count_args = array(
						'post_type'   => 'post',							
						'meta_key'    => 'p4_select_project_related',
						'meta_value'  => $post->ID,
					);
					$count_query = new \WP_Query( $count_args );
					$post->related_posts = $count_query->found_posts;

					$formatted_posts[] = $post;
				}
			}

			wp_reset_postdata();

			$attributes['posts'] = $formatted_posts;
			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

			// lexicon entries
			$lexicon['updates'] = __( 'Updates', 'planet4-gpea-blocks' );

			return [
				'fields' => $attributes,
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
			return ob_get_clean();
		}
	}
}
