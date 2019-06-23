<?php
/**
 * Testimonials block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Testimonials_Controller' ) ) {
	/**
	 * Class Testimonials_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 * @since 0.1.3
	 */
	class Testimonials_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME   = 'testimonials';

		/**
		 * The maximum number of sub-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 20;

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
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'radio',
					'options' => [
						[
							'value' => 'carousel',
							'label' => __( 'Carousel', 'planet4-gpea-blocks' ),
							'desc'  => 'Carousel',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'tiny-carousel',
							'label' => __( 'Tiny carousel', 'planet4-gpea-blocks' ),
							'desc'  => 'Tiny carousel',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						// [
						// 	'value' => 'list',
						// 	'label' => __( 'List', 'planet4-gpea-blocks' ),
						// 	'desc'  => 'List',
						// 	'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						// ],
					],
				],
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
					'label'       => __( 'Select the team people for testimonials', 'planet4-gpea-blocks' ),
					'attr'     => 'testimonial_ids',
					'type'     => 'post_select',
					'multiple' => 'multiple',
					'query'    => [
						'post_type'   => array( 'team' ),
						'post_status' => 'publish',
						'orderby'     => 'post_title',
						'order'           => 'ASC',
					],
				],
				[
					'label' => __( 'Link label 1', 'planet4-gpea-blocks' ),
					'attr'  => 'link_label_1',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Link label 1', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Link URL 1', 'planet4-gpea-blocks' ),
					'attr'  => 'link_url_1',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Link URL 1', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Link label 2', 'planet4-gpea-blocks' ),
					'attr'  => 'link_label_2',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Link label 2', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Link URL 2', 'planet4-gpea-blocks' ),
					'attr'  => 'link_url_2',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Link URL 2', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Testimonials', 'planet4-gpea-blocks' ),
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

			if ( isset( $attributes['testimonial_ids'] ) ) {

				$query = new \WP_Query(
					array(
						'post_type'   => array( 'team' ),
						'post_status' => 'publish',
						'post__in' => explode( ',' , $attributes['testimonial_ids'] ),
						'orderby' => 'post__in',
					)
				);

				if ( $query->posts ) {
					foreach ( $query->posts as $post ) {
						if ( has_post_thumbnail( $post->ID ) ) {
							$img_id = get_post_thumbnail_id( $post->ID );
							$img_data = wp_get_attachment_image_src( $img_id , 'medium_large' );
							$post->img_url = $img_data[0];
						}

						$team_role = get_post_meta( $post->ID, 'p4-gpea_team_role', true );
						$post->team_role = $team_role ?? '';

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
