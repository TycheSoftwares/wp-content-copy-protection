<?php
/**
 * Plugin Name: WP Content Copy Protection (Lite)
 * Plugin URI: https://www.tychesoftwares.com/premium-plugins/
 * Description: WP Content Copy Protection prevents plagiarism and protects your valuable online content (such as source code, text and images) from being copied illegally. Copy methods are disabled via mouse and keyboard.
 * Author: Tyche Softwares
 * Author URI: https://www.tychesoftwares.com/
 * Version: 2.0.6
 * Text Domain: wpccp
 * Domain Path: /languages
 * Tags: content, protection,
 * Requires at least: 3.0.1
 * Tested up to:  7.8
 * Stable tag: 2.0.6
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html/
 *
 * @package wpccp
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Setup Constants
 */
// Plugin version.
if ( ! defined( 'WPCCP_VERSION' ) ) {
	define( 'WPCCP_VERSION', '2.0.6' );
}
// Plugin Root File.
if ( ! defined( 'WPCCP_PLUGIN_FILE' ) ) {
	define( 'WPCCP_PLUGIN_FILE', __FILE__ );
}
// Plugin Folder Path.
if ( ! defined( 'WPCCP_PLUGIN_DIR' ) ) {
	define( 'WPCCP_PLUGIN_DIR', plugin_dir_path( WPCCP_PLUGIN_FILE ) );
}
// Plugin Folder URL.
if ( ! defined( 'WPCCP_PLUGIN_URL' ) ) {
	define( 'WPCCP_PLUGIN_URL', plugin_dir_url( WPCCP_PLUGIN_FILE ) );
}
// Plugin Basename aka: "wp-content-copy-protection-pro/wp-content-copy-protection-pro.php".
if ( ! defined( 'WPCCP_PLUGIN_BASENAME' ) ) {
	define( 'WPCCP_PLUGIN_BASENAME', plugin_basename( WPCCP_PLUGIN_FILE ) );
}
// Autoloader.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
} else {
	require 'vendor/autoload.php';
}

// Bootstrap WPCCP.
use TycheSoftwares\Wpccp\Wpccp;

/**
 * Main instance of Wpccp.
 *
 * Returns the main instance of Wpccp to prevent the need to use globals.
 *
 * @since 2.0.0
 * @return Wpccp
 */
function wpccp() {
	return Wpccp::get_instance();
}
wpccp();
