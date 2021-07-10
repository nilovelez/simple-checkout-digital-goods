<?php
/**
 * Plugin Name: WooCommerce Simple Checkout for Digital Goods
 * Description: Removes billing address fields from the checkout page when you only have downloadable and virtual products in the cart.
 * Version: 1.2.1
 * Author: Nilo Velez
 * Author URI: https://www.nilovelez.com
 * Text Domain: simple-checkout-digital-goods
 * Domain Path: /languages
 *
 * WC requires at least: 4.0
 * WC tested up to: 5.4
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package simple-checkout-digital-goods
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('init', function() {
	global $simple_checkout_digital_goods;
	// check if WooCommerce is active.
	if ( in_array( 'woocommerce/woocommerce.php',
		apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true
	) ) {
		require plugin_dir_path( __FILE__ ) . 'class-simple-checkout-digital-goods.php';
		$simple_checkout_digital_goods = new SIMPLE_CHECKOUT_DIGITAL_GOODS();
	};
});
