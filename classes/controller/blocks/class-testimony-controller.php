<?php
/**
 * Testimony Module (Homepage B Version 6th Screen)
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Testimony_Controller' ) ) {
	/**
	 * Class Testimony_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Testimony_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'testimony';

		/**
		 * The maximum number of sum-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 9;

		/**
		 * Default layout option key.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = '1';

		/**
		 * The layout options for this module & planet 4 child theme.
		 *
		 * @const array LAYOUT_OPTIONS
		 */
		const LAYOUT_OPTIONS = [
			[
				'key' => '1',
				'title' => 'Vertical card, dark text',
				'image' => TRUE,
			],
			[
				'key' => '2',
				'title' => 'Vertical card, light text',
				'image' => TRUE,
			],
			[
				'key' => '3',
				'title' => 'Horizontal card, dark text',
				'image' => FALSE,
			],
			[
				'key' => '4',
				'title' => 'Horizontal card, light text',
				'image' => TRUE,
			]
		];

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
			];

			$layout_options = [];
			foreach(static::LAYOUT_OPTIONS as $layout_data) {
				$layout_options[] = [
					'value' => $layout_data[ 'key' ],
					'label' => __( $layout_data[ 'key' ] . '. ' . $layout_data[ 'title' ] . ( $layout_data[ 'image' ] ? '' : ' (no image)' ), 'planet4-gpea-blocks-backend' ),
				];
			}

			$field_groups = [

				'Card' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Card %s layout</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'layout',
						'type'  => 'radio',
						'value' => static::DEFAULT_LAYOUT,
						'options' => $layout_options,
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Card %s background image</i>', 'planet4-gpea-blocks-backend' ),
						'desc' => __( 'If you leave it empty, default value will be used', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'img',
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					],
					[
						'label'       => __( 'Card %s title', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'title',
						'type'        => 'text',
						'meta'        => [
							'placeholder' => __( 'Title', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label'       => __( 'Card %s name', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'name',
						'type'        => 'text',
						'meta'        => [
							'placeholder' => __( 'Name', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label'       => __( 'Card %s paragraph', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'paragraph',
						'type'        => 'textarea',
						'meta'        => [
							'placeholder' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
				],
			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Testimony', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/testimony-block.jpg' ) . '" />',
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

			// Get block options

			$testimony_block_options = get_option( 'gpea_testimony_block_options' );
			$testimony_block_default_bg_img = [];
			for( $i = 1; $i <= 4; $i ++ ) {
				$option_name = 'gpea_testimony_block_' . $i . '_bg_img';
				$testimony_block_default_bg_img[$i] = isset( $testimony_block_options[$option_name] ) && @strlen( $testimony_block_options[$option_name] ) ? $testimony_block_options[$option_name] : '';
				
			}

			if(!is_array($attributes)) {
				$attributes = [];
			}

			$field_groups = [];
			$last_is_horizontal_card = FALSE;

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

						if ( ( 'img' === $field_name_data ) && isset( $field_content ) ) {
							$field_content = wp_get_attachment_url( $field_content );
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

				$group[ 'start_slide_group' ] = FALSE;
				$group[ 'end_slide_group' ] = FALSE;
				if( isset($group[ 'layout' ]) && in_array( $group[ 'layout' ] , [ 3, 4 ] ) ) {
					if( !$last_is_horizontal_card ) {
						$group[ 'start_slide_group' ] = TRUE;
						$last_is_horizontal_card = TRUE;
					}
					else {
						$group[ 'end_slide_group' ] = TRUE;
						$last_is_horizontal_card = FALSE;
					}
				}
				else {
					$group[ 'start_slide_group' ] = TRUE;
					$group[ 'end_slide_group' ] = TRUE;
					$last_is_horizontal_card = FALSE;
				}

				$field_groups[] = $group;
			}

			if( $last_is_horizontal_card ) {
				$field_groups[ count($field_groups) - 1 ][ 'end_slide_group' ] = TRUE;
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
				'default_bg_images' => $testimony_block_default_bg_img,
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
