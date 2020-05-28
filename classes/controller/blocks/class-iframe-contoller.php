<?php
/**
 * Video Row block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Iframe_Controller' ) ) {
	/**
	 * Class Video_Row_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 * @since 0.1
	 */
    class Iframe_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME   = 'iframe';

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
					'label' => 'Please select the module layout',
					'attr' => 'width_type',
					'type' => 'radio',
					'value' => 'article_width',
					'options' => [
						[
							'value' => 'full_width',
							'label' => __( 'Full-width Video', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => 'article_width',
							'label' => __( 'Article-width Video', 'planet4-gpea-blocks-backend' ),
						],
					],
                ],
                [
					'label' => __( 'Please make sure the script is safe', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'content',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Please make sure the script is safe', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
					'encode' => true,
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | iframe', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/iframe.png' ) . '" />',
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

			$c =  isset($attributes['content']) ? urldecode($attributes['content']) : '';

			return [
				'fields' => $attributes,
				'content' => $c
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

			wp_enqueue_style(
				'iframe.css',
				P4EABKS_ASSETS_DIR . 'css/iframe.css'
			);

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			return ob_get_clean();
		}
	}
}
