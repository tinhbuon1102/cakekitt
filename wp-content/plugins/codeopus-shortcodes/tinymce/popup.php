<?php

// loads the shortcodes class, wordpress is loaded with it
require_once( 'shortcodes.class.php' );

// get popup type
$popup = trim( $_GET['popup'] );
$shortcode = new codeopus_shortcodes( $popup );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>
<body>
<div id="codeopus-popup">

	<div id="codeopus-shortcode-wrap">
		
		<div id="codeopus-sc-form-wrap">
		
			<div id="codeopus-sc-form-head">
			
				<?php echo $shortcode->popup_title; ?>
			
			</div>
			<!-- /#codeopus-sc-form-head -->
			
			<form method="post" id="codeopus-sc-form">
			
				<table id="codeopus-sc-form-table">
				
					<?php echo $shortcode->output; ?>
					
					<tbody>
						<tr class="form-row">
							<?php if( ! $shortcode->has_child ) : ?><td class="label">&nbsp;</td><?php endif; ?>
							<td class="field"><a href="#" class="codeopus-insert"><?php _e('Insert Shortcode','codeopus');?></a></td>							
						</tr>
					</tbody>
				
				</table>
				<!-- /#codeopus-sc-form-table -->
				
			</form>
			<!-- /#codeopus-sc-form -->
		
		</div>
		<!-- /#codeopus-sc-form-wrap -->
		
		<div class="clear"></div>
		
	</div>
	<!-- /#codeopus-shortcode-wrap -->

</div>
<!-- /#codeopus-popup -->

</body>
</html>