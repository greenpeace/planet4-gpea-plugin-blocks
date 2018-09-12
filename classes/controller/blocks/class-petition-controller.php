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
					'value' => 'Ja, ik wil weten hoe dit afloopt! Als je dit aanvinkt, mag Greenpeace je per e-mail op de hoogte houden over campagnes. Ook zullen we je af en toe om steun vragen. Afmelden kan natuurlijk altijd.',
				),
				array(
					'label' => __( 'Teken knop', 'planet4-gpnl-blocks' ),
					'attr'  => 'sign',
					'type'  => 'text',
					'value' => 'TEKEN NU'
				),
				array(
					'label' => __( 'Bedankt tekst', 'planet4-gpnl-blocks' ),
					'attr'  => 'thanktext',
					'type'  => 'textarea',
				),
				array(
					'label' => __( 'Doneer knop bij bedankttekst', 'planet4-gpnl-blocks' ),
					'attr'  => 'donatebuttontext',
					'type'  => 'text',
					'value' => 'Doneer',
				),
				array(
					'label' => __( 'Link van doneerknop', 'planet4-gpnl-blocks' ),
					'attr'  => 'donatebuttonlink',
					'type'  => 'text',
					'value' => '/doneren',
				),
				array(
					'label' => __( 'Marketingcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'marketingcode',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Literatuurcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'literaturecode',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Tellercode', 'planet4-gpnl-blocks' ),
					'attr'  => 'countercode',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Teller maximum', 'planet4-gpnl-blocks' ),
					'attr'  => 'countermax',
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
				'thanktext' => '',
				'donatebuttontext' => '',
				'donatebuttonlink' => '',
				'marketingcode' => '',
				'literaturecode' => '',
				'countercode' => '',
				'countermax' => '',
				'image' => '',
				'alt_text' => '',
			), $fields, $shortcode_tag );
			
			// If an image is selected
			if ( isset( $fields['image'] ) ) {
				// If the selected image is succesfully found
				if ( $image = wp_get_attachment_image_src( $fields['image'], 'full' ) ) {
					$fields['image']        = $image[0];
					$fields['alt_text']     = get_post_meta( $fields['image'], '_wp_attachment_image_alt', true );
					$fields['image_srcset'] = wp_get_attachment_image_srcset( $fields['image'], 'full', wp_get_attachment_metadata( $fields['image'] ) );
					$fields['image_sizes']  = wp_calculate_image_sizes( 'full', null, null, $fields['image'] );
				}
			}

			$data = [
				'fields' => $fields,
			];

			wp_enqueue_script( 'petitioncounterjs', P4NLBKS_ASSETS_DIR . 'js/gpnl-petitioncounter.js', array( 'jquery' ), null, true );

			wp_enqueue_style( 'petitioncountercss', P4NLBKS_ASSETS_DIR . 'css/gpnl-petition.css' );

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
						'countercode'   => $fields['countercode'],
						'countermax'   => $fields['countermax'],
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
function petition_form_process($fields) {
	
	// First check if the nonce is correct
	check_ajax_referer( 'GPNL_Petitions', 'nonce' );

	// get petition specific codes for processing in the database and sanitize
	$marketingcode  = htmlspecialchars(strip_tags($_POST['marketingcode']));
	$literatuurcode = htmlspecialchars(strip_tags($_POST['literaturecode']));
	
	// Get and sanitize the formdata
	$naam           = strip_tags($_POST['name']);
	$email          = strip_tags($_POST['mail']);
	// Accept only numeric characters in the phonenumber
	$phonenumber    = preg_replace("/[^0-9]/", "",strip_tags($_POST['phone']));
	$consent        = htmlspecialchars(strip_tags($_POST['consent']));

	// Accept only phonenumbers of 10 characters long
	$phonenumber    = (strlen($phonenumber) == 10 ? $phonenumber : "");
	// Flip the consent checkbox
	$consent        = ($consent == "on" ? 0 : 1);

	// $baseurl = 'https://www.mygreenpeace.nl/registreren/pixel.aspx';
	$baseurl    = 'p4.local';
	$querystring = '?source=' . $marketingcode . '&per=' . $literatuurcode . '&fn=' . $naam . '&email=' . $email . '&tel=' . $phonenumber . '&stop=' . $consent;

	// initiate a cUrl request to the database
	$ch = curl_init( $baseurl );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $querystring );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

	$result   = curl_exec( $ch );
	$httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	curl_close( $ch );

	// Give the appropriate response to the frontend
	if ( false === $result ) {
		wp_send_json_error( 'ERROR', 500 );
	}
	wp_send_json_success( [ $baseurl . $querystring, $httpcode ], 200 );

}

# use this version for if you want the callback to work for users who are logged in
add_action( 'wp_ajax_petition_form_process', 'P4NLBKS\Controllers\Blocks\petition_form_process' );
# use this version for if you want the callback to work for users who are not logged in
add_action( 'wp_ajax_nopriv_petition_form_process', 'P4NLBKS\Controllers\Blocks\petition_form_process' );
