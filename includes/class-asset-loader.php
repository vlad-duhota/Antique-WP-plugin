<?php

namespace AntiqueWP;

defined( 'ABSPATH' ) || exit;

class AssetLoader {

    private string $version = '1.0.0';
    private bool $in_footer = true;

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    public function enqueue_scripts(): void {
        wp_enqueue_script(
            'antique-wp-script',
            plugins_url( 'assets/js/main.js', __FILE__ ),
            [],
            $this->version,
            $this->in_footer
        );
    }
}
