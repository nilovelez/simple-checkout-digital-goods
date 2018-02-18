<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">

	<h1><?php _e('Simple Checkout for Digital Goods','simple-checkout-digital-goods') ?></h1>
	
	<p class="tab-description"><?php _e('WordPress has a los of code just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer','machete') ?></p>
	
	<div class="feature-section">
		<form id="machete-cleanup-options" action="" method="POST">

			<?php wp_nonce_field( 'machete_save_cleanup' ); ?>

			<input type="hidden" name="machete-cleanup-saved" value="true">

		<h3><?php _e('Header Cleanup','machete') ?>  <span class="machete_security_badge machete_safe_badge"><?php _e('Completely safe','machete') ?></span></h3>

		<p><?php _e('This section removes code from the &lt;head&gt; tag. This makes your site faster and reduces the amount of information revealed to a potential attacker.','machete') ?></p>

		
		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-cleanup-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_cleanup_checkall_fld" <?php if ($this->all_cleanup_checked) echo 'checked' ?>></td>
				<th class="column-title manage-column column-primary"><?php _e('Remove','machete') ?></th>
				<th><?php _e('Explanation','machete') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($this->cleanup_array as $option_slug => $option){ ?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="optionEnabled[]" value="<?php echo $option_slug ?>" id="<?php echo $option_slug ?>_fld" <?php if (in_array($option_slug, $this->settings)) echo 'checked' ?>></th>
				<td class="column-title column-primary"><strong><?php echo $option['title'] ?></strong>
				<button type="button" class="toggle-row"><span class="screen-reader-text"><?php _e('Show more details','machete') ?></span></button>
				</td>
				<td data-colname="<?php _e('Explanation','machete') ?>"><?php echo $option['description'] ?></td>
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


    $('#machete-cleanup-options .machete-cleanup-table :checkbox').change(function() {
	    // this will contain a reference to the checkbox
	    console.log(this.id); 
	    var checkBoxes = $("#machete-cleanup-options .machete-cleanup-table input[name=optionEnabled\\[\\]]");

	    if (this.id == 'machete_cleanup_checkall_fld'){
			if (this.checked) {
				checkBoxes.prop("checked", true);
			} else {
				checkBoxes.prop("checked", false);
				// the checkbox is now no longer checked
			}
	    }else{
	    	var checkBoxes_checked = $("#machete-cleanup-options .machete-cleanup-table input[name=optionEnabled\\[\\]]:checked");
	    	if(checkBoxes_checked.length == checkBoxes.length){
	    		$('#machete_cleanup_checkall_fld').prop("checked", true);
	    	}else{
	    		$('#machete_cleanup_checkall_fld').prop("checked", false);
	    	}
	    }
	});


	$('#machete-cleanup-options .machete-optimize-table :checkbox').change(function() {
	    // this will contain a reference to the checkbox
	    console.log(this.id); 
	    var checkBoxes = $("#machete-cleanup-options .machete-optimize-table input[name=optionEnabled\\[\\]]");

	    if (this.id == 'machete_optimize_checkall_fld'){
			if (this.checked) {
				checkBoxes.prop("checked", true);
			} else {
				checkBoxes.prop("checked", false);
				// the checkbox is now no longer checked
			}
	    }else{
	    	var checkBoxes_checked = $("#machete-cleanup-options .machete-optimize-table input[name=optionEnabled\\[\\]]:checked");
	    	if(checkBoxes_checked.length == checkBoxes.length){
	    		$('#machete_optimize_checkall_fld').prop("checked", true);
	    	}else{
	    		$('#machete_optimize_checkall_fld').prop("checked", false);
	    	}
	    }
	});


})(jQuery);
</script>