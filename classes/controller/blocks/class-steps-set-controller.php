<?php
/**
 * Homepage B2 class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Steps_Set_Controller' ) ) {
	/**
	 * Class Steps_Set_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Steps_Set_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'steps_set';

		/**
		 * The maximum number of sum-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 5;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Title (for PC)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Title (for PC)', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Title (for mobile)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title_mobile',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Title (for mobile)', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			$field_groups = [

				'Item' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Item %s image</i>', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'img',
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Item %s title</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'title',
						'type'  => 'text',
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Item %s text</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'content',
						'type'  => 'textarea',
					],
				],
			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Steps Set', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/steps-set-block.jpg' ) . '" />',
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

						if ( ( 'img' === $field_name_data[0] ) && isset( $field_content ) ) {
							$field_content = wp_get_attachment_url( $field_content );
						}

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
