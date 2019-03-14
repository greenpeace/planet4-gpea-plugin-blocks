<?php

/**
 * Did you just autogenerate this?
 * Don't forget (if applicable) to create a twig template
 * Template should be called:
 * 'gpnl_liveblogitem.twig'
 * Placed under: '/includes/blocks'
 */

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_Liveblogitem_Controller' ) ) {

	/**
	 * Class GPNL_Liveblogitem_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_Liveblogitem_Controller extends Controller {



		/**
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'gpnl_liveblogitem';


		/**
		Shortcode UI setup for the liveblogitem shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			date_default_timezone_set( 'Europe/Amsterdam' );
			$fields = [
				[
					'label' => __( 'Titel', 'planet4-gpnl-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Datum', 'planet4-gpnl-blocks' ),
					'attr'  => 'date',
					'type'  => 'date',
					'value' => '',
				],
				[
					'label' => __( 'Uur', 'planet4-gpnl-blocks' ),
					'attr'  => 'hour',
					'type'  => 'number',
					'value' => date( 'H' ),
					'meta'  => [
						'min'  => '0',
						'max'  => '23',
						'step' => '1',
					],
				],
				[
					'label' => 'Minuut',
					'attr'  => 'minute',
					'type'  => 'number',
					'value' => date( 'i' ),
					'meta'  => [
						'min'  => '0',
						'max'  => '60',
						'step' => '1',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPNL | Liveblogitem', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_liveblogitem.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

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

			$fields = shortcode_atts(
				$fields,
				$shortcode_tag
			);

			if ( $fields['minute'] < 10 ) {
				$fields['minute'] = '0' . $fields['minute'];
			}
			if ( $fields['hour'] < 10 ) {
				$fields['hour'] = '0' . $fields['hour'];
			}

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
