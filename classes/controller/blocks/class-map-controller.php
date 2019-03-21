<?php

/**
 * Did you just autogenerate this?
 * Don't forget (if applicable) to create a twig template
 * Template should be called:
 * 'gpnl_map.twig'
 * Placed under: '/includes/blocks'
 */

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_Map_Controller' ) ) {

	/**
	 * Class GPNL_Map_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_Map_Controller extends Controller {

		/**
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'gpnl_map';


		/**
		Shortcode UI setup for the map shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = [
				[
					'label' => __('Hoogte kaart', 'planet4-gpnl-blocks'),
					'attr'  => 'mapheight',
					'type'  => 'number',
					'value' => '200',
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPNL | Map', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_map.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

		}

		/**
		 * Callback for the shortcake_gpnl_map shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields        Array of fields that are to be used in the template.
		 * @param string $content       The content of the post.
		 * @param string $shortcode_tag The shortcode tag (shortcake_blockname).
		 *
		 * @return string The complete html of the block
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {
			wp_enqueue_style( 'leaflet_css', P4NLBKS_ASSETS_DIR . 'css/leaflet.css', [], '2.6.2' );
			wp_enqueue_style( 'leaflet-draw_css', P4NLBKS_ASSETS_DIR . 'css/leaflet.draw.css', [], '2.6.0' );
			wp_enqueue_script( 'leaflet_js', P4NLBKS_ASSETS_DIR . 'js/leaflet.js', [], '2.6.0', true );
			wp_enqueue_script( 'leaflet_providers_js', P4NLBKS_ASSETS_DIR . 'js/leaflet-providers.js', [ 'leaflet_js' ], '2.6.0', true );
			wp_enqueue_script( 'gpnl_map_js', P4NLBKS_ASSETS_DIR . 'js/gpnl-map.js', [ 'leaflet_js', 'leaflet_providers_js', 'jquery' ], '2.6.3', true );

			$fields = shortcode_atts(
				[
					'mapheight' => '',
				],
				$fields,
				$shortcode_tag
			);

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
