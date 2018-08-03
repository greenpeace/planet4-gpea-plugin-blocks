<?php
/**
 * Plugin Name: Planet4 - GPNL Blocks
 * Description: Creates all the blocks that will be available for usage by Shortcake.
 * Plugin URI: https://github.com/greenpeace/planet4-gpnl-plugin-blocks
 * Version: 0.1
 * Php Version: 7.0
 *
 * Author: Greenpeace Netherlands
 * Author URI: http://www.greenpeace.org/nl
 * Text Domain: planet4-GPNL-blocks
 *
 * License:     GPLv3
 * Copyright (C) 2018 Greenpeace Netherlands
 */

# error tonen
error_reporting(E_ALL);
ini_set('display_errors', 1);

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


/* ========================
         C S S / JS
   ======================== */
// javascript
function wpbootstrap_scripts_with_jquery(){
	// Register the script like this for a theme:
	wp_register_script( 'jquery-docready-script', P4NLBKS_ASSETS_DIR  . 'js/docReady.js', array( 'jquery' ) );
	// Enqueue the script:
	wp_enqueue_script( 'jquery-docready-script' );
	// petition form related code
	wp_localize_script( "jquery-docready-script",
		'theUniqueNameForOurJSObjectPetitionForm',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' )/*, //url for php file that process ajax request to WP
			'nonce' => wp_create_nonce( "unique_id_nonce" ),// this is a unique token to prevent form hijacking
			'someData' => 'extra data you want  available to JS'*/
		)
	);
}
add_action( 'wp_enqueue_scripts', 'wpbootstrap_scripts_with_jquery' );
// stylesheets
function stylesheets() {
	// stylesheets
	wp_enqueue_style('style', P4NLBKS_ASSETS_DIR . 'css/style.css');
}
add_action('wp_enqueue_scripts', 'stylesheets');


/* ========================
    P E T I T I O N F O R M
   ======================== */
function petition_form_process() {
  # do whatever you need in order to process the form.
	
	# This will send the post variables to the specified url, and what the page returns will be in $response
	# get data from form
	$marketingcode  = $_POST["marketingcode"];
	$literatuurcode = $_POST["literatuurcode"];
	$naam           = $_POST["naam"];
	$email          = $_POST["email"];
	$telefoonnummer = $_POST["telefoonnummer"];
	$toestemming    = $_POST["toestemming"];
	# set-up your url
	$url = 'https://secured.greenpeace.nl';
	$myvars = '?source=' . $marketingcode . '&per=' . $literatuurcode . '&fn=' . $naam . '&email=' . $email . '&tel=' . $telefoonnummer . '&stop=' . $toestemming;

	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec( $ch );
	
	return $response;
}
# use this version for if you want the callback to work for users who are logged in
//add_action("wp_ajax_petition_form", "petition_form_process");
# use this version for if you want the callback to work for users who are not logged in
add_action("wp_ajax_nopriv_petition_form", "petition_form_process");

function doStuff() {
	//add this below what you currently have in your enqueue scripts function.
	
}
add_action('wp_enqueue_scripts', 'doStuff');


/* ==========================
      L O A D  P L U G I N
   ========================== */
P4NLBKS\Loader::get_instance( [
	// --- Add here your own Block Controller ---
	//'P4NLBKS\Controllers\Blocks\DonationForm_Controller',
  'P4NLBKS\Controllers\Blocks\Petition_Controller',
	'P4NLBKS\Controllers\Blocks\Force_Form_Old_Controller',
], 'P4NLBKS\Views\View' );
