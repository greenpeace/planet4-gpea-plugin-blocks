<?php
/**
 * People list block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'People_List_Controller' ) ) {
	/**
	 * Class People_List_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 * @since 0.1
	 */
	class People_List_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME   = 'people_list';

		/**
		 * The maximum number of sub-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 20;

		/**
		 * The block default layout.
		 *
		 * @const string BLOCK_NAME
		 */
		const DEFAULT_LAYOUT = 'simple';

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
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// This block will have at most MAX_REPEATER different items.
			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				$fields[] =
						  [
							  // translators: placeholder represents the ordinal of the field.
							  'label' => sprintf( __( '<strong>%s</strong> <i>person name</i>', 'planet4-gpea-blocks' ), $i ),
							  'attr'  => 'name_person_' . $i,
							  'type'  => 'text',
							  'meta'  => [
								  // translators: placeholder represents the ordinal of the field.
								  'placeholder' => sprintf( __( 'Enter person %s name', 'planet4-gpea-blocks' ), $i ),
								  'data-plugin' => 'planet4-gpea-blocks',
								  'data-element-type' => 'person',
								  'data-element-name' => 'person',
								  'data-element-number' => $i,
							  ],
						  ];

				$fields[] =
						  [
							  // translators: placeholder represents the ordinal of the field.
							  'label'       => sprintf( __( '<strong>%s</strong> <i>person picture</i>', 'planet4-gpea-blocks' ), $i ),
							  'attr'        => 'img_person_' . $i,
							  'type'        => 'attachment',
							  'libraryType' => array( 'image' ),
							  'addButton'   => __( 'Select picture', 'planet4-gpea-blocks' ),
							  'frameTitle'  => __( 'Select picture', 'planet4-gpea-blocks' ),
							  'meta'        => [
								  // translators: placeholder represents the ordinal of the field.
								  'placeholder' => sprintf( __( 'Enter person %s picture', 'planet4-gpea-blocks' ), $i ),
								  'data-plugin' => 'planet4-gpea-blocks',
								  'data-element-type' => 'person',
								  'data-element-name' => 'person',
								  'data-element-number' => $i,
							  ],
						  ];

				$fields[] =
						  [
							  // translators: placeholder represents the ordinal of the field.
							  'label' => sprintf( __( '<strong>%s</strong> <i>person code</i>', 'planet4-gpea-blocks' ), $i ),
							  'attr'  => 'code_person_' . $i,
							  'type'  => 'text',
							  'meta'  => [
								  // translators: placeholder represents the ordinal of the field.
								  'placeholder' => sprintf( __( 'Enter person %s code', 'planet4-gpea-blocks' ), $i ),
								  'data-plugin' => 'planet4-gpea-blocks',
								  'data-element-type' => 'person',
								  'data-element-name' => 'person',
								  'data-element-number' => $i,
							  ],
						  ];

			}

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | People list', 'planet4-gpea-blocks' ),
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

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {
				if ( isset( $attributes[ 'img_person_' . $i ] ) ) {
					$attributes[ 'img_person_' . $i ] = wp_get_attachment_url( $attributes[ 'img_person_' . $i ] );
				}
			}

			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

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
