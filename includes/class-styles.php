<?php

namespace AntiqueWP;

defined( 'ABSPATH' ) || exit;

class Styles {

    public function __construct() {
        // Hook for admin styles
        add_action( 'admin_head', [ $this, 'admin_styles' ] );
        
        // Hook for front-end styles
        add_action( 'wp_enqueue_scripts', [ $this, 'front_end_styles' ] );
    }

    /**
     * Add admin styles to the head
     */
    public function admin_styles(): void {
        ?>
        <style type="text/css">
            .curate-switch {
                display: inline-block;
                height: 34px;
                position: relative;
                width: 60px;
            }

            .curate-switch input {
                display: none;
            }

            .curate-slider {
                background-color: #ccc;
                bottom: 0;
                cursor: pointer;
                left: 0;
                position: absolute;
                right: 0;
                top: 0;
                transition: .4s;
            }

            .curate-slider:before {
                background-color: #fff;
                bottom: 4px;
                content: "";
                height: 26px;
                left: 4px;
                position: absolute;
                transition: .4s;
                width: 26px;
            }

            input:checked + .curate-slider {
                background-color: #a0872a;
            }

            input:checked + .curate-slider:before {
                transform: translateX(26px);
            }

            .curate-slider.round {
                border-radius: 34px;
            }

            .curate-slider.round:before {
                border-radius: 50%;
            }

            .curate-wrap .button-primary {
                background: #a0872a !important;
                border-color: #a0872a !important;
            }

            .curate-wrap a {
                color: #a0872a;
            }
        </style>
        <?php
    }

    /**
     * Enqueue front-end styles
     */
    public function front_end_styles(): void {
        wp_enqueue_style( 'antique-wp-style', plugins_url( 'assets/css/style.css', __FILE__ ) );
    }
}