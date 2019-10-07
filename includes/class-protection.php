<?php
/**
 * Protection
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
 * Render frontend protection scripts.
 */
class Protection {
	/**
	 * Options data
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @var array
	 */
	private static $options;

	/**
	 * Init plugin settings.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function init() {
		// Hooks.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'register_assets' ] );
		register_activation_hook( WPCCP_PLUGIN_FILE, [ __CLASS__, 'on_activation' ] );
		add_action( 'wp', [ __CLASS__, 'add_source_padding' ] );
		add_action( 'wp_head', [ __CLASS__, 'render_noscript' ] );

		// Set data.
		self::$options = get_option( 'wpccp' );
	}

	/**
	 * Register assets (css/js)
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function register_assets() {
		// Load minified assets if SCRIPT_DEBUG is turned off.
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Scripts.
		wp_register_script(
			'wpccp',
			WPCCP_PLUGIN_URL . 'assets/js/script' . $suffix . '.js',
			[ 'jquery' ],
			filemtime( WPCCP_PLUGIN_DIR . 'assets/js/script' . $suffix . '.js' ),
			true
		);

		// Inline scripts.
		$wpccp_message      = ! empty( self::$options['protection_message'] ) ? self::$options['protection_message'] : '';
		$wpccp_paste        = ! empty( self::$options['exclude_paste'] ) ? true : false;
		$wpccp_plugin_url   = WPCCP_PLUGIN_URL;

		$inline_code   = [];
		$inline_code[] = "wpccpMessage = '${wpccp_message}'";
		$inline_code[] = "wpccpPaste = '${wpccp_paste}'";
		$inline_code[] = "wpccpUrl = '${wpccp_plugin_url}'";

		$inline_script = sprintf( 'const %s;', implode( ',', $inline_code ) );

		wp_add_inline_script( 'wpccp', $inline_script, 'before' );

		// Styles.
		wp_register_style(
			'wpccp',
			WPCCP_PLUGIN_URL . 'assets/css/style' . $suffix . '.css',
			[],
			filemtime( WPCCP_PLUGIN_DIR . 'assets/css/style' . $suffix . '.css' )
		);

		// Load assets.
		if ( self::apply_protection() ) {
			wp_enqueue_script( 'wpccp' );
			wp_enqueue_style( 'wpccp' );
		}
	}

	/**
	 * Run activation routine.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function on_activation() {
		self::secure_uploads(); // Secure uploads & subfolders.
		update_option( 'image_default_link_type', 'none' ); // Secure image links.
	}


	/**
	 * Secure uploads dir
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function secure_uploads() {
		$start_dir = wp_upload_dir();
		self::secure_dir( $start_dir['basedir'] );
	}

	/**
	 * Copy index file to uploads and subfolders for protection against folder listing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $dir directory to secure.
	 * @return void
	 */
	public static function secure_dir( $dir ) {
		$ignore          = [ '.', '..' ]; // folders to ignore.
		$protection_file = wp_normalize_path( path_join( WPCCP_PLUGIN_DIR, 'index.php' ) ); // path to protection file.
		copy( $protection_file, wp_normalize_path( path_join( $dir, 'index.php' ) ) ); // Copy file to uploads root.

		$sub_folders = opendir( $dir ); // Open dir.

		// Secure subfolder(s) if found.
		if ( $sub_folders ) {
			// phpcs:ignore
			while ( false !== ( $sub_folder = readdir( $sub_folders ) ) ) {
				$current_item = wp_normalize_path( path_join( $dir, $sub_folder ) ); // Filepath for subfolder.

				if ( is_dir( $current_item ) && ! in_array( $sub_folder, $ignore, true ) ) {
					self::secure_dir( wp_normalize_path( path_join( $dir, $sub_folder ) ) );
				}
			}
			closedir( $sub_folders );
		}
	}

	/**
	 * Add padding to html source
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function add_source_padding() {
		ob_start();
		$break = "\n";
		for ( $i = 0; $i <= 499; $i++ ) {
			echo esc_html( $break );
		}
	}

	/**
	 * Exclude pages
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function exclude_pages() {
		$excluded_pages = ! empty( self::$options['exclude_pages'] ) ? self::$options['exclude_pages'] : [];

		if ( $excluded_pages && get_the_ID() ) {
			// Pages.
			$page_id          = (string) get_the_ID();
			$is_excluded_page = in_array( $page_id, $excluded_pages, true ) && is_page( $page_id );

			// WooCommerce Pages - Needs extra checks.
			$woocommerce_shop_page = get_option( 'woocommerce_shop_page_id' );
			$is_excluded_shop_page = is_shop() && in_array( $woocommerce_shop_page, $excluded_pages, true );

			if ( $is_excluded_page || $is_excluded_shop_page ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Exclude posts
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function exclude_posts() {
		$excluded_posts = ! empty( self::$options['exclude_posts'] ) ? self::$options['exclude_posts'] : [];

		if ( $excluded_posts && get_the_ID() ) {
			$post_id = (string) get_the_ID();
			if ( in_array( $post_id, $excluded_posts, true ) && is_single( $post_id ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Exclude categories
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function exclude_categories() {
		$excluded_categories = ! empty( self::$options['exclude_categories'] ) ? self::$options['exclude_categories'] : [];
		$post_categories     = wp_get_post_categories( get_the_ID() );

		if ( ! empty( $excluded_categories ) && is_single() && $post_categories ) {
			if ( count( array_intersect( $excluded_categories, $post_categories ) ) > 0 ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Exclude admin
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function exclude_admin() {
		$exclude_admin = ! empty( self::$options['exclude_admin'] ) ? self::$options['exclude_admin'] : '';
		$user          = wp_get_current_user();

		if ( $exclude_admin && $user && in_array( 'administrator', $user->roles, true ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Exclude registered user
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function exclude_registered() {
		$exclude_registered = ! empty( self::$options['exclude_registered'] ) ? self::$options['exclude_registered'] : '';

		if ( $exclude_registered && is_user_logged_in() ) {
				return true;
		}
		return false;
	}

	/**
	 * Check content protection
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function apply_protection() {
		if (
			self::exclude_pages() ||
			self::exclude_posts() ||
			self::exclude_categories() ||
			self::exclude_registered() ||
			self::exclude_admin()
		) {
			return false;
		}

		return true;
	}

	/**
	 * Render noscript content
	 *
	 * @return void
	 */
	public static function render_noscript() {
		include_once 'views/no-script.php';
	}
}
