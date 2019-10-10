/**
 * Dependencies
 */
const mix = require( 'laravel-mix' );
const wpPot = require( 'wp-pot' );

/**
 * Minify assets
 */
const assets = [
	'assets/css/style.css',
	'assets/css/admin.css',
	'assets/js/script.js',
	'assets/js/admin.js',
];

mix.minify( assets );

/**
 * Copy vendor assets
 */
// Select2
mix.copy( 'node_modules/select2/dist/css/select2.css', 'assets/css' );
mix.copy( 'node_modules/select2/dist/css/select2.min.css', 'assets/css' );
mix.copy( 'node_modules/select2/dist/js/select2.js', 'assets/js' );
mix.copy( 'node_modules/select2/dist/js/select2.min.js', 'assets/js' );

/**
 * Production Task
 */
if ( mix.inProduction() ) {
	// Generate POT file
	wpPot( {
		package: 'wp-content-copy-protection',
		domain: 'wpccp',
		destFile: 'languages/wp-content-copy-protection.pot',
		relativeTo: './',
		src: [ './**/*.php', '!./vendor/**/*' ],
		bugReport: 'https://github.com/TycheSoftwares/wp-content-copy-protection/issues/new',
		team: 'TycheSoftwares<support@tychesoftwares.freshdesk.com>',
	} );
}
