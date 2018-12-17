<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_statistics_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class GPNL_statistics_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_statistics_Controller extends Controller {



		/**
	* @const string BLOCK_NAME
*/
		const BLOCK_NAME = 'gpnl_statistics';


		/**
			Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label' => __( 'Tekst links boven', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_l_top',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Nadruk - Tekst links boven?', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_l_top_emphasis',
					'type'  => 'checkbox',
				),
				array(
					'label' => __( 'Tekst links midden', 'planet4-gpnl-blocks' ),
					'attr'  => 'text_l_mid',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Tekst links onder', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_l_bottom',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Nadruk - Tekst links onder? <hr>', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_l_bottom_emphasis',
					'type'  => 'checkbox',
				),
				array(
					'label' => __( 'Tekst midden boven', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_m_top',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Nadruk - Tekst midden boven?', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_m_top_emphasis',
					'type'  => 'checkbox',
				),
				array(
					'label' => __( 'Tekst midden midden', 'planet4-gpnl-blocks' ),
					'attr'  => 'text_m_mid',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Tekst midden onder', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_m_bottom',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Nadruk - Tekst midden onder? <hr>', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_m_bottom_emphasis',
					'type'  => 'checkbox',
				),
				array(
					'label' => __( 'Tekst rechts boven', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_r_top',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Nadruk - Tekst rechts boven?', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_r_top_emphasis',
					'type'  => 'checkbox',
				),
				array(
					'label' => __( 'Tekst rechts midden', 'planet4-gpnl-blocks' ),
					'attr'  => 'text_r_mid',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Tekst rechts onder', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_r_bottom',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Nadruk - Tekst rechts onder?', 'planet4-gpnl-blocks' ),
					'attr'  => 'txt_r_bottom_emphasis',
					'type'  => 'checkbox',
				),

			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'GPNL | Statistics', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_noindex.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			);

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
				array(
					'txt_l_top'             => '',
					'txt_l_top_emphasis'    => '',
					'text_l_mid'            => '',
					'txt_l_bottom'          => '',
					'txt_l_bottom_emphasis' => '',
					'txt_m_top'             => '',
					'txt_m_top_emphasis'    => '',
					'text_m_mid'            => '',
					'txt_m_bottom'          => '',
					'txt_m_bottom_emphasis' => '',
					'txt_r_top'             => '',
					'txt_r_top_emphasis'    => '',
					'text_r_mid'            => '',
					'txt_r_bottom'          => '',
					'txt_r_bottom_emphasis' => '',
				),
				$fields,
				$shortcode_tag
			);

			$data = [
				'fields' => $fields,
			];

			wp_enqueue_style( 'gpnl_quote_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-statistics.css', [], '2.2.29' );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}


	}
}
