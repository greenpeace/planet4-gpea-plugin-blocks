<?php
/**
 * Big carousel manual selection block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Big_Carousel_Manual_Selection_Controller' ) ) {
	/**
	 * Class Big_Carousel_Manual_Selection_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Big_Carousel_Manual_Selection_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'big_carousel_manual_selection';

		/**
		 * Engaging campaing ID meta value key.
		 *
		 * @const string ENGAGING_CAMPAIGN_META_KEY
		 */
		const ENGAGING_CAMPAIGN_META_KEY = 'engaging_campaign_ID';

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
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'subtitle',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Subtitle', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'paragraph',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'       => __( 'Carousel Items (max 8)', 'planet4-gpea-blocks-backend' ),
					'attr'     => 'carousel_item_ids',
					'type'     => 'post_select',
					'multiple' => 'multiple',
					'query'    => [
						'post_type'   => array( 'post', 'page' ),
						'post_status' => 'publish',
						'orderby'     => 'post_title',
						'order'           => 'ASC',
					],
					'meta'     => [
						'select2_options' => [
							'allowClear'             => true,
							'placeholder'            => __( 'Select carousel items (max 8)', 'planet4-gpea-blocks-backend' ),
							'closeOnSelect'          => false,
							'minimumInputLength'     => 0,
							'multiple'               => true,
							'maximumSelectionLength' => 8,
							'width'                  => '80%',
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Big carousel - manual selection', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/general_updates.png' ) . '" />',
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

			// engaging assets
			$engaging_settings = get_option( 'p4en_main_settings' );
			$engaging_token = $engaging_settings['p4en_frontend_public_api'];

			$formatted_posts = [];

			if ( isset( $attributes['carousel_item_ids'] ) ) {

				$query = new \WP_Query(
					array(
						'post_type'   => array( 'post', 'page' ),
						'post_status' => 'publish',
						'post__in'    => explode( ',' , $attributes['carousel_item_ids'] ),
						'orderby'     => 'post__in',
						'posts_per_page' => 8,
					)
				);

				if ( $query->posts ) {
					foreach ( $query->posts as $post ) {
						if ( has_post_thumbnail( $post->ID ) ) {
							$img_id = get_post_thumbnail_id( $post->ID );
							$img_data = wp_get_attachment_image_src( $img_id , 'large' );
							$post->img_url = $img_data[0];
						}

						$post->link = get_permalink( $post->ID );
						$post->post_date = date( 'Y - m - d', strtotime( $post->post_date ) );

						if ( has_term( 'petition', 'post_tag', $post->ID ) ) {
							$post->is_campaign = 1;
							if ( 'page-templates/petition.php' === get_post_meta( $post->ID, '_wp_page_template', true ) ) {
								$post->engaging_pageid = get_post_meta( $post->ID, 'p4-gpea_petition_engaging_pageid', true );
								$post->engaging_target = get_post_meta( $post->ID, 'p4-gpea_petition_engaging_target', true );

								if ( $post->engaging_pageid ) {
									global $wp_version;
									$url = 'http://www.e-activist.com/ea-dataservice/data.service?service=EaDataCapture&token=' . $engaging_token . '&campaignId=' . $post->engaging_pageid . '&contentType=json&resultType=summary';
									$args = array(
										'timeout'     => 5,
										'redirection' => 5,
										'httpversion' => '1.0',
										'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
										'blocking'    => true,
										'headers'     => array(),
										'cookies'     => array(),
										'body'        => null,
										'compress'    => false,
										'decompress'  => true,
										'sslverify'   => true,
										'stream'      => false,
										'filename'    => null,
									);
									$result = wp_remote_get( $url, $args );
									$obj = json_decode( $result['body'], true );
									$post->signatures = $obj['rows'][0]['columns'][4]['value'];
								}

								if ( $post->engaging_target && $post->signatures ) {
									$post->percentage = intval( intval( $post->signatures ) * 100 / intval( $post->engaging_target ) );
								} else {
									$post->percentage = 100;
								}

								/* if external link is set, we use that instead of standard one */
								$external_link = get_post_meta( $post->ID, 'p4-gpea_petition_external_link', true );
								if ( $external_link ) $post->link = $external_link;
							}
						}

						// get related main issues!
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

						$post->reading_time = get_post_meta( $post->ID, 'p4-gpea_post_reading_time', true );
						$news_type = wp_get_post_terms( $post->ID, 'p4-page-type' ); 					
						if ( $news_type ) {
							$post->news_type = $news_type[0]->name;
						}

						$formatted_posts[] = $post;
					}
				}

				wp_reset_postdata();
			}

			$attributes['posts'] = $formatted_posts;

			// lexicon entries
			$lexicon['reading_time'] = __( 'Reading time', 'planet4-gpea-blocks' );
			$lexicon['sign_now'] = __( 'Sign now', 'planet4-gpea-blocks' );

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
