<?php

/**
 * Support Launcher block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Support_Launcher_Controller' ) ) {
	/**
	 * Class Support_Launcher_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 * @since 0.1
	 */
	class Support_Launcher_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME   = 'support_launcher';

		/**
		 * The block default layout.
		 *
		 * @const string BLOCK_NAME
		 */
		const DEFAULT_LAYOUT = 'form';

		/**
		 * The nonce string.
		 *
		 * @const string NONCE_STRING
		 */
		const NONCE_STRING = 'support_launcher';

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
							'value' => 'form',
							'label' => __( 'Form', 'planet4-gpea-blocks-backend' ),
							'desc'  => 'Form',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'button',
							'label' => __( 'Button', 'planet4-gpea-blocks-backend' ),
							'desc'  => 'Button',
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
					'label' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
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
					'label' => __( 'Thanks message', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'thanks_message',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Thanks message', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Image', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
				[
					'label' => __( 'Accordion label (used only with "Form" layout)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'accordion_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Accordion label', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Recipient e-mail (used only with "Form" layout)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'recipient_email',
					'type'  => 'email',
					'meta'  => [
						'placeholder' => __( 'Recipient e-mail', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Button label (used only with "Button" layout)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'button_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Button label', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Button URL (used only with "Button" layout)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'button_url',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Button URL', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Support Launcher', 'planet4-gpea-blocks-backend' ),
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

			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

			if ( isset( $attributes['img'] ) ) {
				$attributes['img'] = wp_get_attachment_url( $attributes['img'] );
			}

			$attributes['admin_ajax'] = admin_url( 'admin-ajax.php' );
			$attributes['wp_nonce'] = wp_nonce_field( self::NONCE_STRING );

			$lexicon = [
				'your_name'    => __( 'Your name', 'planet4-gpea-blocks' ),
				'your_email'   => __( 'Your email address', 'planet4-gpea-blocks' ),
				'your_message' => __( 'Your message', 'planet4-gpea-blocks' ),
				'submit'       => __( 'Submit', 'planet4-gpea-blocks' ),
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

			// TODO move this JS to theme assets.
			wp_enqueue_script(
				'support_launcher_form_js',
				P4EABKS_ASSETS_DIR . 'js/support-launcher-form.js',
				[ 'jquery' ],
				'0.1',
				true
			);

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			return ob_get_clean();
		}
	}
}
