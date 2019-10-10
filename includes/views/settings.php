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

?>

<div class="wrap">
	<h1><?php esc_html_e( 'Content Copy Protection (Lite)', 'wpccp' ); ?></h1>

	<form id='form_wpccp' action='options.php' method='post'>
	<?php
		settings_fields( 'wpccp' );
		do_settings_sections( 'wpccp' );
		submit_button();
	?>
	</form>
</div>
