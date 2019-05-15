<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Tips_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Tips_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 * @since 0.1.3
	 */
	class Tips_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME   = 'tips';

		/** @const int MAX_REPEATER */
		const MAX_REPEATER = 20;

		/**
		 * Shortcode UI setup for the tasks shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * This example shortcode has many editable attributes, and more complex UI.
		 *
		 * @since 1.0.0
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
					'label' => __( 'Description', 'planet4-gpnl-blocks' ),
					'attr'	=> 'description',
					'type'	=> 'textarea',
					'meta'	=> [
						'placeholder' => __( 'Description', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Label "See more"', 'planet4-gpnl-blocks' ),
					'attr'	=> 'see_more_label',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Label "See more"', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Link "See more"', 'planet4-gpnl-blocks' ),
					'attr'	=> 'see_more_link',
					'type'	=> 'url',
					'meta'	=> [
						'placeholder' => __( 'Link "See more"', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
			];

			// This block will have at most MAX_REPEATER different items

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				$fields[] =
					[
						'label' => sprintf( __('<i>Tip %s tag</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'	=> 'tag_tip_' . $i,
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => sprintf( __( 'Enter tag %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
							'data-element-type' => 'tip',
							'data-element-name' => 'tip',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					  [
						  'label' => sprintf( __('<i>Tip %s description</i>', 'planet4-gpnl-blocks'), $i ),
						  'attr'	=> 'description_tip_' . $i,
						  'type'	=> 'textarea',
						  'meta'	=> [
							  'placeholder' => sprintf( __( 'Enter description %s', 'planet4-gpnl-blocks' ), $i ),
							  'data-plugin' => 'planet4-gpnl-blocks',
							  'data-element-type' => 'tip',
							  'data-element-name' => 'tip',
							  'data-element-number' => $i,
						  ],
					  ];

				$fields[] =
					  [
						  'label' => sprintf( __('<i>Tip %s icon</i>', 'planet4-gpnl-blocks'), $i ),
						  'attr'	=> 'icon_tip_' . $i,
						  'type'	=> 'select',
						  'options' => [
							  [ 'value' => '', 'label' => __( 'Select tip icon') ],
							  [ 'value' => '💦', 'label' => '💦' ],
							  [ 'value' => '🌧', 'label' => '🌧' ],
						  ],
						  'meta'	=> [
							  'placeholder' => sprintf( __( 'Select tip %s icon', 'planet4-gpnl-blocks' ), $i ),
							  'data-plugin' => 'planet4-gpnl-blocks',
							  'data-element-type' => 'tip',
							  'data-element-name' => 'tip',
							  'data-element-number' => $i,
						  ],
					  ];

			}

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Tips', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ) . '" />',
				'attrs'			=> $fields,
				'post_type'		=> P4NLBKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

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

			$data = [
				'fields' => $fields,
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();

			$this->view->block( self::BLOCK_NAME, $data );
			// echo '<pre>' . var_export($fields, true) . '</pre>';

			return ob_get_clean();
		}

	}
}
