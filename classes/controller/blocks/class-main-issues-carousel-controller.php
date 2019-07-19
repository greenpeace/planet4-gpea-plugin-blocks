<?php
/**
 * Main issues carousel block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Main_Issues_Carousel_Controller' ) ) {
	/**
	 * Class Main_Issues_Carousel_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Main_Issues_Carousel_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'main_issues_carousel';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'default';

		/**
		 * The number of main issues to display.
		 *
		 * @const string MAIN_ISSUE_COUNT
		 */
		const MAIN_ISSUE_COUNT = 6;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'radio',
					'options' => [
						[
							'value' => 'carousel',
							'label' => __( 'Inline', 'planet4-gpea-blocks-backend' ),
							'desc'  => 'Inline, carousel behaviour on smaller screen - default',
						],
						[
							'value' => 'list',
							'label' => __( 'List', 'planet4-gpea-blocks-backend' ),
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
					'label' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Main issues carousel', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/main_issues.png' ) . '" />',
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

			$query = new \WP_Query(
				array(
					'order'       => 'desc',
					'orderby'     => 'date',
					'post_type'   => 'page',
					'posts_per_page' => self::MAIN_ISSUE_COUNT,
					'meta_key'    => '_wp_page_template',
					'meta_value'  => 'page-templates/main-issue.php',
				)
			);

			if ( $query->posts ) {
				foreach ( $query->posts as $post ) {
					if ( has_post_thumbnail( $post->ID ) ) {
						$img_id = get_post_thumbnail_id( $post->ID );
						$img_data = wp_get_attachment_image_src( $img_id, 'medium' );
						$post->img_url = $img_data[0];
					}

					$post->link = get_permalink( $post->ID );

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
								$post->main_issue_description = $first_category->description;
							}
						}
					}

					// count associated achievments!
					$count_args = array(
						'post_type'   => 'post',							
						'tag'    => 'achievement',
						'category_name'  => $post->main_issue_slug,
					);
					$count_query = new \WP_Query( $count_args );
					$post->related_posts = $count_query->post_count;

					$formatted_posts[] = $post;
				}
			}
			$attributes['categories'] = $formatted_posts;
			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

			wp_reset_postdata();

			// lexicon entries
			$lexicon['achievements'] = __( 'Achievements', 'planet4-gpea-blocks' );

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
