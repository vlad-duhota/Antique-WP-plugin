<?php

namespace AntiqueWP;

defined( 'ABSPATH' ) || exit;

class CheckoutFields {

    public function __construct() {
        // Hook to customize WooCommerce checkout fields
        add_filter( 'woocommerce_checkout_fields', [ $this, 'customize_fields' ] );
    }

    /**
     * Customize WooCommerce checkout fields.
     *
     * @param array $fields WooCommerce checkout fields.
     * @return array Modified fields.
     */
    public function customize_fields( array $fields ): array {
        // If the option to disable shipping fields is not enabled, return fields as is
        if ( ! get_option( 'antique_wp_field_disable_shipping_fields' ) ) {
            return $fields;
        }

        // List of fields to unset
        $fields_to_unset = [
            'billing' => [
                'billing_last_name',
                'billing_address_1',
                'billing_address_2',
                'billing_city',
                'billing_postcode',
                'billing_country',
                'billing_state',
            ],
            'shipping' => [
                'shipping_first_name',
                'shipping_last_name',
                'shipping_company',
                'shipping_address_1',
                'shipping_address_2',
                'shipping_city',
                'shipping_postcode',
                'shipping_country',
                'shipping_state',
            ],
            'order' => [
                'order_comments',
            ],
        ];

        // Unset the specified fields
        foreach ( $fields_to_unset as $section => $field_names ) {
            foreach ( $field_names as $field_key ) {
                unset( $fields[ $section ][ $field_key ] );
            }
        }

        return $fields;
    }
}