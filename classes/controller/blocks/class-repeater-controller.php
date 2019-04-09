<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Repeater_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Repeater_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 * @since 0.1.3
	 */
	class Repeater_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME   = 'repeater';

		/** @const int MAX_REPEATER */
        const MAX_REPEATER = 25;

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
					'label' => __( 'Repeater block title', 'planet4-gpnl-blocks' ),
					'attr'  => 'repeater_title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Enter block title', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
				[
					'label' => __( 'Repeater block description', 'planet4-gpnl-blocks' ),
					'attr'  => 'repeater_description',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Enter block description', 'planet4-gpnl-blocks' ),
						'data-plugin' => 'planet4-gpnl-blocks',
					],
				],
			];

			// This block will have at most MAX_REPEATER different items

            for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				$fields[] =
					[
						'label' => sprintf( __('<i>Title %s</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'  => 'title_' . $i,
						'type'  => 'text',
						'meta'  => [
							'placeholder' => sprintf( __( 'Enter title %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					];

				$fields[] =
					[
						'label' => sprintf( __('<i>Description %s</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'  => 'description_' . $i,
						'type'  => 'textarea',
						'meta'  => [
							'placeholder' => sprintf( __( 'Enter description %s', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					];

				$fields[] =
					[
						'label'       => sprintf( __('<i>Image %s.</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'        => 'attachment_' . $i,
						'type'        => 'attachment',
						'libraryType' => [ 'image' ],
						'addButton'   => __( 'Select Image', 'planet4-gpnl-blocks' ),
						'frameTitle'  => __( 'Select Image', 'planet4-gpnl-blocks' ),
					];

				$fields[] =
					[
						'label' => sprintf( __('<i>Button link %s</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'  => 'link_' . $i,
						'type'  => 'url',
						'meta'  => [
							'placeholder' => sprintf( __( 'Enter button %s link', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					];

				$fields[] =
					[
						'label' => sprintf( __('<i>Button text %s</i>', 'planet4-gpnl-blocks'), $i ),
						'attr'  => 'cta_text_' . $i,
						'type'  => 'text',
						'meta'  => [
							'placeholder' => sprintf( __( 'Enter button %s text', 'planet4-gpnl-blocks' ), $i ),
							'data-plugin' => 'planet4-gpnl-blocks',
						],
					];
			}

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'LATTE | Repeater', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
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

			$attributes_temp = [
				'repeater_title'       => $attributes['repeater_title'] ?? '',
				'repeater_description' => $attributes['repeater_description'] ?? '',
			];

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {
				$item_atts     = [
					"title_$i"       => $attributes[ "title_$i" ] ?? '',
					"description_$i" => $attributes[ "description_$i" ] ?? '',
					"attachment_$i"  => $attributes[ "attachment_$i" ] ?? '',
					"cta_text_$i"    => $attributes[ "cta_text_$i" ] ?? '',
					"link_$i"        => $attributes[ "link_$i" ] ?? '',
				];
				$attributes_temp = array_merge( $attributes_temp, $item_atts );
			}
			$attributes = shortcode_atts( $attributes_temp, $attributes, $shortcode_tag );

			$block_data = [
				'fields'              => $attributes,
				'available_languages' => P4NLBKS_LANGUAGES,
			];
			return $block_data;
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

            // TODO should really be calling something like this here...
			// $fields = shortcode_atts(
			// 	array(
			// 		'repeater_title'  => '',
			// 		'repeater_description' => '',
			// 	),
			// 	$fields,
			// 	$shortcode_tag
			// );

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
