<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap" style="max-width: 1000px;">

	<h1><?php _e('Simple Checkout for Digital Goods','simple-checkout-digital-goods') ?></h1>
	<hr class="wp-header-end">
	
	<p><?php _e('There are some cases where you don\'t need all the billing fields of a digital product checkout. This plugin lets you remove some fields of the checkout page when all the products in the cart are downloadable or virtual.','simple-checkout-digital-goods') ?></p>
	<p><?php _e('Please keep in mind that you will need those fields for invoicing if you are charging for the products.','simple-checkout-digital-goods') ?></p>

	<h2><?php _e('Check the fields you want to remove from the checkout page','simple-checkout-digital-goods') ?></h2>
	
	<div class="feature-section">
		<form id="scdg-options" action="" method="POST">

			<?php wp_nonce_field( 'scdg_save_options' ); ?>

			<input type="hidden" name="scdg-saved" value="true">

			<table class="wp-list-table widefat fixed striped posts scdg-options-table scdg-table">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="scdg_checkall_fld" <?php if ($this->all_options_checked) echo 'checked' ?>></td>
					<th style="width: 20%;" class="column-title manage-column column-primary"><?php _e('Field to hide','simple-checkout-digital-goods') ?></th>
					<th><?php _e('Visible label','machete') ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($this->disabled_fields_array as $option_slug => $option_title){ ?>
				<tr>
					<th scope="row" class="check-column"><input type="checkbox" name="optionEnabled[]" value="<?php echo $option_slug ?>" id="<?php echo $option_slug ?>_fld" <?php if (! in_array($option_slug, $this->settings)) echo 'checked' ?>></th>
					<td class="column-title column-primary"><strong><?php echo $option_slug ?></strong>
					<button type="button" class="toggle-row"><span class="screen-reader-text"><?php _e('Show more details','simple-checkout-digital-goods') ?></span></button>
					</td>
					<td data-colname="<?php _e('Explanation','simple-checkout-digital-goods') ?>"><?php echo $option_title ?></td>
				</tr>

			<?php } ?>

			</tbody>
			</table>
		
			<?php submit_button(); ?>
		</form>

	</div>
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