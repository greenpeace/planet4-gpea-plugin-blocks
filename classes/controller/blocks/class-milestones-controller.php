<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Milestones_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Milestones_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 * @since 0.1.3
	 */
	class Milestones_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME   = 'milestones';

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
					'label' => __( 'Map image', 'planet4-gpnl-blocks' ),
					'attr'		  => 'map_img',
					'type'		  => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'	  => __( 'Map image', 'planet4-gpnl-blocks' ),
					'frameTitle'  => __( 'Map image', 'planet4-gpnl-blocks' ),
				],
			];

			// This block will have at most MAX_REPEATER different items

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				$fields[] =
					[
						'label' => sprintf( __('<i>Milestone %s date</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'	=> 'date_milestone_' . $i,
						'type'	=> 'text',
						'meta'	=> [
							'placeholder' => sprintf( __( 'Enter date %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						'label' => sprintf( __('<i>Milestone %s description</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'	=> 'description_milestone_' . $i,
						'type'	=> 'textarea',
						'meta'	=> [
							'placeholder' => sprintf( __( 'Enter description %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						'label' => sprintf( __('<i>Tick if milestone %s was achieved</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'	=> 'achieved_milestone_' . $i,
						'type'	=> 'checkbox',
						'meta'	=> [
							'placeholder' => sprintf( __( 'Enter description %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];
			}

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Milestones', 'planet4-gpnl-blocks' ),
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

			if( isset( $attributes[ 'map_img' ] ) ) {
				$attributes[ 'map_img' ] = wp_get_attachment_url( $attributes[ 'map_img' ] );
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
			// echo '<pre>' . var_export($fields, true) . '</pre>';

			return ob_get_clean();
		}

	}
}
