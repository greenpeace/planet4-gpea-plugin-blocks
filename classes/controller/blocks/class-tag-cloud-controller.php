<?php
/**
 * Tag cloud class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Tag_Cloud_Controller' ) ) {
	/**
	 * Class Tag_Cloud_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Tag_Cloud_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'tag_cloud';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'default';

		/**
		 * Engaging campaing ID meta value key.
		 *
		 * @const string ENGAGING_CAMPAIGN_ID_META_KEY
		 */
		const ENGAGING_CAMPAIGN_ID_META_KEY = 'engaging_campaign_ID';		

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
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Tag Cloud', 'planet4-gpea-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/issues_list.png' ) . '" />',
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

			$campaigns = get_terms(
				array(
					'taxonomy' => array( 'post_tag', 'category' ),
				)
			);

			foreach ( $campaigns as $campaign ) {
				$campaign->engaging_id = get_term_meta( $campaign->term_id, self::ENGAGING_CAMPAIGN_ID_META_KEY, true );
				if ( $campaign->engaging_id ) $attributes['campaigns'] = $campaigns;
			}

			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

			/* engaging form needed info*/
			$gpea_options = get_option( 'gpea_options' );
			if ( isset( $gpea_options['gpea_tag_cloud_newsletter_form'] ) && isset( $gpea_options['gpea_default_en_subscription_page'] ) ) {
				$attributes['form'] = '[p4en_form id="' . $gpea_options['gpea_tag_cloud_newsletter_form'] . '" en_form_style="" /]';
				$attributes['gpea_default_en_subscription_page'] = $gpea_options['gpea_default_en_subscription_page'];
				/* thanks messages */
				$attributes['gpea_subscription_page_thankyou_title'] = isset( $gpea_options['gpea_subscription_page_thankyou_title'] ) ? $gpea_options['gpea_subscription_page_thankyou_title'] : '';
				$attributes['gpea_subscription_page_thankyou_subtitle'] = isset( $gpea_options['gpea_subscription_page_thankyou_subtitle'] ) ? $gpea_options['gpea_subscription_page_thankyou_subtitle'] : '';
			}
			// else {
			// 	return false;
			// }

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

			// TODO move this JS to theme assets.
			wp_enqueue_script(
				'issue_list_engaging_js',
				P4EABKS_ASSETS_DIR . 'js/issue-list-engaging.js',
				[ 'jquery' ],
				'0.1',
				true
			);

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			return ob_get_clean();
		}
	}
}
