<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Metablock_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Metablock_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Metablock_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'metablock';

		/** @const string DEFAULT_LAYOUT */
		const DEFAULT_LAYOUT = 'default';

		/** @const int MAX_REPEATER */
		const MAX_REPEATER = 3;

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
						'data-testdataasd' => 'planet4-gpnl-blocks',
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
			];



			// This block will have at most MAX_REPEATER different items
			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				// Field naming convention:
				// __ separates ID from meta-contextual info
				// anyname__itemtype_itemnumber

				$fields[] =
					[
						'label' => sprintf( __('<i>A Title %s</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'	=> 'mytitle__a_' . $i,
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => sprintf( __( 'Enter title %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
							'data-element-type' => 'a',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						'label' => sprintf( __('<i>A Description %s</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'	=> 'mydescription__a_' . $i,
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => sprintf( __( 'Enter description %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
							'data-element-type' => 'a',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						'label' => sprintf( __('<i>B Title %s</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'	=> 'mytitle__b_' . $i,
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => sprintf( __( 'Enter title %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
							'data-element-type' => 'b',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						'label' => sprintf( __('<i>B Description %s</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'	=> 'mydescription__b_' . $i,
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => sprintf( __( 'Enter description %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
							'data-element-type' => 'b',
							'data-element-number' => $i,
						],
					];

				// $fields[] =
				//		[
				//			'label' => sprintf( __('<i>B Title %s</i>', 'planet4-gpnl-blocks'), $i ),
				//			'attr'	=> 'mytitle_B_' . $i,
				//			'type'	=> 'text',
				//			'meta'	=> [
				//				'placeholder' => sprintf( __( 'Enter title %s', 'planet4-gpnl-blocks' ), $i ),
				//				'data-plugin' => 'planet4-gpnl-blocks',
				//				'data-element-type' => 'B',
				//				'data-element-number' => $i,
				//			],
				//		];

				// $fields[] =
				//		[
				//			'label' => sprintf( __('<i>B Description %s</i>', 'planet4-gpnl-blocks'), $i ),
				//			'attr'	=> 'mydescription_B_' . $i,
				//			'type'	=> 'textarea',
				//			'meta'	=> [
				//				'placeholder' => sprintf( __( 'Enter description %s', 'planet4-gpnl-blocks' ), $i ),
				//				'data-plugin' => 'planet4-gpnl-blocks',
				//				'data-element-type' => 'B',
				//				'data-element-number' => $i,
				//			],
				//		];

			}

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Metablock', 'planet4-gpnl-blocks' ),
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

			// $categories = get_categories( array(
			//		'include' => explode(',', $attributes['issue_ids']),
			// ) );
			// $attributes['categories'] = $categories;

			// // TODO remove this magic constant 'issues'
			// $issues_obj = get_category_by_slug( 'issues' );
			// $issues_url = get_category_link( $issues_obj->term_id );
			// $attributes['issues_url'] = $issues_url;

			// $attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

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
