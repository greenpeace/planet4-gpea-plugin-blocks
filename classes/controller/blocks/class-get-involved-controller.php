<?php
/**
 * Get Involved Module (Homepage B Version 4th Screen, Top Part)
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Get_Involved_Controller' ) ) {
	/**
	 * Class Get_Involved_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Get_Involved_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'get_involved';

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
						'placeholder' => __( 'Section Title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'       => __( 'Project page', 'planet4-gpea-blocks-backend' ),
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
					'label' => __( 'Project category', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s category name.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'category',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Project category', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Project title', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s title.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'post_title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Project title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s first paragraph.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'paragraph',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Button label', 'planet4-gpea-blocks-backend' ),
					'description' => sprintf(__( 'Leave empty to use default: "%s".', 'planet4-gpea-blocks-backend' ), __( 'Act Now', 'planet4-gpea-blocks' )),
					'attr'  => 'button_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Button label', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Button link', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s permalink.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'button_url',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Button link', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Image', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use the selected page\'s featured image.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Get Involved', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/get-involved-block.jpg' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4EABKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

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

			if ( isset( $attributes[ 'img' ] ) && @strlen( $attributes[ 'img' ] ) ) {
				$attributes[ 'img' ] = wp_get_attachment_url( $attributes[ 'img' ] );
			}

			if ( isset( $attributes[ 'paragraph' ] ) && @strlen( $attributes[ 'paragraph' ] ) ) {
				$attributes[ 'paragraph' ] = '<p>' . $attributes[ 'paragraph' ] . '</p>';
			}

			$tanslate = [
				'default_button_label' => __( 'Act Now', 'planet4-gpea-blocks' ),
				'category_label' => __( 'Spotlight of the mounth:', 'planet4-gpea-blocks' ),
			];

			$default = [];

			$post = NULL;
			if ( isset( $attributes[ 'post_id' ] ) && @strlen( $attributes[ 'post_id' ] ) ) {
				$post_id = $attributes[ 'post_id' ];
				$post = get_post( $post_id );
				if( $post ) {
					$default[ 'post_title' ] = $post->post_title;
					$default[ 'button_url' ] = get_permalink( $post_id );
					$default[ 'img' ] = get_the_post_thumbnail_url( $post_id, 'post-thumbnails' );
					preg_match_all( 
						'/' . get_shortcode_regex() . '/',
						$post->post_content,
						$shortcodes,
						PREG_SET_ORDER
					);
					foreach( $shortcodes as $shortcode_item ) {
						$shortcode_attr = shortcode_parse_atts( $shortcode_item[ 0 ] );
						if( isset( $shortcode_attr[ 'layout' ], $shortcode_attr[ 'paragraph' ] ) && 'plain_light' === $shortcode_attr[ 'layout' ] ) {
							$default[ 'paragraph' ] = wpautop( $shortcode_attr[ 'paragraph' ] );
						}
					}
				}
			}

			if ( $post && class_exists( 'P4CT_Site' ) ) {
				$gpea_extra = new \P4CT_Site();
				$main_issue = $gpea_extra->gpea_get_main_issue( $post->ID );
				if( $main_issue ) {
					$attributes[ 'category_slug' ] = $main_issue->slug;
					$default[ 'category' ] = $main_issue->name;
				}
			}

			return [
				'static_fields' => $attributes,
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
