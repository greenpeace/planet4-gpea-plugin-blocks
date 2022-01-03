<?php
/**
 * Marketing Cloud Subscription Module (Homepage B Version 7th Screen, Modified from old module)
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'MC_Subscription_Controller' ) ) {
	/**
	 * Class MC_Subscription_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class MC_Subscription_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'mc_subscription';

		/**
		 * The maximum number of sum-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 50;

		const EXCEPTION_LANG = 'ko_KR';

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Section title', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'subtitle',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
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
					'label' => __( 'Submit Button Text', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'submit_btn_text',
					'type'  => 'text',
					'value' => 'Submit',
					'meta'  => [
						'placeholder' => __( 'Submit Button Text', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],

				[
					'label' => __( 'Thankyou Title', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'thankyou_title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'The title to show after the submission', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Thankyou Content', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'thankyou_content',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'The message to show after the submission', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],

				[
					'label' => __( 'MC Endpoint URL', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'endpoint',
					'type'  => 'url',
					'value' => 'https://cloud.greentw.greenpeace.org/websign',
					// 'value' => 'https://cloud.greenhk.greenpeace.org/websign-dev',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
					],
				],
				[
					'label' => __( 'Salesforce CampaignId', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'sf_campaign_id',
					'type'  => 'text',
					'value' => '7012u000000Oxj4AAC',
					'meta'  => [
						'placeholder' => __( 'Salesforce CampaignId', 'planet4-gpea-blocks-backend' ),
					],
				],
			];

			$field_groups = [

				'Option' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Option %s label</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'label',
						'type'  => 'text',
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Option %s is required</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'required',
						'type'  => 'checkbox',
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Option %s field name</i>', 'planet4-gpea-blocks-backend' ),
						'description' => __( 'Field name post to MC Endpoint.', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'name',
						'type'  => 'text',
					],
				],
			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | MC Subscription', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/mc-subscription-block.jpg' ) . '" />',
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
		public function prepare_data( $attributes, $content = '', $shortcode_tag = 'shortcake_' . self::BLOCK_NAME ) : array {

			// lexicon entries
			$lexicon = array();
			$lexicon['firstname_input_placeholder'] = __( 'First Name', 'planet4-gpea-blocks');
			$lexicon['lastname_input_placeholder'] = __( 'Last Name', 'planet4-gpea-blocks');
			$lexicon['email_input_placeholder'] = __( 'Email', 'planet4-gpea-blocks');

			$lexicon['first_name_is_required'] = TRUE;

			$lexicon['policy_checkbox_text'] = [];
			if(get_locale() == self::EXCEPTION_LANG) {
				$lexicon['first_name_is_required'] = FALSE;
				$lexicon['policy_checkbox_text'][] = __( "Yes! Please send me important updates from Greenpeace.", 'planet4-gpea-blocks');
				$lexicon['policy_checkbox_text'][] = __( "Greenpeace respects and protects your personal information and you can unsubscribe at any time. For more details please refer to Greenpeace privacy policy Greenpeace's privacy policy", 'planet4-gpea-blocks');
			}
			else {
				$lexicon['policy_checkbox_text'][] = __( "Yes! Please send me important updates from Greenpeace. Greenpeace respects and protects your personal information and you can unsubscribe at any time. For more details please refer to Greenpeace privacy policy Greenpeace's privacy policy", 'planet4-gpea-blocks');
			}

			$lexicon['input_required_err_message'] = __( 'Required', 'planet4-gpea-blocks');
			$lexicon['email_format_err_message'] = __( 'Please enter a valid e-mail address.', 'planet4-gpea-blocks');
			$lexicon['email_do_you_mean'] = __( 'Do you mean %s ?', 'planet4-gpea-blocks');
			$lexicon['policy_required_err_message'] = __( 'Please check the box to agree.', 'planet4-gpea-blocks');

			$lexicon['server_err_message'] = __( 'There was a problem with the submission', 'planet4-gpea-blocks');

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
						$group[ $field_name_data[0] ] = $field_content;
						$group_type = $field_name_data[1]; // TODO assigning multiple times here, can be more elegant?
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
					$static_fields[ $field_name ] = $field_content;
				}
			}
			return [
				'field_groups' => $field_groups,
				'static_fields' => $static_fields,
				'lexicon' => $lexicon,
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
