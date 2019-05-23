<?php

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Custom_Query_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Custom_Query_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Custom_Query_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'custom_query';

		/**
		 * @const int The maximum number of articles to load with Load more button.
		 */
		const MAX_ARTICLES = 100;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label'		  => __( 'Tags', 'planet4-gpea-blocks' ),
					'attr'		  => 'tags',
					'type'		  => 'term_select',
					'taxonomy'	  => 'post_tag',
					'placeholder' => __( 'Search for tags', 'planet4-gpea-blocks' ),
					'multiple'	  => true,
					'meta'		  => [
						'select2_options' => [
							'allowClear'		 => true,
							'placeholder'		 => __( 'Select tags', 'planet4-gpea-blocks' ),
							'closeOnSelect'		 => true,
							'minimumInputLength' => 0,
						],
					],
				],
				[
					'label'		  => __( 'Categories', 'planet4-gpea-blocks' ),
					'attr'		  => 'categories',
					'type'		  => 'term_select',
					'taxonomy'	  => 'category',
					'placeholder' => __( 'Search for categories', 'planet4-gpea-blocks' ),
					'multiple'	  => true,
					'meta'		  => [
						'select2_options' => [
							'allowClear'		 => true,
							'placeholder'		 => __( 'Select categories', 'planet4-gpea-blocks' ),
							'closeOnSelect'		 => true,
							'minimumInputLength' => 0,
						],
					],
				],
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
						'placeholder' => __( 'Description', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Item Count', 'planet4-gpea-blocks' ),
					'attr'	=> 'item_count',
					'type'	=> 'number',
					'meta'	=> [
						'placeholder' => __( 'Enter item count', 'planet4-gpea-blocks' ),
						'min'		  => 1,
						'max'		  => self::MAX_ARTICLES,
					],
				],
				[
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'radio',
					'options' => [
						[
							'value' => 1,
							'label' => __( 'Layout A', 'planet4-gpea-blocks' ),
							'desc'	=> 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 2,
							'label' => __( 'Layout B', 'planet4-gpea-blocks' ),
							'desc'	=> 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 3,
							'label' => __( 'Layout C', 'planet4-gpea-blocks' ),
							'desc'	=> 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ),
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Custom Post Query', 'planet4-gpea-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/latte.png' ) . '" />',
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

			$posts = get_posts( array(
				'category' => $attributes['categories']
			) );
			$attributes['posts'] = $this->populate_post_items($posts);
			return [
				'fields' => $attributes
			];

		}

		/**
		 * Populate selected posts for frontend template.
		 *
		 * @param array $posts Selected posts.
		 *
		 * @return array
		 */
		private function populate_post_items( $posts ) {

			$formatted_posts = []; 

			if ( $posts ) {
				foreach ( $posts as $post ) {
					$post = (array) $post; // TODO fix forcing to array by using object indirection, -> instead of []
					$post['alt_text'] = '';
					// TODO - Update this method to use P4_Post functionality to get P4_User.
					$author_override		   = get_post_meta( $post['ID'], 'p4_author_override', true );
					$post['author_name']	 = '' === $author_override ? get_the_author_meta( 'display_name', $post['post_author'] ) : $author_override;
					$post['author_url']		 = '' === $author_override ? get_author_posts_url( $post['post_author'] ) : '#';
					$post['author_override'] = $author_override;

					if ( has_post_thumbnail( $post['ID'] ) ) {
						$img_id					   = get_post_thumbnail_id( $post['ID'] );
						$dimensions				   = wp_get_attachment_metadata( $img_id );
						$post['thumbnail_ratio'] = ( isset( $dimensions['height'] ) && $dimensions['height'] > 0 ) ? $dimensions['width'] / $dimensions['height'] : 1;
						$post['alt_text']		 = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
					}

					// TODO - Update this method to use P4_Post functionality to get Tags/Terms.
					$wp_tags = wp_get_post_tags( $post['ID'] );

					$tags = [];

					if ( $wp_tags ) {
						foreach ( $wp_tags as $wp_tag ) {
							$tags_data['name'] = $wp_tag->name;
							$tags_data['slug'] = $wp_tag->slug;
							$tags_data['link'] = get_tag_link( $wp_tag );
							$tags[]			   = $tags_data;
						}
					}

					$post['tags'] = $tags;
					$page_type_data = get_the_terms( $post['ID'], 'p4-page-type' );
					$page_type		= '';
					$page_type_id	= '';

					if ( $page_type_data && ! is_wp_error( $page_type_data ) ) {
						$page_type	  = $page_type_data[0]->name;
						$page_type_id = $page_type_data[0]->term_id;
					}

					$post['page_type']	  = $page_type;
					$post['page_type_id'] = $page_type_id;
					$post['link']		  = get_permalink( $post['ID'] );

					$formatted_posts[] = $post;
				}
			}

			return $formatted_posts;
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
