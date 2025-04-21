<?php

namespace AntiqueWP;

defined( 'ABSPATH' ) || exit;

class WooCommerceProductCustomFields {
    
    public function __construct() {
        add_action('woocommerce_product_options_shipping_product_data', [$this, 'display_product_custom_fields']);
    }

    public function display_product_custom_fields() {
        global $product_object;
        
        $this->add_shipping_method_field();
        
        $shipping_method = get_post_meta($product_object->get_id(), 'woo_arta_ship_method', true);
        
        if ($shipping_method === 'shipstation') {
            $this->add_shipstation_fields($product_object);
        } elseif ($shipping_method === 'fixed') {
            $this->add_fixed_shipping_price_field($product_object);
        } elseif ($shipping_method === 'arta') {
            $this->add_arta_shipping_fields($product_object);
        }
    }

    private function add_shipping_method_field() {
        woocommerce_wp_select([
            'id' => 'woo_arta_ship_method',
            'label' => __('Shipping method:', 'woocommerce'),
            'options' => [
                'arta' => 'Arta',
                'shipstation' => 'Shipstation',
                'fixed' => 'Fixed price'
            ]
        ]);
    }

    private function add_shipstation_fields($product_object) {
        $auth = base64_encode(get_option('antique_wp_field_shipstation') . ':' . get_option('antique_wp_field_shipstation_secret'));
        
        // Get warehouses
        $warehouses = $this->fetch_shipstation_data('warehouses', $auth);
        $this->add_select_field('woo_arta_ship_warehouse', __('Shipstation warehouse:', 'woocommerce'), $warehouses);

        // Get carriers
        $carriers = $this->fetch_shipstation_data('carriers', $auth);
        $this->add_select_field('woo_arta_ship_carrier', __('Shipstation Carriers:', 'woocommerce'), $carriers);
    }

    private function fetch_shipstation_data($endpoint, $auth) {
        $response = wp_remote_post("https://ssapi.shipstation.com/{$endpoint}", [
            'timeout' => 6000,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $auth
            ],
            'method' => 'GET',
        ]);

        if (is_wp_error($response)) {
            echo $response->get_error_message();
            return [];
        }

        $data = json_decode($response['body'], true);
        return array_combine(
            array_map(fn($item) => $item['code'] ?? $item['warehouseId'], $data),
            array_map(fn($item) => $item['name'] ?? $item['warehouseName'], $data)
        );
    }

    private function add_select_field($id, $label, $options) {
        woocommerce_wp_select([
            'id' => $id,
            'label' => $label,
            'options' => $options
        ]);
    }

    private function add_fixed_shipping_price_field($product_object) {
        woocommerce_wp_text_input([
            'id' => 'woo_arta_ship_product_price',
            'label' => __('Fixed shipping price (optional), without $ sign:', 'woocommerce'),
            'placeholder' => '100',
            'desc_tip' => 'false',
            'value' => get_post_meta($product_object->get_id(), 'woo_arta_ship_product_price', true),
        ]);
    }

    private function add_arta_shipping_fields($product_object) {
        $this->add_select_field('woo_arta_ship_product_country', __('Arta Country:', 'woocommerce'), $this->get_arta_countries());
        $this->add_text_field('woo_arta_ship_product_zip', __('Arta ZIP:', 'woocommerce'), '10001', __('Arta ZIP format: 10001', 'woocommerce'), $product_object);
        $this->add_text_field('woo_arta_ship_product_address', __('Arta address:', 'woocommerce'), 'address', __('Arta address format: address', 'woocommerce'), $product_object);
        $this->add_text_field('woo_arta_ship_product_quantity', __('Arta quantity (default is 1):', 'woocommerce'), '1', __('Arta quantity format: 1', 'woocommerce'), $product_object);
        $this->add_select_field('woo_arta_ship_product_type', __('Arta type:', 'woocommerce'), $this->get_arta_types());
    }

    private function get_arta_countries() {
        // Array of countries
        return [
            "AF" => "AF", "AX" => "AX", "AL" => "AL", "DZ" => "DZ", "AS" => "AS", 
            // More countries...
        ];
    }

    private function get_arta_types() {
        return [
            "painting_unframed" => "painting_unframed", "painting_framed" => "painting_framed", 
            "sculpture" => "sculpture", "furniture" => "furniture", "jewelry" => "jewelry", 
            // More types...
        ];
    }

    private function add_text_field($id, $label, $placeholder, $description, $product_object) {
        woocommerce_wp_text_input([
            'id' => $id,
            'label' => $label,
            'placeholder' => $placeholder,
            'desc_tip' => 'true',
            'description' => $description,
            'value' => get_post_meta($product_object->get_id(), $id, true),
        ]);
    }
}

// Initialize the class
new WooCommerceProductCustomFields();
