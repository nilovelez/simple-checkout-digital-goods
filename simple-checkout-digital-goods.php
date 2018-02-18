<?php
/**
 * Plugin Name: Simple Checkout for Digital Goods
 * Description: Removes billing address fields when you only have downloadable and virtual products in the cart.
 * Version: 1.0.0
 * Author: Nilo Velez
 * Author URI: https://www.nilovelez.com
 * Text Domain: simple-checkout-digital-goods
 * Domain Path: /languages
 *
 * WC requires at least: 2.2
 * WC tested up to: 3.3.1
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if (is_admin()){
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        require('admin.php');
    }

}else{
    require('front.php');
}