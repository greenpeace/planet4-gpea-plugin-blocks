<?php
/**
 * Plugin Name: Planet4 - GPEA Blocks
 * Description: Creates all the blocks that will be available for usage by Shortcake.
 * Plugin URI: https://github.com/greenpeace/planet4-gpea-plugin-blocks
 * Version: 0.1
 * Php Version: 7.0
 *
 * Author: Greenpeace Netherlands
 * Author URI: http://www.greenpeace.org/nl
 * Text Domain: planet4-gpea-blocks
 *
 * License:     GPLv3
 * Copyright (C) 2018 Greenpeace Netherlands
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) or die( 'Direct access is forbidden !' );


/*
 ========================
	C O N S T A N T S
 =======================
*/

if ( ! defined( 'P4EABKS_REQUIRED_PHP' ) ) {
	define( 'P4EABKS_REQUIRED_PHP',        '7.0' );
}
if ( ! defined( 'P4EABKS_REQUIRED_PLUGINS' ) ) {
	define(
		'P4EABKS_REQUIRED_PLUGINS',    [
			'timber'    => [
				'min_version' => '1.3.0',
				'rel_path'    => 'timber-library/timber.php',
			],
			'shortcake' => [
				'min_version' => '0.7.0',
				'rel_path'    => 'shortcode-ui/shortcode-ui.php',
			],
			'p4_engaging'    => [
				'min_version' => '2.14',
				'rel_path'    => 'planet4-plugin-engagingnetworks/planet4-engagingnetworks.php',
			],
		]
	);
}
if ( ! defined( 'P4EABKS_PLUGIN_BASENAME' ) ) {
	define( 'P4EABKS_PLUGIN_BASENAME',    plugin_basename( __FILE__ ) );
}
if ( ! defined( 'P4EABKS_PLUGIN_DIRNAME' ) ) {
	define( 'P4EABKS_PLUGIN_DIRNAME',     dirname( P4EABKS_PLUGIN_BASENAME ) );
}
if ( ! defined( 'P4EABKS_PLUGIN_DIR' ) ) {
	define( 'P4EABKS_PLUGIN_DIR',         WP_PLUGIN_DIR . '/' . P4EABKS_PLUGIN_DIRNAME );
}
if ( ! defined( 'P4EABKS_PLUGIN_NAME' ) ) {
	define( 'P4EABKS_PLUGIN_NAME',        'Planet4 - GPEA - Blocks' );
}
if ( ! defined( 'P4EABKS_PLUGIN_SHORT_NAME' ) ) {
	define( 'P4EABKS_PLUGIN_SHORT_NAME',  'Blocks' );
}
if ( ! defined( 'P4EABKS_PLUGIN_SLUG_NAME' ) ) {
	define( 'P4EABKS_PLUGIN_SLUG_NAME',   'blocks' );
}
if ( ! defined( 'P4EABKS_INCLUDES_DIR' ) ) {
	define( 'P4EABKS_INCLUDES_DIR',       P4EABKS_PLUGIN_DIR . '/includes/' );
}
if ( ! defined( 'P4EABKS_ADMIN_DIR' ) ) {
	define( 'P4EABKS_ADMIN_DIR',          plugins_url( P4EABKS_PLUGIN_DIRNAME . '/admin/' ) );
}
if ( ! defined( 'P4EABKS_ASSETS_DIR' ) ) {
	define( 'P4EABKS_ASSETS_DIR',         plugins_url( P4EABKS_PLUGIN_DIRNAME . '/includes/assets/' ) );
}
if ( ! defined( 'P4EABKS_LANGUAGES' ) ) {
	define(
		'P4EABKS_LANGUAGES',          [
			'en_US' => 'English',
		]
	);
}
if ( ! defined( 'P4EABKS_COVERS_NUM' ) ) {
	define( 'P4EABKS_COVERS_NUM',         30 );
}
if ( ! defined( 'P4EABKS_ALLOWED_PAGETYPE' ) ) {
	define(
		'P4EABKS_ALLOWED_PAGETYPE',   [
			'page',
		]
	);
}
if ( ! defined( 'P4EABKS_DONATION_BLOCK_ALLOWED_PAGETYPE' ) ) {
	define(
		'P4EABKS_DONATION_BLOCK_ALLOWED_PAGETYPE',   [
			'page',
			'post',
		]
	);
}
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	define( 'WP_UNINSTALL_PLUGIN',      P4EABKS_PLUGIN_BASENAME );
}

require_once __DIR__ . '/vendor/autoload.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

/*
 ==========================
	L O A D  P L U G I N
 ==========================
*/

P4EABKS\Loader::get_instance(
	[
		// --- Add here your own Block Controller ---
		// 'P4EABKS\Controllers\Blocks\Repeater_Controller', // Test controller
		'P4EABKS\Controllers\Blocks\Mixed_Content_Row_Controller',
		'P4EABKS\Controllers\Blocks\Link_List_Controller',
		'P4EABKS\Controllers\Blocks\People_List_Controller',
		'P4EABKS\Controllers\Blocks\Accordion_List_Controller',
		'P4EABKS\Controllers\Blocks\Support_Launcher_Controller',
		'P4EABKS\Controllers\Blocks\Slideshow_Controller',
		'P4EABKS\Controllers\Blocks\World_Slideshow_Controller',
		'P4EABKS\Controllers\Blocks\Projects_Carousel_Controller',
		'P4EABKS\Controllers\Blocks\Articles_List_Controller',
		'P4EABKS\Controllers\Blocks\Article_Row_Controller',
		'P4EABKS\Controllers\Blocks\Big_Carousel_Manual_Selection_Controller',
		'P4EABKS\Controllers\Blocks\Tag_Cloud_Controller',
		'P4EABKS\Controllers\Blocks\Main_Issues_Carousel_Controller',
		'P4EABKS\Controllers\Blocks\Achievements_List_Controller',
		'P4EABKS\Controllers\Blocks\UGC_Controller',
		'P4EABKS\Controllers\Blocks\Values_Section_Controller',
		'P4EABKS\Controllers\Blocks\Donation_Basic_Controller',
		'P4EABKS\Controllers\Blocks\Donation_Dollar_Handles_Controller',
		'P4EABKS\Controllers\Blocks\Text_And_Image_Controller',
		'P4EABKS\Controllers\Blocks\Launcher_Controller',
		'P4EABKS\Controllers\Blocks\Launcher_Card_Controller',
		'P4EABKS\Controllers\Blocks\Report_Launcher_Controller',
		'P4EABKS\Controllers\Blocks\Milestones_Controller',
		'P4EABKS\Controllers\Blocks\Heart_Counter_Controller',
		'P4EABKS\Controllers\Blocks\Anchor_Links_Controller',
		'P4EABKS\Controllers\Blocks\Testimonials_Controller',
		'P4EABKS\Controllers\Blocks\Tips_Controller',
		'P4EABKS\Controllers\Blocks\Iframe_Controller',
		'P4EABKS\Controllers\Blocks\Video_Row_Controller',
		'P4EABKS\Controllers\Blocks\MC_Subscription_Controller',
		'P4EABKS\Controllers\Blocks\Grid_Images_Controller',
		'P4EABKS\Controllers\Blocks\Donation_Block_Controller',
		'P4EABKS\Controllers\Blocks\Homepage_B2_Controller',
		'P4EABKS\Models\Taxonomy',
		'P4EABKS\Models\Tags_Meta',
	], 'P4EABKS\Views\View'
);
