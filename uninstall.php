<?php
/**
 * Uninstall plugin
 *
 * @author  Tyche Softwares
 * @license MIT
 *
 * @see   https://www.tychesoftwares.com/premium-plugins/
 *
 * @since 2.0.0
 * @copyright TycheSoftwares
 * @package   wpccp
 */

// if uninstall.php is not called by WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Delete all wpccp options.
$all_options = wp_load_alloptions();

foreach ( $all_options as $name => $value ) {
	if ( stristr( $name, 'wpccp_' ) ) {
		delete_option( $name );
	}
}
