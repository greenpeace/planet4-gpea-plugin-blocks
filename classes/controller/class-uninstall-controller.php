<?php

namespace P4EABKS\Controllers;

if ( ! class_exists( 'Uninstall_Controller' ) ) {
	/**
	 * Class Uninstall_Controller
	 *
	 * @package P4EABKS\Controllers
	 */
	class Uninstall_Controller {

		/**
		 * Initialize uninstaller
		 */
		public function __construct() {

			// Exit if accessed directly.
			if ( ! defined( 'ABSPATH' ) ) {
				$this->exit_uninstaller();
			}
			// Not uninstalling.
			if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
				$this->exit_uninstaller();
			}
			// Not uninstalling.
			if ( ! WP_UNINSTALL_PLUGIN ) {
				$this->exit_uninstaller();
			}
			// Clean any options that were created by Planet4 - EngagingNetworks plugin.
			self::clean_options();
		}

		/**
		 * Cleanup options
		 *
		 * Deletes Planet4 - NL - Blocks options and transients.
		 *
		 * @return void
		 */
		protected static function clean_options() {
			// Delete options.
			delete_option( 'p4nlbks_main_settings' );
			delete_option( 'p4nlbks_pages_settings' );
		}

		/**
		 * Exit uninstaller
		 *
		 * Gracefully exit the uninstaller if we should not be here
		 *
		 * @return void
		 */
		protected function exit_uninstaller() {
			status_header( 404 );
			exit;

		}
	}
}
