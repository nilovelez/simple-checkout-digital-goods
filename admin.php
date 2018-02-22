<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">

	<h1><?php _e('Simple Checkout for Digital Goods','simple-checkout-digital-goods') ?></h1>
	<hr class="wp-header-end">
	
	<p><?php _e('WordPress has a los of code just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer','simple-checkout-digital-goods') ?></p>
	
	<div class="feature-section">
		<form id="scdg-options" action="" method="POST">

			<?php wp_nonce_field( 'scdg_save_options' ); ?>

			<input type="hidden" name="scdg-saved" value="true">

			<table class="wp-list-table widefat fixed striped posts scdg-options-table scdg-table">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="scdg_checkall_fld" <?php if ($this->all_options_checked) echo 'checked' ?>></td>
					<th style="width: 20%;" class="column-title manage-column column-primary"><?php _e('Campo','simple-checkout-digital-goods') ?></th>
					<th><?php _e('Texto visible','machete') ?></th>
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