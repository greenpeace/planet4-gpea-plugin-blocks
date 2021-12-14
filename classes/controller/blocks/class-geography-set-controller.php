<?php
/**
 * Geography Set Module (Homepage B Version 3rd Screen)
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Geography_Set_Controller' ) ) {
	/**
	 * Class Geography_Set_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Geography_Set_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'geography_set';

		/**
		 * The maximum number of sum-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 10;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Section title', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Section title', 'planet4-gpea-blocks-backend' ),
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
					'label' => __( 'Video thumbnail', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'video_img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
				[
					'label' => __( 'Paragraph under thumbnail', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'video_paragraph',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Paragraph under thumbnail', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Video title', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use resource\'s title. (Not support on YouTube)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'video_title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Video title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Youtube URL', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'video_youtube',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Youtube URL', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'       => __( 'Or choose a video file', 'planet4-blocks-backend' ),
					'attr'        => 'video',
					'type'        => 'attachment',
					'libraryType' => [ 'video' ],
					'addButton'   => __( 'Select a Video', 'planet4-blocks-backend' ),
					'frameTitle'  => __( 'Select a Video', 'planet4-blocks-backend' )
				],
			];

			$field_groups = [

				'Ship' => [
					[
						'label' => __( '<i>Ship %s icon</i>', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'icon',
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					],
					[
						'label' => __( '<i>Ship %s name</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'title',
						'type'  => 'text',
					],
					[
						'label' => __( '<i>Ship %s subtitle</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'subtitle',
						'type'  => 'text',
					],
					[
						'label' => __( '<i>Ship %s paragraph</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'paragraph',
						'type'  => 'textarea',
					],
					[
						'label' => __( '<i>Ship %s position endpoint URL</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'endpoint',
						'type'  => 'url',
					],
					[
						'label' => __( '<i>Ship %s image</i>', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'img',
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					],
					[
						'label' => __( '<i>Ship %s is visible on map</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'enabled',
						'type'  => 'checkbox',
						// 'value' => 'true',
					],
					[
						'label' => __( '<i>Ship %s video button is visible</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'layout',
						'type'  => 'video_enabled',
						'type'  => 'checkbox',
						// 'value' => 'true',
					],
					[
						'label' => __( '<i>Ship %s video button label</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'video_button_label',
						'type'  => 'text',
					],
					[
						'label' => __( 'Youtube URL', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'video_youtube',
						'type'  => 'text',
					],
					[
						'label'       => __( 'Or choose a video file', 'planet4-blocks-backend' ),
						'attr'        => 'video',
						'type'        => 'attachment',
						'libraryType' => [ 'video' ],
						'addButton'   => __( 'Select a Video', 'planet4-blocks-backend' ),
						'frameTitle'  => __( 'Select a Video', 'planet4-blocks-backend' )
					],
					[
						'label' => __( 'Auto play (Only support on PC)', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'video_autoplay',
						'type'  => 'checkbox',
						// 'value' => 'true',
					],
				],
			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Geography Set', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/geography-set-block.jpg' ) . '" />',
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

			// Extract static fields only.
			$static_fields = [];
			foreach ( $attributes as $field_name => $field_content ) {
				if ( ! preg_match( '/_\d+$/', $field_name ) ) {
					$static_fields[ $field_name ] = $field_content;
				}
			}
			return [
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
