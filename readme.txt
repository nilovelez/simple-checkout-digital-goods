=== WooCommerce Simple Checkout for Digital Goods ===
Contributors: nilovelez
Donate link: https://www.paypal.me/nilovelez
Tags: woocommerce, checkout, payment
Requires at least: 4.6
Tested up to: 5.8
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Removes billing address fields from the checkout page when you only have downloadable and virtual products in the cart.

== Description ==

There are some cases where you don't need all the billing fields of a digital product checkout. This plugin lets you remove some fields of the checkout page when all the products in the cart are downloadable or virtual.

Since version 1.2, the default behaviour is to remove billing fields only from free orders (those where the cart total is zero). You have the option to remove billing fields from all orders, but keep in mind that you will need those fields for invoicing if you are charging for the products.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/simple-checkout-digital-goods` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to **WooCommerce > Simple Checkout** and check the fields you want to remove from the checkout page.

== Screenshots ==

1. Checkout page
2. Configuration panel

== Changelog ==

= 1.2.1 =
* Tested up to WordPress 4.8
* Tested up tp WooCommerce 5.4

= 1.2 =
* Added option to remove fields only from free orders
* Code refactor to adhere to WordPress coding standards

= 1.1.3 = 
* Added missing "settings" link

= 1.1.2 = 
* l10n fixes

= 1.1.1 = 
* WooCommerce menu fix

= 1.1 = 
* First public version