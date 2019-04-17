<?php

/**
 * Did you just autogenerate this?
 * Don't forget (if applicable) to create a twig template
 * Template should be called:
 * 'gpnl_newsletter.twig'
 * Placed under: '/includes/blocks'
 */

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_Newsletter_Controller' ) ) {

	/**
	 * Class GPNL_Newsletter_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_Newsletter_Controller extends Controller {



		/**
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'gpnl_newsletter';


		/**
		Shortcode UI setup for the newsletter shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = [
				[
					'label' => __( 'Titel', 'planet4-gpnl-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Ondertitel', 'planet4-gpnl-blocks' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label'       => __( 'Afbeelding', 'planet4-blocks-backend' ),
					'attr'        => 'background',
					'type'        => 'attachment',
					'libraryType' => [ 'image' ],
					'addButton'   => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
					'frameTitle'  => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
				],
				[
					'label'   => __( 'Select focus point for image', 'planet4-blocks-backend' ) .
						'<img src="' . esc_url( plugins_url( '/planet4-plugin-blocks/admin/images/grid_9.png' ) ) . '" />',

					'attr'    => 'focus_image',
					'type'    => 'select',
					'options' => [
						[
							'value' => 'left top',
							'label' => __( '1 - Top Left', 'planet4-blocks-backend' ),
						],
						[
							'value' => 'center top',
							'label' => __( '2 - Top Center', 'planet4-blocks-backend' ),
						],
						[
							'value' => 'right top',
							'label' => __( '3 - Top Right', 'planet4-blocks-backend' ),
						],
						[
							'value' => 'left center',
							'label' => __( '4 - Middle Left', 'planet4-blocks-backend' ),
						],
						[
							'value' => 'center center',
							'label' => __( '5 - Middle Center', 'planet4-blocks-backend' ),
						],
						[
							'value' => 'right center',
							'label' => __( '6 - Middle Right', 'planet4-blocks-backend' ),
						],
						[
							'value' => 'left bottom',
							'label' => __( '7 - Bottom Left', 'planet4-blocks-backend' ),
						],
						[
							'value' => 'center bottom',
							'label' => __( '8 - Bottom Center', 'planet4-blocks-backend' ),
						],
						[
							'value' => 'right bottom',
							'label' => __( '9 - Bottom Right', 'planet4-blocks-backend' ),
						],
					],
				],
				[
					'label' => __( '<i>We use an overlay to fade the image back. Use a number between 1 and 100,<br /> the higher the number, the more faded the image will look. If you leave this <br/> empty, the default of 30 will be used.</i>', 'planet4-blocks-backend' ),
					'attr'  => 'opacity',
					'type'  => 'number',
					'meta'  => [
						'value' => 30,
					],
				],
				[
					'label' => __( 'Marketingcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'marketingcode',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'Literatuurcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'literaturecode',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label' => __( 'ScreenID', 'planet4-gpnl-blocks' ),
					'attr'  => 'screenid',
					'type'  => 'text',
					'value' => '',
				],
				[
					'label'       => __( 'Formulier ID', 'planet4-gpnl-blocks' ),

					'attr'        => 'form_id',
					'type'        => 'number',
					'description' => 'Gebruik dit als er meerdere nieuwsbriefformulieren op 1 pagina staan. Elk formulier moet een uniek numeriek id hebben.',
					'value'       => '1',
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPNL | Newsletter', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_newsletters.png' ) . '" />',
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

			wp_enqueue_style( 'gpnl_newsletter_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-newsletter.css', [], '2.8.0' );
			wp_enqueue_script( 'gpnl_newsletter_js', P4NLBKS_ASSETS_DIR . 'js/gpnl-newsletter.js', [ 'jquery' ], '2.8.0', true );

			$shortcode_atts_pairs = [
				'background'     => '',
				'opacity'        => 30,
				'focus_image'    => $fields['focus_image'] ?? 'center center',
				'title'          => '',
				'subtitle'       => '',
				'marketingcode'  => '',
				'literaturecode' => '',
				'screenid'       => '',
				'form_id'        => '',
			];

			$fields = shortcode_atts( $shortcode_atts_pairs, $fields, $shortcode_tag );

			if ( ! is_numeric( $fields['opacity'] ) ) {
				$fields['opacity'] = 30;
			}

			$opacity = number_format( ( $fields['opacity'] / 100 ), 1 );

			$options                     = get_option( 'planet4_options' );
			$p4_happy_point_bg_image     = $options['happy_point_bg_image_id'] ?? '';
			$image_id                    = '' !== $fields['background'] ? $fields['background'] : $p4_happy_point_bg_image;
			$img_meta                    = wp_get_attachment_metadata( $image_id );
			$image_alt                   = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$fields['background_src']    = wp_get_attachment_image_src( $image_id, 'retina-large' );
			$fields['background_srcset'] = wp_get_attachment_image_srcset( $image_id, 'retina-large', $img_meta );
			$fields['background_sizes']  = wp_calculate_image_sizes( 'retina-large', null, null, $image_id );
			$fields['opacity']           = $opacity;
			$fields['default_image']     = get_bloginfo( 'template_directory' ) . '/images/happy-point-block-bg.jpg';
			$fields['background_alt']    = empty( $image_alt ) ? __( 'Background image', 'planet4-blocks' ) : $image_alt;

			$data = [
				'fields' => $fields,
			];

			// Pass options to frontend code
			wp_localize_script(
				'gpnl_newsletter_js',
				'newsletter_form_object_' . $fields['form_id'],
				[
					'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
					'nonce'          => wp_create_nonce( 'GPNL_Newsletters' ),
					'marketingcode'  => $fields['marketingcode'],
					'literaturecode' => $fields['literaturecode'],
					'screenid'       => $fields['screenid'],
				]
			);

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}


	}
}

function newsletter_form_process () {

	check_ajax_referer( 'GPNL_Newsletters', 'nonce' );

	// get codes for processing in the database and sanitize
	$marketingcode  = htmlspecialchars( wp_strip_all_tags( $_POST['marketingcode'] ) );
	$literatuurcode = htmlspecialchars( wp_strip_all_tags( $_POST['literaturecode'] ) );
	$screenid       = htmlspecialchars( wp_strip_all_tags( $_POST['screenid'] ) );

	// Get and sanitize the formdata
	$naam  = wp_strip_all_tags( $_POST['name'] );
	$email = wp_strip_all_tags( $_POST['mail'] );

	$data_array = [
		'voornaam'       => $naam,
		'email'          => $email,
		'marketingcode'  => $marketingcode,
		'literatuurcode' => $literatuurcode,
		'screenId'       => $screenid,
	];

	$data = wp_json_encode( $data_array );

	$url = 'https://www.mygreenpeace.nl/GPN.RegistrerenApi.Test/register/email';

	// initiate a cUrl request to the database
	$request = curl_init( $url );
	curl_setopt( $request, CURLOPT_POSTFIELDS, $data);
	curl_setopt( $request, CURLOPT_CUSTOMREQUEST, "POST" );
	curl_setopt( $request, CURLOPT_HEADER, true );
	curl_setopt( $request, CURLOPT_HTTPHEADER,
		[
			'Content-Type:application/json',
			'Content-Length: ' . strlen($data)
		]
	);
	curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );

	$result   = curl_exec( $request );
	$httpcode = curl_getinfo( $request, CURLINFO_HTTP_CODE );
	curl_close( $request );

	// Give the appropriate response to the frontend
	if ( false === $result || 200 !== $httpcode ) {
		wp_send_json_error(
			[
				'statuscode' => $httpcode,
				// 'cUrlresult'    => $result,
			],
			500
		);
	}
	wp_send_json_success(
		[
			'statuscode' => $httpcode,
			 'cUrlresult'    => $result,
		],
		200
	);
}

# use this version for if you want the callback to work for users who are logged in
add_action( 'wp_ajax_newsletter_form_process', 'P4NLBKS\Controllers\Blocks\newsletter_form_process' );
# use this version for if you want the callback to work for users who are not logged in
add_action( 'wp_ajax_nopriv_newsletter_form_process', 'P4NLBKS\Controllers\Blocks\newsletter_form_process' );
