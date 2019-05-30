<?php
/**
 * Mixed content row block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Values_Section_Controller' ) ) {
	/**
	 * Class Values_Section_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Values_Section_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'values_section';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'default';

		/**
		 * The maximum number of sub-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 4;

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
					'label' => __( 'Values section title', 'planet4-gpea-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Values section title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Values section description', 'planet4-gpea-blocks' ),
					'attr'  => 'subtitle',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Values section description', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'radio',
					'options' => [
						[
							'value' => 1,
							'label' => __( 'Layout A', 'planet4-gpea-blocks' ),
							'desc'  => 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 2,
							'label' => __( 'Layout B', 'planet4-gpea-blocks' ),
							'desc'  => 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 3,
							'label' => __( 'Layout C', 'planet4-gpea-blocks' ),
							'desc'  => 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
					],
				],
			];

			// This block will have at most MAX_REPEATER different items.
			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				$fields[] =
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => sprintf( __( '<i>Title %s</i>', 'planet4-gpea-blocks' ), $i ),
						'attr'  => 'title_' . $i,
						'type'  => 'text',
						'meta'  => [
							// translators: placeholder represents the ordinal of the field.
							'placeholder' => sprintf( __( 'Enter title %s', 'planet4-gpea-blocks' ), $i ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					];

				$fields[] =
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => sprintf( __( '<i>Description %s</i>', 'planet4-gpea-blocks' ), $i ),
						'attr'  => 'description_' . $i,
						'type'  => 'textarea',
						'meta'  => [
							// translators: placeholder represents the ordinal of the field.
							'placeholder' => sprintf( __( 'Enter description %s', 'planet4-gpea-blocks' ), $i ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					];

			}

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'Values section', 'planet4-gpea-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/values_block.png' ) . '" />',
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

			$key = 'gpea_values_section_bg_image';
			$options = get_option( 'gpea_options' );
			$attributes['bg_img'] = isset( $options[ $key ] ) ? $options[ $key ] : '';

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
