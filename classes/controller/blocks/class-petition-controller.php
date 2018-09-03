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
					'label'       => __( 'Afbeelding', 'planet4-blocks-backend' ),
					'attr'        => 'image',
					'type'        => 'attachment',
					'libraryType' => [ 'image' ],
					'addButton'   => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
					'frameTitle'  => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
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
				'image' => '',
			), $fields, $shortcode_tag );

			if ( $fields[ "image" ] ) {
				$img = wp_get_attachment_image_src( $fields[ "image" ], 'medium_large' );
				$fields[ "alt" ]   = get_post_meta( $fields[ "image" ], '_wp_attachment_image_alt', true );
				$fields[ "image" ] = $img;
			}

			$data = [
				'fields' => $fields,
			];

			wp_enqueue_script( 'petitioncounterjs', P4NLBKS_ASSETS_DIR . 'js/petitioncounter.js' );

			wp_enqueue_style( 'petitioncountercss', P4NLBKS_ASSETS_DIR . 'css/petitioncounter.css' );
			wp_enqueue_style( 'checkboxcss', P4NLBKS_ASSETS_DIR . 'css/checkbox.css' );

			/* ========================
				C S S / JS
			   ======================== */
				// Enqueue the script:
				wp_enqueue_script( 'jquery-docready-script', P4NLBKS_ASSETS_DIR . 'js/docReady.js', array( 'jquery' ), null, true );

				// Pass options to frontend code
				wp_localize_script( 'jquery-docready-script',
					'petition_form_object',
					array(
						'ajaxUrl' => admin_url( 'admin-ajax.php' ),
						//url for php file that process ajax request to WP
						'nonce'   => wp_create_nonce( 'GPNL_Petitions' ),
					)
				);
			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
	/* ========================
		P E T I T I O N F O R M
   ======================== */
function petition_form_process() {
	# do whatever you need in order to process the form.

	# This will send the post variables to the specified url, and what the page returns will be in $response
	# get data from form
	check_ajax_referer( 'GPNL_Petitions', 'nonce' );

	$marketingcode  = '09481';
	$literatuurcode = 'EN119';
	// $marketingcode  = $_POST['marketingcode'];
	// $literatuurcode = $_POST['literatuurcode'];
	$naam           = $_POST['name'];
	$email          = $_POST['mail'];
	$telefoonnummer = $_POST['phone'];
	$toestemming    = $_POST['consent'];
	# set-up your url
	$baseurl = 'https://www.mygreenpeace.nl/registreren/pixel.aspx';
	// $baseurl = 'https://secured.greenpeace.nl';
	// $baseurl    = 'p4.local';
	$querystring = '?source=' . $marketingcode . '&per=' . $literatuurcode . '&fn=' . $naam . '&email=' . $email . '&tel=' . $telefoonnummer . '&stop=' . $toestemming;
	$url = $baseurl . $querystring;
	echo $url;
	die();
	// $ch = curl_init( $baseurl );
	// curl_setopt( $ch, CURLOPT_POST, 1 );
	// curl_setopt( $ch, CURLOPT_POSTFIELDS, $querystring );
	// curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
	// curl_setopt( $ch, CURLOPT_HEADER, 0 );
	// curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

	// $result   = curl_exec( $ch );
	// $httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	// curl_close( $ch );
	// if ( false === $result ) {
	// 	wp_send_json_success( 'ERROR', 500 );
	// }
	// wp_send_json_success( [ $baseurl . $querystring, $httpcode ], 200 );

}

# use this version for if you want the callback to work for users who are logged in
add_action( 'wp_ajax_petition_form_process', 'P4NLBKS\Controllers\Blocks\petition_form_process' );
# use this version for if you want the callback to work for users who are not logged in
add_action( 'wp_ajax_nopriv_petition_form_process', 'P4NLBKS\Controllers\Blocks\petition_form_process' );
