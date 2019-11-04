<?php
/**
 * Settings
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
 * Plugin settings.
 */
class Settings {
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
		add_action( 'admin_menu', [ __CLASS__, 'menu' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'register_assets' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
		add_action( 'wp_ajax_wpccp_getposts', [ __CLASS__, 'get_posts' ] );

		// Set data.
		self::$options = get_option( 'wpccp' );
	}

	/**
	 * Add menu page for settings tab URL.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function menu() {
		add_options_page(
			__( 'WP Content Copy Protection', 'wpccp' ),
			__( 'WP Content Copy Protection', 'wpccp' ),
			'manage_options',
			'wpccp',
			[ __CLASS__, 'page' ]
		);
	}

	/**
	 * Render settings page.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function page() {
		include_once 'views/settings.php';
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
		// Bailout if not on plugin page(s).
		$screen       = get_current_screen();
		$plugin_pages = [ 'settings_page_wpccp' ];
		if ( ! in_array( $screen->id, $plugin_pages, true ) ) {
			return;
		}

		// Load minified assets if SCRIPT_DEBUG is turned off.
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Scripts.
		wp_register_script(
			'wpccp-select2',
			WPCCP_PLUGIN_URL . 'assets/js/select2' . $suffix . '.js',
			[ 'jquery' ],
			filemtime( WPCCP_PLUGIN_DIR . 'assets/js/select2' . $suffix . '.js' ),
			true
		);
		wp_enqueue_script( 'wpccp-select2' );

		wp_register_script(
			'wpccp-admin',
			WPCCP_PLUGIN_URL . 'assets/js/admin' . $suffix . '.js',
			[ 'jquery', 'wpccp-select2' ],
			filemtime( WPCCP_PLUGIN_DIR . 'assets/js/admin' . $suffix . '.js' ),
			true
		);
		wp_enqueue_script( 'wpccp-admin' );

		// Inline scripts.
		$wpccp_nonce = wp_create_nonce( 'wpccp_ajax' );
		wp_add_inline_script( 'wpccp-admin', sprintf( 'const wpccpNonce=\'%s\'', $wpccp_nonce ), 'before' );

		// Styles.
		wp_register_style(
			'wpccp-select2',
			WPCCP_PLUGIN_URL . 'assets/css/select2' . $suffix . '.css',
			[],
			filemtime( WPCCP_PLUGIN_DIR . 'assets/css/select2' . $suffix . '.css' )
		);
		wp_enqueue_style( 'wpccp-select2' );

		wp_register_style(
			'wpccp-admin',
			WPCCP_PLUGIN_URL . 'assets/css/admin' . $suffix . '.css',
			[ 'wpccp-select2' ],
			filemtime( WPCCP_PLUGIN_DIR . 'assets/css/admin' . $suffix . '.css' )
		);
		wp_enqueue_style( 'wpccp-admin' );
	}

	/**
	 * Register options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function register_settings() {
		// Register setting.
		register_setting( 'wpccp', 'wpccp' );

		// Register section.
		add_settings_section(
			'wpccp_exclude_settings',
			__( 'Disable Protection', 'wpccp' ),
			[ __CLASS__, 'render_exclude_section' ],
			'wpccp'
		);

		// Register section.
		add_settings_section(
			'wpccp_pro_settings',
			__( 'More Settings', 'wpccp' ),
			[ __CLASS__, 'render_pro_section' ],
			'wpccp'
		);

		// Register fields.
		// Field: Exclude Pages.
		add_settings_field(
			'protection_message',
			__( 'Message', 'wpccp' ),
			[ __CLASS__, 'render_message_field' ],
			'wpccp',
			'wpccp_exclude_settings'
		);

		// Field: Exclude Pages.
		add_settings_field(
			'exclude_pages',
			__( 'On Page(s)', 'wpccp' ),
			[ __CLASS__, 'render_pages_field' ],
			'wpccp',
			'wpccp_exclude_settings'
		);

		// Field: Exclude Posts.
		add_settings_field(
			'exclude_posts',
			__( 'On Post(s)', 'wpccp' ),
			[ __CLASS__, 'render_posts_field' ],
			'wpccp',
			'wpccp_exclude_settings'
		);

		// Field: Exclude PostTypes.
		add_settings_field(
			'exclude_post_types',
			__( 'On Custom PostType(s)', 'wpccp' ),
			[ __CLASS__, 'render_post_types_field' ],
			'wpccp',
			'wpccp_pro_settings'
		);

		// Field: Exclude Categories.
		add_settings_field(
			'exclude_categories',
			__( 'On Categories', 'wpccp' ),
			[ __CLASS__, 'render_categories_field' ],
			'wpccp',
			'wpccp_exclude_settings'
		);

		// Field: Exclude Roles.
		add_settings_field(
			'exclude_roles',
			__( 'For Roles', 'wpccp' ),
			[ __CLASS__, 'render_roles_field' ],
			'wpccp',
			'wpccp_pro_settings'
		);

		// Field: Exclude Registered.
		add_settings_field(
			'exclude_registered',
			__( 'For Registered User', 'wpccp' ),
			[ __CLASS__, 'render_registered_field' ],
			'wpccp',
			'wpccp_exclude_settings'
		);

		// Field: Exclude Admin.
		add_settings_field(
			'exclude_admin',
			__( 'For Admin User', 'wpccp' ),
			[ __CLASS__, 'render_admin_field' ],
			'wpccp',
			'wpccp_exclude_settings'
		);

		// Field: Exclude Paste (Ctrl + V).
		add_settings_field(
			'exclude_paste',
			__( 'On Paste', 'wpccp' ),
			[ __CLASS__, 'render_paste_field' ],
			'wpccp',
			'wpccp_exclude_settings'
		);

		// Field: Exclude Print Screen.
		add_settings_field(
			'exclude_print_screen',
			__( 'On Print Screen', 'wpccp' ),
			[ __CLASS__, 'render_print_screen_field' ],
			'wpccp',
			'wpccp_pro_settings'
		);

		// Field: Exclude Links.
		add_settings_field(
			'exclude_links',
			__( 'On Links', 'wpccp' ),
			[ __CLASS__, 'render_links_field' ],
			'wpccp',
			'wpccp_pro_settings'
		);

		// Field: Exclude Feed.
		add_settings_field(
			'protect_feed',
			__( 'On Feed', 'wpccp' ),
			[ __CLASS__, 'render_feed_field' ],
			'wpccp',
			'wpccp_pro_settings'
		);
	}

	/**
	 * Render Section: General Settings
	 *
	 * @return void
	 */
	public static function render_exclude_section() {
		esc_html_e( 'Fine tune protection by excluding content and actions based on conditions below', 'wpccp' );
	}

	/**
	 * Render Section: Pro Settings
	 *
	 * @return void
	 */
	public static function render_pro_section() {
		esc_html_e( 'Get fine control with more settings avilable in pro', 'wpccp' );
		include_once 'views/upgrade.php';
	}

	/**
	 * Render Field: Protection Message
	 */
	public static function render_message_field() {
		$message = ! empty( self::$options['protection_message'] ) ? self::$options['protection_message'] : '';
		?>
		<input id="wpccp_message" class="regular-text" type='text' name='wpccp[protection_message]' value='<?php echo esc_attr( $message ); ?>'>
		<p class="description">
			<?php esc_html_e( 'shown on copy or right click action', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude Pages
	 */
	public static function render_pages_field() {
		?>
		<select id="wpccp_exclude_pages" data-placeholder="<?php esc_attr_e( 'Select Page(s)', 'wpccp' ); ?>" class="regular-text" multiple name='wpccp[exclude_pages][]'>
			<?php
			if ( ! empty( self::$options['exclude_pages'] ) ) {
				$pages = self::$options['exclude_pages'];
				foreach ( $pages as $page ) {
					$title = get_the_title( $page );
					$title = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title;
					?>
					<option value="<?php echo esc_attr( $page ); ?>" <?php selected( true, in_array( $page, $pages, true ) ); ?>><?php echo esc_html( $title ); ?></option>
					<?php
				}
			}
			?>
		</select>
		<p class="description">
			<?php esc_html_e( 'disable protection on selected pages', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude Posts
	 */
	public static function render_posts_field() {
		?>
		<select id="wpccp_exclude_posts" data-placeholder="<?php esc_attr_e( 'Select Post(s)', 'wpccp' ); ?>" class="regular-text" multiple name='wpccp[exclude_posts][]'>
			<?php
			if ( ! empty( self::$options['exclude_posts'] ) ) {
				$posts = self::$options['exclude_posts'];

				foreach ( $posts as $post ) {
					$title = get_the_title( $post );
					$title = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title;
					?>
					<option value="<?php echo esc_attr( $post ); ?>" <?php selected( true, in_array( $post, $posts, true ) ); ?>><?php echo esc_html( $title ); ?></option>
					<?php
				}
			}
			?>
		</select>
		<p class="description">
			<?php esc_html_e( 'disable protection on selected posts', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude PostTypes
	 */
	public static function render_post_types_field() {
		// Get PostTypes.
		$custom_type_args = [
			'public'   => true,
			'_builtin' => false,
		];

		$custom_post_types = get_post_types( $custom_type_args, 'names', 'and' );

		// Get Saved Settings.
		$post_types = ! empty( self::$options['exclude_post_types'] ) ? self::$options['exclude_post_types'] : [];

		// Render Field.
		if ( empty( $custom_post_types ) ) {
			echo esc_html_e( 'No custom posttype(s) found', 'wpccp' );
			return;
		}
		?>
		<select disabled id="wpccp_exclude_posttypes" data-placeholder="<?php esc_attr_e( 'Select PostType(s)', 'wpccp' ); ?>" class="regular-text" multiple name='wpccp[exclude_post_types][]'>
			<option value="all"><?php esc_html_e( 'All', 'wpccp' ); ?></option>
			<?php foreach ( $custom_post_types as $posttype ) { ?>
				<option value="<?php echo esc_attr( $posttype ); ?>" <?php selected( true, in_array( $posttype, $post_types, true ) ); ?>><?php echo esc_html( $posttype ); ?></option>
			<?php } ?>
		</select>
		<p class="description">
			<?php esc_html_e( 'disable protection on selected custom posttypes', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude Categories
	 */
	public static function render_categories_field() {
		$all_categories = get_categories( [ 'hide_empty' => false ] );
		$categories     = ! empty( self::$options['exclude_categories'] ) ? self::$options['exclude_categories'] : [];
		?>
		<select id="wpccp_exclude_categories" data-placeholder="<?php esc_attr_e( 'Select Categories', 'wpccp' ); ?>" class="regular-text" multiple name='wpccp[exclude_categories][]'>
			<?php foreach ( $all_categories as $category ) { ?>
				<option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( true, in_array( (string) $category->term_id, $categories, true ) ); ?>><?php echo esc_html( $category->name ); ?></option>
			<?php } ?>
		</select>
		<p class="description">
			<?php esc_html_e( 'disable protection on selected categories', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude Roles
	 */
	public static function render_roles_field() {
		$all_roles = get_editable_roles();
		$roles     = ! empty( self::$options['exclude_roles'] ) ? self::$options['exclude_roles'] : [];
		?>
		<select disabled id="wpccp_exclude_roles" data-placeholder="<?php esc_attr_e( 'Select Role(s)', 'wpccp' ); ?>" class="regular-text" multiple name='wpccp[exclude_roles][]'>
			<option value="all"><?php esc_html_e( 'All', 'wpccp' ); ?></option>
			<?php foreach ( $all_roles as $role => $role_info ) { ?>
				<option value="<?php echo esc_attr( $role ); ?>" <?php selected( true, in_array( $role, $roles, true ) ); ?>><?php echo esc_html( $role_info['name'] ); ?></option>
			<?php } ?>
		</select>
		<p class="description">
			<?php esc_html_e( 'disable protection for selected roles', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude Registered
	 */
	public static function render_registered_field() {
		$registered = ! empty( self::$options['exclude_registered'] ) ? self::$options['exclude_registered'] : '';
		?>
		<input id="wpccp_exclude_registered" type='checkbox' name='wpccp[exclude_registered]' <?php checked( $registered, 'on' ); ?>>
		<p class="description">
			<?php esc_html_e( 'disable protection for registered users', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude Admin
	 */
	public static function render_admin_field() {
		$admin = ! empty( self::$options['exclude_admin'] ) ? self::$options['exclude_admin'] : '';
		?>
		<input id="wpccp_exclude_admin" type='checkbox' name='wpccp[exclude_admin]' <?php checked( $admin, 'on' ); ?>>
		<p class="description">
			<?php esc_html_e( 'disable protection for users with admin role', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude Paste ( Ctrl + V )
	 */
	public static function render_paste_field() {
		$paste = ! empty( self::$options['exclude_paste'] ) ? self::$options['exclude_paste'] : '';
		?>
		<input id="wpccp_exclude_paste" type='checkbox' name='wpccp[exclude_paste]' <?php checked( $paste, 'on' ); ?>>
		<p class="description">
			<?php esc_html_e( 'allow data to be pasted using shortcuts (helpful for pasting copied data into input fields)', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude Print Screen
	 */
	public static function render_print_screen_field() {
		$print_screen = ! empty( self::$options['exclude_print_screen'] ) ? self::$options['exclude_print_screen'] : '';
		?>
		<input disabled id="wpccp_exclude_print_screen" type='checkbox' name='wpccp[exclude_print_screen]' <?php checked( $print_screen, 'on' ); ?>>
		<p class="description">
			<?php esc_html_e( 'allow print screen', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Exclude Links
	 */
	public static function render_links_field() {
		$links = ! empty( self::$options['exclude_links'] ) ? self::$options['exclude_links'] : '';
		?>
		<input disabled id="wpccp_exclude_links" type='checkbox' name='wpccp[exclude_links]' <?php checked( $links, 'on' ); ?>>
		<p class="description">
			<?php esc_html_e( 'allow content menu on links', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Render Field: Protect Feed
	 */
	public static function render_feed_field() {
		$feed = ! empty( self::$options['protect_feed'] ) ? self::$options['protect_feed'] : '';
		?>
		<input disabled id="wpccp_protect_feed" type='checkbox' name='wpccp[protect_feed]' <?php checked( $feed, 'on' ); ?>>
		<p class="description">
			<?php esc_html_e( 'disable protection on feeds', 'wpccp' ); ?>
		</p>
		<?php
	}

	/**
	 * Get Post Callback for select2 ajax
	 *
	 * @return void
	 */
	public static function get_posts() {
		check_ajax_referer( 'wpccp_ajax' );

		$search_q    = ! empty( $_GET['wpccp_q'] ) ? sanitize_text_field( wp_unslash( $_GET['wpccp_q'] ) ) : '';
		$search_type = ! empty( $_GET['wpccp_type'] ) ? sanitize_text_field( wp_unslash( $_GET['wpccp_type'] ) ) : 'post';

		$posts = [];

		$search_args = [
			's'                   => $search_q,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => 10,
			'post_type'           => $search_type,
		];

		$search_results = new \WP_Query( $search_args );

		if ( $search_results->have_posts() ) {
			while ( $search_results->have_posts() ) {
				$search_results->the_post();
				$title = $search_results->post->post_title;

				if ( mb_strlen( $title ) > 50 ) {
					$title = mb_substr( $search_results->post->post_title, 0, 49 ) . '...';
				}

				$posts[] = array( $search_results->post->ID, $title );
			}
		}

		echo wp_json_encode( $posts );
		wp_die();
	}
}
