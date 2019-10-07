<?php
/**
 * Wpccp
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

namespace TycheSoftwares\Wpccp;

/**
 * Bootstrap plugin
 */
final class Wpccp {
	/**
	 * Instance.
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @var Wpccp
	 */
	private static $instance;
	/**
	 * Singleton pattern.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	private function __construct() {
		$this->setup();
	}

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return Wpccp
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			self::$instance = new static();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object, therefore we don't want the object to be cloned.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		wpccp_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpccp' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		wpccp_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpccp' ), '1.0' );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function setup() {
		// Hooks.
		add_action( 'init', array( $this, 'load_textdomain' ), 0 );
		add_filter( 'plugin_action_links_' . WPCCP_PLUGIN_BASENAME, [ $this, 'settings_link' ] );

		// Modules.
		Protection::init();

		if ( is_admin() ) {
			Settings::init();
		}
	}

	/**
	 * Loads the plugin language files.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function load_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wpccp' );
		// wp-content/languages/plugin-name/plugin-name-en_EN.mo.
		load_textdomain( 'wpccp', trailingslashit( WP_LANG_DIR ) . 'wp-content-copy-protection/wp-content-copy-protection' . $locale . '.mo' );
		// wp-content/plugins/plugin-name/languages/plugin-name-en_EN.mo.
		load_plugin_textdomain( 'wpccp', false, basename( WPCCP_PLUGIN_DIR ) . '/languages/' );
	}

	/**
	 * Plugin action link
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $links action links array.
	 * @return array
	 */
	public function settings_link( $links ) {
		$text = sprintf( _x( 'Settings', 'WP Content Copy Protection Settings', 'wpccp' ) );
		$html = '<a href="%s">%s</a>';
		$link = sprintf( $html, admin_url( 'options-general.php?page=wpccp' ), $text );
		array_unshift( $links, $link );
		return $links;
	}
}
