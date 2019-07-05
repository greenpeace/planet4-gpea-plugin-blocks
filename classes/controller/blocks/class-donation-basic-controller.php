<?php
/**
 * Donation basic block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Donation_Basic_Controller' ) ) {
	/**
	 * Class Donation_Basic_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Donation_Basic_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'donation_basic';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'default';

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Title', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'paragraph',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Label donate once', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'label_donate_once',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Label donate once', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Label donate monthly', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'label_donate_monthly',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Label donate monthly', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Minimum amount for one-off donation', 'planet4-gpea-blocks-backend' ),
					'desc' => __( 'If you leave it empty, default value will be used', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'minimum_oneoff',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Minimum amount', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Minimum amount for regular donation', 'planet4-gpea-blocks-backend' ),
					'desc' => __( 'If you leave it empty, default value will be used', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'minimum_regular',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Minimum amount', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Suggested amount for one-off donation', 'planet4-gpea-blocks-backend' ),
					'desc' => __( 'If you leave it empty, default value will be used', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'suggested_oneoff',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Suggested amount', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Suggested amount for regular donation', 'planet4-gpea-blocks-backend' ),
					'desc' => __( 'If you leave it empty, default value will be used', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'suggested_regular',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Suggested amount', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Error message in case lower amount', 'planet4-gpea-blocks-backend' ),
					'desc' => __( 'If you leave it empty, default value will be used', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'minimum_error',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Message', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Currency code', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'currency_code',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Currency code', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Button label', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'button_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Button label', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Button landing link', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'button_landing_link',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Button landing link', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Background image', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'bg_img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
				[
					'label' => __( 'Background image mobile', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'bg_img_mobile',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
				[
					'label' => __( 'Blur upper border', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'upper_blur',
					'type'        => 'checkbox',
					'meta'  => [
						'placeholder' => __( 'Blur upper border', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Donation Basic', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/donation_block.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4EABKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

		}
		/**
		 * Get all the data that will be needed to render the block correctly.
		 *
		 * @param array  $attributes This is the array of fields of this block.
		 * @param string $content This is the post content.
		 * @param string $shortcode_tag The shortcode tag of this block.
		 *
		 * @return array The data to be passed in the View.
		 */
		public function prepare_data( $attributes, $content = '', $shortcode_tag = 'shortcake_' . self::BLOCK_NAME ) : array {

			if ( isset( $attributes['bg_img'] ) ) {
				$attributes['bg_img'] = wp_get_attachment_url( $attributes['bg_img'] );
			}

			if ( isset( $attributes['bg_img_mobile'] ) ) {
				$attributes['bg_img_mobile'] = wp_get_attachment_url( $attributes['bg_img_mobile'] );
			}

			// engaging and other crm integration
			$gpea_options = get_option( 'gpea_options' );
			$attributes['external_recurring_question'] = isset( $gpea_options['gpea_donation_recurring_question'] ) ? $gpea_options['gpea_donation_recurring_question'] : 'recurring';

			if ( ! isset( $attributes['button_landing_link'] ) ) {
				$attributes['button_landing_link'] = isset( $gpea_options['gpea_default_donation_link'] ) ? $gpea_options['gpea_default_donation_link'] : '';
			}

			// minimum amount, suggested and error message
			if ( ! isset( $attributes['minimum_oneoff'] ) ) {
				$attributes['minimum_oneoff'] = isset( $gpea_options['gpea_donation_minimum-oneoff'] ) ? $gpea_options['gpea_donation_minimum-oneoff'] : '';
			}
			if ( ! isset( $attributes['minimum_regular'] ) ) {
				$attributes['minimum_regular'] = isset( $gpea_options['gpea_donation_minimum-regular'] ) ? $gpea_options['gpea_donation_minimum-regular'] : '';
			}
			if ( ! isset( $attributes['suggested_oneoff'] ) ) {
				$attributes['suggested_oneoff'] = isset( $gpea_options['gpea_donation_suggested-oneoff'] ) ? $gpea_options['gpea_donation_suggested-oneoff'] : '';
			}
			if ( ! isset( $attributes['suggested_regular'] ) ) {
				$attributes['suggested_regular'] = isset( $gpea_options['gpea_donation_suggested-regular'] ) ? $gpea_options['gpea_donation_suggested-regular'] : '';
			}
			if ( ! isset( $attributes['minimum_error'] ) ) {
				$attributes['minimum_error'] = isset( $gpea_options['gpea_donation_minimum-error-message'] ) ? $gpea_options['gpea_donation_minimum-error-message'] : '';
			}

			// lexicon fallback values
			if ( ! isset( $attributes['label_donate_once'] ) ) {
				$attributes['label_donate_once'] = __( 'One-off', 'planet4-gpea-blocks' );
			}
			if ( ! isset( $attributes['label_donate_monthly'] ) ) {
				$attributes['label_donate_monthly'] = __( 'Monthly', 'planet4-gpea-blocks' );
			}
			if ( ! isset( $attributes['button_label'] ) ) {
				$attributes['button_label'] = __( 'Donate', 'planet4-gpea-blocks' );
			}
			if ( ! isset( $attributes['currency_code'] ) ) {
				$attributes['currency_code'] = __( 'currency_code', 'planet4-gpea-blocks' );
			}

			return [
				'fields' => $attributes,
			];

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

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			return ob_get_clean();
		}

	}
}
