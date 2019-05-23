<?php

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Mixed_Content_Slideshow_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Mixed_Content_Slideshow_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Mixed_Content_Slideshow_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'mixed_content_slideshow';

		/** @const string DEFAULT_LAYOUT */
		const DEFAULT_LAYOUT = 'light';

		/** @const int MAX_REPEATER */
		const MAX_REPEATER = 10;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Title', 'planet4-gpea-blocks' ),
					'attr'	=> 'title',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
						'data-testdataasd' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'CTA text', 'planet4-gpea-blocks' ),
					'attr'	=> 'cta_text',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'CTA link', 'planet4-gpea-blocks' ),
					'attr'	=> 'cta_url',
					'type'	=> 'url',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'CTA 2 description', 'planet4-gpea-blocks' ),
					'attr'	=> 'cta_2_desc',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'CTA 2 text', 'planet4-gpea-blocks' ),
					'attr'	=> 'cta_2_text',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'CTA 2 link', 'planet4-gpea-blocks' ),
					'attr'	=> 'cta_2_url',
					'type'	=> 'url',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'radio',
					'options' => [
						[
							'value' => 'light',
							'label' => __( 'Light', 'planet4-gpea-blocks' ),
							'desc'	=> 'Light',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'dark',
							'label' => __( 'Dark', 'planet4-gpea-blocks' ),
							'desc'	=> 'Dark',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'planet',
							'label' => __( 'Planet', 'planet4-gpea-blocks' ),
							'desc'	=> 'Planet',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
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
				'label' => __( 'Please select a post', 'planet4-gpea-blocks' ),
			]);

			$field_groups = [

				'Split text and image' => [
					[
						'label' => __('<strong>%s</strong> <i>title</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'title',
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => __( 'Enter title', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label' => __('<strong>%s</strong> <i>subtitle</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'subtitle',
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => __( 'Enter subtitle', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label' => __('<strong>%s</strong> <i>text</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'textblock',
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => __( 'Enter text', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label'		  => __( '<strong>%s</strong> <i>background image</i>', 'planet4-gpea-blocks' ),
						'attr'		  => 'img',
						'type'		  => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'	  => __( 'Select image', 'planet4-gpea-blocks' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks' ),
					],
				],

				'Big image and text' => [
					[
						'label' => __('<strong>%s</strong> <i>title</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'title',
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => __( 'Enter title', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label' => __('<strong>%s</strong> <i>subtitle</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'subtitle',
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => __( 'Enter subtitle', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label' => __('<strong>%s</strong> <i>text</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'textblock',
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => __( 'Enter text', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label'		  => __( '<strong>%s</strong> <i>background image</i>', 'planet4-gpea-blocks' ),
						'attr'		  => 'img',
						'type'		  => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'	  => __( 'Select image', 'planet4-gpea-blocks' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks' ),
					],
				],

				'Testimonial' => [
					[
						'label' => __('<strong>%s</strong> <i>text</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'textblock',
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => __( 'Enter text', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label' => __('<strong>%s</strong> <i>name</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'name',
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => __( 'Enter name', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label' => __('<strong>%s</strong> <i>position</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'position',
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => __( 'Enter position', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						'label'		  => __( '<strong>%s</strong> <i>icon</i>', 'planet4-gpea-blocks' ),
						'attr'		  => 'img',
						'type'		  => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'	  => __( 'Select image', 'planet4-gpea-blocks' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks' ),
					],
				],

				'Post' => [
					[
						'label' => __('<strong>%s</strong> <i>post</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'post',
						'type'	=> 'select',
						'options' => $posts,
						'meta'	=> [
							'placeholder' => __( 'Select post', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
				],

				'Petition' => [
					// TODO
					[
						'label' => __('<strong>%s</strong> <i>post</i>', 'planet4-gpea-blocks'),
						'attr'	=> 'post',
						'type'	=> 'select',
						'options' => $posts,
						'meta'	=> [
							'placeholder' => __( 'Select post', 'planet4-gpea-blocks' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
				],

			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Mixed Content Slideshow', 'planet4-gpea-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ) . '" />',
				'attrs'			=> $fields,
				'post_type'		=> P4EABKS_ALLOWED_PAGETYPE,
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
						if( $field_name_data[0] == 'img' ) {
							$field_content = wp_get_attachment_url( $field_content );
						}
						$group[ $field_name_data[0] ] = $field_content;
						$group_type = $field_name_data[1]; // TODO assigning multiple times here, can be more elegant?
					}
				}

				// Extract group field type
				if( $group_type ) {
					$group[ '__group_type__' ] = $group_type;
				}

				if( $group_type == 'post' and preg_match( '/^\d+$/', $group['post'] ) ) {
					$post = get_posts([
						'include' => $group['post'],
					]);
					if( is_array( $post ) and sizeof( $post ) ) {
						$post = (array) $post[0];
						if ( has_post_thumbnail( $post['ID'] ) ) {
							$img_id = get_post_thumbnail_id( $post['ID'] );
							$img_data = wp_get_attachment_image_src( $img_id , 'medium' );
							$post['img_url'] = $img_data[0];
							$post['tags'] = get_the_tags( $post['ID'] );
						}
						$group['post'] = $post;
					} else {
						$group['post'] = false;
					}
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
