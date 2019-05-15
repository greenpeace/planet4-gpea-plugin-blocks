<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Mixed_Content_Row_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Mixed_Content_Row_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Mixed_Content_Row_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'mixed_content_row';

		/** @const string DEFAULT_LAYOUT */
		const DEFAULT_LAYOUT = 'default';

		/** @const int MAX_REPEATER */
		const MAX_REPEATER = 5;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Title', 'planet4-gpnl-blocks' ),
					'attr'	=> 'title',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
						'data-testdataasd' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Subtitle', 'planet4-gpnl-blocks' ),
					'attr'	=> 'subtitle',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Subtitle', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
						'data-testdataasd' => 'planet4-gpnl-blocks',
					],
				],
			];

			$posts = get_posts([
				'orderby' => 'title',
				'order' => 'asc',
			]);
			$posts = array_map( function( $post ) {
				return [
					'value' => strval( $post->ID ),
					'label' => esc_html( $post->post_title ),
				];
			}, $posts);
			array_unshift($posts, [
					'value' => '',
					'label' => __( 'Please select a post', 'planet4-gpnl-blocks' ),
			]);

			$field_groups = [

				'Title and text' => [
					[
						'label' => __('<i>Block %s title</i>', 'planet4-gpnl-blocks'),
						'attr'	=> 'title',
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => __( 'Enter title', 'planet4-gpnl-blocks' ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					],
					[
						'label' => __('<i>Block %s text</i>', 'planet4-gpnl-blocks'),
						'attr'	=> 'textblock',
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => __( 'Enter text', 'planet4-gpnl-blocks' ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					],
				],

				'Two line title and text' => [
					[
						'label' => __('<i>Block %s title line 1</i>', 'planet4-gpnl-blocks'),
						'attr'	=> 'titlea',
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => __( 'Enter line 1', 'planet4-gpnl-blocks' ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					],
					[
						'label' => __('<i>Block %s title</i>', 'planet4-gpnl-blocks'),
						'attr'	=> 'titleb',
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => __( 'Enter title', 'planet4-gpnl-blocks' ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					],
					[
						'label' => __('<i>Block %s text</i>', 'planet4-gpnl-blocks'),
						'attr'	=> 'textblock',
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => __( 'Enter text', 'planet4-gpnl-blocks' ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					],
				],

				'Text only' => [
					[
						'label' => __('<i>Block %s text</i>', 'planet4-gpnl-blocks'),
						'attr'	=> 'textblock',
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => __( 'Enter text', 'planet4-gpnl-blocks' ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					],
				],

				'Tip' => [
					[
						'label' => __('<i>Block %s tip text</i>', 'planet4-gpnl-blocks'),
						'attr'	=> 'textblock',
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => __( 'Enter text', 'planet4-gpnl-blocks' ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					],
					[
						'label' => __('<i>Block %s tip icon</i>', 'planet4-gpnl-blocks'),
						'attr'	=> 'icon',
						'type'	=> 'select',
						'options' => [
							[ 'value' => '', 'label' => __( 'Select tip icon') ],
							[ 'value' => '💦', 'label' => '💦' ],
							[ 'value' => '🌧', 'label' => '🌧' ],
						],
						'meta'	=> [
							'placeholder' => __( 'Select tip icon', 'planet4-gpnl-blocks' ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					],
				],

				'Post' => [
					[
						'label' => __('<i>Block %s post</i>', 'planet4-gpnl-blocks'),
						'attr'	=> 'post',
						'type'	=> 'select',
						'options' => $posts,
						'meta'	=> [
							'placeholder' => __( 'Select post', 'planet4-gpnl-blocks' ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					],
				],

			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Mixed Content Row', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ) . '" />',
				'attrs'			=> $fields,
				'post_type'		=> P4NLBKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

		}

		/**
		 * Get all the data that will be needed to render the block correctly.
		 *
		 * @param array	$fields This will contain the fields to be rendered
		 * @param array $field_groups This contains the field templates to be repeated
		 *
		 * @return array The fields to be rendered
		 */
		private function format_meta_fields( $fields, $field_groups ) : array {

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {
				foreach( $field_groups as $group_name=>$group_fields ) {
					foreach( $group_fields as $field ) {

						$safe_name = preg_replace( '/\s/', '', strtolower( $group_name ) );
						$attr_extension = '_' . $safe_name . '_' . $i;

						if( array_key_exists( 'attr' , $field ) ) {
							$field['attr'] .= $attr_extension;
						} else {
							$field['attr'] = $i . $attr_extension;
						}

						if( array_key_exists( 'label' , $field ) ) {
							$field['label'] = sprintf( $field['label'], $i );
						} else {
							$field['label'] = $field['attr'];
						}

						$new_meta = [
							'data-element-type' => $safe_name,
							'data-element-name' => $group_name,
							'data-element-number' => $i,
						];
						if( !array_key_exists( 'meta' , $field ) ) {
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
		 * @param array	 $attributes This is the array of fields of this block.
		 * @param string $content This is the post content.
		 * @param string $shortcode_tag The shortcode tag of this block.
		 *
		 * @return array The data to be passed in the View.
		 */
		public function prepare_data( $fields, $content = '', $shortcode_tag = 'shortcake_' . self::BLOCK_NAME ) : array {

			$field_groups = [];

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				// Group fields based on index number
				$group = [];
				$group_type = false;
				foreach( $fields as $field_name => $field_content ) {
					if( preg_match( '/_' . $i . '$/', $field_name ) ) {
						$field_name_data = explode( '_', $field_name );
						$group[ $field_name_data[0] ] = $field_content;
						$group_type = $field_name_data[1]; // TODO assigning multiple times here, can be more elegant?
					}
				}

				// Extract group field type
				if( $group_type ) {
					$group[ '__group_type__' ] = $group_type;
				}

				if( $group_type == 'post' ) {
					$post = get_posts([
						'include' => $group['post'],
					]);
					$group['post'] = (array) $post[0];
				}

				$field_groups[] = $group;
			}

			// Extract static fields only
			$static_fields = [];
			foreach( $fields as $field_name => $field_content ) {
				if( ! preg_match( '/_\d+$/', $field_name ) ) {
					$static_fields[ $field_name ] = $field_content;
				}
			}

			return [
				// 'fields' => $fields,
				'field_groups' => $field_groups,
				'static_fields' => $static_fields,
			];

		}

		/**
		 * Callback for the shortcake_noindex shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array	 $fields		Array of fields that are to be used in the template.
		 * @param string $content		The content of the post.
		 * @param string $shortcode_tag The shortcode tag (shortcake_blockname).
		 *
		 * @return string The complete html of the block
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			// echo '<pre>' . print_r($data, true) . '</pre>';

			return ob_get_clean();
		}

	}
}
