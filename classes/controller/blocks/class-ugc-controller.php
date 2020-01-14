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
					'label' => __( 'Thank you message', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'thankyou_message',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Thanks', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Add attachment field?', 'planet4-gpea-blocks-backend' ),
					'desc' => __( 'If checked the attach file field will be shown and email will be sent', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'show_attachment_field',
					'type'  => 'checkbox',
					'meta'  => [
						'placeholder' => __( 'Add attachment field?', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | UGC Form', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/ugc-block.jpg' ) . '" />',
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
			$lexicon['upload_cover'] = __( 'Upload a cover image', 'planet4-gpea-blocks' );
			$lexicon['cover_consent'] = __( 'Cover image consent', 'planet4-gpea-blocks' );
			$lexicon['input_file_chosen'] = __( 'No file chosen', 'planet4-gpea-blocks' );
			$lexicon['choose_file_button'] = __( 'Choose a file', 'planet4-gpea-blocks' );

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
					'message' => __( 'Did not save because your form seemed to be invalid. Sorry', 'planet4-gpea-blocks-backend' ),
				);
			}

			// TODO validate form here.
			if ( strlen( $_POST['ugc_title'] ) < 3 ) {
				return array(
					'result'  => 'error',
					'message' => __( 'The title must be 3 characters or more', 'planet4-gpea-blocks-backend' ),
				);
			}
			if ( strlen( $_POST['ugc_content'] ) < 10 ) {
				return array(
					'result'  => 'error',
					'message' => __( 'The content must be 10 characters or more', 'planet4-gpea-blocks-backend' ),
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
			$post_inserted = wp_insert_post( $post );

			/* send notification email, in case it's present attach also the file */

			// get general options (for the recipient email)
			$gpea_options = get_option( 'gpea_options' );

			// if recipient mail is set go on
			// $sent store the success of mail sent, $file_error info if file too big or wrong format, $attachment if the attachemt is present or not
			$sent = false;
			$file_error = false;
			$attachment = false;

			if ( isset( $gpea_options['gpea_ugc_recipient_email'] ) && $gpea_options['gpea_ugc_recipient_email'] ) {

				// send mail
				$data_mail = array(
					'title'           => htmlspecialchars( sanitize_text_field( $_POST['ugc_title'] ) ), // TODO check if sanitizing needed here.
					'message'         => htmlspecialchars( sanitize_textarea_field( $_POST['ugc_content'] ) ), // TODO check if sanitizing needed here.
					'recipient_email' => filter_var( $gpea_options['gpea_ugc_recipient_email'], FILTER_SANITIZE_EMAIL ),
					'author'          => filter_var( $_POST[ 'user_name' ], FILTER_SANITIZE_STRING ),
					'author_email'    => filter_var( $_POST[ 'user_email' ], FILTER_SANITIZE_EMAIL ),
				);

				// $attachment 				
				if ( isset( $_FILES['ugc_cover'] ) && $_FILES['ugc_cover']['tmp_name'] ) {
					// mark file error true, if everything is fine will change
					$file_error = true;
					// allowed images format
					$allowed_format = array( 'image/png', 'image/gif', 'image/jpg', 'image/jpeg' );
					if ( filesize( $_FILES['ugc_cover']['tmp_name'] ) > 2000000 ) {
						// originally we sent email with info about problem with attachemtn, now no mail is sent if there are error with attach
						$data_mail['message'] .= '<br /> File was too big so it has not been sent';
					} elseif ( in_array( mime_content_type( $_FILES['ugc_cover']['tmp_name'] ), $allowed_format ) ) {
						// try to set a nicer name, if everything is fine prepare attachemnt info
						if ( move_uploaded_file( $_FILES['ugc_cover']['tmp_name'], $_FILES['ugc_cover']['tmp_name'] . '_' . $_FILES['ugc_cover']['name']) ) {
							// if everything is fine mark $file_error false and add attachment variable
							$file_error = false;
							$attachment = array( $_FILES['ugc_cover']['tmp_name'] . '_' . $_FILES['ugc_cover']['name'], 'ugc_cover.jpg' );
						}
					} 
					else {
						// originally we sent email with info about problem with attachemtn, now no mail is sent if there are error with attach
						$data_mail['message'] .= '<br /> Not allowed file type was added so it has not been sent';
					}
				}

				if ( $data_mail['author'] && $data_mail['recipient_email'] && $data_mail['message'] && ( ! $file_error ) ) {

					$message = 'Title ' . $data_mail['title'] . ' <br /> <br /> Author ' . $data_mail['author'] . '<br /> Author email ' . $data_mail['author_email'] . '<br /> Message ' . $data_mail['message'];
					$to      = $data_mail['recipient_email'];
					$subject = 'UGC from gp website';
					// $message = $data['message'];
					// $headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers = array('Content-type: text/html; charset=UTF-8');
					// $headers .= 'From: ' . $data_mail['author'] . '<no-reply@lattecreative.com>\n';
					// add_action('phpmailer_init', 'prefix_phpmailer_init');

					$sent = wp_mail( $to, $subject, $message, $headers, $attachment );

					// remove file from tmp
					if ( $attachment ) {
						@unlink($_FILES['ugc_cover']['tmp_name'] . '_' . $_FILES['ugc_cover']['name']);
					}


					// remove_action('phpmailer_init', 'prefix_phpmailer_init');
					// $this->safe_echo( __( 'Message sent.', 'gpea_theme' ) );
					// return;
				}
			} else {
				$sent = true;
			}

			$thanks_message = sanitize_text_field( $_POST['thankyou_message'] );
			$error_message = __( 'Your story could not be sent', 'planet4-gpea-blocks' );

			if ( $file_error ) {
				// if there were problem with file, add more info about the error
				$error_message .= '<br />' . __( 'Attachment problem', 'planet4-gpea-blocks' );
			}

			if ( $post_inserted && $sent ) {
				return array(
					'result'  => 'success',
					'message' => $thanks_message,
				);
			} else {
				return array(
					'result'  => 'error',
					'message' => $error_message,
				);
			}

			return;

		}
	}
}
