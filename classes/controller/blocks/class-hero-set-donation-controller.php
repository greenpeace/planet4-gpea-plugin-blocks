<?php
/**
 * Hero Set Module (Homepage B Version 1st Screen)
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Hero_Set_Donation_Controller' ) ) {
	/**
	 * Class Hero_Set_Donation_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Hero_Set_Donation_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'hero_set_donation';

		/**
		 * The maximum number of sum-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 30;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [

				[
					'label' => __( '<h3>Donation Module Config</h3><hr>', 'planet4-gpea-blocks-backend' ),
					'description' => __( '
						Market and language will be auto-detected.
					', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'donation_title_hint',
					'type'  => 'radio',
					'meta'  => [
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],

				[
					'label' => __( '<i>Script URL</i>', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'This field must be set or the donation module will not be rendered.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'donation_script',
					'type'  => 'url',
				],
				[
					'label' => __( 'Environment', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'donation_env',
					'type'  => 'radio',
					'value' => 'production',
					'options' => [
						[
							'value' => 'test',
							'label' => __( 'Test', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => 'full',
							'label' => __( 'Full', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => 'production',
							'label' => __( 'Production', 'planet4-gpea-blocks-backend' ),
						],
					],
					'meta'  => [
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Campaign (name)', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'This field must be set or the donation module will not be rendered.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'donation_campaign',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Campaign (name)', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'CampaignId', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use donation module\'s default.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'donation_campaign_id',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'CampaignId', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],

				[
					'label' => __( '<h3>HERO Set Content</h3><hr>', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'content_title_hint',
					'type'  => 'radio',
					'meta'  => [
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],

				[
					'label' => __( 'H1 Part1, first sentence (for PC)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'H1 Part1, first sentence', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'H1 Part1, first sentence (for mobile, you can change it to a shorter state)', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use PC version.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title_mobile',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'H1 Part1, first sentence', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'H1 Part1, first sentence\'s color', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title_color',
					'type'  => 'radio',
					'value' => 'light',
					'options' => [
						[
							'value' => 'light',
							'label' => __( 'White', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => 'dark',
							'label' => __( 'Black', 'planet4-gpea-blocks-backend' ),
						],
					],
					'meta'  => [
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'H1 Part2, second sentence (for PC)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'secondary_title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'H1 Part2, second sentence (for PC)', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'H1 Part2, second sentence (for mobile, you can change it to a shorter state)', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use PC version.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'secondary_title_mobile',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'H1 Part2, second sentence', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'H1 Part2, second sentence\'s color', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'secondary_title_color',
					'type'  => 'radio',
					'value' => 'green',
					'options' => [
						[
							'value' => 'green',
							'label' => __( 'Green', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => 'orange',
							'label' => __( 'Orange', 'planet4-gpea-blocks-backend' ),
						],
					],
					'meta'  => [
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Subtitle, focus on your value proposition', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'subtitle',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			$field_groups = [

				'Background' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'type'        => 'text',
						'meta'  => [
							'data-plugin' => 'planet4-gpea-blocks',
							'data-hidden' => TRUE,
						],
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Background %s image (for PC, 1366x768)</i>', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'img',
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					],
					[
						'label' => __( '<i>Background %s Youtube URL (for PC, 1366x768)</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'video_youtube',
						'type'  => 'text',
						'meta'  => [
							'placeholder' => __( 'Youtube URL', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Background %s image (for mobile, 375x814)</i>', 'planet4-gpea-blocks-backend' ),
						'description' => __( 'Leave empty to use PC version.', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'img_mobile',
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Background %s overlay opacity</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'opacity',
						'type'  => 'radio',
						'value' => '25',
						'options' => [
							[
								'value' => '25',
								'label' => __( '25%', 'planet4-gpea-blocks-backend' ),
							],
							[
								'value' => '50',
								'label' => __( '50%', 'planet4-gpea-blocks-backend' ),
							],
							[
								'value' => '75',
								'label' => __( '75%', 'planet4-gpea-blocks-backend' ),
							],
						],
					],
				],
			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | HERO Set+Donation Module', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/hero-set-block.jpg' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4EABKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

		}

		/**
		 * Get all the data that will be needed to render the block correctly.
		 *
		 * @param array $fields This will contain the fields to be rendered.
		 * @param array $field_groups This contains the field templates to be repeated.
		 *
		 * @return array The fields to be rendered
		 */
		private function format_meta_fields( $fields, $field_groups ) : array {

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {
				foreach ( $field_groups as $group_name => $group_fields ) {
					foreach ( $group_fields as $field ) {

						$safe_name = preg_replace( '/\s/', '', strtolower( $group_name ) );
						$attr_extension = '_' . $safe_name . '_' . $i;

						if ( array_key_exists( 'attr' , $field ) ) {
							$field['attr'] .= $attr_extension;
						} else {
							$field['attr'] = $i . $attr_extension;
						}

						if ( array_key_exists( 'label' , $field ) ) {
							$field['label'] = sprintf( $field['label'], $i );
						} else {
							$field['label'] = $field['attr'];
						}

						$new_meta = [
							'data-element-type' => $safe_name,
							'data-element-name' => $group_name,
							'data-element-number' => $i,
						];
						if ( ! array_key_exists( 'meta' , $field ) ) {
							$field['meta'] = [];
						}
						$field['meta'] += $new_meta;

						$fields[] = $field;
					}
				}
			}

			return $fields;
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
		public function prepare_data( $attributes = '', $content = '', $shortcode_tag = 'shortcake_' . self::BLOCK_NAME ) : array {

			if(!is_array($attributes)) {
				$attributes = [];
			}

			$field_groups = [];

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				// Group fields based on index number.
				$group = [];
				$group_type = false;
				foreach ( $attributes as $field_name => $field_content ) {
					if ( preg_match( '/_' . $i . '$/', $field_name ) ) {

						$field_name_data = explode( '_', $field_name );
						array_pop($field_name_data);
						$group_type = array_pop($field_name_data);
						$field_name_data = implode('_', $field_name_data);

						if ( ( 'img' === $field_name_data || 'img_mobile' === $field_name_data ) && isset( $field_content ) && strlen( $field_content ) ) {
							$field_content = wp_get_attachment_image_url( $field_content, 'large' );
						}
						elseif( 'video_youtube' === $field_name_data && isset( $field_content ) && strlen( $field_content ) ) {
							$field_content = $this->getYoutubeId( $field_content );
						}

						$group[ $field_name_data ] = $field_content;

					}
				}

				// Extract group field type.
				if ( $group_type ) {
					$group['__group_type__'] = $group_type;
				} else {
					continue;
				}

				$field_groups[] = $group;
			}

			// Extract static fields only.
			$static_fields = [];
			foreach ( $attributes as $field_name => $field_content ) {
				if ( ! preg_match( '/_\d+$/', $field_name ) ) {
					if( 'title' === $field_name || 'title_mobile' === $field_name ) {
						$field_content = str_replace( [ '<p>', '</p>' ], '<br />', $field_content );
					}
					$static_fields[ $field_name ] = $field_content;
				}
			}

			$current_lang = get_locale();
			$current_lang_array = explode('_', $current_lang);
			return [
				'field_groups' => $field_groups,
				'static_fields' => $static_fields,
				'donation_module_language' => $current_lang,
				'donation_module_market' => isset($current_lang_array[1]) ? $current_lang_array[1] : '',
			];

		}

		private function getYoutubeId($url) {
			$pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/';
        	preg_match($pattern, $url, $matches);
        	return (isset($matches[7])) ? $matches[7] : false;
		}

		private function generateRandomString($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
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
			
			if( isset( $data['static_fields']['donation_script'] ) ) {
				wp_enqueue_script(
					'donation-module',
					$data['static_fields']['donation_script']
				);
			}

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			return ob_get_clean();
		}
	}
}
