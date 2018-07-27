<?php

/*
  Plugin Name: WP Content Copy Protection
  Plugin URI: https://www.tychesoftwares.com/premium-plugins/
  Description: WP Content Copy Protection prevents plagiarism and protects your valuable online content (such as source code, text and images) from being copied illegally. Copy methods are disabled via mouse and keyboard. See <a href="options-general.php?page=wpcp_options">Settings > WP Content Copy Protection</a> to learn more about WP Content Copy Protection - The complete content protection plugin for WordPress.
  Version: 1.1.8.4
  Author: Tyche Softwares
  Author URI: https://www.tychesoftwares.com/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html/
 */

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

<div align="center"><noscript>
   <div style="position:fixed; top:0px; left:0px; z-index:3000; height:100%; width:100%; background-color:#FFFFFF">
   <div style="font-family: Trebuchet MS; font-size: 14px; background-color:#FFF000; padding: 10pt;">Oops! It appears that you have disabled your Javascript. In order for you to see this page as it is meant to appear, we ask that you please re-enable your Javascript!</div></div>
   </noscript></div>

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
</script>

<script type="text/javascript">
document.onkeydown=function(e){e=e||window.event;if(e.keyCode==123||e.keyCode==18){return false}}
</script>

<!-- WP Content Copy Protection script by Rynaldo Stoltz Ends  -->





























<?php } ?>
