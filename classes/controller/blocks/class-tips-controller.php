<?php

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Tips_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Tips_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 * @since 0.1.3
	 */
	class Tips_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME   = 'tips';

		/** @const int MAX_REPEATER */
		const MAX_REPEATER = 20;

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
					'label' => __( 'Title', 'planet4-gpea-blocks' ),
					'attr'	=> 'title',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks' ),
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
					'label' => __( 'Label "See more"', 'planet4-gpea-blocks' ),
					'attr'	=> 'see_more_label',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Label "See more"', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Link "See more"', 'planet4-gpea-blocks' ),
					'attr'	=> 'see_more_link',
					'type'	=> 'url',
					'meta'	=> [
						'placeholder' => __( 'Link "See more"', 'planet4-gpea-blocks' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'		  => __( 'Tips', 'planet4-gpea-blocks' ),
					'attr'	   => 'tip_ids',
					'type'	   => 'post_select',
					'multiple' => 'multiple',
					'query'	   => [
						'post_type'	  => array('post',),
						'post_status' => 'publish',
						'orderby'	  => 'post_title',
						'order'			  => 'ASC',
						'tax_query'	  => array(
								array(
									'taxonomy' => 'p4_post_attribute',
									'field'	   => 'term',
									'terms'	   => 'tip',
								),
						),
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Tips', 'planet4-gpea-blocks' ),
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

			$formatted_posts = [];

			if( isset( $attributes[ 'tip_ids' ] ) ) {

				$posts = get_posts( array(
					'post_type'	  => array('post'),
					'post_status' => 'publish',
					'include' => explode( ',' , $attributes['tip_ids'] ),
					'orderby' => 'post__in',
				) );

				if( $posts ) {
					foreach( $posts as $post ) {
						$post = (array) $post; // TODO clean up this typecasting
						$tip_icon = get_post_meta( $post['ID'], 'p4-gpea_tip_icon', true );
						$post['img_url'] = $tip_icon ?? '';
						$frequency = get_post_meta( $post['ID'], 'p4-gpea_tip_frequency', true );
						$post['frequency'] = $frequency ?? '';
						// TODO:
						// - abstract this one to parent
						// - also avoid magic constant 'issues'
						$issues = get_category_by_slug( 'issues' );
						$issues = $issues->term_id;
						$categories = get_the_category( $post['ID'] );
						$categories = array_filter( $categories , function( $cat ) use ( $issues ) {
							return $cat->category_parent === $issues;
						});
						$categories = array_map( function( $cat ) {
							return $cat->slug;
						}, $categories );
						$categories = join( ', ', $categories );
						$post['categories'] = $categories ?? '';
						$formatted_posts[] = $post;
					}
				}
			}

			$attributes['posts'] = $formatted_posts;

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
			// echo '<pre>' . var_export($fields, true) . '</pre>';

			return ob_get_clean();
		}

	}
}