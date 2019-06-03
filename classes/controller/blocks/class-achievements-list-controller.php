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
					'label' => __( 'Title', 'planet4-gpea-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Subtitle', 'planet4-gpea-blocks' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Module image', 'planet4-gpea-blocks' ),
					'attr'        => 'bg_img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks' ),
				],
				[
					'label' => __( 'Module image (mobile)', 'planet4-gpea-blocks' ),
					'attr'        => 'bg_img_mobile',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks' ),
				],
				[
					'label'    => __( 'Achievements (manual selection)', 'planet4-gpea-blocks' ),
					'attr'     => 'achievements_item_ids',
					'type'     => 'post_select',
					'multiple' => 'multiple',
					'query'    => [
						'post_type'   => array( 'post' ),
						'post_status' => 'publish',
						'orderby'     => 'post_title',
						'order'           => 'ASC',
						'tax_query'   => array(
							array(
								'taxonomy' => 'post_tag',
								'field'    => 'slug',
								'terms'    => 'achievement',
							),
						),
					],
					'meta'     => [
						'select2_options' => [
							'allowClear'             => true,
							'placeholder'            => __( 'Select achievements (max 4)', 'planet4-gpea-blocks' ),
							'closeOnSelect'          => false,
							'minimumInputLength'     => 0,
							'multiple'               => true,
							'maximumSelectionLength' => 8,
							'width'                  => '80%',
						],
					],
				],
				[
					'label' => __( 'Label "See more"', 'planet4-gpea-blocks' ),
					'attr'  => 'see_more_label',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Label "See more"', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Link "See more"', 'planet4-gpea-blocks' ),
					'attr'  => 'see_more_link',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Link "See more"', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Achievements List', 'planet4-gpea-blocks' ),
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

			if ( isset( $attributes['achievements_item_ids'] ) ) {

				$options = array(
					'post_type'   => array( 'post', 'page' ),
					'post_status' => 'publish',
					'post__in'    => explode( ',', $attributes['achievements_item_ids'] ),
					'orderby'     => 'post__in',
					'numberposts' => 8,
				);
			} else {
				// Project block default text setting.
				$options = array(
					'order'       => 'desc',
					'orderby'     => 'date',
					'post_type'   => array( 'post', 'page' ),
					'numberposts' => 4,
					'tax_query' => array(
						array(
							'taxonomy' => 'post_tag',
							'field' => 'slug',
							'terms' => 'achievement',
						),
					),
				);
			}

			$query = new \WP_Query( $options );

			if ( $query->posts ) {
				foreach ( $query->posts as $post ) {
					if ( has_post_thumbnail( $post->ID ) ) {
						$img_id = get_post_thumbnail_id( $post->ID );
						$img_data = wp_get_attachment_image_src( $img_id , 'medium_large' );
						$post->img_url = $img_data[0];
					}
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
