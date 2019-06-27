<?php
/**
 * UGC block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'UGC_Controller' ) ) {
	/**
	 * Class UGC_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class UGC_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'ugc';		

		/**
		 * The nonce string.
		 *
		 * @const string NONCE_STRING
		 */
		const NONCE_STRING = 'ugc-frontend-post';

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [				
				[
					'label' => __( 'Thank you message', 'planet4-gpea-blocks' ),
					'attr'  => 'thankyou_message',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Thanks', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | UGC Form', 'planet4-gpea-blocks' ),
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

			$attributes['submit_result'] = self::save_if_submitted();
			$attributes['wp_nonce'] = wp_nonce_field( self::NONCE_STRING );


			// lexicon entries
			$lexicon['submit_story'] = __( 'Submit your story', 'planet4-gpea-blocks' );
			$lexicon['your_name'] = __( 'Your name', 'planet4-gpea-blocks' );
			$lexicon['your_email_address'] = __( 'Your email address', 'planet4-gpea-blocks' );
			$lexicon['story_subject'] = __( 'Subject of your story', 'planet4-gpea-blocks' );
			$lexicon['story_text'] = __( 'Your story', 'planet4-gpea-blocks' );
			$lexicon['story_publish'] = __( 'Publish your story', 'planet4-gpea-blocks' );

			return [
				'fields' => $attributes,
				'lexicon' => $lexicon,
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

		/**
		 * Saves user generated post as draft if request is POST
		 */
		private function save_if_submitted() {

			if ( ! isset( $_POST['ugc_title'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_POST['_wpnonce'], self::NONCE_STRING ) ) {
				return array(
					'result'  => 'error',
					'message' => __( 'Did not save because your form seemed to be invalid. Sorry', 'planet4-gpea-blocks' ),
				);
			}

			// TODO validate form here.
			if ( strlen( $_POST['ugc_title'] ) < 3 ) {
				return array(
					'result'  => 'error',
					'message' => __( 'The title must be 3 characters or more', 'planet4-gpea-blocks' ),
				);
			}
			if ( strlen( $_POST['ugc_content'] ) < 10 ) {
				return array(
					'result'  => 'error',
					'message' => __( 'The content must be 10 characters or more', 'planet4-gpea-blocks' ),
				);
			}

			$post = array(
				'post_title'    => htmlspecialchars( sanitize_text_field( $_POST['ugc_title'] ) ), // TODO check if sanitizing needed here.
				'post_content'  => htmlspecialchars( sanitize_textarea_field( $_POST['ugc_content'] ) ), // TODO check if sanitizing needed here.
				'post_status'   => 'draft',
				'post_type'     => 'user_story',
				'meta_input'   => array(
					'p4_author_override' => sanitize_text_field( $_POST['user_name'] ),
					'p4_author_email_address' => sanitize_text_field( $_POST['user_email'] ),
				),
			);
			wp_insert_post( $post );

			$thanks_message = sanitize_text_field( $_POST['thankyou_message'] );

			return array(
				'result'  => 'success',
				'message' => $thanks_message,
			);

		}
	}
}
