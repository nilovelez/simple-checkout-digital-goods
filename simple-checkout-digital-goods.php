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
	        add_action( 'admin_menu', function() {
				add_submenu_page(
				  	'woocommerce',
				    __('Simple Checkout for Digital Goods','simple-checkout-digital-goods'),
				    __('Simple Checkout','simple-checkout-digital-goods'),
				    'manage_options',    
				    'simple-checkout-digital-goods',
				    array($this, 'submenu_page_callback')
				  );
			});
		}else{
			$this->read_settings();
		    require('front.php');
		}
	}
	public function submenu_page_callback(){

		if ( isset( $_POST['scdg-saved'] ) ){
		    check_admin_referer( 'scdg_save_options' );
		    if ( isset( $_POST['optionEnabled'] ) ){
		  		$this->save_settings($_POST['optionEnabled']);
		    }else{
		  		$this->save_settings();
		    }

		}

		$this->read_settings();

		$this->all_options_checked = ( count( $this->settings ) == 0 ) ? true : false;

		require('admin.php');
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

	public $notice_message;
	public $notice_class;
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