<?php
/**
 * Taxonomy class
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Models;

if ( ! class_exists( 'Taxonomy' ) ) {

	/**
	 * Class Taxonomy
	 *
	 * @package P4EABKS\Models
	 */
	class Taxonomy {

		/**
		 * Creates the plugin's Taxonomy object.
		 */
		public function __construct() {}

		/**
		 * Hooks all the needed functions to load the taxonomies.
		 */
		public function load() {
			// add_action calls to custom taxonomies go here.
			add_action( 'init', array( $this, 'create_special_attribute_taxonomy' ) );
		}

		/**
		 * Creates a 'special attribute' taxonomy.
		 */
		public function create_special_attribute_taxonomy() {

			$labels = array(
				'name'                       => _x( 'Special attributes', 'taxonomy general name', 'planet4-gpea-blocks' ),
				'singular_name'              => _x( 'Special attribute', 'taxonomy singular name', 'planet4-gpea-blocks' ),
				'edit_item'                  => __( 'Edit Special attribute', 'planet4-gpea-blocks' ),
				'update_item'                => __( 'Update Special attribute', 'planet4-gpea-blocks' ),
				'add_new_item'               => __( 'Add New Special attribute', 'planet4-gpea-blocks' ),
				'new_item_name'              => __( 'New Special attribute Name', 'planet4-gpea-blocks' ),
				'separate_items_with_commas' => __( 'Separate special attributes with commas', 'planet4-gpea-blocks' ),
				'add_or_remove_items'        => __( 'Add or remove special attributes', 'planet4-gpea-blocks' ),
				'choose_from_most_used'      => __( 'Choose from the most used special attributes', 'planet4-gpea-blocks' ),
				'not_found'                  => __( 'No special attributes found.', 'planet4-gpea-blocks' ),
				'menu_name'                  => __( 'Special attributes', 'planet4-gpea-blocks' ),
			);

			$args = array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => false,
			);

			register_taxonomy( 'special_attribute', array( 'post' ), $args );

		}
	}
}
