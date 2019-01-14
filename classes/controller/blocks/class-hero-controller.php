<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'GPNL_hero_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class GPNL_hero_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class GPNL_hero_Controller extends Controller {



		/**
	* @const string BLOCK_NAME
*/
		const BLOCK_NAME = 'gpnl_hero';


		/**
			Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = array(
				[
					'label' => __( 'Kop', 'planet4-gpnl-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
				],
				[
					'label' => __( 'Abstract', 'planet4-gpnl-blocks' ),
					'attr'  => 'description',
					'type'  => 'textarea',
				],
				[
					'label'       => __( 'Afbeelding', 'planet4-blocks-backend' ),
					'attr'        => 'image',
					'type'        => 'attachment',
					'libraryType' => [ 'image' ],
					'addButton'   => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
					'frameTitle'  => __( 'Selecteer afbeelding', 'planet4-blocks-backend' ),
				],
				[
					'label' => __( 'Text for link', 'planet4-blocks-backend' ),
					'attr'  => 'link_text',
					'type'  => 'url',
					'meta'  => [
						// translators: placeholder needs to represent the ordinal of the image, eg. 1st, 2nd etc.
						'placeholder' => sprintf( __( 'Enter link text for %s image', 'planet4-blocks-backend' ) ),
						'data-plugin' => 'planet4-blocks',
					],
				],
				[
					'label' => __( 'Url for link', 'planet4-blocks-backend' ),
					'attr'  => 'link_url',
					'type'  => 'url',
					'meta'  => [
						// translators: placeholder needs to represent the ordinal of the image, eg. 1st, 2nd etc.
						'placeholder' => sprintf( __( 'Enter link url for %s image', 'planet4-blocks-backend' ) ),
						'data-plugin' => 'planet4-blocks',
					],
				],
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'GPNL | Hero image', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/images/icon_noindex.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4NLBKS_ALLOWED_PAGETYPE,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

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

			$fields = shortcode_atts(
				array(
					'title'       => '',
					'description' => '',
					'image'       => '',
					'link_text'   => '',
					'link_url'    => '',
				),
				$fields,
				$shortcode_tag
			);

			// If an image is selected
			if ( isset( $fields['image'] ) && $image = wp_get_attachment_image_src( $fields['image'], 'full' ) ) {
				// load the image from the library
				$fields['image']        = $image[0];
				$fields['alt_text']     = get_post_meta( $fields['image'], '_wp_attachment_image_alt', true );
				$fields['image_srcset'] = wp_get_attachment_image_srcset( $fields['image'], 'full', wp_get_attachment_metadata( $fields['image'] ) );
				$fields['image_sizes']  = wp_calculate_image_sizes( 'full', null, null, $fields['image'] );
			}

			$data = [
				'fields' => $fields,
			];

			wp_enqueue_style( 'gpnl_hero_css', P4NLBKS_ASSETS_DIR . 'css/gpnl-hero.css', [], '2.2.29' );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}


	}
}

