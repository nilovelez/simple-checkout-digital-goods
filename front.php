<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'woocommerce_checkout_fields', 'SCDG_remove_fields', 10 );	

function SCDG_remove_fields($fields) {

    global $woocommerce,$product;

    //if ($woocommerce->cart->needs_shipping()) {
	//    return $fields;
	//}

    $disabled_fields_array = get_option('wcdg_checkout_fields');
    


    $temp_product = array();
    $temp_product_flag = 1;
    // basic checks

    foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
        $_product = $values['data'];
        $product_id = apply_filters('woocommerce_cart_item_product_id', $values['product_id'], $values, $cart_item_key);

        if ($_product->is_virtual() || $_product->is_downloadable()) {
            $temp_product_flag = 0;
        }else{
            $temp_product[] = $product_id;
        }
    }
    if (!empty($temp_product)) {
        return $fields;
    } else {

        if (isset($woo_checkout_field_array) && !empty($woo_checkout_field_array)) {
            foreach ($disabled_fields_array as $key => $values) {
                if ($values == 'order_comments') {
                    unset($fields['order']['order_comments']);
                } else {
                    unset($fields['billing'][$values]);
                }
            }
        } else {

            unset($fields['billing']['billing_company']);
            unset($fields['billing']['billing_address_1']);
            unset($fields['billing']['billing_address_2']);
            unset($fields['billing']['billing_city']);
            unset($fields['billing']['billing_postcode']);
            unset($fields['billing']['billing_country']);
            unset($fields['billing']['billing_state']);
            unset($fields['billing']['billing_phone']);
            unset($fields['order']['order_comments']);
            unset($fields['billing']['billing_address_2']);
            unset($fields['billing']['billing_postcode']);
            unset($fields['billing']['billing_company']);
            unset($fields['billing']['billing_city']);
            return $fields;
        }
    }
    return $fields;
}
