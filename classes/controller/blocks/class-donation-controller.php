<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Donation_Controller' ) ) {

	/**
	 * Class Donation_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Donation_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'donation';

		/**
		 * Shortcode UI setup for the donationblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label' => __( 'Titel', 'planet4-gpnl-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Omschrijving', 'planet4-gpnl-blocks' ),
					'attr'  => 'description',
					'type'  => 'textarea',
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'Donatie', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/donation_form.png' ) . '" />',
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
				'title'       => '',
				'description' => '',
			), $fields, $shortcode_tag );

			$data = [
				'fields' => $fields,
			];

			wp_enqueue_script( 'donationform', P4NLBKS_ADMIN_DIR . 'js/donationform.js' );
			wp_enqueue_style( 'style', P4NLBKS_ADMIN_DIR . 'css/style.css' );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
