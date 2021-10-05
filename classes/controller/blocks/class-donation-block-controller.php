<?php
/**
 * Grid images class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Donation_Block_Controller' ) ) {
	/**
	 * Class Donation_Block_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Donation_Block_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'donation_block';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'standard';

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( '<h3>Donation Block</h3>', 'planet4-gpea-blocks-backend' ),
					'description' => sprintf(__( '
						<ol>
							<li>Location: it\'s better to set around the middle area of the post.</li>
							<li>Leave the fields empty to use the default value.</li>
							<li>Go to the setting &quot;%s > Post/Page Donation Blocks&quot; to setup default values.</li>
						</ol>
					', 'planet4-gpea-blocks-backend' ), __( 'Settings' )),
					'attr'  => 'title',
					'type'  => 'radio',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Title', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use default.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Description<br>For a better user experience, it\'s better to leave empty or not over 20 characters.', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use default.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'desc',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Description', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'       => __( 'Button Link', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use default.', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'button_link',
					'type'        => 'url',
					'meta'        => [
						'placeholder' => __( 'Button Link', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'       => __( 'Button label', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use default.', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'button_text',
					'type'        => 'text',
					'meta'        => [
						'placeholder' => __( 'Button label', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Background Image', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use default.', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'bg_img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],				
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'             => __( 'GPEA | Donation Block', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/donation-block.jpg' ) . '" />',
				'attrs'             => $fields,
				'post_type'         => P4EABKS_DONATION_BLOCK_ALLOWED_PAGETYPE,
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

			global $post;
			if ( class_exists( 'P4CT_Site' ) ) {
				$gpea_extra = new \P4CT_Site();
				$main_issue = $gpea_extra->gpea_get_main_issue($post->ID);
				if( $main_issue ) {
					$main_issue_slug = $main_issue->slug;
				}
			}
			
			// Get block options

			$donation_block_options = get_option( 'gpea_donation_block_options' );

			$donation_block_default_title = '';
			if( isset( $main_issue_slug ) && isset( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_title'] ) && @strlen( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_title'] ) ) {
				$donation_block_default_title = $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_title'];
			}
			elseif( isset( $donation_block_options['gpea_donation_block_default_title'] ) ) {
				$donation_block_default_title = $donation_block_options['gpea_donation_block_default_title'];
			}

			$donation_block_default_desc = '';
			if( isset( $main_issue_slug ) && isset( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_desc'] ) && @strlen( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_desc'] ) ) {
				$donation_block_default_desc = $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_desc'];
			}
			elseif( isset( $donation_block_options['gpea_donation_block_default_desc'] ) ) {
				$donation_block_default_desc = $donation_block_options['gpea_donation_block_default_desc'];
			}

			$donation_block_default_link = '';
			if( isset( $main_issue_slug ) && isset( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_button_link'] ) && @strlen( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_button_link'] ) ) {
				$donation_block_default_link = $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_button_link'];
			}
			elseif( isset( $donation_block_options['gpea_donation_block_default_button_link'] ) ) {
				$donation_block_default_link = $donation_block_options['gpea_donation_block_default_button_link'];
			}

			$donation_block_default_text = '';
			if( isset( $main_issue_slug ) && isset( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_button_text'] ) && @strlen( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_button_text'] ) ) {
				$donation_block_default_text = $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_button_text'];
			}
			elseif( isset( $donation_block_options['gpea_donation_block_default_button_text'] ) ) {
				$donation_block_default_text = $donation_block_options['gpea_donation_block_default_button_text'];
			}

			$donation_block_default_img = '';
			if( isset( $main_issue_slug ) && isset( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_bg_img'] ) && @strlen( $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_bg_img'] ) ) {
				$donation_block_default_img = $donation_block_options['gpea_donation_block_' . $main_issue_slug . '_bg_img'];
			}
			elseif( isset( $donation_block_options['gpea_donation_block_default_bg_img'] ) ) {
				$donation_block_default_img = $donation_block_options['gpea_donation_block_default_bg_img'];
			}

			if(!is_array($attributes)) {
				$attributes = [];
			}

			if ( !isset( $attributes['title'] ) || !@strlen( $attributes['title'] ) ) {
				$attributes['title'] = $donation_block_default_title;
			}

			if ( !isset( $attributes['desc'] ) || !@strlen( $attributes['desc'] ) ) {
				$attributes['desc'] = nl2br($donation_block_default_desc);
			}

			if ( !isset( $attributes['button_link'] ) || !@strlen( $attributes['button_link'] ) ) {
				$attributes['button_link'] = $donation_block_default_link;
			}

			if ( !isset( $attributes['button_text'] ) || !@strlen( $attributes['button_text'] ) ) {
				$attributes['button_text'] = $donation_block_default_text;
			}

			if ( isset( $attributes['bg_img'] ) && @strlen( $attributes['button_text'] ) ) {
				$attributes['bg_img'] = wp_get_attachment_url( $attributes['bg_img'] );
			}
			else {
				$attributes['bg_img'] = $donation_block_default_img;
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
