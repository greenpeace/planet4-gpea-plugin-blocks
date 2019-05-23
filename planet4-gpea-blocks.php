<?php
/**
 * Plugin Name: Planet4 - GPEA Blocks
 * Description: Creates all the blocks that will be available for usage by Shortcake.
 * Plugin URI: https://github.com/greenpeace/planet4-gpea-plugin-blocks
 * Version: 2.8.5
 * Php Version: 7.0
 *
 * Author: Greenpeace Netherlands
 * Author URI: http://www.greenpeace.org/nl
 * Text Domain: planet4-GPEA-blocks
 *
 * License:     GPLv3
 * Copyright (C) 2018 Greenpeace Netherlands
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) or die( 'Direct access is forbidden !' );


/* ========================
	C O N S T A N T S
   ======================== */
if ( ! defined( 'P4EABKS_REQUIRED_PHP' ) )        define( 'P4EABKS_REQUIRED_PHP',        '7.0' );
if ( ! defined( 'P4EABKS_REQUIRED_PLUGINS' ) )    define( 'P4EABKS_REQUIRED_PLUGINS',    [
	'timber'    => [
		'min_version' => '1.3.0',
		'rel_path'    => 'timber-library/timber.php',
	],
	'shortcake' => [
		'min_version' => '0.7.0',
		'rel_path'    => 'shortcode-ui/shortcode-ui.php',
	],
	'p4_engaging'    => [
		'min_version' => '1.6',
		'rel_path'    => 'planet4-plugin-engagingnetworks/planet4-engagingnetworks.php',
	],
] );
if ( ! defined( 'P4EABKS_PLUGIN_BASENAME' ) )     define( 'P4EABKS_PLUGIN_BASENAME',    plugin_basename( __FILE__ ) );
if ( ! defined( 'P4EABKS_PLUGIN_DIRNAME' ) )      define( 'P4EABKS_PLUGIN_DIRNAME',     dirname( P4EABKS_PLUGIN_BASENAME ) );
if ( ! defined( 'P4EABKS_PLUGIN_DIR' ) )          define( 'P4EABKS_PLUGIN_DIR',         WP_PLUGIN_DIR . '/' . P4EABKS_PLUGIN_DIRNAME );
if ( ! defined( 'P4EABKS_PLUGIN_NAME' ) )         define( 'P4EABKS_PLUGIN_NAME',        'Planet4 - GPEA - Blocks' );
if ( ! defined( 'P4EABKS_PLUGIN_SHORT_NAME' ) )   define( 'P4EABKS_PLUGIN_SHORT_NAME',  'Blocks' );
if ( ! defined( 'P4EABKS_PLUGIN_SLUG_NAME' ) )    define( 'P4EABKS_PLUGIN_SLUG_NAME',   'blocks' );
if ( ! defined( 'P4EABKS_INCLUDES_DIR' ) )        define( 'P4EABKS_INCLUDES_DIR',       P4EABKS_PLUGIN_DIR . '/includes/' );
if ( ! defined( 'P4EABKS_ADMIN_DIR' ) )           define( 'P4EABKS_ADMIN_DIR',          plugins_url( P4EABKS_PLUGIN_DIRNAME . '/admin/' ) );
if ( ! defined( 'P4EABKS_ASSETS_DIR' ) )          define( 'P4EABKS_ASSETS_DIR',         plugins_url( P4EABKS_PLUGIN_DIRNAME . '/includes/assets/' ) );
if ( ! defined( 'P4EABKS_LANGUAGES' ) )           define( 'P4EABKS_LANGUAGES',          [
	'en_US' => 'English'
] );
if ( ! defined( 'P4EABKS_COVERS_NUM' ) )          define( 'P4EABKS_COVERS_NUM',         30 );
if ( ! defined( 'P4EABKS_ALLOWED_PAGETYPE' ) )    define( 'P4EABKS_ALLOWED_PAGETYPE',   [
	'page',
] );
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )       define( 'WP_UNINSTALL_PLUGIN',      P4EABKS_PLUGIN_BASENAME );

require_once __DIR__ . '/vendor/autoload.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

/* ==========================
	L O A D  P L U G I N
   ========================== */
P4EABKS\Loader::get_instance( [
	// --- Add here your own Block Controller ---
	// 'P4EABKS\Controllers\Blocks\Repeater_Controller', // Test controller
	// 'P4EABKS\Controllers\Blocks\Test_Controller', // Test controller
	// 'P4EABKS\Controllers\Blocks\Custom_Query_Controller', // Test controller
	'P4EABKS\Controllers\Blocks\Metablock_Controller',
	'P4EABKS\Controllers\Blocks\Mixed_Content_Row_Controller',
	// 'P4EABKS\Controllers\Blocks\Mixed_Content_Slideshow_Controller',
 	'P4EABKS\Controllers\Blocks\World_Slideshow_Controller',
	'P4EABKS\Controllers\Blocks\Projects_Overview_Controller',
	'P4EABKS\Controllers\Blocks\Article_Row_Controller',
	'P4EABKS\Controllers\Blocks\General_Updates_Controller',
	'P4EABKS\Controllers\Blocks\Main_Issues_Controller',
	'P4EABKS\Controllers\Blocks\Issue_List_Controller',
	'P4EABKS\Controllers\Blocks\Achievement_Section_Controller',
	'P4EABKS\Controllers\Blocks\UGC_Controller',
	'P4EABKS\Controllers\Blocks\Values_Section_Controller',
	'P4EABKS\Controllers\Blocks\Donate_Section_Controller',
	'P4EABKS\Controllers\Blocks\Text_And_Image_Controller',
	'P4EABKS\Controllers\Blocks\Launcher_Controller',
	'P4EABKS\Controllers\Blocks\Report_Controller',
	'P4EABKS\Controllers\Blocks\Milestones_Controller',
	'P4EABKS\Controllers\Blocks\Testimonials_Controller',
	'P4EABKS\Controllers\Blocks\Tips_Controller',
	'P4EABKS\Controllers\Blocks\Achievement_About_Controller',
	'P4EABKS\Models\Taxonomy',
	'P4EABKS\Models\Tags_Meta',
], 'P4EABKS\Views\View' );