<?php

namespace AntiqueWP;

defined( 'ABSPATH' ) || exit;

class Plugin {
    public function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies(): void {
        require_once __DIR__ . '/class-shipping.php';
        require_once __DIR__ . '/class-product-options.php';
        require_once __DIR__ . '/plugin-options.php';
        require_once __DIR__ . '/class-checkout-fields.php';
        
        if ( ! get_option( 'antique_wp_field_timers' ) ) {
            require_once __DIR__ . '/class-timers.php';
            require_once __DIR__ . '/class-asset-loader.php';
        }
    }

    private function init_hooks(): void {
        new AssetLoader();
        new CheckoutFields();
        new Styles();
        new Timers();
        new WooCommerceProductCustomFields();
        // Add more initializations as needed
    }
}
