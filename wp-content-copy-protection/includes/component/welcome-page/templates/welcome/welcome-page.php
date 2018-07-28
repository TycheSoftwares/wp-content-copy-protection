<?php
/**
 * Welcome page on activate or updation of the plugin
 */

$shortcodes_array = get_query_var( 'shortcodes_array' );

$badge_url = $shortcodes_array['badge_url'];

$ts_dir_image_path = $shortcodes_array['ts_dir_image_path'];

$ts_plugin_name = $shortcodes_array['plugin_name'];

?>
<style>
    .feature-section .feature-section-item {
        float:left;
        width:48%;
    }
</style>
<div class="wrap about-wrap">

    <?php echo $shortcodes_array[ 'get_welcome_header'] ?>

    <div style="float:left;width: 80%;">
    <p class="about-text" style="margin-right:20px;"><?php
        printf(
            __( "Thank you for activating or updating to the latest version of $ts_plugin_name! If you're a first time user, welcome! You're well on your way to explore the $ts_plugin_name functionality for your WordPress site." )
        );
        ?></p>
    </div>
    <div class="faq-badge"><img src="<?php echo $badge_url; ?>" style="width:150px;"/></div>

    <p>&nbsp;</p>

    <p>
        WP Content Copy Protection is a simple, yet effective plugin that uses an array of aggressive techniques in protecting your online content from being stolen.
    </p>
    <p>
        Some of the most common content copy methods (via mouse, keyboard and browser), such as right-click, image drag/drop/save, text selection/drag/drop, source code viewing, and keyboard copy shortcut keys such as CTRL A, C, X, U, S, and P are disabled with this plugin (just to name a few).
    </p>
    <h4>Basic Features (included)</h4>
    <pre><code>√ Disables right click context menu on all content (except href links)

√ Disables text selection (globally) on PC and mobile devices

√ Disables text and image drag/drop/save on PC and mobile devices

√ Basic image protection (image link URL's are automatically removed)

√ Copy methods disabled from onscreen keyboard and shortcut context key

√ Secures your uploads directory and sub-directories from public access

√ Disables right click and save function on default video and audio embeds

√ Javascript validation (displays error message when disabled in user browser)

√ Disables keyboard copy controls (CTRL A, C, X) - Windows only

√ Disables 'Source view', 'Save Page', and 'Print' key functions

√ Disables f shortcut key for accessing developer tools to view source code

√ No obtrusive popups or alert messages as they are known to defame your site

√ No negative side-effects on your SEO (search engines can read your content)

√ This is a non resource-intensive plugin that works silently in the background

√ No configuration, customization or coding needed. Simply plug in and leave
</code></pre>

    <div class="feature-section clearfix">

        <div class="content feature-section-item">

            <h3><?php esc_html_e( 'Getting to Know Tyche Softwares', 'acs' ); ?></h3>

            <ul class="ul-disc">
                <li><a href="https://tychesoftwares.com/?utm_source=wpaboutp    age&utm_medium=link&utm_campaign=WpContentCopyPlugin" target="_blank"><?php esc_html_e( 'Visit the Tyche Softwares Website', 'acs' ); ?></a></li>
                <li><a href="https://tychesoftwares.com/premium-woocommerce-plugins/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=WpContentCopyPlugin" target="_blank"><?php esc_html_e( 'View all Premium Plugins', 'acs' ); ?></a>
                <ul class="ul-disc">
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=WpContentCopyPlugin" target="_blank">Abandoned Cart Pro Plugin for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/woocommerce-booking-plugin/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=WpContentCopyPlugin" target="_blank">Booking & Appointment Plugin for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=WpContentCopyPlugin" target="_blank">Order Delivery Date for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=WpContentCopyPlugin" target="_blank">Product Delivery Date for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/deposits-for-woocommerce/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=WpContentCopyPlugin" target="_blank">Deposits for WooCommerce</a></li>
                </ul>
                </li>
                <li><a href="https://tychesoftwares.com/about/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=WpContentCopyPlugin" target="_blank"><?php esc_html_e( 'Meet the team', 'acs' ); ?></a></li>
            </ul>
        </div>
    </div>            
    <!-- /.feature-section -->
</div>
