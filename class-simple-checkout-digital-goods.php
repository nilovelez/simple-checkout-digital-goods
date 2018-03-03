<?php
/**
 * Main plugin class

 * @package simple-checkout-digital-goods
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */

/**
 * Main plugin class.
 */
class SIMPLE_CHECKOUT_DIGITAL_GOODS {

	/**
	 * Temporal container for plugin settings
	 *
	 * @var Array $settings
	 **/
	protected $settings = array();

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->excluded_fields_array = array(
			'billing_company'   => __( 'Company name', 'woocommerce' ),
			'billing_country'   => __( 'Country', 'woocommerce' ),
			'billing_address_1' => __( 'Street address', 'woocommerce' ),
			'billing_address_2' => esc_attr__( 'Apartment, suite, unit etc. (optional)', 'woocommerce' ),
			'billing_city'      => __( 'Town / City', 'woocommerce' ),
			'billing_state'     => __( 'State / County', 'woocommerce' ),
			'billing_postcode'  => __( 'Postcode / ZIP', 'woocommerce' ),
			'billing_phone'     => __( 'Phone number.', 'woocommerce' ),
			'order_comments'    => __( 'Order notes', 'woocommerce' ),
		);

		if ( is_admin() ) {

			load_plugin_textdomain( 'simple-checkout-digital-goods', false, basename( dirname( __FILE__ ) ) . '/languages' );

			// add "settings" link to Machete in the plugin list.
			add_filter( 'plugin_action_links', function( $plugin_actions, $plugin_file ) {
				$new_actions = array();
				if ( basename( dirname( __FILE__ ) ) . '/simple-checkout-digital-goods.php' === $plugin_file ) {
					/* translators: plugin settings page */
					$new_actions['sc_settings'] = sprintf( __( '<a href="%s">Settings</a>', 'simple-checkout-digital-goods' ), esc_url( admin_url( 'admin.php?page=simple-checkout-digital-goods' ) ) );
				}
				return array_merge( $new_actions, $plugin_actions );
			}, 10, 2 );

			add_action( 'admin_menu', function() {
				add_submenu_page(
					'woocommerce',
					__( 'Simple Checkout for Digital Goods', 'simple-checkout-digital-goods' ),
					__( 'Simple Checkout', 'simple-checkout-digital-goods' ),
					'manage_options',
					'simple-checkout-digital-goods',
					array( $this, 'submenu_page_callback' )
				);
			}, 99);

			/* Sanitization not needed, only used to check if form has been submitted */
			if ( isset( $_POST['scdg-saved'] ) ) {
				check_admin_referer( 'scdg_save_options' );

				/* Sanitization not needed, values are checked against a valid options array */
				$this->save_settings( $_POST );
			}
		} else {
			$this->read_settings();
			add_action( 'woocommerce_checkout_fields', array( $this, 'remove_fields' ), 10 );
		}
	}
	/**
	 * Filters WooCommerce checkout fields removing those especified in the plugin settings.
	 *
	 * @param array $fields reference to WooCommerce checkout fields.
	 * @return array WooCommerce checkout fields
	 */
	public function remove_fields( $fields ) {

		global $woocommerce, $product;

		if ( 'no' === $this->settings['include_non_free'] ) {
			if ( intval( preg_replace( '#[^\d.]#', '', $woocommerce->cart->get_cart_total() ) ) > 0 ) {
				return $fields;
			}
		}

		$excluded_fields = array_diff( array_keys( $this->excluded_fields_array ), $this->settings['excluded_fields'] );

		if ( count( $excluded_fields ) === 0 ) {
			return $fields;
		}

		$temp_product      = array();
		$temp_product_flag = 1;

		// basic checks.
		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
			$_product   = $values['data'];
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $values['product_id'], $values, $cart_item_key );

			if ( $_product->is_virtual() || $_product->is_downloadable() ) {
				$temp_product_flag = 0;
			} else {
				$temp_product[] = $product_id;
			}
		}

		if ( ! empty( $temp_product ) ) {
			return $fields;
		}

		foreach ( $excluded_fields as $field ) {
			if ( 'order_comments' === $field ) {
				unset( $fields['order']['order_comments'] );
			} else {
				unset( $fields['billing'][ $field ] );
			}
		}

		return $fields;

	}
	/**
	 * Callback for the add_submenu_page function.
	 */
	public function submenu_page_callback() {
		$this->read_settings();
		$this->all_options_checked = ( count( $this->settings['excluded_fields'] ) === 0 ) ? true : false;
		require plugin_dir_path( __FILE__ ) . 'admin-content.php';
	}

	/**
	 * Returns an array with the plugin settings:
	 * excluded_fields: fields NOT to remove from the checkout page
	 * include_non_free: yes|no Remove fields in non-free orders?
	 */
	protected function read_settings() {
		$this->settings = array(
			'excluded_fields'  => get_option( 'scdg_settings', array() ),
			'include_non_free' => get_option( 'scdg_include_non_free', 'no' ),
		);
	}

	/**
	 * Saves options to database
	 *
	 * @param array $options options array, normally $_POST.
	 * @param bool  $silent prevent the function from generating admin notices.
	 */
	protected function save_settings( $options = array(), $silent = false ) {
		$this->read_settings();

		$no_changes = true;

		if ( 'yes' === $options['include_non_free'] ) {
			if ( 'no' === $this->settings['include_non_free'] ) {
				if ( update_option( 'scdg_include_non_free', 'yes' ) ) {
					$this->settings['include_non_free'] = 'yes';
					$no_changes = false;
				} else {
					if ( ! $silent ) {
						$this->save_error_notice();
					}
					return false;
				}
			}
		} else {
			if ( 'yes' === $this->settings['include_non_free'] ) {
				if ( delete_option( 'scdg_include_non_free' ) ) {
					$this->settings['include_non_free'] = 'no';
					$no_changes = false;
				} else {
					if ( ! $silent ) {
						$this->save_error_notice();
					}
					return false;
				}
			}
		}

		if ( isset( $options['optionEnabled'] ) ) {
			$excluded_fields = array_intersect( $options['optionEnabled'], array_keys( $this->excluded_fields_array ) );
			$excluded_fields = array_values( array_diff( array_keys( $this->excluded_fields_array ), $excluded_fields ) );
		} else {
			$excluded_fields = array();
		}

		$num_excluded_fields = count( $excluded_fields );
		if ( $num_excluded_fields > 0 ) {
			if ( ! $this->is_equal_array( $this->settings['excluded_fields'], $excluded_fields ) ) {
				if ( update_option( 'scdg_settings', $excluded_fields ) ) {
					$this->settings['excluded_fields'] = $excluded_fields;
					if ( ! $silent ) {
						$this->save_success_notice();
					}
					return true;
				} else {
					if ( ! $silent ) {
						$this->save_error_notice();
					}
					return false;
				}
			}
		} elseif ( count( $this->settings['excluded_fields'] ) > 0 ) {
			if ( delete_option( 'scdg_settings' ) ) {
				$this->settings['excluded_fields'] = array();
				if ( ! $silent ) {
					$this->save_success_notice();
				}
				return true;
			} else {
				if ( ! $silent ) {
					$this->save_error_notice();
				}
				return false;
			}
		}

		if ( ! $silent && $no_changes ) {
			$this->save_no_changes_notice();
		} else {
			$this->save_success_notice();
		}
		return true;
	}

	/* Dashboard notices */

	/**
	 * Displays standar WordPress dashboard notice.
	 *
	 * @param string $message Message to display.
	 * @param string $level Can be error, warning, info or success.
	 * @param bool   $dismissible determines if the notice can be dismissed via javascript.
	 */
	public function notice( $message, $level = 'info', $dismissible = true ) {

		$this->notice_message = $message;

		if ( ! in_array( $level, array( 'error', 'warning', 'info', 'success' ), true ) ) {
			$level = 'info';
		}
		$this->notice_class = 'notice notice-' . $level;
		if ( $dismissible ) {
			$this->notice_class .= ' is-dismissible';
		}

		add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}
	/**
	 * Callback function for the admin_notices action in the notice() function.
	 */
	public function display_notice() {
		if ( ! empty( $this->notice_message ) ) {
		?>
		<div class="<?php echo esc_html( $this->notice_class ); ?>">
			<p><?php echo esc_html( $this->notice_message ); ?></p>
		</div>
		<?php
		}
	}
	/**
	 * Displays a generic 'Options saved!' success notice
	 */
	protected function save_success_notice() {
		$this->notice( __( 'Options saved!', 'simple-checkout-digital-goods' ), 'success' );
	}
	/**
	 * Displays a generic save error notice
	 */
	protected function save_error_notice() {
		$this->notice( __( 'Error saving configuration to database.', 'simple-checkout-digital-goods' ), 'error' );
	}
	/**
	 * Displays a generic 'No changes were needed.' info notice
	 */
	protected function save_no_changes_notice() {
		$this->notice( __( 'No changes were needed.', 'simple-checkout-digital-goods' ), 'info' );
	}

	/* utils */
	/**
	 * Checks if two arrays are exactly equal
	 *
	 * @param array $a first array to compare.
	 * @param array $b second array to compare.
	 */
	public function is_equal_array( $a, $b ) {
		return (
			is_array( $a ) && is_array( $b ) &&
			count( $a ) === count( $b ) &&
			array_diff( $a, $b ) === array_diff( $b, $a )
		);
	}
}
