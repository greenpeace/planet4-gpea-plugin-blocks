<?php
/**
 * Achievement block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Achievement_About_Controller' ) ) {
	/**
	 * Class Achievement_About_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Achievement_About_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'achievement_about';

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
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
					'label' => __( 'Label for link', 'planet4-gpea-blocks' ),
					'attr'  => 'link_label',
					'type'  => 'text',
				],
				[
					'label' => __( 'Link address', 'planet4-gpea-blocks' ),
					'attr'  => 'link_address',
					'type'  => 'text',
				],
				[
					'label'       => __( 'Achievement', 'planet4-gpea-blocks' ),
					'attr'     => 'update_ids',
					'type'     => 'post_select',
					'multiple' => 'multiple',
					'query'    => [
						'post_type'   => array( 'post', 'page' ),
						'post_status' => 'publish',
						'orderby'     => 'post_title',
						'order'           => 'ASC',
					],
					'meta'     => [
						'select2_options' => [
							'allowClear'             => true,
							'placeholder'            => __( 'Select Achievement', 'planet4-gpea-blocks' ),
							'closeOnSelect'          => false,
							'minimumInputLength'     => 0,
							'multiple'               => true,
							'maximumSelectionLength' => 20,
							'width'                  => '80%',
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'About section: Achievements', 'planet4-gpea-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/general_updates.png' ) . '" />',
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

			if ( isset( $attributes['update_ids'] ) ) {

				$posts = get_posts(
					array(
						'post_type'   => array( 'post', 'page' ),
						'post_status' => 'publish',
						'include' => explode( ',' , $attributes['update_ids'] ),
						'orderby' => 'post__in',
					)
				);

				if ( $posts ) {
					foreach ( $posts as $post ) {
						$post = (array) $post; // TODO clean up this typecasting.

						if ( has_post_thumbnail( $post['ID'] ) ) {
							$img_id = get_post_thumbnail_id( $post['ID'] );
							$img_data = wp_get_attachment_image_src( $img_id , 'medium_large' );
							$post['img_url'] = $img_data[0];
						}

						$post['post_date'] = date( 'Y-m-d' , strtotime( $post['post_date'] ) );

						$formatted_posts[] = $post;
					}
				}
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
