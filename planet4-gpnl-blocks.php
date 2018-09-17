<?php
/**
 * Plugin Name: Planet4 - GPNL Blocks
 * Description: Creates all the blocks that will be available for usage by Shortcake.
 * Plugin URI: https://github.com/greenpeace/planet4-gpnl-plugin-blocks
 * Version: 2.1.3
 * Php Version: 7.0
 *
 * Author: Greenpeace Netherlands
 * Author URI: http://www.greenpeace.org/nl
 * Text Domain: planet4-GPNL-blocks
 *
 * License:     GPLv3
 * Copyright (C) 2018 Greenpeace Netherlands
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) or die( 'Direct access is forbidden !' );


/* ========================
      C O N S T A N T S
   ======================== */
if ( ! defined( 'P4NLBKS_REQUIRED_PHP' ) )        define( 'P4NLBKS_REQUIRED_PHP',        '7.0' );
if ( ! defined( 'P4NLBKS_REQUIRED_PLUGINS' ) )    define( 'P4NLBKS_REQUIRED_PLUGINS',    [
	'timber' => [
		'min_version' => '1.3.0',
		'rel_path' => 'timber-library/timber.php',
	],
	'shortcake' => [
		'min_version' => '0.7.0',
		'rel_path' => 'shortcode-ui/shortcode-ui.php',
	],
] );
if ( ! defined( 'P4NLBKS_PLUGIN_BASENAME' ) )     define( 'P4NLBKS_PLUGIN_BASENAME',    plugin_basename( __FILE__ ) );
if ( ! defined( 'P4NLBKS_PLUGIN_DIRNAME' ) )      define( 'P4NLBKS_PLUGIN_DIRNAME',     dirname( P4NLBKS_PLUGIN_BASENAME ) );
if ( ! defined( 'P4NLBKS_PLUGIN_DIR' ) )          define( 'P4NLBKS_PLUGIN_DIR',         WP_PLUGIN_DIR . '/' . P4NLBKS_PLUGIN_DIRNAME );
if ( ! defined( 'P4NLBKS_PLUGIN_NAME' ) )         define( 'P4NLBKS_PLUGIN_NAME',        'Planet4 - GPNL - Blocks' );
if ( ! defined( 'P4NLBKS_PLUGIN_SHORT_NAME' ) )   define( 'P4NLBKS_PLUGIN_SHORT_NAME',  'Blocks' );
if ( ! defined( 'P4NLBKS_PLUGIN_SLUG_NAME' ) )    define( 'P4NLBKS_PLUGIN_SLUG_NAME',   'blocks' );
if ( ! defined( 'P4NLBKS_INCLUDES_DIR' ) )        define( 'P4NLBKS_INCLUDES_DIR',       P4NLBKS_PLUGIN_DIR . '/includes/' );
if ( ! defined( 'P4NLBKS_ADMIN_DIR' ) )           define( 'P4NLBKS_ADMIN_DIR',          plugins_url( P4NLBKS_PLUGIN_DIRNAME . '/admin/' ) );
if ( ! defined( 'P4NLBKS_ASSETS_DIR' ) )          define( 'P4NLBKS_ASSETS_DIR',         plugins_url( P4NLBKS_PLUGIN_DIRNAME . '/includes/assets/' ) );
if ( ! defined( 'P4NLBKS_LANGUAGES' ) )           define( 'P4NLBKS_LANGUAGES',          [
	'en_US' => 'English'
] );
if ( ! defined( 'P4NLBKS_COVERS_NUM' ) )          define( 'P4NLBKS_COVERS_NUM',         30 );
if ( ! defined( 'P4NLBKS_ALLOWED_PAGETYPE' ) )    define( 'P4NLBKS_ALLOWED_PAGETYPE',   [
	'page',
] );
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )       define( 'WP_UNINSTALL_PLUGIN',      P4NLBKS_PLUGIN_BASENAME );

require_once __DIR__ . '/vendor/autoload.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

/* ==========================
	L O A D  P L U G I N
   ========================== */
P4NLBKS\Loader::get_instance( [
	// --- Add here your own Block Controller ---
	// 'P4NLBKS\Controllers\Blocks\Donation_Controller',
	'P4NLBKS\Controllers\Blocks\Petition_Controller',
], 'P4NLBKS\Views\View' );
