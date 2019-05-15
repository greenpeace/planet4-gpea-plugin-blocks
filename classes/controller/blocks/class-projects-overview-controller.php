<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Projects_Overview_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Projects_Overview_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Projects_Overview_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'projects_overview';

		/** @const string DEFAULT_LAYOUT */
		const DEFAULT_LAYOUT = '';

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
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'radio',
					'options' => [
						[
							'value' => 'light',
							'label' => __( 'Light background, dark text', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Light background, dark text',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'dark',
							'label' => __( 'Dark background, light text', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Dark background, light text',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Project Section', 'planet4-gpnl-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/projects_block.png' ) . '" />',
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

			$formatted_posts = [];

			$posts = get_posts( array(
				'order'		  => 'desc',
				'orderby'	  => 'date',
				'post_type'	  => 'page',
				'numberposts' => 20,
				'tax_query' => array(
					array(
						'taxonomy' => 'p4_post_attribute',
						'field' => 'slug',
						'terms' => 'project',
					)
				)
			) );

			if( $posts ) {
				foreach( $posts as $post ) {
					$post = (array) $post;
					if ( has_post_thumbnail( $post['ID'] ) ) {
						$img_id = get_post_thumbnail_id( $post['ID'] );
						$img_data = wp_get_attachment_image_src( $img_id , 'medium_large' );
						$post['img_url'] = $img_data[0];
					}
					$formatted_posts[] = $post;
				}
			}

			$attributes['posts'] = $formatted_posts;
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

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();

			$this->view->block( self::BLOCK_NAME, $data );
			// echo '<pre>' . var_export($data, true) . '</pre>';

			return ob_get_clean();
		}


	}
}
