<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Article_Row_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Article_Row_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Article_Row_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'article_row';

		/** @const string DEFAULT_LAYOUT */
		const DEFAULT_LAYOUT = 'show_tag';

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
					'label'	   => __( 'Article tag to display', 'planet4-gpnl-blocks' ),
					'attr'	   => 'article_tag',
					'type'	   => 'term_select',
					'taxonomy' => 'post_tag',
					'multiple' => false,					
					'meta'	   => [
						'select2_options' => [
							'allowClear'		 => true,
							'placeholder'		 => __( 'Select Tags', 'planet4-blocks-backend' ),
							'closeOnSelect'		 => true,
							'minimumInputLength' => 0,
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
							'value' => 'show_tag',
							'label' => __( 'Display the tag', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Display the tag',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'hide_tag',
							'label' => __( 'Hide the tag', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Hide the tag',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Article Row', 'planet4-gpnl-blocks' ),
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

			$article_tag  = $attributes['article_tag'] ?? 0;
			$tag_details = get_tag( $article_tag );
			$tag_name = $tag_details->name ?? '';
			$tag_slug = $tag_details->slug ?? '';
			
			$options = array(
				'order'		  => 'desc',
				'orderby'	  => 'date',
				'post_type'	  => array('page','post'),
				'numberposts' => 20,
				'tax_query' => array(
					array(
						'taxonomy' => 'post_tag',
						'field' => 'id',
						'terms' => $article_tag,
					),					
				)
			);

			$posts = get_posts( $options );

			if( $posts ) {
				foreach( $posts as $post ) {
					$post->post_date = date( "Y-m-d" , strtotime( $post->post_date ) );
				}
			}

			$attributes['posts'] = $posts;
			$attributes['tag_name'] = $tag_name;
			if ($tag_slug == 'stories') $attributes['ugc_stories'] = 1;
			else $attributes['ugc_stories'] = 0;

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
