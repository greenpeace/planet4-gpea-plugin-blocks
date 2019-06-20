<?php
/**
 * Tags meta
 *
 * @package P4EABKS
 * @since 0.1
 */

namespace P4EABKS\Models;

if ( ! class_exists( 'Tags_Meta' ) ) {
	/**
	 * Class Tags_Meta
	 *
	 * @package P4EABKS\Models
	 */
	class Tags_Meta {

		/**
		 * Engaging campaign ID meta key.
		 *
		 * @const string ENGAGING_CAMPAIGN_ID_META_KEY
		 */
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

			add_action( 'category_add_form_fields', [ $this, 'add_taxonomy_form_fields' ] );
			add_action( 'category_edit_form_fields', [ $this, 'add_taxonomy_form_fields' ] );
			add_action( 'create_category', [ $this, 'save_taxonomy_meta' ] );
			add_action( 'edit_category', [ $this, 'save_taxonomy_meta' ] );
		}

		/**
		 * Add custom field(s) to taxonomy form.
		 *
		 * @param WP_Term $wp_tag The object passed to the callback when on Edit Tag page.
		 */
		public function add_taxonomy_form_fields( $wp_tag ) {

			wp_enqueue_script(
				'p4nlbks_admin_questions_script',
				P4EABKS_ADMIN_DIR . 'js/blocks/admin-questions.js',
				[ 'jquery' ],
				'0.1',
				true
			);

			wp_localize_script(
				'p4nlbks_admin_questions_script',
				'wpgpeaApiSettings',
				array(
					'jsonUrl' => esc_url_raw( get_rest_url() ),
				)
			);

			wp_add_inline_script( 'p4nlbks_admin_questions_script', 'try{Typekit.load({ async: true });}catch(e){}' );

			if ( isset( $wp_tag ) && $wp_tag instanceof \WP_term ) {
				$engaging_campaign_id = get_term_meta( $wp_tag->term_id, self::ENGAGING_CAMPAIGN_ID_META_KEY, true );
				?>
				<tr class="form-field edit-wrap">
				<th>
				<label>Engaging campaign</label>
				</th>
				<td>
				<div id="<?php echo self::ENGAGING_CAMPAIGN_ID_META_KEY; ?>_container" data-activecampaign="<?php echo $engaging_campaign_id; ?>">Fetching questions...</div>
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
			$engaging_campaign_id  = filter_input( INPUT_POST, self::ENGAGING_CAMPAIGN_ID_META_KEY, FILTER_VALIDATE_INT );
			update_term_meta( $term_id, self::ENGAGING_CAMPAIGN_ID_META_KEY, $engaging_campaign_id );
		}
	}
}
