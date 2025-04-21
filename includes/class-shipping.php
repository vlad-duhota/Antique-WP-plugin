<?php
/*
 * ARTA shipping method
 */

function start_arta_shipping_method() {
    if ( ! class_exists( 'Arta_Shipping_Method' ) ) {
        class Arta_Shipping_Method extends WC_Shipping_Method {

            /**
             * Constructor for your shipping class
             *
             * @access public
             * @return void
             */
            public function __construct() {
                $this->id                 = 'antique_wp';
                $this->method_title       = __( 'antique Custom Shipping', 'antique_wp' );
                $this->method_description = __( 'Shipping from:', 'antique_wp' ).' Arta, ShipStation.';

                $this->init();
                $this->enabled = 'yes';
                $this->title   = isset( $this->settings['title']   ) ? $this->settings['title']   : __( 'antique Custom Shipping', 'antique_wp' );
            }

            /**
             * Init your settings
             *
             * @access public
             * @return void
             */
            public function init() {
                // Load the settings API
                $this->init_form_fields();
                $this->init_settings();

                // Save settings in admin if you have any defined
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                
                $_SESSION['quote_id'] = [];
            }

            /**
             * Define settings field for this shipping
             * @return void
             */
            public function init_form_fields() {
                $this->form_fields = array(
                    'antique_curate_estimates' => array(
                        'title'   => esc_html__('Hide "Estimates widget", and show custom text instead?', 'antique_wp' ),
                        'type'    => 'checkbox',
                        'default' => 'no'
                    ),
                    'antique_curate_estimates_text' => array(
                        'title'   => esc_html__('Text that will show instead "Estimates widget"', 'antique_wp' ),
                        'type'    => 'text',
                        'default' => ''
                    ),
                );
            }

            /**
             * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
             *
             * @access public
             * @param mixed $package
             * @return void
             */
            public function calculate_shipping( $package = array() ) {
                $finalData = [];
                if( $this->settings['antique_curate_estimates'] === 'yes'){
                    $this->add_rate( array(
                        'id'    => 'free_shipping',
                        'label' => 'Free shipping',
                        'cost'  => 0
                    ) );    
                    return;
                }

                foreach ( $package['contents'] as $item_id => $values ) {
                    // get all product parameters
                    $_product = $values['data'];
                    $shipppingMethod = get_post_meta( $_product->get_id(), 'woo_arta_ship_method', true ) ?: 'arta';
                    
                    $width = $_product->get_width();
                    $height = $_product->get_height();
                    $length = $_product->get_length();
                    $weight = $_product->get_weight();
                    $product_quantity = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_quantity', true ) ?: 1;
                    $fixedPrice = (int)get_post_meta( $_product->get_id(), 'woo_arta_ship_product_price', true );
                    $product_price = fdiv($_product->get_regular_price(), $product_quantity);
                    $product_country = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_country', true );
                    $product_postal = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_zip', true );
                    $product_type = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_type', true );
                    $product_material = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_material', true );
                    $product_packing = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_packing', true );
                    $product_address = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_address', true );
                    $dest_country = $package[ 'destination' ]['country'];
                    $dest_region = $package[ 'destination' ]['state'];
                    $dest_city = $package[ 'destination' ]['city'];
                    $dest_postal = $package[ 'destination' ]['postcode'];
                    $dest_addr = $package[ 'destination' ]['address'];
                    $seller_name = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_seller_name', true );
                    $seller_email = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_seller_email', true );
                    $seller_phone = get_post_meta( $_product->get_id(), 'woo_arta_ship_product_seller_phone', true );
                    $shipWarehouseId = get_post_meta( $_product->get_id(), 'woo_arta_ship_warehouse', true );
                    $shipCarrierCode = get_post_meta( $_product->get_id(), 'woo_arta_ship_carrier', true );

                    $product_name = get_the_title($_product->get_id());
                    $customer_name = '';

                    switch($shipppingMethod){
                        case 'arta' : {
                            $key = get_option('antique_wp_field_arta');
                            break;
                        }
                        case 'shipstation' : {
                            $key = base64_encode(get_option('antique_wp_field_shipstation') . ':' . get_option('antique_wp_field_shipstation_secret'));
                            break;
                        }
                    }

                    $quote_types = array('select', 'parcel', 'premium');
                    $arr = [];

                    // get api response based on chosen shipping method
                    switch($shipppingMethod){
                        case 'arta' : {
                            foreach($quote_types as $quote_type){
                                $quoteArr = self::arta_request_cost( $key, $quote_type, $width, $height, $length, $weight, $product_price, $product_quantity, $product_country, $product_region, $product_city, $product_postal, $product_address, $dest_country, $dest_region, $dest_city, $dest_postal, $dest_addr, $product_type, $product_material, $product_packing, $seller_name, $seller_email, $seller_phone, $product_name, $customer_name );
                                if(!$quoteArr['data']['err']){
                                    $quote_types = array($quote_type);
                                    $arr = $quoteArr;
                                    break;
                                } else {
                                    unset($quote_types[array_search($quote_type, $quote_types)]);
                                    $arr = self::arta_request_cost( $key, $quote_types, $width, $height, $length, $weight, $product_price, $product_quantity, $product_country, $product_region, $product_city, $product_postal, $product_address, $dest_country, $dest_region, $dest_city, $dest_postal, $dest_addr, $product_type, $product_material, $product_packing, $seller_name, $seller_email, $seller_phone, $product_name, $customer_name );
                                }
                            };
                            array_push($finalData, $arr);
                            break;
                        }
                        case 'shipstation' : {
                            $shipstaionArr = self::ship_station_request_cost($key, $width, $height, $length, $weight, $product_price, $product_quantity, $product_country, $product_region, $product_city, $product_postal, $product_address, $dest_country, $dest_region, $dest_city, $dest_postal, $dest_addr, $product_type, $product_material, $product_packing, $seller_name, $seller_email, $seller_phone, $product_name, $customer_name, $shipWarehouseId, $shipCarrierCode);
                            array_push($finalData, $shipstaionArr);
                            break;
                        }
                        case 'fixed' : {
                            array_push($finalData, ['data' => ['fixed' => $fixedPrice], 'type' => 'fixed']);
                            break;
                        }
                    }
                }

                // intialiaze values
                $finalCost = 0;
                $errors = [];
                $_SESSION['quote_id'] = [];

                // get final data 
                foreach($finalData as $arr){
                    // choose method
                    $data = $arr['data'];
                    switch($arr['type']){
                        case 'arta' : {
                            if(!$data['err']){

                                usort($data['data'], function ($item1, $item2) {
                                    return $item1['total'] <=> $item2['total'];
                                });
                                
                                $info = $data['data'];
                                $arr_i = $info[0];
                                $finalCost += $arr_i['total'];
                            
                                array_push($_SESSION['quote_id'], $arr_i['id']);
                            } else {
                                array_push($errors, $data['err']);
                            }
                            break;
                        }
                        case 'shipstation' : {
                            if(!isset($data['ExceptionMessage'])){
                                $finalCost += $data['shipmentCost'];
                                $finalCost += $data['otherCost'];
                            } else {
                                array_push($errors, $data['ExceptionMessage']);
                            }
                            break;
                        }
                        case 'fixed' : {
                            $finalCost += $data['fixed'];
                            break;
                        }
                    }
                }
                if(count($errors) === 0){
                    $this->add_rate( array(
                        'id'    => $finalCost,
                        'label' => 'Shipping',
                        'cost'  => $finalCost
                    ) );    
                } else {
                    foreach($errors as $error){
                        wc_add_notice($error, 'error');
                    }
                }
            }

            public static function arta_request_cost( $key, $quote_types=array('premium','select','parcel'), $product_width=0, $product_height=0, $length=0, $product_weight=0, $product_price=0, $quantity=1, $product_country='', $product_region='', $product_city='', $product_postal='', $product_address='', $dest_country='', $dest_region='', $dest_city='', $dest_postal='', $dest_addr='', $product_type='', $product_material='wood', $product_packing='no_packing', $seller_name='', $seller_email='', $seller_phone='', $product_name='' ){
                $res = array('err'=>'', 'data'=>array() );
                $url = 'https://api.arta.io/requests';
                $key = (string)$key;
                $quote_types = (array)$quote_types;
                $product_width = (float)$product_width;
                $product_height = (float)$product_height;
                $length = (float)$length;
                $product_weight = (float)$product_weight;
                $product_price = (int)$product_price;
                $quantity = (int)$quantity;
                $product_country = (string)$product_country;
                $product_city = (string)$product_city;
                $product_postal = (string)$product_postal;
                $product_address = (string)$product_address;
                $dest_country = (string)$dest_country;
                $dest_region = (string)$dest_region;
                $dest_city = (string)$dest_city;
                $dest_postal = (string)$dest_postal;
                $dest_addr = (string)$dest_addr;
                $product_material = (string)$product_material;
                $product_packing = (string)$product_packing;
                $seller_name = (string)$seller_name;
                $seller_email = (string)$seller_email;
                $seller_phone = (string)$seller_phone;
                $product_type = (string)$product_type;
                $product_name = (string)$product_name;

                $args = array(
                    'method'  => 'POST',
                    'headers' => array(
                        'Authorization' => 'ARTA_APIKey ' . $key,
                        'Content-Type' => 'application/json'
                    ),
                    'body' => json_encode(array(
                        'method' => $quote_types,
                        'product' => array(
                            'width' => $product_width,
                            'height' => $product_height,
                            'length' => $length,
                            'weight' => $product_weight,
                            'quantity' => $quantity,
                            'price' => $product_price,
                            'country' => $product_country,
                            'region' => $product_region,
                            'city' => $product_city,
                            'postal' => $product_postal,
                            'address' => $product_address
                        ),
                        'destination' => array(
                            'country' => $dest_country,
                            'region' => $dest_region,
                            'city' => $dest_city,
                            'postal' => $dest_postal,
                            'address' => $dest_addr
                        ),
                        'product_type' => $product_type,
                        'material' => $product_material,
                        'packing' => $product_packing,
                        'seller' => array(
                            'name' => $seller_name,
                            'email' => $seller_email,
                            'phone' => $seller_phone
                        ),
                        'product_name' => $product_name
                    ))
                );

                return $res;
            }

            public static function ship_station_request_cost($key, $width, $height, $length, $weight, $product_price, $product_quantity, $product_country, $product_region, $product_city, $product_postal, $product_address, $dest_country, $dest_region, $dest_city, $dest_postal, $dest_addr, $product_type, $product_material, $product_packing, $seller_name, $seller_email, $seller_phone, $product_name, $customer_name, $shipWarehouseId, $shipCarrierCode) {
                $res = [];
                $url = 'https://ssapi.shipstation.com/shipments/create'; // ShipStation API endpoint
                $args = [
                    'method'  => 'POST',
                    'headers' => [
                        'Authorization' => 'Basic ' . $key,
                        'Content-Type'  => 'application/json',
                    ],
                    'body'    => json_encode([
                        'fromWarehouse'   => $shipWarehouseId,
                        'carrierCode'     => $shipCarrierCode,
                        'weight'          => $weight,
                        'dimensions'      => [
                            'length' => $length,
                            'width'  => $width,
                            'height' => $height
                        ],
                        'productType'     => $product_type,
                        'material'        => $product_material,
                        'packing'         => $product_packing,
                        'recipient'       => [
                            'name'    => $seller_name,
                            'email'   => $seller_email,
                            'phone'   => $seller_phone,
                            'address' => $dest_addr,
                            'city'    => $dest_city,
                            'state'   => $dest_region,
                            'postal'  => $dest_postal,
                            'country' => $dest_country
                        ],
                        'quantity'        => $product_quantity
                    ])
                ];

                // Placeholder for ShipStation API response logic
                // You would typically send a request here and return the result
                return $res;
            }
        }
    }
} 

add_action( 'woocommerce_shipping_init', 'start_arta_shipping_method' );

?>