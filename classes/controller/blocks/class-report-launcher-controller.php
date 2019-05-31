<?php
/**
 * Mixed content row block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Report_Launcher_Controller' ) ) {
	/**
	 * Class Report_Launcher_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Report_Launcher_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'report_launcher';

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
					'label' => __( 'Caption', 'planet4-gpea-blocks' ),
					'attr'  => 'caption',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Caption', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Image', 'planet4-gpea-blocks' ),
					'attr'        => 'img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks' ),
				],
				[
					'label' => __( 'Button label', 'planet4-gpea-blocks' ),
					'attr'  => 'btn_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Button label', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Button link', 'planet4-gpea-blocks' ),
					'attr'  => 'btn_link',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Button link', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Label "See All"', 'planet4-gpea-blocks' ),
					'attr'  => 'see_all_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Label "See All"', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Link "See All"', 'planet4-gpea-blocks' ),
					'attr'  => 'see_all_link',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Link "See All"', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'Report Launcher', 'planet4-gpea-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ) . '" />',
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
		public function prepare_data( $attributes, $content = '', $shortcode_tag = 'shortcake_' . self::BLOCK_NAME ) : array {

			if ( isset( $attributes['img'] ) ) {
				$attributes['img'] = wp_get_attachment_url( $attributes['img'] );
			}

			return [
				'fields' => $attributes,
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
