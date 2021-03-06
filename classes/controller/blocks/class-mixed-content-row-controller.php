<?php
/**
 * Mixed content row block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Mixed_Content_Row_Controller' ) ) {
	/**
	 * Class Mixed_Content_Row_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Mixed_Content_Row_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'mixed_content_row';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'default';

		/**
		 * The maximum number of sum-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 6;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'radio',
					'options' => [
						[
							'value' => 'light_green_grey',
							'label' => __( 'Light Theme', 'planet4-gpea-blocks-backend' ),
							'desc'  => 'Green title, grey text.',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'dark_green_white',
							'label' => __( 'Dark Theme', 'planet4-gpea-blocks-backend' ),
							'desc'  => 'Green title, white text.',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'grey_grey',
							'label' => __( 'Default, takes color from section or if not available shows grey title and grey text', 'planet4-gpea-blocks-backend' ),
							'desc'  => 'Grey title, grey text.',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
					],
				],
				[
					'label' => __( 'Select text-align', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'align',
					'type'  => 'radio',
					'value' => 'left',
					'options' => [
						[
							'value' => 'left',
							'label' => __( 'Left', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => 'center',
							'label' => __( 'Center', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => 'right',
							'label' => __( 'Right', 'planet4-gpea-blocks-backend' ),
						],
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
					'label' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
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
					'label' => __( 'Optional PC background image', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'image',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
				[
					'label' => __( 'Optional mobile background image. (Leave blank to use the same image as background image)', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'bg_img_mobile',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
			];

			$query = new \WP_Query(
				[
					'post_type' => array( 'post' ),
					'posts_per_page' => -1
				]
			);
			$posts = array_map(
				function( $post ) {
						return [
							'value' => strval( $post->ID ),
							'label' => esc_html( $post->post_title ),
						];
				}, $query->posts
			);
			array_unshift(
				$posts, [
					'value' => '',
					'label' => __( 'Please select a post', 'planet4-gpea-blocks-backend' ),
				]
			);

			wp_reset_postdata();

			$query = new \WP_Query(
				[
					'post_type' => array( 'tips' ),
					'orderby'   => 'post_title',
					'order'     => 'asc',
				]
			);
			$tips = array_map(
				function( $tip ) {
						return [
							'value' => strval( $tip->ID ),
							'label' => esc_html( $tip->post_title ),
						];
				}, $query->posts
			);
			array_unshift(
				$tips, [
					'value' => '',
					'label' => __( 'Please select a tip', 'planet4-gpea-blocks-backend' ),
				]
			);

			wp_reset_postdata();

			$field_groups = [

				'Title and text' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s title</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'title',
						'type'  => 'text',
						'meta'  => [
							'placeholder' => __( 'Enter title', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s text</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'textblock',
						'type'  => 'textarea',
						'meta'  => [
							'placeholder' => __( 'Enter text', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
				],

				'Two line title and text' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s title line 1</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'titlea',
						'type'  => 'text',
						'meta'  => [
							'placeholder' => __( 'Enter line 1', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s title</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'titleb',
						'type'  => 'text',
						'meta'  => [
							'placeholder' => __( 'Enter title', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s text</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'textblock',
						'type'  => 'textarea',
						'meta'  => [
							'placeholder' => __( 'Enter text', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
				],

				'Text only' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s text</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'textblock',
						'type'  => 'textarea',
						'meta'  => [
							'placeholder' => __( 'Enter text', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
				],

				'Tip' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s tip text</i>', 'planet4-gpea-blocks-backend' ),
						'attr'     => 'tip',
						'type'     => 'select',
						'options'  => $tips,
						'meta'     => [
							'placeholder' => __( 'Select tip', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
							'data-input-transform' => 'js-select2-enable',
						],
					],
				],

				'Post' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s post</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'post',
						'type'  => 'select',
						'options' => $posts,
						'meta'  => [
							'placeholder'          => __( 'Select post', 'planet4-gpea-blocks-backend' ),
							'data-plugin'          => 'planet4-gpea-blocks',
							'data-input-transform' => 'js-select2-enable',
						],
					],
				],

				'Animate Number' => [
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s animate number</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'number',
						'type'  => 'text',
						'meta'  => [
							'placeholder' => __( 'Enter animate number', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => __( '<i>Block %s description</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'description',
						'type'  => 'textarea',
						'meta'  => [
							'placeholder' => __( 'Enter description', 'planet4-gpea-blocks-backend' ),
							'data-plugin' => 'planet4-gpea-blocks',
						],
					],
				],

			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Mixed Content Row', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/mixed-content-row-block.jpg' ) . '" />',
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
				} else {
					continue;
				}

				if ( 'post' === $group_type && preg_match( '/^\d+$/', $group['post'] ) ) {
					$query = new \WP_Query(
						[
							'post__in' => explode( ',' , $group['post'] ),
						]
					);
					if ( $query->posts ) {
						$post = $query->posts[0];

						$post->link = get_permalink( $post->ID );
						// $post->post_date = date( 'Y - m - d' , strtotime( $post->post_date ) );
						$post->post_date = get_the_date( '', $post->ID );

						if ( has_post_thumbnail( $post->ID ) ) {
							$img_id = get_post_thumbnail_id( $post->ID );
							$img_data = wp_get_attachment_image_src( $img_id , 'medium' );
							$post->img_url = $img_data[0];
							$post->tags = get_the_tags( $post->ID );
						}
						$planet4_options = get_option( 'planet4_options' );
						$main_issues_category_id = isset( $planet4_options['issues_parent_category'] ) ? $planet4_options['issues_parent_category'] : false;
						if ( ! $main_issues_category_id ) {
							$main_issues_category = get_term_by( 'slug', 'issues', 'category' );
							if ( $main_issues_category ) $main_issues_category_id = $main_issues_category->term_id;
						}

						if ( $main_issues_category_id ) {
							$categories = get_the_category( $post->ID );
							if ( ! empty( $categories ) ) {
								$categories = array_filter( $categories, function( $cat ) use ( $main_issues_category_id ) {
									return $cat->category_parent === intval( $main_issues_category_id );
								});
								if ( ! empty( $categories ) ) {
									$first_category = array_values( $categories )[0];
									$post->main_issue = $first_category->name;
									$post->main_issue_slug = $first_category->slug;
								}
							}
						}
						$group['post'] = $post;
					} else {
						$group['post'] = false;
					}

					wp_reset_postdata();
				}

				if ( 'tip' === $group_type && preg_match( '/^\d+$/', $group['tip'] ) ) {
					$query = new \WP_Query(
						[
							'post_type' => array( 'tips' ),
							'post__in' => explode( ',' , $group['tip'] ),
						]
					);
					if ( $query->posts ) {
						$post = $query->posts[0];
						$tip_icon = get_post_meta( $post->ID, 'p4-gpea_tip_icon', true );
						$post->img_url = $tip_icon ?? '';
						$frequency = get_post_meta( $post->ID, 'p4-gpea_tip_frequency', true );
						$post->frequency = $frequency ?? '';
						$formatted_posts[] = $post;
						$group['post'] = $post;
					} else {
						$group['post'] = false;
					}

					wp_reset_postdata();
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

			if ( isset( $static_fields['image'] ) ) {
				$static_fields['image'] = wp_get_attachment_url( $static_fields['image'] );
			}

			if ( isset( $static_fields['bg_img_mobile'] ) ) {
				$static_fields['bg_img_mobile'] = wp_get_attachment_url( $static_fields['bg_img_mobile'] );
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
