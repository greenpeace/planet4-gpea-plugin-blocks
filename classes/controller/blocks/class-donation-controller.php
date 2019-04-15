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
				array(
					'label'   => __( 'Voorgestelde periodiek', 'planet4-gpnl-blocks' ),
					'attr'    => 'suggested_frequency',
					'type'    => 'select',
					'meta'    => ['onchange' => 'hideNonForcesOptions()'],
					'options' => [
						[
							'value' => 'E',
							'label' => __( 'Eenmalig' ),
						],
						[
							'value' => 'M',
							'label' => __( 'Maandelijks' ),
						],
						[
							'value' => 'F',
							'label' => __( 'Maandelijks voor 12 maanden (Forces)' ),
						],
					],
				),
				array(
					'label'   => __( 'Donateur kan periodiek wijzigen', 'planet4-gpnl-blocks' ),
					'attr'    => 'allow_frequency_override',
					'type'    => 'checkbox',
					'checked' => 'checked',
				),
				array(
					'label' => __( 'Minimum bedrag', 'planet4-gpnl-blocks' ),
					'attr'  => 'min_amount',
					'type'  => 'number',
					'value' => 5,

				),
				array(
					'label' => __( 'Eenmalig: Bedrag 1', 'planet4-gpnl-blocks' ),
					'attr'  => 'oneoff_amount1',
					'type'  => 'number',
					'value' => 5,
				),
				array(
					'label' => __( 'Eenmalig: Bedrag 2', 'planet4-gpnl-blocks' ),
					'attr'  => 'oneoff_amount2',
					'type'  => 'number',
					'value' => 10,

				),
				array(
					'label' => __( 'Eenmalig: Bedrag 3', 'planet4-gpnl-blocks' ),
					'attr'  => 'oneoff_amount3',
					'type'  => 'number',
					'value' => 25,

				),
				array(
					'label' => __( 'Eenmalig: Voorgesteld bedrag', 'planet4-gpnl-blocks' ),
					'attr'  => 'oneoff_suggested_amount',
					'type'  => 'number',
					'value' => 10,

				),
				array(
					'label' => __( 'Periodiek: Bedrag 1', 'planet4-gpnl-blocks' ),
					'attr'  => 'recurring_amount1',
					'type'  => 'number',
					'value' => 5,
				),
				array(
					'label' => __( 'Periodiek: Bedrag 2', 'planet4-gpnl-blocks' ),
					'attr'  => 'recurring_amount2',
					'type'  => 'number',
					'value' => 10,

				),
				array(
					'label' => __( 'Periodiek: Bedrag 3', 'planet4-gpnl-blocks' ),
					'attr'  => 'recurring_amount3',
					'type'  => 'number',
					'value' => 25,

				),
				array(
					'label' => __( 'Periodiek: Voorgesteld bedrag', 'planet4-gpnl-blocks' ),
					'attr'  => 'recurring_suggested_amount',
					'type'  => 'number',
					'value' => 10,

				),
				array(
					'label' => __( 'Bedankt Titel', 'planet4-gpnl-blocks' ),
					'attr'  => 'thanktitle',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Bedankt Omschrijving', 'planet4-gpnl-blocks' ),
					'attr'  => 'thankdescription',
					'type'  => 'textarea',
				),
				array(
					'label' => __( 'Literatuurcode', 'planet4-gpnl-blocks' ),
					'attr'  => 'literatuurcode',
					'type'  => 'text',
					'value' => 'EN999',
				),
				array(
					'label' => __( 'Marketingcode - Terugkerende betalingen', 'planet4-gpnl-blocks' ),
					'attr'  => 'marketingcode_recurring',
					'type'  => 'text',
					'value' => '04888',
				),
				array(
					'label' => __( 'Marketingcode  - Eenmalige betalingen', 'planet4-gpnl-blocks' ),
					'attr'  => 'marketingcode_oneoff',
					'type'  => 'text',
					'value' => '04888',
				),
				array(
					'label' => __( 'iDeal bedanktpagina', 'planet4-gpnl-blocks' ),
					'attr'  => 'returnpage',
					'type'  => 'text',
					'value' => 'https://www.greenpeace.org/nl/',
				),
				array(
					'label' => __( 'iDeal errorpagina', 'planet4-gpnl-blocks' ),
					'attr'  => 'errorpage',
					'type'  => 'text',
					'value' => 'https://www.greenpeace.org/nl/',
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'GPNL | Donation', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_donation.png' ) . '" />',
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

			$fields = shortcode_atts( [
				'title'                      => '',
				'description'                => '',
				'min_amount'                 => '',
				'oneoff_amount1'             => '',
				'oneoff_amount2'             => '',
				'oneoff_amount3'             => '',
				'oneoff_suggested_amount'    => '',
				'recurring_amount1'          => '',
				'recurring_amount2'          => '',
				'recurring_amount3'          => '',
				'recurring_suggested_amount' => '',
				'suggested_frequency'        => '',
				'allow_frequency_override'   => '',
				'thanktitle'                 => '',
				'thankdescription'           => '',
				'literatuurcode'             => '',
				'marketingcode_recurring'    => '',
				'marketingcode_oneoff'       => '',
				'returnpage'                 => '',
				'errorpage'                  => '',
				'drplus_amount1'             => '',
				'drplus_amount2'             => '',
				'drplus_amount3'             => '',
			], $fields, $shortcode_tag );

			$frequencies = [
				'E' => 'Eenmalig',
				'M' => 'Maandelijks',
				'K' => 'Kwartaal',
				'H' => 'Halfjaarlijks',
				'J' => 'Jaarlijks',
				'F' => 'Maandelijks voor 12 maanden',
			];

			$fields['suggested_frequency'] = [$fields['suggested_frequency'], strtolower($frequencies[ $fields['suggested_frequency'] ] ) ];

			$data = [
				'fields' => $fields,
			];


			wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue@2.5.15/dist/vue.js', null, '2.5.15', true );
            wp_enqueue_script( 'vueform', 'https://cdn.jsdelivr.net/npm/vue-form-wizard@0.8.4/dist/vue-form-wizard.min.js', [ 'vue' ], '0.8.4', true );
            wp_enqueue_script( 'vueresource', 'https://cdn.jsdelivr.net/npm/vue-resource@1.5.0/dist/vue-resource.min.js', [ 'vue', 'vueform' ], '1.5.0', true );
            wp_enqueue_script( 'vuelidate', 'https://cdn.jsdelivr.net/npm/vuelidate@0.7.4/dist/vuelidate.min.js', [ 'vue', 'vueform' ], '0.7.4', true );
            wp_enqueue_script( 'vuelidators', 'https://cdn.jsdelivr.net/npm/vuelidate@0.7.4/dist/validators.min.js', [ 'vue', 'vueform' ], '0.7.4', true );
            wp_enqueue_script( 'donationform', P4NLBKS_ASSETS_DIR . 'js/donationform.js', ['vue', 'vueresource', 'vueform', 'vuelidate', 'vuelidators'], '2.7.3', true );
			// Pass options to frontend code
			wp_localize_script(
				'donationform',
				'formconfig',
				array(
					'min_amount'                 => $fields['min_amount'],
					'oneoff_amount1'             => $fields['oneoff_amount1'],
					'oneoff_amount2'             => $fields['oneoff_amount2'],
					'oneoff_amount3'             => $fields['oneoff_amount3'],
					'oneoff_suggested_amount'    => $fields['oneoff_suggested_amount'],
					'recurring_amount1'          => $fields['recurring_amount1'],
					'recurring_amount2'          => $fields['recurring_amount2'],
					'recurring_amount3'          => $fields['recurring_amount3'],
					'recurring_suggested_amount' => $fields['recurring_suggested_amount'],
					'suggested_frequency'        => $fields['suggested_frequency'],
					'allow_frequency_override'   => $fields['allow_frequency_override'],
					'literatuurcode'             => $fields['literatuurcode'],
					'marketingcode_recurring'    => $fields['marketingcode_recurring'],
					'marketingcode_oneoff'       => $fields['marketingcode_oneoff'],
					'thanktitle'                 => $fields['thanktitle'],
					'thankdescription'           => $fields['thankdescription'],
					'returnpage'                 => $fields['returnpage'],
					'errorpage'                  => $fields['errorpage'],
					'drplus_amount1'             => $fields['drplus_amount1'],
					'drplus_amount2'             => $fields['drplus_amount2'],
					'drplus_amount3'             => $fields['drplus_amount3'],

				)
			);

            wp_enqueue_style( 'vueform_style', 'https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.min.css', [], '2.7.3' );
            wp_enqueue_style( 'gpnl_donationform_style', P4NLBKS_ASSETS_DIR . 'css/donationform.css', 'vueform_style', '2.7.3' );

            // Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
