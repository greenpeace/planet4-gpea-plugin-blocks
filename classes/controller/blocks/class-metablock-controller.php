<?php
/**
 * Mixed content row block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Metablock_Controller' ) ) {
	/**
	 * Class Metablock_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Metablock_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'metablock';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'default';

		/**
		 * The maximum number of sub-elements.
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
					'label' => __( 'Title', 'planet4-gpea-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
						'data-testdataasd' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Subtitle', 'planet4-gpea-blocks' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Description', 'planet4-gpea-blocks' ),
					'attr'  => 'description',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Description', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			$field_groups = [
				'Static content' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Static item %s title</i>', 'planet4-gpea-blocks' ),
						'attr'  => 'title',
						'type'  => 'text',
						'meta'  => [
							'placeholder' => __( 'Enter title', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Static item %s description</i>', 'planet4-gpea-blocks' ),
						'attr'  => 'description',
						'type'  => 'textarea',
						'meta'  => [
							'placeholder' => __( 'Enter description', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
				],
				'Dynamic content' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Dynamic item %s title</i>', 'planet4-gpea-blocks' ),
						'attr'  => 'title',
						'type'  => 'text',
						'meta'  => [
							'placeholder' => __( 'Enter title', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Dynamic item %s post</i>', 'planet4-gpea-blocks' ),
						'attr'  => 'post',
						'type'  => 'post_select',
						'query' => array(
							'post_type' => 'post',
						),
						'meta'  => [
							'placeholder' => __( 'Select dynamic content', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
				],
			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'Metablock', 'planet4-gpea-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ) . '" />',
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
		 * @param array  $fields This is the array of fields of this block.
		 * @param string $content This is the post content.
		 * @param string $shortcode_tag The shortcode tag of this block.
		 *
		 * @return array The data to be passed in the View.
		 */
		public function prepare_data( $fields, $content = '', $shortcode_tag = 'shortcake_' . self::BLOCK_NAME ) : array {

			$field_groups = [];

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				// Group fields based on index number.
				$group = [];
				$group_type = false;
				foreach ( $fields as $field_name => $field_content ) {
					if ( preg_match( '/_' . $i . '$/', $field_name ) ) {
						$field_name_data = explode( '_', $field_name );
						$group[ $field_name_data[0] ] = $field_content;
						$group_type = $field_name_data[1]; // TODO assigning multiple times here, can be more elegant?
					}
				}

				// Extract group field type.
				if ( $group_type ) {
					$group['__group_type__'] = $group_type;
				}

				if ( 'dynamiccontent' === $group_type ) {
					$post = get_posts(
						[
							'include' => $group['post'],
						]
					);
					$group['post'] = (array) $post[0];
				}

				$field_groups[] = $group;
			}

			// Extract static fields only.
			$static_fields = [];
			foreach ( $fields as $field_name => $field_content ) {
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
