<?php
/**
 * Get Involved Cards Module (Homepage B Version 4th Screen, Bottom Part)
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Get_Involved_Cards_Controller' ) ) {
	/**
	 * Class Get_Involved_Cards_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Get_Involved_Cards_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'get_involved_cards';

		/**
		 * The maximum number of sum-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 3;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			// get issues list

			$planet4_options = get_option( 'planet4_options' );
			$main_issues_category_id = isset( $planet4_options['issues_parent_category'] ) ? $planet4_options['issues_parent_category'] : FALSE;
			if ( ! $main_issues_category_id ) {
				$main_issues_category = get_term_by( 'slug', 'issues', 'category' );
				if ( $main_issues_category ) {
					$main_issues_category_id = $main_issues_category->term_id;
				}
			}

			$main_issues = [];
			if( $main_issues_category_id ) {
				$main_issues = get_terms([
					'taxonomy' => 'category',
					'parent' => $main_issues_category_id,
				]);
			}

			$main_issue_options = [];
			$main_issue_options[] = [
				'value' => '',
				'label' => __( 'Use the selected page\'s category', 'planet4-gpea-blocks-backend' ),
			];
			foreach($main_issues as $issue_item) {
				$main_issue_options[] = [
					'value' => $issue_item->slug,
					'label' => esc_html($issue_item->name),
				];
			}

			// group list

			$group_name_list = [
				1 => 'Group 1',
				2 => 'Group 2',
				3 => 'Group 3',
			];

			// static fields

			$see_all_label = __( 'See all', 'planet4-gpea-blocks' );

			$fields = [];

			foreach($group_name_list as $i => $group_name) {
				$fields[] = [
					'label' => __( $group_name . ' link text', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'button_' . $i . '_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( $group_name . ' link text', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				];
				$fields[] = [
					'label' => __( $group_name . ' link is visible</i>', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'button_' . $i . '_enabled',
					'type'  => 'checkbox',
					// 'value' => 'true',
				];
			}

			$fields[] = [
				'label' => sprintf(__( '"%s" link url', 'planet4-gpea-blocks-backend' ), $see_all_label),
				'attr'  => 'button_more_url',
				'type'  => 'url',
			];
			$fields[] = [
				'label' => sprintf(__( '"%s" link is visible', 'planet4-gpea-blocks-backend' ), $see_all_label),
				'attr'  => 'button_more_enabled',
				'type'  => 'checkbox',
				// 'value' => 'true',
			];

			// group fields

			$location_label = __( 'LOCATION', 'planet4-gpea-blocks' );
			$people_label = __( 'PEOPLE', 'planet4-gpea-blocks' );

			$group_field_list = [
				[
					'label'       => __( '<i>%s: Card %s project page</i>', 'planet4-gpea-blocks-backend' ),
					'attr'     => 'post_id',
					'type'     => 'post_select',
					// 'multiple' => 'multiple',
					'query'    => [
						'post_type'   => array( 'page' ),
						'post_status' => 'publish',
						'orderby'     => 'post_title',
						'order'           => 'ASC',
						'meta_key'    => '_wp_page_template',
						'meta_value'  => 'page-templates/project.php',
					],
					'meta'     => [
						'select2_options' => [
							'allowClear'             => TRUE,
							'placeholder'            => __( 'Select project page', 'planet4-gpea-blocks-backend' ),
							'closeOnSelect'          => FALSE,
							'minimumInputLength'     => 0,
							'multiple'               => FALSE,
							'maximumSelectionLength' => 1,
							'width'                  => '80%',
						],
					],
				],
				[
					'label' => __( '<i>%s: Card %s project category</i>', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'category',
					'type'  => 'select',
					'options'  => $main_issue_options,
				],
				[
					'label' => __( '<i>%s: Card %s project title</i>', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s title.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'post_title',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s Image</i>', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s featured image.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
				[
					'label' => __( '<i>%s: Card %s subtitle</i>', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s paragraph</i>', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'paragraph',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s link</i>', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s permalink.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'url',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s layout</i>', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'layout',
					'type'  => 'radio',
					'value' => '1',
					'options' => [
						[
							'value' => '1',
							'label' => __( 'Location & pecentage', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => '2',
							'label' => __( 'Only location', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => '3',
							'label' => __( 'No location & pecentage', 'planet4-gpea-blocks-backend' ),
						],
					],
				],
				[
					'label' => __( '<i>%s: Card %s pecentage</i>', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s setting.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'pecentage',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s location label</i>', 'planet4-gpea-blocks-backend' ),
					'description' => sprintf(__( 'Leave empty to use the default: "%s".', 'planet4-gpea-blocks-backend' ), $location_label),
					'attr'  => 'location_label',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s location</i>', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s setting.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'location',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s people label</i>', 'planet4-gpea-blocks-backend' ),
					'description' => sprintf(__( 'Leave empty to use the default: "%s".', 'planet4-gpea-blocks-backend' ), $people_label),
					'attr'  => 'people_label',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s people</i>', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'people',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s people unit</i>', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'people_unit',
					'type'  => 'text',
				],
			];

			$field_groups = [];
			foreach($group_name_list as $group_name) {
				$field_groups[$group_name] = $group_field_list;
			}

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Get Involved Cards', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/get-involved-cards-block.jpg' ) . '" />',
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
							$field['label'] = sprintf( $field['label'], $group_name, $i );
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
