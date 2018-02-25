<?php
/**
 * Plugin Name: WooCommerce Simple Checkout for Digital Goods
 * Description: Removes billing address fields from the checkout page when you only have downloadable and virtual products in the cart.
 * Version: 1.1.3
 * Author: Nilo Velez
 * Author URI: https://www.nilovelez.com
 * Text Domain: simple-checkout-digital-goods
 * Domain Path: /languages
 *
 * WC requires at least: 2.2
 * WC tested up to: 3.3.3
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class simple_checkout_digital_goods {
	
	protected $settings = array();


	function __construct(){
		$this->disabled_fields_array = array(
			'billing_company' => __( 'Company name', 'woocommerce' ),
			'billing_country' => __( 'Country', 'woocommerce' ),
		    'billing_address_1' => __( 'Street address', 'woocommerce' ),
		    'billing_address_2' => esc_attr__( 'Apartment, suite, unit etc. (optional)', 'woocommerce' ),
		    'billing_city' => __( 'Town / City', 'woocommerce' ),
		    'billing_state' => __( 'State / County', 'woocommerce' ),
		    'billing_postcode' => __( 'Postcode / ZIP', 'woocommerce' ),
		    'billing_phone' => __( 'Phone number.', 'woocommerce' ),
		    'order_comments' => __('Order notes', 'woocommerce')
		);

		if (is_admin()){

			load_plugin_textdomain('simple-checkout-digital-goods', false, basename( dirname( __FILE__ ) ) . '/languages' );
			

			// add "settings" link to Machete in the plugin list
			add_filter( 'plugin_action_links', function( $plugin_actions, $plugin_file ) {
			  $new_actions = array();
			  if ( basename( dirname( __FILE__ ) ) . '/simple-checkout-digital-goods.php' === $plugin_file ) {
			    $new_actions['sc_settings'] = sprintf( __( '<a href="%s">Settings</a>', 'simple-checkout-digital-goods' ), esc_url( admin_url( 'admin.php?page=simple-checkout-digital-goods' ) ) );
			  }
			  return array_merge( $new_actions, $plugin_actions );
			}, 10, 2 );



	        add_action( 'admin_menu', function() {
				add_submenu_page(
				  	'woocommerce',
				    __('Simple Checkout for Digital Goods','simple-checkout-digital-goods'),
				    __('Simple Checkout','simple-checkout-digital-goods'),
				    'manage_options',    
				    'simple-checkout-digital-goods',
				    array($this, 'submenu_page_callback')
				  );
			}, 99);

			if ( isset( $_POST['scdg-saved'] ) ){
			    check_admin_referer( 'scdg_save_options' );
			    if ( isset( $_POST['optionEnabled'] ) ){
			  		$this->save_settings($_POST['optionEnabled']);
			    }else{
			  		$this->save_settings();
			    }

			}
		}else{
			$this->read_settings();
			add_action( 'woocommerce_checkout_fields', array($this , 'remove_fields'), 10 );
		}	
	}
	public function remove_fields($fields) {

		global $woocommerce,$product;

	    //if ($woocommerce->cart->needs_shipping()) {
		//    return $fields;
		//}


	    $disabled_fields_array = array_diff( array_keys($this->disabled_fields_array) , $this->settings);
		if (count ($disabled_fields_array) == 0 ) return $fields;


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
	    }

        foreach ($disabled_fields_array as $field) {
            if ($field == 'order_comments') {
                unset($fields['order']['order_comments']);
            } else {
                unset($fields['billing'][$field]);
            }
        }
        
        return $fields;
	    
	}
	public function submenu_page_callback(){

		
		$this->read_settings();
		$this->all_options_checked = ( count( $this->settings ) == 0 ) ? true : false;
		require(plugin_dir_path( __FILE__ ).'admin.php');
	}

	protected function read_settings(){
		if(!$this->settings = get_option('scdg_settings')){
			$this->settings = array();
		}
		return $this->settings;
	}

	protected function save_settings( $options = array(), $silent = false ) {
		$this->read_settings();
		$options = array_intersect($options, array_keys($this->disabled_fields_array) );
		$options = array_values(array_diff(array_keys($this->disabled_fields_array), $options));

		if ( count($options) > 0 ){

			for($i = 0; $i < count($options); $i++){
				$options[$i] = sanitize_text_field($options[$i]);
			}
			
			if ($this->is_equal_array($this->settings, $options)){
				if (!$silent) $this->save_no_changes_notice();
				return true;
			}			

			if (update_option('scdg_settings',$options)){
				$this->settings = $options;
				if (!$silent) $this->save_success_notice();
				return true;
			}else{
				if (!$silent) $this->save_error_notice();
				return false;
			}

		}elseif (count($this->settings) > 0 ){
			if ( delete_option('scdg_settings')){
				$this->settings = array();
				if (!$silent) $this->save_success_notice();
				return true;
			}else{
				if (!$silent) $this->save_error_notice();
				return false;
			}
		}

		

		if (!$silent) $this->save_no_changes_notice();		
		return true;
	}

	/* Dashboard notices */

	//public $notice_message;
	//public $notice_class;
	public function notice( $message, $level = 'info', $dismissible = true) {

		$this->notice_message = $message;

		if (!in_array($level, array('error','warning','info','success'))){
			$level = 'info';
		}
		$this->notice_class = 'notice notice-'.$level;
		if ($dismissible){
			$this->notice_class .= ' is-dismissible';
		}

		add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}
				
	public function display_notice() {
		echo '<h1>hola'.$this->notice_message.'</h1>';
		
		if (!empty($this->notice_message)){
		?>
		<div class="<?php echo $this->notice_class ?>">
			<p><?php echo $this->notice_message; ?></p>
		</div>
		<?php }
	}

	protected function save_success_notice(){
		$this->notice(__( 'Options saved!', 'simple-checkout-digital-goods' ), 'success');
	}
	protected function save_error_notice(){
		$this->notice(__( 'Error saving configuration to database.', 'simple-checkout-digital-goods' ), 'error');
	}

    /*
	protected function save_no_changes_notice(){
		add_action( 'admin_notices', 'scdg_display_save_no_change_notice');
		echo '<h1>Hola</h1>';
	}

	*/

	protected function save_no_changes_notice(){
		$this->notice(__( 'No changes were needed.', 'simple-checkout-digital-goods' ), 'info');
	}

	

	/* utils */
	public function is_equal_array($a, $b) {
	    return (
	         is_array($a) && is_array($b) && 
	         count($a) == count($b) &&
	         array_diff($a, $b) === array_diff($b, $a)
	    );
	}


}

add_action('init', function(){
	global $simple_checkout_digital_goods;
	// check if WooCommerce is active
	if ( in_array( 'woocommerce/woocommerce.php',
		apply_filters( 'active_plugins', get_option( 'active_plugins' ) )
		) ) {
		$simple_checkout_digital_goods = new simple_checkout_digital_goods;
	};
});


