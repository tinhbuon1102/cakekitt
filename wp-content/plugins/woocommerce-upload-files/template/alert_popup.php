<a id="wcuf_show_popup_button" style="display:none;" href="#wcuf_alert_popup"></a> 
<div id="wcuf_alert_popup" class="mfp-hide">
	<!--<a href= "#"  id="wcuf_upper_close_button" class="mfp-close">X</a>-->
	<h4 id="wcuf_alert_popup_title"><?php _e('Warning', 'woocommerce-files-upload'); ?></h4>
	<div id="wcuf_alert_popup_content"></div>
	<?php if($all_options['allow_user_to_leave_page_in_case_of_required_field']=='no'): ?>
		<button class="button" id="wcuf_close_popup_alert" class="mfp-close"><?php _e('OK', 'woocommerce-files-upload'); ?></button>
	<?php else: ?>
		<button class="button" id="wcuf_leave_page" class="mfp-close"><?php _e('OK', 'woocommerce-files-upload'); ?></button>
	<?php endif; ?>
</div>