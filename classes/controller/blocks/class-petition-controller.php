<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Petition_Controller' ) ) {

	/**
	 * Class Petition_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Petition_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'petition';

		/**
		 * Shortcode UI setup for the petitionblock shortcode.
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
					'label' => __( 'Ondertitel', 'planet4-gpnl-blocks' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Opt in tekst', 'planet4-gpnl-blocks' ),
					'attr'  => 'consent',
					'type'  => 'textarea',
				),
				array(
					'label' => __( 'Teken knop', 'planet4-gpnl-blocks' ),
					'attr'  => 'sign',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Marketingcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'marketingcode',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Literatuurcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'literatuurcode',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Tellercode', 'planet4-gpnl-blocks' ),
					'attr'  => 'tellercode',
					'type'  => 'text',
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'Petition Ask', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_petition_colum_right.png' ) . '" />',
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
				'subtitle' => '',
				'consent' => '',
				'sign' => '',
				'marketingcode' => '',
				'literatuurcode' => '',
				'tellercode' => '',
			), $fields, $shortcode_tag );

			$data = [
				'fields' => $fields,
			];

			wp_enqueue_script( 'petitioncounter', P4NLBKS_ASSETS_DIR . 'js/petitioncounter.js' );

			wp_enqueue_style( 'style', P4NLBKS_ASSETS_DIR . 'css/petitioncounter.css' );
			wp_enqueue_style( 'style', P4NLBKS_ASSETS_DIR . 'css/checkbox.css' );

			/* ========================
				C S S / JS
			   ======================== */
			// javascript
			function wpbootstrap_scripts_with_jquery() {
				// Register the script like this for a theme:
				wp_register_script( 'jquery-docready-script', P4NLBKS_ASSETS_DIR . 'js/docReady.js', array( 'jquery' ) );
				// Enqueue the script:
				wp_enqueue_script( 'jquery-docready-script' );
				// petition form related code
				wp_localize_script( 'jquery-docready-script',
					'theUniqueNameForOurJSObjectPetitionForm',
					array(
						'ajaxUrl' => admin_url( 'admin-ajax.php' ),
						//url for php file that process ajax request to WP
						// 'nonce' => wp_create_nonce( "unique_id_nonce" ),// this is a unique token to prevent form hijacking
						// 'someData' => 'extra data you want  available to JS'*/
					)
				);
			}
			add_action( 'wp_enqueue_scripts', 'wpbootstrap_scripts_with_jquery' );

			/* ========================
				P E T I T I O N F O R M
			   ======================== */
			function petition_form_process() {
				# do whatever you need in order to process the form.

				# This will send the post variables to the specified url, and what the page returns will be in $response
				# get data from form
				# TODO: Add nonce verification
				$marketingcode  = $_POST['marketingcode'];
				$literatuurcode = $_POST['literatuurcode'];
				$naam           = $_POST['naam'];
				$email          = $_POST['email'];
				$telefoonnummer = $_POST['telefoonnummer'];
				$toestemming    = $_POST['toestemming'];
				# set-up your url
				$url = 'https://secured.greenpeace.nl';
				$myvars = '?source=' . $marketingcode . '&per=' . $literatuurcode . '&fn=' . $naam . '&email=' . $email . '&tel=' . $telefoonnummer . '&stop=' . $toestemming;

				$ch = curl_init( $url );
				curl_setopt( $ch, CURLOPT_POST, 1);
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt( $ch, CURLOPT_HEADER, 0);
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

				$response = curl_exec( $ch );

				return $response;
			}
			# use this version for if you want the callback to work for users who are logged in
			//add_action("wp_ajax_petition_form", "petition_form_process");
			# use this version for if you want the callback to work for users who are not logged in
			add_action( 'wp_ajax_nopriv_petition_form', 'petition_form_process' );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
