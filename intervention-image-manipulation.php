<?php
/*
Plugin Name: Intervention Image Manipulation
Plugin URI: https://github.com/getdave/wp-intervention/
Description: On demand image manipulation via the Intervention Library (by Oliver Vogel).
Author: David Smith
Version: 0.1
Author URI: http://www.aheadcreative.com
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Composer Autoloader
require_once __DIR__ . '/vendor/autoload.php';


// Plugin core Class (WP_Intervention)
require_once __DIR__ . '/src/plugin.php';

// Wrapper for Intervention Library
require_once __DIR__ . '/src/intervention-wrapper.php';

// Global helper functions
require_once __DIR__ . '/src/globals.php';

// Setup hooks
register_activation_hook(__FILE__, array( 'WP_Intervention', 'activated' ) );
register_deactivation_hook( __FILE__, array( 'WP_Intervention', 'deactivated' ) );

// Boot
$wp_intervention_plugin = new WP_Intervention(__FILE__);
add_action( 'wp_loaded', array( $wp_intervention_plugin, 'load' ) );

