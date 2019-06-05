<?php
/**
 * Article row block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Article_Row_Controller' ) ) {
	/**
	 * Class Article_Row_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Article_Row_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'article_row';

		/**
		 * The block default layout.
		 *
		 * @const string DEFAULT_LAYOUT
		 */
		const DEFAULT_LAYOUT = 'show_tag';

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'radio',
					'options' => [
						[
							'value' => 'show_tag',
							'label' => __( 'Display the tag', 'planet4-gpea-blocks' ),
							'desc'  => 'Display the tag',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 'hide_tag',
							'label' => __( 'Hide the tag', 'planet4-gpea-blocks' ),
							'desc'  => 'Hide the tag',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
					],
				],
				[
					'label' => __( 'Title', 'planet4-gpea-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'    => __( 'Article tag to display', 'planet4-gpea-blocks' ),
					'attr'     => 'article_tag',
					'type'     => 'term_select',
					'taxonomy' => 'post_tag',
					'multiple' => false,
					'meta'     => [
						'select2_options' => [
							'allowClear'         => true,
							'placeholder'        => __( 'Select Tags', 'planet4-blocks-backend' ),
							'closeOnSelect'      => true,
							'minimumInputLength' => 0,
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Article Row', 'planet4-gpea-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ) . '" />',
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

			$article_tag  = $attributes['article_tag'] ?? 0;
			$tag_details = get_tag( $article_tag );
			$tag_name = $tag_details->name ?? '';
			$tag_slug = $tag_details->slug ?? '';

			$query = new \WP_Query(
				array(
					'order'       => 'desc',
					'orderby'     => 'date',
					'post_type'   => 'any',
					'numberposts' => 20,
					'tax_query' => array(
						array(
							'taxonomy' => 'post_tag',
							'field' => 'id',
							'terms' => $article_tag,
						),
					),
				)
			);

			$posts = $query->posts;

			if ( $posts ) {
				foreach ( $posts as $post ) {
					$post->post_date = date( 'Y-m-d' , strtotime( $post->post_date ) );
				}
			}

			$attributes['posts'] = $posts;
			$attributes['tag_name'] = $tag_name;
			if ( 'stories' === $tag_slug ) {
				$attributes['ugc_stories'] = 1;
			} else {
				$attributes['ugc_stories'] = 0;
			}

			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

			wp_reset_postdata();

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

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			return ob_get_clean();
		}
	}
}
