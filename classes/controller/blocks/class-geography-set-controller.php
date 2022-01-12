<?php
/**
 * Geography Set Module (Homepage B Version 3rd Screen)
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Controllers\Blocks;

if ( ! class_exists( 'Geography_Set_Controller' ) ) {
	/**
	 * Class Geography_Set_Controller
	 *
	 * @package P4EABKS\Controllers\Blocks
	 */
	class Geography_Set_Controller extends Controller {

		/**
		 * The block name constant.
		 *
		 * @const string BLOCK_NAME
		 */
		const BLOCK_NAME = 'geography_set';

		/**
		 * The maximum number of sum-elements.
		 *
		 * @const string MAX_REPEATER
		 */
		const MAX_REPEATER = 10;

		/**
		 * Shortcode UI setup for the noindexblock shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$fields = [
				[
					'label' => __( 'Section title', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Section title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Paragraph, keep it in 6 lines.', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'paragraph',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Paragraph', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Video cover (first shot)', 'planet4-gpea-blocks-backend' ),
					'attr'        => 'video_img',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
				],
				[
					'label' => __( 'Paragraph under video cover (Please follow the headline or explanation from original video/youtube)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'video_paragraph',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Paragraph under video cover', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Video title', 'planet4-gpea-blocks-backend' ),
					'description' => __( 'Leave empty to use resource\'s title. (Not support on YouTube)', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'video_title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Video title', 'planet4-gpea-blocks-backend' ),
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
				[
					'label' => __( 'Youtube URL', 'planet4-gpea-blocks-backend' ),
					'attr'  => 'video_youtube',
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
					'label' => __( '<h3>Ships on the Map</h3>', 'planet4-gpea-blocks-backend' ),
					'description' => sprintf(__( '
						Ship position endpoint URL:
						<ol>
							<li>極地曙光號 ARCTIC SUNRISE: https://maps.greenpeace.org/maps/gpships/arctic_sunrise_gps_lastpoint.geojson</li>
							<li>希望號 ESPERANZA: https://maps.greenpeace.org/maps/gpships/esperanza_gps_lastpoint.geojson</li>
							<li>彩虹勇士號 RAINBOW WARRIOR: https://maps.greenpeace.org/maps/gpships/rainbow_warrior_gps_lastpoint.geojson</li>
							<li>見證者號 WITNESS: N/A</li>
						</ol>
					', 'planet4-gpea-blocks-backend' ), __( 'Settings' )),
					'attr'  => 'hint',
					'type'  => 'radio',
					'meta'  => [
						'data-plugin' => 'planet4-gpea-blocks',
					],
				],
			];

			$field_groups = [

				'Ship' => [
					[
						'label' => __( '<i>Ship %s icon</i>', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'icon',
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					],
					[
						'label' => __( '<i>Ship %s name</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'title',
						'type'  => 'text',
					],
					[
						'label' => __( '<i>Ship %s subtitle</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'subtitle',
						'type'  => 'text',
					],
					[
						'label' => __( '<i>Ship %s paragraph</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'paragraph',
						'type'  => 'textarea',
					],
					[
						'label' => __( '<i>Ship %s position endpoint URL</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'endpoint',
						'type'  => 'url',
					],
					[
						'label' => __( '<i>Ship %s image</i>', 'planet4-gpea-blocks-backend' ),
						'attr'        => 'img',
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => __( 'Select image', 'planet4-gpea-blocks-backend' ),
						'frameTitle'  => __( 'Select image', 'planet4-gpea-blocks-backend' ),
					],
					[
						'label' => __( '<i>Ship %s is visible on map</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'enabled',
						'type'  => 'checkbox',
						// 'value' => 'true',
					],
					[
						'label' => __( '<i>Ship %s video button is visible</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'video_enabled',
						'type'  => 'checkbox',
						// 'value' => 'true',
					],
					[
						'label' => __( '<i>Ship %s video button label</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'video_button_label',
						'type'  => 'text',
					],
					[
						'label' => __( '<i>Ship %s Youtube URL</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'video_youtube',
						'type'  => 'text',
					],
					[
						'label'       => __( '<i>Or choose a video file</i>', 'planet4-blocks-backend' ),
						'attr'        => 'video',
						'type'        => 'attachment',
						'libraryType' => [ 'video' ],
						'addButton'   => __( 'Select a Video', 'planet4-blocks-backend' ),
						'frameTitle'  => __( 'Select a Video', 'planet4-blocks-backend' )
					],
					[
						'label' => __( '<i>Auto play (Only support on PC)</i>', 'planet4-gpea-blocks-backend' ),
						'attr'  => 'video_autoplay',
						'type'  => 'checkbox',
						// 'value' => 'true',
					],
				],
			];

			$fields = $this->format_meta_fields( $fields, $field_groups );

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'GPEA | Geography Set', 'planet4-gpea-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-gpea-plugin-blocks/admin/img/geography-set-block.jpg' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4EABKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );

		}

		/**
		 * Get all the data that will be needed to render the block correctly.
		 *
		 * @param array $fields This will contain the fields to be rendered.
		 * @param array $field_groups This contains the field templates to be repeated.
		 *
		 * @return array The fields to be rendered
		 */
		private function format_meta_fields( $fields, $field_groups ) : array {

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {
				foreach ( $field_groups as $group_name => $group_fields ) {
					foreach ( $group_fields as $field ) {

						$safe_name = preg_replace( '/\s/', '', strtolower( $group_name ) );
						$attr_extension = '_' . $safe_name . '_' . $i;

						if ( array_key_exists( 'attr' , $field ) ) {
							$field['attr'] .= $attr_extension;
						} else {
							$field['attr'] = $i . $attr_extension;
						}

						if ( array_key_exists( 'label' , $field ) ) {
							$field['label'] = sprintf( $field['label'], $i );
						} else {
							$field['label'] = $field['attr'];
						}

						$new_meta = [
							'data-element-type' => $safe_name,
							'data-element-name' => $group_name,
							'data-element-number' => $i,
						];
						if ( ! array_key_exists( 'meta' , $field ) ) {
							$field['meta'] = [];
						}
						$field['meta'] += $new_meta;

						$fields[] = $field;
					}
				}
			}
			return $fields;
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
		public function prepare_data( $attributes = '', $content = '', $shortcode_tag = 'shortcake_' . self::BLOCK_NAME ) : array {

			if(!is_array($attributes)) {
				$attributes = [];
			}

			$default = [];
			$field_groups = [];

			for ( $i = 1; $i <= static::MAX_REPEATER; $i++ ) {

				// Group fields based on index number.
				$group = [];
				$group_type = false;
				foreach ( $attributes as $field_name => $field_content ) {
					if ( preg_match( '/_' . $i . '$/', $field_name ) ) {
						$field_name_data = explode( '_', $field_name );

						$field_name_data = explode( '_', $field_name );
						array_pop($field_name_data);
						$group_type = array_pop($field_name_data);
						$field_name_data = implode('_', $field_name_data);

						if ( ( 'img' === $field_name_data || 'icon' === $field_name_data || 'video' === $field_name_data ) && isset( $field_content ) ) {
							$field_content = wp_get_attachment_url( $field_content );
						}
						elseif( 'video_youtube' === $field_name_data ) {
							$field_content = $this->getYoutubeId( $field_content );
						}

						$group[ $field_name_data ] = $field_content;

					}
				}

				// Extract group field type.
				if ( $group_type ) {
					$group['__group_type__'] = $group_type;
				} else {
					continue;
				}

				$field_groups[ $i ] = $group;

			}

			// Extract static fields only.
			$static_fields = [];
			foreach ( $attributes as $field_name => $field_content ) {
				if ( ! preg_match( '/_\d+$/', $field_name ) ) {
					if( 'video_img' === $field_name || 'video' === $field_name ) {
						$attachment_id = $field_content;
						$field_content = wp_get_attachment_url( $attachment_id );
						if( 'video' === $field_name ) {
							$static_fields[ 'default_video_title' ] = get_the_title( $attachment_id );
						}
					}
					elseif( 'video_youtube' === $field_name ) {
						$field_content = $this->getYoutubeId( $field_content );
						$static_fields[ 'default_video_img' ] = 'http://i1.ytimg.com/vi/' . $field_content . '/maxresdefault.jpg';
					}
					$static_fields[ $field_name ] = $field_content;
				}
			}
			return [
				'static_fields' => $static_fields,
				'field_groups' => $field_groups,
				'id' => $this->generateRandomString(5),
			];

		}

		private function getYoutubeId($url) {
			$pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/';
        	preg_match($pattern, $url, $matches);
        	return (isset($matches[7])) ? $matches[7] : false;
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

			global $post;

			wp_enqueue_script(
				'youtube_embed_api.js',
				'https://www.youtube.com/iframe_api'
			);

			wp_enqueue_script(
				'geography-set',
				P4EABKS_ASSETS_DIR . 'js/geography-set.js',
				[ 'jquery' ]
			);
			// Pass options to frontend code.
			wp_localize_script(
				'geography-set',
				'geography_set_data_object',
				[
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'postId' => $post->ID,
				]
			);

			$data = $this->prepare_data( $fields );

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );
			return ob_get_clean();
		}

	}
}

function ajax_geography_get_ships() {
	if( !isset( $_POST['post_id'] ) ) {
		wp_send_json_error();
		return;
	}
	if( $post_content = get_the_content( NULL, FALSE, $_POST['post_id'] ) ) {
		preg_match_all(
			'/' . get_shortcode_regex() . '/',
			$post_content,
			$shortcodes,
			PREG_SET_ORDER
		);
	}
	else {
		wp_send_json_error();
		return;
	}
	$ships = [];
	foreach( $shortcodes as $shortcode_item ) {
		$shortcode_name = $shortcode_item[ 2 ];
		$shortcode_attr = shortcode_parse_atts( $shortcode_item[ 3 ] );
		if( 'shortcake_geography_set' === $shortcode_name ) {
			$current_ships = [];
			foreach( $shortcode_attr as $field_name => $field_value ) {
				if ( preg_match( '/^endpoint_(ship)_([0-9]+)$/', $field_name, $matches ) ) {
					$group_name = $matches[1];
					$ship_key = $matches[2];
					if( isset( $shortcode_attr[ 'enabled_' . $group_name . '_' . $ship_key ] ) && $shortcode_attr[ 'enabled_' . $group_name . '_' . $ship_key ] ) {
						$curl = curl_init();
					}
					if( !isset( $curl ) || !$curl ) {
						wp_send_json_error();
						continue;
					}
					curl_setopt( $curl, CURLOPT_URL, $field_value );
					curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );
					$ship_data = curl_exec( $curl );
					curl_close( $curl );
					$ship_data = @json_decode($ship_data, TRUE);
					if( isset( $ship_data[ 'features' ][ 0 ][ 'geometry' ][ 'coordinates' ] ) ) {
						$ship_position = $ship_data[ 'features' ][ 0 ][ 'geometry' ][ 'coordinates' ];
					}
					if( !isset( $ship_position ) || count( $ship_position ) != 2 ) {
						wp_send_json_error();
						continue;
					}

					$long = $ship_position[0];
					$lat = $ship_position[1];

					$left = $long + ($long < -170 ? 360 : 0);
					$left += 168;
					$left /= 358;

					$top = $lat * -1;
					$top += 83;
					$top /= 139;

					$current_ships[ $ship_key ] = [
						'long' => $long,
						'lat' => $lat,
						'x' => ($left * 100) . '%',
						'y' => ($top * 100) . '%',
					];
				}
			}
			$ships[] = $current_ships;
		}
		//$aaa[] = $shortcode_attr;
		/*if( isset( $shortcode_attr[ 'layout' ], $shortcode_attr[ 'paragraph' ] ) && 'plain_light' === $shortcode_attr[ 'layout' ] ) {
			$default[ 'paragraph' ] = wpautop( $shortcode_attr[ 'paragraph' ] );
		}*/
	}
	wp_send_json_success($ships);
}
add_action( 'wp_ajax_nopriv_geography_get_ships', 'P4EABKS\Controllers\Blocks\ajax_geography_get_ships' );
add_action( 'wp_ajax_geography_get_ships', 'P4EABKS\Controllers\Blocks\ajax_geography_get_ships' );