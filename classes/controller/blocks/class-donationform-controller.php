<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'DonationForm_Controller' ) ) {

	/**
	 * Class DontationForm_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class DonationForm_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'donation_form';

		/**
		 * Shortcode UI setup for the twocolumn shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			/*
			 * Define the UI for attributes of the shortcode. Optional.
			 *
			 * If no UI is registered for an attribute, then the attribute will
			 * not be editable through Shortcake's UI. However, the value of any
			 * unregistered attributes will be preserved when editing.
			 *
			 * Each array must include 'attr', 'type', and 'label'.
			 * * 'attr' should be the name of the attribute.
			 * * 'type' options include: text, checkbox, textarea, radio, select, email,
			 *     url, number, and date, post_select, attachment, color.
			 * * 'label' is the label text associated with that input field.
			 *
			 * Use 'meta' to add arbitrary attributes to the HTML of the field.
			 *
			 * Use 'encode' to encode attribute data. Requires customization in shortcode callback to decode.
			 *
			 * Depending on 'type', additional arguments may be available.
			 */

			//  $focus_options = [
 			// 	[ 'value' => 'left top',      'label' => __( '1 - Top Left', 'planet4-gpnl-blocks' ) ],
 			// 	[ 'value' => 'center top',    'label' => __( '2 - Top Center', 'planet4-gpnl-blocks' ) ],
 			// 	[ 'value' => 'right top',     'label' => __( '3 - Top Right', 'planet4-gpnl-blocks' ) ],
 			// 	[ 'value' => 'left center',   'label' => __( '4 - Middle Left', 'planet4-gpnl-blocks' ) ],
 			// 	[ 'value' => 'center center', 'label' => __( '5 - Middle Center', 'planet4-gpnl-blocks' ) ],
 			// 	[ 'value' => 'right center',  'label' => __( '6 - Middle Right', 'planet4-gpnl-blocks' ) ],
 			// 	[ 'value' => 'left bottom',   'label' => __( '7 - Bottom Left', 'planet4-gpnl-blocks' ) ],
 			// 	[ 'value' => 'center bottom', 'label' => __( '8 - Bottom Center', 'planet4-gpnl-blocks' ) ],
 			// 	[ 'value' => 'right bottom',  'label' => __( '9 - Bottom Right', 'planet4-gpnl-blocks' ) ],
 			// ];

			$fields = [
				// First column fields.
				[
					'label' => __( 'Title', 'planet4-gpnl-blocks' ),
					'attr'  => 'title_1',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Enter title of first column', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Description', 'planet4-gpnl-blocks' ),
					'attr'  => 'description_1',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Enter description of first column', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Button text', 'planet4-gpnl-blocks' ),
					'attr'  => 'button_text_1',
					'type'  => 'text',
					'meta'  => [
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Button link', 'planet4-gpnl-blocks' ),
					'attr'  => 'button_link_1',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => esc_html__( 'Enter button link of first column', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],

				// Second column fields.
				[
					'label' => __( 'Title', 'planet4-gpnl-blocks' ),
					'attr'  => 'title_2',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Enter title of second column', 'planet4-gpnl-blocks' ),
					],
				],
				[
					'label' => __( 'Description', 'planet4-gpnl-blocks' ),
					'attr'  => 'description_2',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Enter description of second column', 'planet4-gpnl-blocks' ),
					],
				],
				[
					'label' => __( 'Button text', 'planet4-gpnl-blocks' ),
					'attr'  => 'button_text_2',
					'type'  => 'text',
					'meta'  => [
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Button link', 'planet4-gpnl-blocks' ),
					'attr'  => 'button_link_2',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Enter button link of second column', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				// [
				// 	'label'       => __( 'Campaign Image', 'planet4-gpnl-blocks' ),
				// 	'attr'        => 'tag_image',
				// 	'type'        => 'attachment',
				// 	'libraryType' => [ 'image' ],
				// 	'addButton'   => __( 'Select Image for Campaign', 'planet4-gpnl-blocks' ),
				// 	'frameTitle'  => __( 'Select Image for Campaign', 'planet4-gpnl-blocks' ),
				// 	'description' => __( '(Optional)', 'planet4-gpnl-blocks' ),
				// ],
				// [
				// 	'label'       => __( 'Select focus point for campaign image', 'planet4-gpnl-blocks' ) . '<img src="' .
				// 									 esc_url( plugins_url( '/planet4-plugin-blocks/admin/images/donation_form.png' ) ) . '" />',
				// 	'attr'        => 'focus_tag_image',
				// 	'type'        => 'select',
				// 	'options'     => $focus_options,
				// ],
			];

			/*
			 * Define the Shortcode UI arguments.
			 */
			$shortcode_ui_args = [

				/*
				 * How the shortcode should be labeled in the UI. Required argument.
				 */
				'label' => __( 'Donation Form', 'planet4-gpnl-blocks' ),

				/*
				 * Include an icon with your shortcode. Optional.
				 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
				 */
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/' . self::BLOCK_NAME . '.png' ) . '" />',

				/*
				 * Define the UI for attributes of the shortcode. Optional.
				 * See above, to where the the assignment to the $fields variable was made.
				 */
				'attrs' => $fields,
				'post_type' => P4NLBKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields Array of fields that are to be used in the template.
		 * @param string $content The content of the post.
		 * @param string $shortcode_tag The shortcode tag (shortcake_two_columns).
		 *
		 * @return string The html markup for the block.
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

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
