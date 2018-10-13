<?php

/*
  Plugin Name: WP Content Copy Protection
  Plugin URI: https://www.tychesoftwares.com/premium-plugins/
  Description: WP Content Copy Protection prevents plagiarism and protects your valuable online content (such as source code, text and images) from being copied illegally. Copy methods are disabled via mouse and keyboard. See <a href="options-general.php?page=wpcp_options">Settings > WP Content Copy Protection</a> to learn more about WP Content Copy Protection - The complete content protection plugin for WordPress.
  Version: 1.1.8.7
  Text Domain: wpccpl
  Author: Tyche Softwares
  Author URI: https://www.tychesoftwares.com/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html/
 */

define ( 'wpccpl_version', '1.1.8.7' );
 
/*
  Original work: Copyright 2013-2015  Rynaldo Stoltz  (email: rcstoltz@gmail.com )
  Modified work: Copyright 2017  Vishal Kothari (email: vishal@tychesoftwares.com )

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

if(is_admin()) {
  add_action('admin_menu', 'constr_menu');
  add_action ('init', 'wpccl_add_all_component');

  add_filter( 'ts_deativate_plugin_questions', 'wpccpl_deactivate_add_questions' );
  add_filter( 'ts_tracker_data',               'wpccpl_ts_add_plugin_tracking_data' );
  add_filter( 'ts_tracker_opt_out_data',       'wpccpl_get_data_for_opt_out' );
  
  add_action( 'admin_init', 'wpccl_admin_actions' );
}

function wpccl_add_all_component () {
  
  	include_once ('includes/wpccpl-all-component.php');
	do_action ('wpccpl_activate');
}

function wpccpl_deactivate_add_questions ( $wpccpl_deactivate_questions ) {

  $wpccpl_deactivate_questions = array(
      0 => array(
          'id'                => 4, 
          'text'              => __( "Some keys does not blocked.", "wpccpl" ),
          'input_type'        => 'textfield',
          'input_placeholder' => 'Which keys?'
          ),
      1 => array(
          'id'                => 5,
          'text'              => __( "I need more keys need to be blocked.", "wpccpl" ),
          'input_type'        => 'textfield',
          'input_placeholder' => 'Which keys?'
      ), 
      2 =>  array(
          'id'                => 6,
          'text'              => __( "The plugin does not protect Audio and video files on my site.", "wpccpl" ),
          'input_type'        => '',
          'input_placeholder' => ''
      ),
      3 => array(
          'id'                => 7,
          'text'              => __( "The plugin is not compatible with my browser.", "wpccpl" ),
          'input_type'        => 'textfield',
          'input_placeholder' => 'Which browser?'
      )

  );
  return $wpccpl_deactivate_questions;
}
/**
 * Plugin's data to be tracked when Allow option is choosed.
 *
 * @hook ts_tracker_data
 *
 * @param array $data Contains the data to be tracked.
 *
 * @return array Plugin's data to track.
 * 
 */

function wpccpl_ts_add_plugin_tracking_data ( $data ) {
	if ( isset( $_GET[ 'wpccpl_tracker_optin' ] ) && isset( $_GET[ 'wpccpl_tracker_nonce' ] ) && wp_verify_nonce( $_GET[ 'wpccpl_tracker_nonce' ], 'wpccpl_tracker_optin' ) ) {

		$plugin_data[ 'ts_meta_data_table_name' ] = 'ts_tracking_wpccpl_meta_data';
		$plugin_data[ 'ts_plugin_name' ]		  = 'WP Content Copy Protection';
		/**
		 * Add Plugin data
		 */
		$plugin_data[ 'wpccpl_plugin_version' ]   = wpccpl_version;
		
		$plugin_data[ 'wpccpl_allow_tracking' ]   = get_option ( 'wpccpl_allow_tracking' );
		$data[ 'plugin_data' ]                    = $plugin_data;
	}
	return $data;
}
    
/**
 * Tracking data to send when No, thanks. button is clicked.
 *
 * @hook ts_tracker_opt_out_data
 *
 * @param array $params Parameters to pass for tracking data.
 *
 * @return array Data to track when opted out.
 * 
 */
function wpccpl_get_data_for_opt_out ( $params ) {
	$plugin_data[ 'ts_meta_data_table_name'] = 'ts_tracking_wpccpl_meta_data';
	$plugin_data[ 'ts_plugin_name' ]		 = 'WP Content Copy Protection';
	
	// Store count info
	$params[ 'plugin_data' ]  				 = $plugin_data;
	
	return $params;
}

function wpccl_admin_actions () {
  /**
   * We need to store the plugin version in DB, so we can show the welcome page and other contents.
   */
    $wpccpl_version_in_db = get_option( 'wpccpl_version' ); 
    if ( $wpccpl_version_in_db != wpccpl_version ){
        update_option( 'wpccpl_version', wpccpl_version );
    }
}

function constr_menu() {
	add_options_page('WP Content Copy Protection', 'WP Content Copy Protection', 'manage_options', 'wpcp_options', 'return_settings');
}

function return_settings() {
	require_once('settings.php');
}

function ccp_config_link($links) {
  $settings_link = '<a href="options-general.php?page=wpcp_options">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'ccp_config_link' );

function rate_wpccp_yoo ($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		$rate_url = 'http://wordpress.org/support/view/plugin-reviews/' . basename(dirname(__FILE__)) . '?rate=5#postform';
		$links[] = '<a href="' . $rate_url . '" target="_blank" title="Click here to rate and review this plugin on WordPress.org">Rate this plugin</a>';
	}
	return $links;
}

add_filter('plugin_row_meta', 'rate_wpccp_yoo', 10, 2);

function secure_uploads_dir() {
	$start_dir = wp_upload_dir();
	secure_copy_file($start_dir['basedir']);
}

function secure_copy_file($dir){
	$empty_file = realpath( dirname( __FILE__ ) ) . '/index.php';
	copy($empty_file, $dir . '/index.php');
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if ( is_dir($dir . '/' . $file) && $file!='.' && $file!='..' ) {
				secure_copy_file( $dir . '/' . $file );
			}
		}
		closedir($dh);
	}
}

register_activation_hook( __FILE__, 'secure_uploads_dir' );

add_action('wp_head', 'fwpcon_pro');
update_option('image_default_link_type','none');

function fwpcon_pro() {

?>

<!-- WP Content Copy Protection script by Rynaldo Stoltz Starts -->


<noscript>
    <div style="position:fixed; top:0px; left:0px; z-index:3000; height:100%; width:100%; background-color:#FFFFFF">
    <div style="font-family: Trebuchet MS; font-size: 14px; background-color:#FFF000; padding: 10pt;">Oops! It appears that you have disabled your Javascript. In order for you to see this page as it is meant to appear, we ask that you please re-enable your Javascript!</div></div>
</noscript>


<script type="text/javascript">
function disableSelection(e){if(typeof e.onselectstart!="undefined")e.onselectstart=function(){return false};else if(typeof e.style.MozUserSelect!="undefined")e.style.MozUserSelect="none";else e.onmousedown=function(){return false};e.style.cursor="default"}window.onload=function(){disableSelection(document.body)}
</script>

<script type="text/javascript">
document.oncontextmenu=function(e){var t=e||window.event;var n=t.target||t.srcElement;if(n.nodeName!="A")return false};
document.ondragstart=function(){return false};
</script>

<style type="text/css">
* : (input, textarea) {
	-webkit-touch-callout:none;
	-webkit-user-select:none;
}
</style>

<style type="text/css">
img {
	-webkit-touch-callout:none;
	-webkit-user-select:none;
}
</style>

<script type="text/javascript">
window.addEventListener("keydown",function(e){if(e.ctrlKey&&(e.which==65||e.which==66||e.which==67||e.which==70||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){e.preventDefault()}});document.keypress=function(e){if(e.ctrlKey&&(e.which==65||e.which==66||e.which==70||e.which==67||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){}return false}
/**
 * For mac we need to check metakey
 */
window.addEventListener("keydown",function(e){if( event.metaKey&&(e.which==65||e.which==66||e.which==67||e.which==70||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){e.preventDefault()}});document.keypress=function(e){if(e.ctrlKey&&(e.which==65||e.which==66||e.which==70||e.which==67||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){}return false}

</script>


<script type="text/javascript">
document.onkeydown=function(e){e=e||window.event;if(e.keyCode==123||e.keyCode==18){return false}}
</script>

<!-- WP Content Copy Protection script by Rynaldo Stoltz Ends  -->





























<?php } ?>
