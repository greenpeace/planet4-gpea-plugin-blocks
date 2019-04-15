<?php

namespace P4NLBKS\Controllers\Blocks;

if ( ! class_exists( 'Topic_Section_Controller' ) ) {
	/**
	 * @noinspection AutoloadingIssuesInspection
	 */

	/**
	 * Class Topic_Section_Controller
	 *
	 * @package P4NLBKS\Controllers\Blocks
	 */
	class Topic_Section_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'topic_section';

		/** @const string DEFAULT_LAYOUT */
		const DEFAULT_LAYOUT = 'default';

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
					'label' => __( 'Subtitle', 'planet4-gpnl-blocks' ),
					'attr'	=> 'subtitle',
					'type'	=> 'text',
					'meta'	=> [
						'placeholder' => __( 'Subtitle', 'planet4-gpnl-blocks' ),
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
					'label'		  => __( 'Topics', 'planet4-gpnl-blocks' ),
					'attr'     => 'topic_ids',
					'type'     => 'term_select',
					'taxonomy' => 'category',
					'multiple' => 'multiple',
					'meta'     => [
						'select2_options' => [
							'allowClear'             => true,
							'placeholder'            => __( 'Select topics', 'planet4-gpnl-blocks' ),
							'closeOnSelect'          => false,
							'minimumInputLength'     => 0,
							'multiple'               => true,
							'maximumSelectionLength' => 20,
							'width'                  => '80%',
						],
					],
				],
				[
					'label' => 'Select the layout',
					'description' => 'Select the layout',
					'attr' => 'layout',
					'type' => 'p4_radio',
					'options' => [
						[
							'value' => 1,
							'label' => __( 'Layout A', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 2,
							'label' => __( 'Layout B', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
						[
							'value' => 3,
							'label' => __( 'Layout C', 'planet4-gpnl-blocks' ),
							'desc'	=> 'Sample layout description',
							'image' => esc_url( plugins_url() . '/planet4-gpnl-plugin-blocks/admin/img/latte.png' ),
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'			=> __( 'LATTE | Topic Section', 'planet4-gpnl-blocks' ),
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
            
			$categories = get_categories( array(
                'include' => explode(',', $attributes['topic_ids']), 
			) );
			$attributes['categories'] = $categories;

            // TODO remove this magic constant 'issues'
            $topics_obj = get_category_by_slug( 'issues' );
            $topics_url = get_category_link( $topics_obj->term_id );
			$attributes['topics_url'] = $topics_url;

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
