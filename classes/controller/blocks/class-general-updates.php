<?php

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'General_Updates_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class General_Updates_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class General_Updates_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'general_updates';

		/** @const string ENGAGING_CAMPAIGN_META_KEY */
		const ENGAGING_CAMPAIGN_META_KEY = 'engaging_campaign_ID';

		/** @const string DEFAULT_LAYOUT */
		const DEFAULT_LAYOUT = 'light';

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Title', 'planet4-gpea-blocks' ),
					'attr'	=> 'title',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Subtitle', 'planet4-gpea-blocks' ),
					'attr'	=> 'subtitle',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Description', 'planet4-gpea-blocks' ),
					'attr'	=> 'description',
					'type'	=> 'textarea',
					'meta'	=> [
						'placeholder' => __( 'Description (light layout only)', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'		  => __( 'Updates', 'planet4-gpea-blocks' ),
					'attr'	   => 'update_ids',
					'type'	   => 'post_select',
					'multiple' => 'multiple',
					'query'	   => [
						'post_type'	  => array('post','page'),
						'post_status' => 'publish',
						'orderby'	  => 'post_title',
						'order'			  => 'ASC',
						// 'tax_query'	  => array(
						//		array(
						//			'taxonomy' => 'p4-page-type',
						//			'field'	   => 'slug',
						//			'terms'	   => 'update',
						//		),
						// ),
					],
					'meta'	   => [
						'select2_options' => [
							'allowClear'			 => true,
							'placeholder'			 => __( 'Select updates', 'planet4-gpea-blocks' ),
							'closeOnSelect'			 => false,
							'minimumInputLength'	 => 0,
							'multiple'				 => true,
							'maximumSelectionLength' => 20,
							'width'					 => '80%',
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
							'label' => __( 'Light background, dark text', 'planet4-gpea-blocks' ),
							'desc'	=> 'Light background, dark text',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'dark',
							'label' => __( 'Dark background, light text', 'planet4-gpea-blocks' ),
							'desc'	=> 'Dark background, light text',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
					],
				],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | General Updates - manual selection', 'planet4-gpea-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/general_updates.png' ) . '" />',
				'attrs'			=> $fields,
				'post_type'		=> P4EABKS_ALLOWED_PAGETYPE,
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

			if( isset( $attributes[ 'update_ids' ] ) ) {

				$posts = get_posts( array(
					'post_type'	  => array('post','page'),
					'post_status' => 'publish',
					'include' => explode( ',' , $attributes['update_ids'] ),
					'orderby' => 'post__in',
				) );

				if( $posts ) {
					foreach( $posts as $post ) {
						$post = (array) $post; // TODO clean up this typecasting

						if ( has_post_thumbnail( $post['ID'] ) ) {
							$img_id = get_post_thumbnail_id( $post['ID'] );
							$img_data = wp_get_attachment_image_src( $img_id , 'medium_large' );
							$post['img_url'] = $img_data[0];
						}

						if( has_term( 'petition', 'p4_post_attribute', $post['ID'] ) ) {
							$post['is_campaign'] = 1;
						}

						$formatted_posts[] = $post;
					}
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
