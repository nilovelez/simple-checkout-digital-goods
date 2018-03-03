<?php
/**
 * Configuration screen contents

 * @package simple-checkout-digital-goods
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap" style="max-width: 1000px;">

	<h1><?php esc_html_e( 'Simple Checkout for Digital Goods', 'simple-checkout-digital-goods' ); ?></h1>
	<hr class="wp-header-end">

	<p><?php esc_html_e( 'There are some cases where you don\'t need all the billing fields of a digital product checkout. This plugin lets you remove some fields of the checkout page when all the products in the cart are downloadable or virtual.', 'simple-checkout-digital-goods' ); ?></p>

	<form id="scdg-options" action="" method="POST">

		<?php wp_nonce_field( 'scdg_save_options' ); ?>

		<input type="hidden" name="scdg-saved" value="true">

		<div class="feature-section">

			<table class="form-table">
				<tbody>
					<tr>
					<th scope="row"><?php esc_html_e( 'Remove from non-free orders?', 'simple-checkout-digital-goods' ); ?></th>
					<td><fieldset>
						<label>
							<input name="include_non_free" value="no" type="radio"
							<?php checked( 'no', $this->settings['include_non_free'], true ); ?>>
							<strong><?php esc_html_e( 'Remove only from free orders', 'simple-checkout-digital-goods' ); ?></strong> - <?php esc_html_e( 'Billing fields will be removed only if the cart total is zero.', 'simple-checkout-digital-goods' ); ?>
						</label><br>

						<label>
							<input name="include_non_free" value="yes" type="radio"
							<?php checked( 'yes', $this->settings['include_non_free'], true ); ?>>
							<strong><?php esc_html_e( 'Remove from all orders', 'simple-checkout-digital-goods' ); ?></strong> - <?php esc_html_e( 'The shipping fields will be removed from all orders, including those that are not free.', 'simple-checkout-digital-goods' ); ?><br><span style="color: #ff0000"><?php esc_html_e( 'Please keep in mind that you will need billing fields for invoicing if you are charging for the products.', 'simple-checkout-digital-goods' ); ?></span>
						</label><br>

					</fieldset></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="feature-section">

			<h2><?php esc_html_e( 'Check the fields you want to remove from the checkout page', 'simple-checkout-digital-goods' ); ?></h2>

			<table class="wp-list-table widefat fixed striped posts scdg-options-table scdg-table">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="scdg_checkall_fld" <?php checked( true, $this->all_options_checked, true ); ?>></td>
					<th style="width: 20%;" class="column-title manage-column column-primary"><?php esc_html_e( 'Field to hide', 'simple-checkout-digital-goods' ); ?></th>
					<th><?php esc_html_e( 'Visible label', 'simple-checkout-digital-goods' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $this->excluded_fields_array as $option_slug => $option_title ) { ?>
				<tr>
					<th scope="row" class="check-column"><input type="checkbox" name="optionEnabled[]" value="<?php echo esc_attr( $option_slug ); ?>" id="<?php echo esc_attr( $option_slug . '_fld' ); ?>" <?php checked( false, in_array( $option_slug, $this->settings['excluded_fields'], true ), true ); ?>></th>
					<td class="column-title column-primary"><strong><?php echo esc_html( $option_slug ); ?></strong>
					<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'simple-checkout-digital-goods' ); ?></span></button>
					</td>
					<td data-colname="<?php esc_html_e( 'Explanation', 'simple-checkout-digital-goods' ); ?>"><?php echo esc_html( $option_title ); ?></td>
				</tr>

			<?php } ?>

			</tbody>
			</table>
		</div>

		<?php submit_button(); ?>
	</form>

</div>

<script>
(function($){

	$('#scdg-options .scdg-table :checkbox').change(function() {
		// this will contain a reference to the checkbox
		console.log(this.id); 
		var checkBoxes = $("#scdg-options .scdg-table input[name=optionEnabled\\[\\]]");

		if (this.id == 'scdg_checkall_fld'){
			if (this.checked) {
				checkBoxes.prop("checked", true);
			} else {
				checkBoxes.prop("checked", false);
				// the checkbox is now no longer checked
			}
		}else{
			var checkBoxes_checked = $("#scdg-options .scdg-table input[name=optionEnabled\\[\\]]:checked");
			if(checkBoxes_checked.length == checkBoxes.length){
				$('#scdg_checkall_fld').prop("checked", true);
			}else{
				$('#scdg_checkall_fld').prop("checked", false);
			}
		}
	});
})(jQuery);
</script>
