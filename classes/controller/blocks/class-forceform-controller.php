<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Force_Form_Old_Controller' ) ) {

	/**
	 * Class Force_Form_Old_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Force_Form_Old_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'oldforceform';

		/**
		 * Shortcode UI setup for the Force_Form_Oldblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label'   => __( 'Afbeelding', 'planet4-gpnl-blocks' ),
					'attr'    => 'image',
					'type'    => 'image',
				),
				array(
					'label'   => __( 'Formulier voor?', 'planet4-gpnl-blocks' ),
					'attr'    => 'form',
					'type'    => 'select',
					'options' => [
						[
							'value' => 'food',
							'label' => __( 'Food Force' ),
						],
						[
							'value' => 'ocean',
							'label' => __( 'Ocean Force' ),
						],
						[
							'value' => 'force',
							'label' => __( 'Climate Force' ),
						],
					],
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'Force_Form', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_Force_Form_Old_colum_right.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcake_twocolumn shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields Array of fields that are to be used in the template.
		 * @param string $content The content of the post.
		 * @param string $shortcode_tag The shortcode tag (shortcake_blockname).
		 *
		 * @return string The complete html of the block
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			$fields = shortcode_atts( array(
				'image'       => '',
				'form' => '',
			), $fields, $shortcode_tag );

			$data = [
				'fields' => $fields,
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
