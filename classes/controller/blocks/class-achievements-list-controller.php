<?php
/**
 * Achievements list block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Achievements_List_Controller' ) ) {
	/**
	 * Class Achievements_List_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Achievements_List_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'achievements_list';

		/**
		 * The block default layout.
		 *
		 * @const string BLOCK_NAME
		 */
		const DEFAULT_LAYOUT = 'default';

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Title', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks-backend',
					],
				],
				[
					'label' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks-backend',
					],
				],
				[
					'label' => __( 'Module image', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'bg_img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
				[
					'label' => __( 'Module image (mobile)', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'bg_img_mobile',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
				[
					'label'    => __( 'Prominent achievement', 'planet4-gpea-blocks-backend' ),
					'attr'     => 'main_achievement_id',
					'type'     => 'post_select',
					'query'    => [
						'post_type'   => array( 'post' ),
						'post_status' => 'publish',
						'orderby'     => 'post_title',
						'order'       => 'ASC',
						'tag'         => 'achievement',
					],
					'meta'     => [
						'select2_options' => [
							'allowClear'             => true,
							'placeholder'            => __( 'Select prominent achievement', 'planet4-gpea-blocks-backend' ),
							'closeOnSelect'          => true,
						],
					],
				],
				[
					'label' => __( 'Label "See more"', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'see_more_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Label "See more"', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks-backend',
					],
				],
				[
					'label' => __( 'Link "See more"', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'see_more_link',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Link "See more"', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks-backend',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Achievements List', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/achivements_block.png' ) . '" />',
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

			$formatted_posts = [];

			if ( isset( $attributes['bg_img'] ) ) {
				$attributes['bg_img'] = wp_get_attachment_url( $attributes['bg_img'] );
			}

			if ( isset( $attributes['bg_img_mobile'] ) ) {
				$attributes['bg_img_mobile'] = wp_get_attachment_url( $attributes['bg_img_mobile'] );
			}

			// get original name of achievemt tag!
			$achievement_tag = get_term_by( 'slug', 'achievement', 'post_tag' );
			if ( $achievement_tag ) {
				$attributes['achievement_name'] = $achievement_tag->name;
			}
			// First query - get only post IDs.
			$exclude = isset( $attributes['main_achievement_id'] ) ? array( $attributes['main_achievement_id'] ) : array();
			$num_posts = $exclude ? 3 : 4;
			$options = array(
				'order'        => 'desc',
				'orderby'      => 'date',
				'post_type'    => array( 'post' ),
				'posts_per_page'  => $num_posts,
				'fields'       => 'ids',
				'tag'          => 'achievement',
				'post__not_in' => $exclude,
			);
			$query = new \WP_Query( $options );
			$post_ids = $query->posts;
			wp_reset_postdata();
			if ( $exclude ) {
				array_unshift( $post_ids, $exclude[0] );
			}

			// Second query - get all posts.
			$options = array(
				'post__in' => $post_ids,
				'orderby' => 'post__in',
			);
			$query = new \WP_Query( $options );
			if ( $query->posts ) {
				foreach ( $query->posts as $post ) {
					if ( has_post_thumbnail( $post->ID ) ) {
						$img_id = get_post_thumbnail_id( $post->ID );
						$img_data = wp_get_attachment_image_src( $img_id, 'medium_large' );
						$post->img_url = $img_data[0];
					}
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

					/* get reading time */
					$post->reading_time = get_post_meta( $post->ID, 'p4-gpea_post_reading_time', true );
					$news_type = wp_get_post_terms( $post->ID, 'p4-page-type' ); 					
					if ( $news_type ) {
						$post->news_type = $news_type[0]->name;
					}
					$post->link = get_the_permalink( $post->ID );

					$formatted_posts[] = $post;
				}
			}

			$attributes['posts'] = $formatted_posts;
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
