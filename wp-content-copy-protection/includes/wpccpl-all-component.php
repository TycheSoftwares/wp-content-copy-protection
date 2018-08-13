<?php
/**
 * It will Add all the Boilerplate component when we activate the plugin.
 * @author  Tyche Softwares
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if ( ! class_exists( 'WPCCPL_Component' ) ) {
	/**
	 * It will Add all the Boilerplate component when we activate the plugin.
	 * 
	 */
	class WPCCPL_Component {
	    
		/**
		 * It will Add all the Boilerplate component when we activate the plugin.
		 */
		public function __construct() {
            
			$is_admin = is_admin();

			if ( true === $is_admin ) {

                require_once( "component/tracking-data/ts-tracking.php" );
                require_once( "component/deactivate-survey-popup/class-ts-deactivation.php" );

                require_once( "component/welcome-page/ts-welcome.php" );
                require_once( "component/faq-support/ts-faq-support.php" );
                require_once( "component/pro-notices-in-lite/ts-pro-notices.php" );
                
                $wpccpl_plugin_name          = self::ts_get_plugin_name();;
                $wpccpl_locale               = self::ts_get_plugin_locale();

                $wpccpl_file_name            = 'wp-content-copy-protection/wpccpl.php';
                $wpccpl_plugin_prefix        = 'wpccpl';
                $wpccpl_lite_plugin_prefix   = 'wpccpl';
                $wpccpl_plugin_folder_name   = 'wp-content-copy-protection/';
                $wpccpl_plugin_dir_name      = dirname ( untrailingslashit( plugin_dir_path ( __FILE__ ) ) ) . '/wpccpl.php' ;
                $wpccpl_plugin_url           = dirname ( untrailingslashit( plugins_url( '/', __FILE__ ) ) );

                $wpccpl_get_previous_version = get_option( 'wpccpl_version', '1' );

                $wpccpl_blog_post_link       = 'https://www.tychesoftwares.com/docs/docs/wp-content-copy-protection/usage-tracking/';

                $wpccpl_plugins_page         = '';
                $wpccpl_plugin_slug          = '';
                $wpccpl_pro_file_name        = '';

                $wpccpl_settings_page        = '';

                new WPCCPL_TS_tracking ( $wpccpl_plugin_prefix, $wpccpl_plugin_name, $wpccpl_blog_post_link, $wpccpl_locale, $wpccpl_plugin_url, $wpccpl_settings_page, '', '', '', $wpccpl_file_name );

                new WPCCPL_TS_Tracker ( $wpccpl_plugin_prefix, $wpccpl_plugin_name );

                $wpccpl_deativate = new WPCCPL_TS_deactivate;
                $wpccpl_deativate->init ( $wpccpl_file_name, $wpccpl_plugin_name );
                
                // $user = wp_get_current_user();
                
                // if ( in_array( 'administrator', (array) $user->roles ) ) {
                //     new WPCCPL_TS_Welcome ( $wpccpl_plugin_name, $wpccpl_plugin_prefix, $wpccpl_locale, $wpccpl_plugin_folder_name, $wpccpl_plugin_dir_name, $wpccpl_get_previous_version );
                // }

                $ts_pro_wpccpl = self::wpccpl_get_faq ();
                new WPCCPL_TS_Faq_Support( $wpccpl_plugin_name, $wpccpl_plugin_prefix, $wpccpl_plugins_page, $wpccpl_locale, $wpccpl_plugin_folder_name, $wpccpl_plugin_slug, $ts_pro_wpccpl, '', $wpccpl_file_name );

                if ( in_array('woocommerce/woocommerce.php', get_option('active_plugins') ) ) {
                    $ts_pro_notices = self::wpccpl_get_notice_text ();
                    new WPCCPL_ts_pro_notices( $wpccpl_plugin_name, $wpccpl_lite_plugin_prefix, $wpccpl_plugin_prefix, $ts_pro_notices, $wpccpl_file_name, $wpccpl_pro_file_name );
                }

            }
        }

         /**
         * It will retrun the plguin name.
         * @return string $ts_plugin_name Name of the plugin
         */
		public static function ts_get_plugin_name () {
            $ordd_plugin_dir = dirname ( dirname ( __FILE__ ) ) ;
            $ordd_plugin_dir .= '/wpccpl.php';
           
            $ts_plugin_name = '';
            $plugin_data = get_file_data( $ordd_plugin_dir, array( 'name' => 'Plugin Name' ) );
            if ( ! empty( $plugin_data['name'] ) ) {
                $ts_plugin_name = $plugin_data[ 'name' ];
            }
            return $ts_plugin_name;
        }

        /**
         * It will retrun the Plugin text Domain
         * @return string $ts_plugin_domain Name of the Plugin domain
         */
        public static function ts_get_plugin_locale () {
            $ordd_plugin_dir =  dirname( dirname ( __FILE__ ) ) ;
            $ordd_plugin_dir .= '/wpccpl.php';

            $ts_plugin_domain = '';
            $plugin_data = get_file_data( $ordd_plugin_dir, array( 'domain' => 'Text Domain' ) );
            if ( ! empty( $plugin_data['domain'] ) ) {
                $ts_plugin_domain = $plugin_data[ 'domain' ];
            }
            return $ts_plugin_domain;
        }
        
        /**
         * It will Display the notices in the admin dashboard for the pro vesion of the plugin.
         * @return array $ts_pro_notices All text of the notices
         */
        public static function wpccpl_get_notice_text () {
            $ts_pro_notices = array();

            $wpccpl_locale               = self::ts_get_plugin_locale();

            $message_first = wp_kses_post ( __( 'Thank you for using WooCommerce Print Invoice & Delivery Note plugin! Now make your deliveries more accurate by allowing customers to select their preferred delivery date & time from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Get it now!</a></strong>', $wpccpl_locale ) );  

            $message_two = wp_kses_post ( __( 'Never login to your admin to check your deliveries by syncing the delivery dates to the Google Calendar from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Get it now!</a></strong>', $wpccpl_locale ) );

            $message_three = wp_kses_post ( __( 'You can now view all your deliveries in list view or in calendar view from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Get it now!</a></strong>.', $wpccpl_locale ) );

            $message_four = wp_kses_post ( __( 'Allow your customers to pay extra for delivery for certain Weekdays/Dates from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Have it now!</a></strong>.', $wpccpl_locale ) );

            $message_five = wp_kses_post ( __( 'Customers can now edit the Delivery date & time on cart and checkout page or they can reschedule the deliveries for the already placed orders from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Have it now!</a></strong>.', $wpccpl_locale ) );

		// message six
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpnotice&utm_medium=sixth&utm_campaign=WpContentCopyPlugin';
            $message_six = wp_kses_post ( __( 'Boost your sales by recovering up to 60% of the abandoned carts with our Abandoned Cart Pro for WooCommerce plugin. You can capture customer email addresses right when they click the Add To Cart button. <strong><a target="_blank" href= "'.$_link.'">Grab your copy of Abandon Cart Pro plugin now</a></strong>.', $wpccpl_locale ) );
            
            $wpccpl_message_six = array ( 'message' => $message_six, 'plugin_link' => 'woocommerce-abandon-cart-pro/woocommerce-ac.php' );
		// message seven
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpnotice&utm_medium=seventh&utm_campaign=WpContentCopyPlugin';
            $message_seven = wp_kses_post ( __( 'Don\'t loose your sales to abandoned carts. Use our Abandon Cart Pro plugin & start recovering your lost sales in less then 60 seconds.<br> 
            <strong><a target="_blank" href= "'.$_link.'">Get it now!</a></strong>', $wpccpl_locale ) );
            $wpccpl_message_seven = array ( 'message' => $message_seven, 'plugin_link' => 'woocommerce-abandon-cart-pro/woocommerce-ac.php' );
        
        // message eight
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpnotice&utm_medium=eight&utm_campaign=WpContentCopyPlugin';
            $message_eight = wp_kses_post ( __( 'Send Abandoned Cart reminders that actually convert. Take advantage of our fully responsive email templates designed specially with an intent to trigger conversion. <br><strong><a target="_blank" href= "'.$_link.'">Grab your copy now!</a></strong>', $wpccpl_locale ) );
            $wpccpl_message_eight = array ( 'message' => $message_eight, 'plugin_link' => 'woocommerce-abandon-cart-pro/woocommerce-ac.php' );

		// message nine
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpnotice&utm_medium=ninth&utm_campaign=WpContentCopyPlugin';
            $message_nine = wp_kses_post ( __( 'Increase your store sales by recovering your abandoned carts for just $119. No profit sharing, no monthly fees. Our Abandoned Cart Pro plugin comes with a 30 day money back guarantee as well. :). Use coupon code ACPRO20 & save $24!<br>
            <strong><a target="_blank" href= "'.$_link.'">Purchase now</a></strong>', $wpccpl_locale ) );
            $wpccpl_message_nine = array ( 'message' => $message_nine, 'plugin_link' => 'woocommerce-abandon-cart-pro/woocommerce-ac.php' );
            
		// message ten  
	        $_link = 'https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/?utm_source=wpnotice&utm_medium=tenth&utm_campaign=WpContentCopyPlugin';
            $message_ten = wp_kses_post ( __( 'Allow your customers to select the Delivery Date & Time on the Checkout Page using our Order Delivery Date Pro for WooCommerce Plugin. <br> 
            <strong><a target="_blank" href= "'.$_link.'">Shop now</a></strong> & be one of the 20 customers to get 20% discount on the plugin price. Use the code "ORDPRO20". Hurry!!', $wpccpl_locale ) );
            $wpccpl_message_ten = array ( 'message' => $message_ten, 'plugin_link' => 'order-delivery-date/order_delivery_date.php' );

		// message eleven
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-booking-plugin/?utm_source=wpnotice&utm_medium=eleven&utm_campaign=WpContentCopyPlugin';
            $message_eleven = wp_kses_post ( __( ' Allow your customers to book an appointment or rent an apartment with our Booking and Appointment for WooCommerce plugin. You can also sell your product as a resource or integrate with a few Vendor plugins. <br>Shop now & Save 20% on the plugin with the code "BKAP20". Only for first 20 customers. <strong><a target="_blank" href= "'.$_link.'">Have it now!</a></strong>', $wpccpl_locale ) );
            $wpccpl_message_eleven = array ( 'message' => $message_eleven, 'plugin_link' => 'woocommerce-booking/woocommerce-booking.php' );

		// message 12
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/deposits-for-woocommerce/?utm_source=wpnotice&utm_medium=twelve&utm_campaign=WpContentCopyPlugin';
            $message_twelve = wp_kses_post ( __( ' Allow your customers to pay deposits on products using our Deposits for WooCommerce plugin.<br>
            <strong><a target="_blank" href= "'.$_link.'">Purchase now</a></strong> & Grab 20% discount with the code "DFWP20". The discount code is valid only for the first 20 customers.', $wpccpl_locale ) );
            $wpccpl_message_twelve = array ( 'message' => $message_twelve, 'plugin_link' => 'woocommerce-deposits/deposits-for-woocommerce.php' );

		// message 13 
	        $_link = 'https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wpnotice&utm_medium=thirteen&utm_campaign=WpContentCopyPlugin';
            $message_thirteen = wp_kses_post ( __( 'Allow your customers to select the Delivery Date & Time for your WooCommerce products using our Product Delivery Date Pro for WooCommerce Plugin. <br> 
            <strong><a target="_blank" href= "'.$_link.'">Shop now</a></strong>', $wpccpl_locale ) );
            $wpccpl_message_thirteen = array ( 'message' => $message_thirteen, 'plugin_link' => 'product-delivery-date/product-delivery-date.php' );

            $ts_pro_notices = array (
                1 => $message_first,
                2 => $message_two,
                3 => $message_three,
                4 => $message_four,
                5 => $message_five,
                6 => $wpccpl_message_six,
                7 => $wpccpl_message_seven,
                8 => $wpccpl_message_eight,
                9 => $wpccpl_message_nine,
                10 => $wpccpl_message_ten,
                11 => $wpccpl_message_eleven,
                12 => $wpccpl_message_twelve,
                13 => $wpccpl_message_thirteen
            );

            return $ts_pro_notices;
        }
		
		/**
         * It will contain all the shortcodes which need to be display on the shortcodes page.
         * @return array $ts_shortcodes All questions and answers.
         * 
         */
        public static function wpccpl_get_faq() {

            $ts_wpccpl = array ();

            $ts_wpccpl = array(
                1 => array (
                        'question' => 'Will WP Content Copy Protection Plugin have a negative impact on my SEO?',
                        'answer'   => 'Absolutely not! This plugin will only affect the client browser and will have absolutely no negative impact on your SEO. In fact, it would assist in increasing your SEO score as your content will remain unique.'
                    ), 
                2 => array (
                        'question' => 'Will this plugin disable the features from the site administrator also?',
                        'answer'   => 'Yes! However, our Pro version allows the blog administrator to enable/disable copy protect functions for registered and logged in users (globally)
                        '
                    ),
                3 => array (
						'question' => 'Will your WP Content Copy Protection Plugin affect my Advertising Units (Adsense)?',
                        'answer'   => 'Absolutely not! Although this plugin locks your content and prevents it from being copied by anyone else, your advertising units will remain unaffected as the code doesn’t alter any embeddable code or the functioning thereof.'
                ),
                4 => array (
                    'question' => 'Why Did you Exclude The alert-message (Popup), Function?',
                    'answer'   => 'This function was removed simply because it could scare away your website visitors – cause a higher bounce rate – and essentially defame your website. We like to be silent! However, this is optional in our Pro edition.'
                ),
                5 => array (
                    'question' => 'Does your Plugin work on all major Browsers?',
                    'answer'   => 'This plugin works on all major browsers and theme frameworks. The full functionality of WPCCP was tested on the latest versions of IE (Internet Explorer), Mozilla Firefox, Safari and Chrome without any problems.'
                ),
                6 => array (
                    'question' => 'What is the difference between the free version and the pro version?',
                    'answer'   => 'The pro version includes super aggressive image protection (making it near impossible for a user to copy/steal your images using advanced masking), Prt Sc (print screen) deterrent agent, optional alert message for right click, Javascript validation with idle redirect, removed all RSS feeds instances to counter content scraping software/autoblogs and much, much more! See our features above.'
                ),
                7 => array (
                    'question' => 'Why did you remove the iframe breaker?',
                    'answer'   => 'We decided to remove the Iframe breaker due to conflict with theme appearance/customization.'
                ),
                8 => array (
                    'question' => 'How would I break out of Iframes now?',
                    'answer'   => '<p>We have developed a small plugin that will act as an extension to WP Content Copy Protection. This plugin, WP noFrame/noClickjacking can be found <a href="https://wordpress.org/plugins/wp-noframenoclickjacking/">HERE</a>. This plugin is a simple (yet) effective frame breaking plugin (iframe buster) that protects your site content from being embedded into other sites – effectively defending you against clickjacking attacks. This is achieved by adding a Header always append X-Frame-Options DENY instruction to your .htaccess file – where the DENY rule will prevent ALL domains from framing your content.</p>'
                ),
                9 => array (
                    'question' => 'How is my Video and Audio Protected?',
                    'answer'   => 'This plugin inheritently disables right click/copy/save functions on your default HTML5 video and audio embeds.'
                )   
            );

            return $ts_wpccpl;
        }
	}
	$WPCCPL_Component = new WPCCPL_Component();
}