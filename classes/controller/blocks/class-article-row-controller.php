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
		 * The maximum number of articles to display.
		 *
		 * @const string MAX_ARTICLES
		 */
		const MAX_ARTICLES = 4;

		/**
		 * The tag to display the user content submit form.
		 *
		 * @const string DISPLAY_FORM_FLAG_TAG
		 */
		const DISPLAY_FORM_FLAG_TAG = 'stories';

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
					'label'    => __( 'Article tags to display', 'planet4-gpea-blocks' ),
					'attr'     => 'tag_ids',
					'type'     => 'term_select',
					'taxonomy' => 'post_tag',
					'multiple' => true,
					'meta'     => [
						'select2_options' => [
							'allowClear'         => true,
							'placeholder'        => __( 'Select Tags', 'planet4-gpea-blocks' ),
							'closeOnSelect'      => true,
							'minimumInputLength' => 0,
							'maximumSelectionLength' => 3,
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

			// Set defaults if no tags selected.
			$posts = array();
			$display_submit_form = false;

			if ( isset( $attributes['tag_ids'] ) ) {

				$tag_ids = array_map( 'intval', explode( ',', $attributes['tag_ids'] ) );

				$tags = get_terms(
					'post_tag',
					array(
						'include' => $tag_ids,
					)
				);

				$query = new \WP_Query(
					array(
						'post_type'      => array( 'post', 'page', 'user_story' ),
						'tag__in'        => $tag_ids,
						'order'          => 'desc',
						'orderby'        => 'date',
						'posts_per_page' => self::MAX_ARTICLES,
					)
				);

				$posts = $query->posts;

				if ( $posts ) {
					foreach ( $posts as $post ) {
						$post->link = get_permalink( $post->ID );
						$post->post_date = date( 'Y - m - d' , strtotime( $post->post_date ) );
						$post->tags = array_filter(
							get_the_tags( $post->ID ), function( $tag ) use ( $tag_ids ) {
								return in_array( $tag->term_id, $tag_ids, true );
							}
						);
						if ( has_post_thumbnail( $post->ID ) ) {
							$img_id = get_post_thumbnail_id( $post->ID );
							$img_data = wp_get_attachment_image_src( $img_id, 'medium_large' );
							$post->img_url = $img_data[0];
						}
						$news_type = wp_get_post_terms( $post->ID, 'p4-page-type' ); 					
						if ( $news_type ) {
							$post->news_type = $news_type[0]->name;
						}
						$planet4_options = get_option( 'planet4_options' );
						$main_issues_category_id = isset( $planet4_options['issues_parent_category'] ) ? $planet4_options['issues_parent_category'] : false;
						if ( ! $main_issues_category_id ) {
							$main_issues_category = get_term_by( 'slug', 'issues', 'category' );
							if ( $main_issues_category ) $main_issues_category_id = $main_issues_category->term_id;
						}

						if ( $main_issues_category_id ) {
							$categories = get_the_category( $post->ID );
							if ( ! empty( $categories ) ) {
								$categories = array_filter( $categories, function( $cat ) use ( $main_issues_category_id ) {
									return $cat->category_parent === intval( $main_issues_category_id );
								});
								if ( ! empty( $categories ) ) {
									$first_category = array_values( $categories )[0];
									$post->main_issue = $first_category->name;
									$post->main_issue_slug = $first_category->slug;
								}
							}
						}
					}
				}

				wp_reset_postdata();

				$display_submit_form = in_array(
					self::DISPLAY_FORM_FLAG_TAG,
					array_map(
						function( $tag ) {
							return $tag->slug;
						}, $tags
					),
					true
				);

				// Pop last post if we're displaying submit form + pass the ugc link.
				if ( $display_submit_form && count( $posts ) === 4 ) {
					array_pop( $posts );
					$gpea_options = get_option( 'gpea_options' );
					$attributes['ugc_link'] = isset( $gpea_options['gpea_default_ugc_link'] ) ? get_permalink( $gpea_options['gpea_default_ugc_link'] ) : site_url();
				}
			}

			$attributes['display_submit_form'] = $display_submit_form;
			$attributes['posts'] = $posts;
			$attributes['layout'] = isset( $attributes['layout'] ) ? $attributes['layout'] : self::DEFAULT_LAYOUT;

			// lexicon entries
			$lexicon['add_your_story_par'] = __( 'Want to add your story?', 'planet4-gpea-blocks' );
			$lexicon['add_your_story_submit'] = __( 'Submit here', 'planet4-gpea-blocks' );

			return [
				'fields' => $attributes,
				'lexicon' => $lexicon,
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
