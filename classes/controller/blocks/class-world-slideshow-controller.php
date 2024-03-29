<?php
/**
 * World slideshow block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'World_Slideshow_Controller' ) ) {
	/**
	 * Class World_Slideshow_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 * @since 0.1
	 */
	class World_Slideshow_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME   = 'world_slideshow';

		/**
		 * The maximum number of sub-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 35;

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
					'label' => __( 'Title', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// This block will have at most MAX_REPEATER different items.
			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				$fields[] =
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => sprintf( __( '<strong>%s</strong> <i>date</i>', 'planet4-gpea-blocks-backend' ), $i ),
						'attr'  => 'date_milestone_' . $i,
						'type'  => 'text',
						'meta'  => [
							// translators: placeholder represents the ordinal of the field.
							'placeholder' => sprintf( __( 'Enter date %s', 'planet4-gpea-blocks-backend' ), $i ),
							'data-plugin' => 'planet4-gpea-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => sprintf( __( '<strong>%s</strong> <i>latitude</i>', 'planet4-gpea-blocks-backend' ), $i ),
						'attr'  => 'lat_milestone_' . $i,
						'type'  => 'number',
						'meta'  => [
							// translators: placeholder represents the ordinal of the field.
							'placeholder' => sprintf( __( 'Enter latitude %s', 'planet4-gpea-blocks-backend' ), $i ),
							'data-plugin' => 'planet4-gpea-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => sprintf( __( '<strong>%s</strong> <i>longitude</i>', 'planet4-gpea-blocks-backend' ), $i ),
						'attr'  => 'lon_milestone_' . $i,
						'type'  => 'number',
						'meta'  => [
							// translators: placeholder represents the ordinal of the field.
							'placeholder' => sprintf( __( 'Enter longitude %s', 'planet4-gpea-blocks-backend' ), $i ),
							'data-plugin' => 'planet4-gpea-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => sprintf( __( '<strong>%s</strong> <i>title</i>', 'planet4-gpea-blocks-backend' ), $i ),
						'attr'  => 'title_milestone_' . $i,
						'type'  => 'text',
						'meta'  => [
							// translators: placeholder represents the ordinal of the field.
							'placeholder' => sprintf( __( 'Enter title %s', 'planet4-gpea-blocks-backend' ), $i ),
							'data-plugin' => 'planet4-gpea-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => sprintf( __( '<strong>%s</strong> <i>subtitle</i>', 'planet4-gpea-blocks-backend' ), $i ),
						'attr'  => 'subtitle_milestone_' . $i,
						'type'  => 'text',
						'meta'  => [
							// translators: placeholder represents the ordinal of the field.
							'placeholder' => sprintf( __( 'Enter subtitle %s', 'planet4-gpea-blocks-backend' ), $i ),
							'data-plugin' => 'planet4-gpea-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						// translators: placeholder represents the ordinal of the field.
						'label' => sprintf( __( '<strong>%s</strong> <i>paragraph</i>', 'planet4-gpea-blocks-backend' ), $i ),
						'attr'  => 'paragraph_milestone_' . $i,
						'type'  => 'textarea',
						'meta'  => [
							// translators: placeholder represents the ordinal of the field.
							'placeholder' => sprintf( __( 'Enter paragraph %s', 'planet4-gpea-blocks-backend' ), $i ),
							'data-plugin' => 'planet4-gpea-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];

				$fields[] =
					[
						// translators: placeholder represents the ordinal of the field.
						'label'       => sprintf( __( '<strong>%s</strong> <i>image</i>', 'planet4-gpea-blocks-backend' ), $i ),
						'attr'        => 'img_milestone_' . $i,
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'meta'        => [
							// translators: placeholder represents the ordinal of the field.
							'placeholder' => sprintf( __( 'Enter image %s', 'planet4-gpea-blocks-backend' ), $i ),
							'data-plugin' => 'planet4-gpea-blocks',
							'data-element-type' => 'milestone',
							'data-element-name' => 'milestone',
							'data-element-number' => $i,
						],
					];
			}

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | World Slideshow', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/world-slideshow-block.jpg' ) . '" />',
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

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {
				if ( isset( $attributes[ 'img_milestone_' . $i ] ) ) {
					$attributes[ 'img_milestone_' . $i ] = wp_get_attachment_url( $attributes[ 'img_milestone_' . $i ] );
				}
			}

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

			$js_creation = filectime( get_stylesheet_directory() . '/static/js/world.js' );

			wp_register_script( 'world-script', get_stylesheet_directory_uri() . '/static/js/world.js',['child-script'], $js_creation, true );
			wp_enqueue_script( 'world-script' );
			$translation_array = array( 'templateUrl' => get_stylesheet_directory_uri() );
			wp_localize_script( 'world-script', 'world_vars', $translation_array );

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			return ob_get_clean();
		}
	}
}
