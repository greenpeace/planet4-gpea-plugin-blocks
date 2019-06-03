<?php
/**
 * Mixed content row block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Tips_Controller' ) ) {
	/**
	 * Class Tips_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 * @since 0.1.3
	 */
	class Tips_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'tips';

		/**
		 * Shortcode UI setup for the tasks shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * This example shortcode has many editable attributes, and more complex UI.
		 *
		 * @since 1.0.0
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Title', 'planet4-gpea-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Subtitle', 'planet4-gpea-blocks' ),
					'attr'  => 'subtitle',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Paragraph', 'planet4-gpea-blocks' ),
					'attr'  => 'paragraph',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Paragraph', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'       => __( 'Tips (10 max)', 'planet4-gpea-blocks' ),
					'attr'     => 'tip_ids',
					'type'     => 'post_select',
					'multiple' => 'multiple',
					'query'    => [
						'post_type'   => array( 'tips' ),
						'post_status' => 'publish',
						'orderby'     => 'post_title',
						'order'       => 'ASC',
					],
					'meta'     => [
						'select2_options' => [
							'allowClear'             => true,
							'placeholder'            => __( 'Select tips (max 10)', 'planet4-gpea-blocks' ),
							'closeOnSelect'          => false,
							'minimumInputLength'     => 0,
							'multiple'               => true,
							'maximumSelectionLength' => 10,
							'width'                  => '80%',
						],
					],

				],
				[
					'label' => __( 'Label "See more"', 'planet4-gpea-blocks' ),
					'attr'  => 'see_more_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Label "See more"', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Link "See more"', 'planet4-gpea-blocks' ),
					'attr'  => 'see_more_link',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Link "See more"', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Tips', 'planet4-gpea-blocks' ),
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

			if ( isset( $attributes['tip_ids'] ) ) {

				$query = new \WP_Query(
					array(
						'post_type'   => array( 'tips' ),
						'post_status' => 'publish',
						'post__in' => explode( ',' , $attributes['tip_ids'] ),
						'orderby' => 'post__in',
						'numberposts' => 10,
					)
				);

				if ( $query->posts ) {
					foreach ( $query->posts as $post ) {
						$tip_icon = get_post_meta( $post->ID, 'p4-gpea_tip_icon', true );
						$post->img_url = $tip_icon ?? '';
						$frequency = get_post_meta( $post->ID, 'p4-gpea_tip_frequency', true );
						$post->frequency = $frequency ?? '';
						// TODO:
						// - abstract this one to parent.
						// - also avoid magic constant 'issues'.
						$issues = get_category_by_slug( 'issues' );
						$issues = $issues->term_id;
						$categories = get_the_category( $post->ID );
						$categories = array_filter(
							$categories , function( $cat ) use ( $issues ) {
								return $cat->category_parent === $issues;
							}
						);
						$categories = array_map(
							function( $cat ) {
									return $cat->slug;
							}, $categories
						);
						$categories = join( ', ', $categories );
						$post->categories = $categories ?? '';
						$formatted_posts[] = $post;
					}
				}

				wp_reset_postdata();
			}

			$attributes['posts'] = $formatted_posts;

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
			return ob_get_clean();
		}
	}
}
