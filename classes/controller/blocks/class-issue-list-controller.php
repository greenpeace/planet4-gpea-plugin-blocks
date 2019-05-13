<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Issue_List_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Issue_List_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Issue_List_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'issue_list';

		/** @const string DEFAULT_LAYOUT */
		const DEFAULT_LAYOUT = 'default';

		/** @const string ENGAGING_CAMPAIGN_ID_META_KEY */
		const ENGAGING_CAMPAIGN_ID_META_KEY = 'engaging_campaign_ID';

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
					'type'	=> 'textarea',
					'meta'	=> [
						'placeholder' => __( 'Description', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label'		  => __( 'Campaigns', 'planet4-gpnl-blocks' ),
					'attr'	   => 'campaign_ids',
					'type'	   => 'term_select',
					'taxonomy' => 'post_tag',
					'multiple' => 'multiple',
					'meta'	   => [
						'select2_options' => [
							'allowClear'			 => true,
							'placeholder'			 => __( 'Select campaigns', 'planet4-gpnl-blocks' ),
							'closeOnSelect'			 => false,
							'minimumInputLength'	 => 0,
							'multiple'				 => true,
							'maximumSelectionLength' => 20,
							'width'					 => '80%',
						],
					],
				],
				[
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'radio',
					'options' => [
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
				'label'			=> __( 'LATTE | Campaign Section', 'planet4-gpnl-blocks' ),
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

			if( array_key_exists( 'campaign_ids', $attributes ) ) {
				$campaigns = get_terms( array(
					'taxonomy' => 'post_tag',
					'include'  => explode(',', $attributes['campaign_ids']),
				) );
			} else {
				$campaigns = [];
			}

			foreach( $campaigns as $campaign ) {
				$campaign->engaging_ID = get_term_meta( $campaign->term_id, self::ENGAGING_CAMPAIGN_ID_META_KEY, true );
			}
			$attributes['campaigns'] = $campaigns;

			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

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

			wp_enqueue_script(
				'issue_list_engaging_js',
				P4NLBKS_ASSETS_DIR . 'js/issue-list-engaging.js',
				[ 'jquery' ],
				'0.1',
				true
			);

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();

			$this->view->block( self::BLOCK_NAME, $data );
			// echo '<pre>' . var_export($data, true) . '</pre>';

			return ob_get_clean();
		}
	}
}
