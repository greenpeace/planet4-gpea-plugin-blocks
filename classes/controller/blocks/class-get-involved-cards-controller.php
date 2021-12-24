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
		const MAX_REPEATER = 12;

		/**
		 * The group names.
		 *
		 * @const array GROUP_NAMES
		 */
		const GROUP_NAMES = [
			1 => 'Group 1',
			2 => 'Group 2',
			3 => 'Group 3',
		];

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			// get issues list
			$main_issues = [];
			if ( class_exists( 'P4CT_Site' ) ) {
				$gpea_extra = new \P4CT_Site();
				$main_issues = $gpea_extra->gpea_get_all_main_issues( TRUE );
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

			$group_name_list = self::GROUP_NAMES;

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
					'description' => __( 'Leave empty to use the selected page\'s category.', 'planet4-gpea-blocks-backend' ),
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
					'type'  => 'textarea',
				],
				[
					'label' => __( '<i>%s: Card %s link</i>', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s permalink.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'url',
					'type'  => 'text',
				],
				[
					'label' => __( '<i>%s: Card %s layout/visiblity</i>', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'layout',
					'type'  => 'radio',
					'value' => '0',
					'options' => [
						[
							'value' => '0',
							'label' => __( 'Don\'t show this card', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => '1',
							'label' => __( 'Location & percentage', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => '2',
							'label' => __( 'Only location', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => '3',
							'label' => __( 'No location & percentage', 'planet4-gpea-blocks-backend' ),
						],
					],
				],
				[
					'label' => __( '<i>%s: Card %s percentage</i>', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s setting.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'percentage',
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

			foreach ( $field_groups as $group_name => $group_fields ) {
				for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

					$safe_name = preg_replace( '/\s/', '', strtolower( $group_name ) );

					foreach ( $group_fields as $field ) {

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

			$tanslate = [
				'more_button_label' => __( 'See all', 'planet4-gpea-blocks' ),
				'category_label' => __( 'hot items', 'planet4-gpea-blocks' ),
				'location_label' => __( 'LOCATION', 'planet4-gpea-blocks' ),
				'people_label' => __( 'PEOPLE', 'planet4-gpea-blocks' ),
			];

			$default = [];
			$field_groups = [];

			// Group fields based on index number.

			$group_name_list = self::GROUP_NAMES;

			foreach( $group_name_list as $group_name ) {

				$safe_name = preg_replace( '/\s/', '', strtolower( $group_name ) );

				for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

					$attr_extension = '_' . $safe_name . '_' . $i;

					foreach ( $attributes as $field_name => $field_content ) {

						if ( preg_match( '/(.+)' . $attr_extension . '$/', $field_name, $matches ) ) {

							$field_name_data = $matches[ 1 ];
							$post = NULL;

							if ( ( 'img' === $field_name_data ) && isset( $field_content ) && strlen( $field_content ) ) {
								$field_content = wp_get_attachment_url( $field_content );
							}
							elseif ( ( 'post_id' === $field_name_data ) && isset( $field_content ) && strlen( $field_content ) ) {
								$post = get_post( $field_content );
							}

							if( $post ) {
								$post_default = [];
								$post_default[ 'post_title' ] = $post->post_title;
								$post_default[ 'url' ] = get_permalink( $post->ID );
								$post_default[ 'img' ] = get_the_post_thumbnail_url( $post->ID, 'post-thumbnails' );
								$post_default[ 'percentage' ] = get_post_meta( $post->ID, 'p4-gpea_project_percentage', TRUE );
								$post_default[ 'location' ] = get_post_meta( $post->ID, 'p4-gpea_project_localization', TRUE );
							}
							if ( $post && class_exists( 'P4CT_Site' ) ) {
								$gpea_extra = new \P4CT_Site();
								$main_issue = $gpea_extra->gpea_get_main_issue( $post->ID );
								if( $main_issue ) {
									$post_default[ 'category_slug' ] = $main_issue->slug;
									$post_default[ 'category' ] = $main_issue->name;
								}
							}
							if( $post && !isset( $default[ $safe_name ] )) {
								$default[ $safe_name ] = [];
							}
							if( $post ) {
								$default[ $safe_name ][ $i ] = $post_default;
							}

							if( !isset( $field_groups[ $safe_name ] ) ) {
								$field_groups[ $safe_name ] = [];
							}
							if( !isset( $field_groups[ $safe_name ][ $i ] ) ) {
								$field_groups[ $safe_name ][ $i ] = [];
							}

							if ( ( 'category' === $field_name_data ) && isset( $field_content ) && strlen( $field_content ) ) {
								if( $category = get_term_by( 'slug', $field_content, 'category' ) ) {
									$field_groups[ $safe_name ][ $i ][ 'category_slug' ] = $field_content;
									$field_groups[ $safe_name ][ $i ][ $field_name_data ] = $category->name;
								}
							}
							else {
								$field_groups[ $safe_name ][ $i ][ $field_name_data ] = $field_content;
							}

						}

					}


				}

			}

			// Extract static fields only.
			$static_fields = [];
			foreach ( $attributes as $field_name => $field_content ) {
				if( preg_match( '/^(button)_([a-z0-9]+)_([a-z]+)$/', $field_name, $matches ) ) {
					$safe_name = $matches[ 1 ];
					$i = $matches[ 2 ];
					$field_name_data = $matches[ 3 ];
					if( !isset( $static_fields[ $i ] ) ) {
						$static_fields[ $i ] = [];
					}
					$static_fields[ $i ][ $field_name_data ] = $field_content;
				}
			}
			return [
				'field_groups' => $field_groups,
				'static_fields' => $static_fields,
				'tanslate_strings' => $tanslate,
				'default_strings' => $default,
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
