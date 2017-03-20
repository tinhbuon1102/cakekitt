<link rel='stylesheet' id='validation_engine_css-css' href='<?php echo get_stylesheet_directory_uri()?>/css/validationEngine.jquery.css' type='text/css' media='all' />
<script type='text/javascript' src='<?php echo get_stylesheet_directory_uri()?>/js/jquery.validationEngine.js'></script>
<script type='text/javascript' src='<?php echo get_stylesheet_directory_uri()?>/js/jquery.validationEngine-ja.js'></script>
<script>
var gl_templateUrl = '<?= get_stylesheet_directory_uri(); ?>';
var gl_ajaxUrl = '<?= admin_url('admin-ajax.php');  ?>';
</script>
<?php

$field_mappings = getCustomFormFieldMapping();
function storePriceSubmit ()
{
	// validate
// 	update_option('cake_custom_price', array());
	$myKey = '';
	if (isset($_POST['price']))
	{
		if (in_array($_POST['price']['type']['custom_order_cake_shape'], getArrayRoundShape()))
		{
			unset($_POST['price']['type']['custom_order_cakesize_square']);
		}
		else {
			unset($_POST['price']['type']['custom_order_cakesize_round']);
		}
		
		$myKey .= implode('_', array_keys($_POST['price']['type'])) .'__'. implode('_', $_POST['price']['type']);
	}
	
	if (!($_POST['price']) || !($_POST['price']['type'])) return ;
	
	// Get key by type
	$cakePrices = get_option('cake_custom_price');
	$cakePrices = is_array($cakePrices) ? $cakePrices : array();
	
	if (!isset($cakePrices[($myKey)]))
		$cakePrices[($myKey)] = $_POST['price'];
	
	
	ksort($cakePrices);
	update_option('cake_custom_price', $cakePrices);
}

function deleteCustomPrice() {
	$cakePrices = get_option('cake_custom_price');
	if (!empty($cakePrices))
	{
		$cakePricesTmp = $cakePrices;
		foreach ($cakePricesTmp as $priceKey => $cakePrice)
		{
			if ($_REQUEST['delete'] == $priceKey)
			{
				unset($cakePrices[$priceKey]);
				break;
			}
		}
	}
	update_option('cake_custom_price', $cakePrices);
}

if ( ! empty($_POST) )
{
	storePriceSubmit();
}
if ($_REQUEST['delete']){
	deleteCustomPrice();
}

$cakePrices = get_option('cake_custom_price');
$cakePrices = is_array($cakePrices) ? $cakePrices : array();
?>
<style>
.acf_input .col0 {
	width: 130px;
}

.acf_input .col1, .acf_input .col1 select{
	width: 180px;
}

.acf_input .col2 {
	width: 200px;
}

.acf_input .col3, .acf_input .col3 input {
	width: 130px;
}

.acf_input .col4{
	text-align: right; 
	margin-right: 20px;
}

#acf-field-price-type_custom_order_cakesize_square, #acf-field-price-type_custom_order_cakesize_round {
	display: none;
}
</style>
<br /><br />
<h1><?php _e('Add new Price', 'cake')?></h1>
<form method="post" action="edit.php?post_type=cakegal&page=cake-price-combination" id="price_combine_form" class="form_price">
	<table class="acf_input widefat" style="width: 80%">
		<tbody>
			<tr id="shape_price_row">
				<td class="col0"><?php _e('Shape/Size Price', 'cake')?></td>
				<td class="cake-shape col1"><?php
				
				// create field
				$args = array(
					'type' => 'select',
					'name' => 'price[type][custom_order_cake_shape]',
					'class' => 'validate[required]',
					'value' => @$_POST['price']['type']['custom_order_cake_shape'],
					'choices' => $field_mappings['custom_order_cake_shape']['value']
				);
				
				kitt_acf_render_field_wrap( $args);
				
				?></td>
				<td class="cake-size col2"><?php
				
				// create field
				$args = array(
					'type' => 'select',
					'name' => 'price[type][custom_order_cakesize_square]',
					'class' => 'validate[required]',
					'choices' => array('' => __('Select Size'))
				);
				
				kitt_acf_render_field_wrap( $args);
				
				// create field
				$args = array(
					'type' => 'select',
					'name' => 'price[type][custom_order_cakesize_round]',
					'class' => 'validate[required]',
					'choices' => array('' => __('Select Size'))
				);
				
				kitt_acf_render_field_wrap( $args);
				
				?></td>
				<td class="col3">
				<?php
				
				$args = array(
					'type' => 'text',
					'name' => 'price[amount]',
					'class' => 'validate[required,custom[number]]',
					'placeholder' => __('Enter Price', 'cake')
				);
				
				kitt_acf_render_field_wrap( $args);
				?>
				</td>
				<td class="add col4">
					<input type="submit" class="location-add-rule button" value="<?php _e("Add Price",'acf'); ?>" />
				</td>
			</tr>
		</tbody>
	</table>
</form>
<form method="post" action="edit.php?post_type=cakegal&page=cake-price-combination" id="price_combine_form_decorate" class="form_price">
	<table class="acf_input widefat" style="width: 80%">
		<tbody>
			<tr id="decorate_price_row">
				<td class="col0"><?php _e('Decoration', 'cake')?></td>
				<td class="cake-shape col1"><?php
				
				// create field
				$decorateChoices = $field_mappings['custom_order_cake_decorate']['value'];
				foreach ($field_mappings['custom_order_cake_decorate']['value'] as $decorateKey => $decorateVal)
				{
					if (in_array(('custom_order_cake_decorate' . '__' . $decorateKey), array_keys($cakePrices)))
					{
						unset($decorateChoices[$decorateKey]);
					}
				}
				$args = array(
					'type' => 'select',
					'name' => 'price[type][custom_order_cake_decorate]',
					'class' => 'validate[required]',
					'choices' => $decorateChoices
				);
				
				kitt_acf_render_field_wrap( $args);
				
				?></td>
				<td class="col2"></td>
				<td class="col3">
				<?php
				
				$args = array(
					'type' => 'text',
					'name' => 'price[amount]',
					'class' => 'validate[required,custom[number]]',
					'placeholder' => __('Enter Price', 'cake')
				);
				
				kitt_acf_render_field_wrap( $args);
				?>
				</td>
				<td class="add col4">
					<input type="submit" class="location-add-rule button" value="<?php _e("Add Price",'acf'); ?>" />
				</td>
			</tr>
		</tbody>
	</table>
</form>
<div class="submit">
	<input type="hidden" name="add" value="1" />
</div>
<br />

<h1><?php _e('Price added')?></h1>
<div class="table_price_wraper">
<table class="widefat attributes-table wp-list-table ui-sortable" style="width: 100%">
	<thead>
		<tr>
			<th scope="col">Type</th>
			<th scope="col">Price</th>
			<th scope="col">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cakePrices as $cakePrice) {?>
		<tr class="alternate">
			<td><?php 
			if($cakePrice['type']['custom_order_cake_shape']) 
			{
				echo __('Shape/Size', 'cake') . ': '; 
			}
					
			if($cakePrice['type']['custom_order_cake_decorate']) 
			{
				echo __('Decoration', 'cake') . ': '; 
			}
			
			$aShowTypes = array();
			if (is_array($cakePrice['type']))
			{
				foreach ($cakePrice['type'] as $typeName => $typeVal)
				{
					$aShowTypes[] = $field_mappings[$typeName]['value'][$typeVal];
				}
			}
			echo implode(' / ', $aShowTypes);
			?> 
			</td>
			<td><?php echo showCakePrice($cakePrice['amount'])?></td>
			<td class="attribute-actions">
				<a href="edit.php?post_type=cakegal&page=cake-price-combination&delete=<?php echo (is_array($cakePrice['type']) ? implode('_', array_keys($cakePrice['type'])) .'__'. implode('_', $cakePrice['type']) : '');?>" class="location-add-rule button delete_price">Delete</a>
			</td>
		</tr>
		<?php }?>
	</tbody>
</table>
</div>
<script type="text/javascript">
	jQuery(function($){
		$('body').on('click', '.delete_price', function(e){
			e.preventDefault();
			if (confirm('<?php echo __('Are you sure you want to delete this ?', 'cake')?>'))
			{
				location.href = $(this).attr('href');
			}
		});
		
		$(".form_price").validationEngine({promptPosition: 'inline', addFailureCssClassToField: "inputError", bindMethod:"live"});
		$('body').on('change', '#acf-field-price-type_custom_order_cake_shape', function(){
			
			$('#acf-field-price-type_custom_order_cakesize_square').fadeOut();
			$('#acf-field-price-type_custom_order_cakesize_round').fadeOut();
			
			var shapeElement = $(this);
			var formData = $(this).closest('form').serialize();
			formData += '&action=get_size_cake_shape_price';
			$.ajax({
            	url: gl_ajaxUrl,
            	data: formData, 
                method: 'POST',
                dataType: 'html',
                success: function(response){
                	var roundGroup = <?php echo json_encode(getArrayRoundShape())?>;
        			if (roundGroup.indexOf(shapeElement.val()) != -1)
        			{
        				$('#acf-field-price-type_custom_order_cakesize_square').attr('disabled', true);
        				$('#acf-field-price-type_custom_order_cakesize_round').attr('disabled', false);
        				$('#acf-field-price-type_custom_order_cakesize_round').fadeIn();
        			}
        			else {
        				$('#acf-field-price-type_custom_order_cakesize_square').attr('disabled', false);
        				$('#acf-field-price-type_custom_order_cakesize_round').attr('disabled', true);
        				$('#acf-field-price-type_custom_order_cakesize_square').fadeIn();
        			}

                    
                	$('#acf-field-price-type_custom_order_cakesize_square').html(response);
                	$('#acf-field-price-type_custom_order_cakesize_round').html(response);
                }
            });
			
		});
		$('#acf-field-price-type_custom_order_cake_shape').trigger('change');
	});
</script>
