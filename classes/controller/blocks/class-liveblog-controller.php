<?php

/**
 * Did you just autogenerate this?
 * Don't forget (if applicable) to create a twig template
 * Template should be called:
 * 'gpnl_liveblog.twig'
 * Placed under: '/includes/blocks'
 */

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_Liveblog_Controller' ) ) {

	/**
	 * Class GPNL_Liveblog_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_Liveblog_Controller extends Controller {



		/**
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'gpnl_liveblog';


		/**
		Shortcode UI setup for the liveblog shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = [
				[
					'label'   => __('Is dit het begin of eind van het liveblog?', 'planet4-gpnl-blocks'),
					'attr'    => 'start',
					'type'    => 'select',
					'options' => [
						[
							'value' => '1',
							'label' => 'Begin',
						],
						[
							'value' => '0',
							'label' => 'Eind',
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPNL | Liveblog feed', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_liveblogfeed.png' ) . '" />',
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
			wp_enqueue_style(
				'gpnl_liveblog_css',
				P4NLBKS_ASSETS_DIR . 'css/gpnl-liveblog.css',
				[],
				'2.5.0'
			);
			wp_enqueue_script(
				'gpnl_liveblog_moment_js',
				'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js',
				[ 'jquery' ],
				'2.5.0',
				true
			);
			wp_enqueue_script(
				'gpnl_liveblog_moment_locale_js',
				'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/nl.js',
				[ 'jquery', 'gpnl_liveblog_moment_js' ],
				'2.5.0',
				true
			);
			wp_enqueue_script(
				'gpnl_liveblog_js',
				P4NLBKS_ASSETS_DIR . 'js/gpnl-liveblog.js',
				[ 'jquery', 'gpnl_liveblog_moment_locale_js' ],
				'2.5.0',
				true
			);


			$fields = shortcode_atts(
				[
					'start' => '',
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
