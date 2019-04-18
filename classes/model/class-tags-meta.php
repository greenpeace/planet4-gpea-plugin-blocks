<?php

namespace P4NLBKS\Models;

if ( ! class_exists( 'Tags_Meta' ) ) {

	/**
	 * Class Tags_Meta
	 *
	 * @package P4NLBKS\Models
	 */
	class Tags_Meta {

		/** @const string ENGAGING_CAMPAIGN_ID_META_KEY */
		const ENGAGING_CAMPAIGN_ID_META_KEY = 'engaging_campaign_ID';

		/**
		 * Creates the plugin's Tags_Meta object.
		 */
		public function __construct() {}

		/**
		 * Hooks all the needed functions to update tags metadata.
		 */
		public function load() {
			add_action( 'post_tag_add_form_fields', [ $this, 'add_taxonomy_form_fields' ] );
			add_action( 'post_tag_edit_form_fields', [ $this, 'add_taxonomy_form_fields' ] );
			add_action( 'create_post_tag', [ $this, 'save_taxonomy_meta' ] );
			add_action( 'edit_post_tag', [ $this, 'save_taxonomy_meta' ] );
		}

		/**
		 * Add custom field(s) to taxonomy form.
		 *
		 * @param WP_Term $wp_tag The object passed to the callback when on Edit Tag page.
		 */
		public function add_taxonomy_form_fields( $wp_tag ) {
			if ( isset( $wp_tag ) && $wp_tag instanceof WP_Term ) {
				$engaging_campaign_ID = get_term_meta( $wp_tag->term_id, self::ENGAGING_CAMPAIGN_ID_META_KEY, true );
				?>				
				<tr class="form-field edit-wrap">
					<th>
						<label>Engaging campaign ID</label>
					</th>
					<td>
						<input type="number" name="<?php echo self::ENGAGING_CAMPAIGN_ID_META_KEY; ?>" id="<?php echo self::ENGAGING_CAMPAIGN_ID_META_KEY; ?>" value="<?php echo esc_attr( $engaging_campaign_ID ); ?>"/>
					</td>
				</tr>
				<?php
			}
		}

		/**
		 * Save taxonomy custom field(s).
		 *
		 * @param int $term_id The ID of the WP_Term object that is added or edited.
		 */
		public function save_taxonomy_meta( $term_id ) {
			$engaging_campaign_ID  = filter_input( INPUT_POST, self::ENGAGING_CAMPAIGN_ID_META_KEY, FILTER_VALIDATE_INT );
			update_term_meta( $term_id, self::ENGAGING_CAMPAIGN_ID_META_KEY, $engaging_campaign_ID );
		}
	}
}
