<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', function() {
	add_submenu_page(
	  	'woocommerce',
	    __('Simple Checkout for Digital Goods','simple-checkout-digital-goods'),
	    __('Simple Checkout for Digital Goods','simple-checkout-digital-goods'),
	    'manage_options',    
	    'simple-checkout-digital-goods',
	    'SCDG_page_content'
	  );
) );

if ( isset( $_POST['machete-cleanup-saved'] ) ){
    check_admin_referer( 'machete_save_cleanup' );
    if ( isset( $_POST['optionEnabled'] ) ){
  		$SCDG_save_settings($_POST['optionEnabled']);
    }else{
  		$SCDG_save_settings();
    }
}

function SCDG_page_content(){

	require('admin_content.php');
}
