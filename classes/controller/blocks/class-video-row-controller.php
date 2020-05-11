<?php
/**
 * Video Row block class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Video_Row_Controller' ) ) {
	/**
	 * Class Video_Row_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 * @since 0.1
	 */
    class Video_Row_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME   = 'video_row';

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
					'label' => __( 'Title (Optional)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Sub-Title (Optional)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'sub_title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Sub-Title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Youtube URL', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'youtube_url',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Youtube URL', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label'       => __( 'Or choose a video file', 'planet4-blocks-backend' ),
					'attr'        => 'video',
					'type'        => 'attachment',
					'libraryType' => [ 'video' ],
					'addButton'   => __( 'Select a Video', 'planet4-blocks-backend' ),
					'frameTitle'  => __( 'Select a Video', 'planet4-blocks-backend' )
				],
				[
					'label'       => __( 'Poster Image (For mobile browser)', 'planet4-blocks-backend' ),
					'attr'        => 'poster_image',
					'type'        => 'attachment',
					'libraryType' => [ 'image' ],
					'addButton'   => __( 'Select a Image', 'planet4-blocks-backend' ),
					'frameTitle'  => __( 'Select a Image', 'planet4-blocks-backend' )
				],
				[
					'label' => __( 'Auto play (Only support on PC)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'is_autoplay',
					'type'  => 'checkbox',
					'meta'  => [
						'placeholder' => __( 'Auto play (Only support on PC)', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => 'Video Layout',
					'attr' => 'width_type',
					'type' => 'radio',
					'options' => [
						[
							'value' => 'full_width',
							'label' => __( 'Full-width Video', 'planet4-gpea-blocks-backend' ),
						],
						[
							'value' => 'article_width',
							'label' => __( 'Article-width Video', 'planet4-gpea-blocks-backend' ),
						],
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Video Row', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/world-slideshow-block.jpg' ) . '" />',
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

			return [
				'fields' => $attributes,
				'video' => isset($attributes['video'])?wp_get_attachment_url($attributes['video']):null,
				'youtubeUrl' => isset($attributes['youtube_url'])?$this->getYoutubeId($attributes['youtube_url']):null,
				'isShowVideo' => isset($attributes['video']),
				'isShowYoutube' => isset($attributes['youtube_url']),
				'id' => $this->generateRandomString(5)
			];

		}

		private function getYoutubeId($url) {
			return '668nUCeBHyY';
		}

		private function generateRandomString($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
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

			//$js_creation = filectime( get_stylesheet_directory() . '/static/js/world.js' );

			//wp_register_script( 'world-script', get_stylesheet_directory_uri() . '/static/js/world.js',['child-script'], $js_creation, true );
			//wp_enqueue_script( 'world-script' );
			//$translation_array = array( 'templateUrl' => get_stylesheet_directory_uri() );
			//wp_localize_script( 'world-script', 'world_vars', $translation_array );
			wp_enqueue_script(
				'video_row.js',
				P4EABKS_ASSETS_DIR . 'js/video_row.js',
				[ 'jquery' ]
			);

			wp_enqueue_script(
				'youtube_embed_api.js',
				'https://www.youtube.com/iframe_api'
			);
			 
			wp_enqueue_style(
				'video_row.css',
				P4EABKS_ASSETS_DIR . 'css/video_row.css'
			);

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			return ob_get_clean();
		}
	}
}