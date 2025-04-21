<?php
/**
 * Plugin Name:       Antique WP plugin
 * Plugin URI:        https://antique_wp/
 * Description:       ShipStation && Arta integration for WooCommerce. Custom anqiue auction plugin for wordpress.
 * Version:           1.6.0
 * Author:            vladyslavduhota@gmail.com
 * Text Domain:       antique_wp
 * License:           GPLv2
 * License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) || exit;

use AntiqueWP\Plugin;
 
// Autoloader (optional if using PSR-4 or manual includes)
spl_autoload_register( function ( $class ) {
	$prefix = 'AntiqueWP\\';
	$base_dir = __DIR__ . '/includes/';
 
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}
 
	$relative_class = substr( $class, $len );
	$file = $base_dir . 'class-' . strtolower( str_replace( '\\', '-', $relative_class ) ) . '.php';
 
	if ( file_exists( $file ) ) {
		require $file;
	}
} );
 
// Check WooCommerce dependency
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="error"><h3>' .
			esc_html__( 'WooCommerce plugin is required to use the [Antique WP] plugin!', 'antique_wp' ) .
			'</h3></div>';
	} );
	return;
}
 
// Boot plugin
new Plugin();