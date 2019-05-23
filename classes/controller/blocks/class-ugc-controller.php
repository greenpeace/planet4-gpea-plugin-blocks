<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'UGC_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class UGC_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class UGC_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'ugc';

		/** @const string DEFAULT_LAYOUT */
		const DEFAULT_LAYOUT = 'default';

		/** @const string NONCE_STRING */
        const NONCE_STRING = 'ugc-frontend-post';
		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Title label', 'planet4-gpnl-blocks' ),
					'attr'	=> 'title_label',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title label', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Content label', 'planet4-gpnl-blocks' ),
					'attr'	=> 'content_label',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Content label', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label'		  => 'Select the layout',
					'description' => 'Select the layout',
					'attr'		  => 'layout',
					'type'		  => 'radio',
					'options'	  => [
						[
							'value' => 1,
							'label' => __( 'Layout A', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 2,
							'label' => __( 'Layout B', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 3,
							'label' => __( 'Layout C', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | UGC Form', 'planet4-gpnl-blocks' ),
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

            $attributes['submit_msg'] = self::save_if_submitted();

			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;
			$attributes['wp_nonce'] = wp_nonce_field( self::NONCE_STRING );

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
			// echo '<pre>' . print_r($data, true) . '</pre>';

			return ob_get_clean();
		}

		private function save_if_submitted() {

			if ( !isset( $_POST['ugc_title'] ) ) {
				return;
			}

			if( !wp_verify_nonce( $_POST['_wpnonce'], self::NONCE_STRING ) ) {
				return 'Did not save because your form seemed to be invalid. Sorry';
			}

			// TODO validate form here
			if (strlen( $_POST['ugc_title'] ) < 3 ) {
				return 'The title must be 3 characters or more';
			}
			if (strlen( $_POST['ugc_content'] ) < 10 ) {
				return 'The content must be 10 characters or more';
			}

			$post = array(
				'post_title'	=> htmlspecialchars($_POST['ugc_title']), // TODO check if sanitizing needed here
				'post_content'	=> htmlspecialchars($_POST['ugc_content']), // TODO check if sanitizing needed here
				'post_status'	=> 'draft',
				'post_type'		=> 'post'
			);
			wp_insert_post( $post );
            
			return 'Post saved.';

		}
	}
}
