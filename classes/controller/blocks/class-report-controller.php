<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Report_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Report_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Report_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'report';

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
					],
				],
				[
					'label' => __( 'Subtitle', 'planet4-gpnl-blocks' ),
					'attr'	=> 'subtitle',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Subtitle', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Description', 'planet4-gpnl-blocks' ),
					'attr'	=> 'description',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Description', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Image', 'planet4-gpnl-blocks' ),
					'attr'		  => 'img',
					'type'		  => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'	  => __( 'Select image', 'planet4-gpnl-blocks' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpnl-blocks' ),
				],
				[
					'label' => __( 'CTA text', 'planet4-gpnl-blocks' ),
					'attr'	=> 'cta_text',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'CTA link', 'planet4-gpnl-blocks' ),
					'attr'	=> 'cta_url',
					'type'	=> 'url',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'CTA 2 text', 'planet4-gpnl-blocks' ),
					'attr'	=> 'cta_2_text',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'CTA 2 link', 'planet4-gpnl-blocks' ),
					'attr'	=> 'cta_2_url',
					'type'	=> 'url',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Report', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ) . '" />',
				'attrs'			=> $fields,
				'post_type'		=> P4NLBKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

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
		public function prepare_data( $attributes, $content = '', $shortcode_tag = 'shortcake_' . self::BLOCK_NAME ) : array {

			if( isset( $attributes[ 'img' ] ) ) {
				$attributes[ 'img' ] = wp_get_attachment_url( $attributes[ 'img' ] );
			}

			return [
				'fields' => $attributes,
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
			// echo '<pre>' . var_export($data, true) . '</pre>';

			return ob_get_clean();
		}


	}
}
