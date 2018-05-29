<?php 
$userID = (int) get_current_user_id();
$user_data = get_userdata( $userID );

$meta_query_args = array(
	'relation' => 'AND',
	array(
		'key'     => '_customer_user',
		'value'   => $userID,
		'compare' => '='
	),
	array(
		'key'     => 'cake_custom_order',
		'value'   => '',
		'compare' => '!='
	)
);

$customer_orders = get_posts( array(
	'numberposts' => 1,
	'meta_query'    => $meta_query_args,
	'post_type'   => wc_get_order_types(),
	'post_status' => array_keys( wc_get_order_statuses() ),
) );

// Reset session form 
$_SESSION['cake_custom_order'] = array();
$field_mappings = getCustomFormFieldMapping();

$aStates = getCountryState();
$default_county_states = $aStates['states'];

$post_id = isset($_REQUEST['post_id']) ? $_REQUEST['post_id'] : '';
$inspired_pic = get_the_post_thumbnail_url($post_id);
// Store to temp
if ($inspired_pic)
{
	$upload_dir = wp_upload_dir();
	$file_name = uniqid() . '_' . basename($inspired_pic);
	$temp_folder = $upload_dir['basedir'] . '/temp/';
	$dest_file = $temp_folder . $file_name;
	
	$tmp_img = file_get_contents($inspired_pic);
	file_put_contents($dest_file, $tmp_img);
	$inspired_pic = $upload_dir['baseurl'] . '/temp/' . $file_name;
}
$yearMonthDays = kitt_get_year_month_day();
$current_year = date('Y');
?>

<script type="text/javascript">
	var field_mappings = <?php echo json_encode($field_mappings)?>;
	var roundGroup = <?php echo json_encode(getArrayRoundShape())?>;
	var is_loggedin = <?php echo (int)is_user_logged_in();?>;
</script>
<div class="col-md-12">
	<div id="four_steps" class="steps">
		<div class="step first" data-step="1">
			<div class="circle">1</div>
			<div class="text">STEP1</div>
		</div>
		<div class="step second" data-step="2">
			<div class="circle">2</div>
			<div class="text">STEP2</div>
		</div>
		<div class="step third" data-step="3">
			<div class="circle">3</div>
			<div class="text">STEP3</div>
		</div>
	</div>
</div>
<div class="col-md-8 columns">
	<form action="#" name="omc_order" method="post" id="omOrder" class="form-style-common" novalidate>
	<!--<div id="progress_bar" class="step order-step">
			<div id="progress"></div>
			<div id="progress_text">0% Complete</div>
		</div>-->
		<div id="first_step" class="step_wraper" data-step="1">
			<h1 class="order-heading"><?php echo __('Select Cake Type', 'cake')?></h1>
			<div class="m-section_content_selectOption">
				<ul class="cake-type">
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">1</span>
							<span class="display-table-cell pl-2"><?php echo __('Choose cake type', 'cake')?></span>
						</h4>
						<small class="help-block">ケーキのタイプを1つ選択してください</small>
						<ul class="type-cake c-list_3Column">
							<?php 
							$cakeTypesArg = array(
								'taxonomy' => 'cakegal_taxonomy',
								'hide_empty' => false,
								'orderby'           => 'slug',
								'order'             => 'ASC',
							);
							$terms = get_terms($cakeTypesArg); // Get all terms of a taxonomy
							if ( $terms && !is_wp_error( $terms ) ){
							?>	
								<?php foreach ( $terms as $term_index => $term ) { 
									if (strpos($term->slug, 'cake_type_') === false) continue;
									
									// Auto set cake_type_f checked
									$_REQUEST['type'] = 'cake_type_f';
								?>
									<li class="m-input__radio">
										<input type="radio" name="custom_order_cake_type" id="<?php echo $term->slug?>" class="radio_input validate[required]" value="<?php echo $term->slug?>" <?php echo $_REQUEST['type'] == $term->slug ? 'checked' : ''?> >
										<label for="<?php echo $term->slug?>" class="js-fixHeightChildText radio_label <?php echo $term->slug?>">
											<div class="radio_option radio_size">
												<div class="radio_img rounded">
													<?php echo WPCustomCategoryImage::get_category_image(array(
											                'term_id' => $term->term_id,
											                'size'    => 'thumbnail',
											            ))?>
												</div>
												<h3 class="js-fixHeightChildTitle radio_option_caption">
													<span class="caption_wrap"><?php echo $term->name?></span>
												</h3>
											</div>
										</label>
									</li>
								<?php }?>
							<?php
							}
							?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<!-- #second_step -->
		<div id="second_step" class="step_wraper" data-step="2">
			<h1 class="order-heading"><?php echo __('About Cake Design', 'cake')?></h1>
			<div class="m-section_content_selectOption">
				<ul class="about-design">
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">1</span>
							<span class="display-table-cell pl-2"><?php echo __('Choose shape', 'cake')?></span>
						</h4>
						<ul class="cake-shape text-radio list-type round-icon-select col_5">
							<?php 
							$cake_shape_index = 0;
							foreach ($field_mappings['custom_order_cake_shape']['value'] as $value => $label) {
								$cake_shape_index ++;
							?>
								<li class="m-input__radio cake_shape_<?php echo $value?>">
									<input type="radio" name="custom_order_cake_shape" id="cake_shape_<?php echo $value?>" class="radio_input validate[required]" <?php echo $field_mappings['custom_order_cake_shape']['field']['default_value'] == $value   ? '' : ''?> value="<?php echo $value?>">
									<label for="cake_shape_<?php echo $value?>" class="js-fixHeightChildText radio_label cake_shape_<?php echo $value?>">
                                    <div id="fixwh-inner">
										<div class="radio_option radio_size">
                                        <div class="center-middle-fix">
                                        <div class="select-icon"><i class="show-icon size50"></i></div>
											<h5 class="js-fixHeightChildTitle radio_option_caption">
												<span class="caption_wrap"><?php echo $label?></span>
											</h5>
                                            </div><!--/center-middle-fix-->
										</div><!--/radio_option radio_size-->
                                        </div><!--/fixwh-inner-->
									</label>
									
									<?php if ($value == 'custom') {?>
									<div id="shape_custom" class="suboption_box disable">
										<textarea name="custom_order_cake_shape_custom" class="subinfo txtLL empty validate[required]" placeholder="ご希望の形についてご記入ください。"></textarea>
									</div>
									<?php }?>
								</li>
							<?php }?>
						</ul>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">2</span>
							<span class="display-table-cell pl-2"><?php echo __('Choose flavor', 'cake')?></span>
						</h4>
						<ul class="cake-flavor text-radio list-type round-icon-select col_5">
							<?php 
							$index = 0;
							foreach ($field_mappings['custom_order_cakeflavor']['value'] as $value => $label) {
								$index ++;
							?>
								<li class="m-input__radio">
									<input type="radio" name="custom_order_cakeflavor" id="cake_flavor_<?php echo $value?>" class="radio_input validate[required]" <?php echo $field_mappings['custom_order_cakeflavor']['field']['default_value'] == $value ? 'checked' : ''; ?> value="<?php echo $value?>">
									<label for="cake_flavor_<?php echo $value?>" class="js-fixHeightChildText radio_label cake_flavor_<?php echo $value?>">
                                    <div id="fixwh-inner">
										<div class="radio_option radio_size">
                                        <div class="center-middle-fix">
                                        <div class="select-icon"><i class="show-icon size50"></i></div>
											<h5 class="js-fixHeightChildTitle radio_option_caption">
												<span class="caption_wrap"><?php echo $label?></span>
											</h5>
                                            </div><!--/center-middle-fix-->
										</div><!--/radio_option radio_size-->
                                         </div><!--/fixwh-inner-->
									</label>
								</li>
							<?php }?>
							
						</ul>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">3</span>
							<span class="display-table-cell pl-2"><?php echo __('Choose Size', 'cake')?></span>
						</h4>
						<div class="cake-layer select-wrapper">
							<select name="custom_order_layer" class="form-control select select-primary" data-toggle="select">
								<!--<option value=""><?php //echo __('Select Layer', 'cake')?></option>-->
								<?php 
								$index = 0;
								foreach ($field_mappings['custom_order_layer']['value'] as $value => $label) {
									$index ++
								?>
									<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_layer']['field']['default_value'] == $value  ? 'selected' : ''; ?>>
										<?php echo $label?>
									</option>
								<?php }?>
							</select>
						</div>
						<div class="cake-size select-wrapper">
							<select name="custom_order_cakesize_square" class="form-control select select-primary disable" data-toggle="select">
								<option value=""><?php echo __('Select Size', 'cake')?></option>
								<!--for round shape-->
								<?php 
								$index = 0;
								foreach ($field_mappings['custom_order_cakesize_square']['value'] as $value => $label) {
									$index ++;
									if ($index == 1) continue;
								?>
									<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_cakesize_square']['field']['default_value'] == $value  ? 'selected' : ''; ?>>
										<?php echo $label?>
									</option>
								<?php }?>
							</select>
							
							<select name="custom_order_cakesize_round" class="form-control select select-primary validate[required]" data-toggle="select">
								<option value=""><?php echo __('Select Size', 'cake')?></option>
								<!--for round shape-->
								<?php 
								$index = 0;
								foreach ($field_mappings['custom_order_cakesize_round']['value'] as $value => $label) {
									$index ++;
									if ($index <= KITT_CAKESIZE_ROUND_FOR_LAYER_1) continue;
								?>
									<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_cakesize_round']['field']['default_value'] == $value  ? 'selected' : ''; ?>>
										<?php echo $label?>
									</option>
								<?php }?>
							</select>
							
							<select name="custom_order_cakesize_heart" class="form-control select select-primary disable" data-toggle="select">
								<option value=""><?php echo __('Select Size', 'cake')?></option>
								<!--for heart shape-->
								<?php 
								$index = 0;
								foreach ($field_mappings['custom_order_cakesize_heart']['value'] as $value => $label) {
									$index ++;
								?>
									<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_cakesize_heart']['field']['default_value'] == $value  ? 'selected' : ''; ?>>
										<?php echo $label?>
									</option>
								<?php }?>
							</select>
							<div class="disable text-notice-layer">
								
							</div>
						</div>
						
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">4</span>
							<span class="display-table-cell pl-2"><?php _e( 'Choose color', 'cake' ); ?></span>
						</h4>
						<ul class="cake-color text-radio list-type">
							<?php 
							$index = 0;
							foreach ($field_mappings['custom_order_cakecolor']['value'] as $value => $label) {
								$index ++;
							?>
								<li class="m-input__radio">
								<input type="radio" name="custom_order_cakecolor" id="cake_color_<?php echo $value?>" class="radio_input validate[required]" 
									<?php echo $field_mappings['custom_order_cakecolor']['field']['default_value'] == $value  ? 'checked' : ''; ?> 
									value="<?php echo $value?>" <?php echo ($value == 'other') ? 'has_subop' : ''?>>
									<label for="cake_color_<?php echo $value?>" class="js-fixHeightChildText radio_label cake_color_<?php echo $value?>">
										<div class="radio_option radio_size">
											<h5 class="js-fixHeightChildTitle radio_option_caption">
												<span class="caption_wrap"><?php echo $label?></span>
											</h5>
										</div>
									</label>
									
									<?php if ($value == 'other') {?>
									<div id="ColorOptionbox" class="suboption_box disable">
										<a href="#" class="btn btn-default cp-select" id="custom_order_color_picker"><?php _e( 'Color Picker', 'cake' ); ?></a>
										<input style="opacity: 0; width: 1px; height: 1px;" type="text" name="custom_order_cakecolor_other" id="custom_order_cakecolor_other" class="validate[required]" value=""/>
										<div class="selected-color"></div>
									</div>
									<?php }?>
								</li>
							<?php }?>
						</ul>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">5</span>
							<span class="display-table-cell pl-2"><?php echo $field_mappings['custom_order_printq']['field']['label']; ?></span>
						</h4>
						<ul class="cake-print text-radio list-type">
							<?php 
							$index = 0;
							foreach ($field_mappings['custom_order_printq']['value'] as $value => $label) {
								$index ++;
							?>
								<li class="m-input__radio">
									<input type="radio" name="custom_order_printq" id="custom_order_printq_<?php echo $value?>" class="radio_input validate[required]" 
									<?php echo $field_mappings['custom_order_printq']['field']['default_value'] == $value  ? 'checked' : ''?>
									value="<?php echo $value?>">
									<label for="custom_order_msgplate_<?php echo $value?>" class="js-fixHeightChildText radio_label">
										<div class="radio_option radio_size">
											<h5 class="js-fixHeightChildTitle radio_option_caption">
												<span class="caption_wrap"><?php echo $label?></span>
											</h5>
										</div>
									</label>
									<?php if ($value == 'print_no') {?>
									<?php }?>
									<?php if ($value == 'print_yes') {?>
									<div id="optionbox07" class="suboption_box disable upload_cakePic_wraper">
										<div class="sub_form">
											<ul class="inspired_images"></ul>
											<span class="option_label"><?php _e( 'Upload pictures', 'woocommerce' ); ?></span>
											<div class="image_loading"></div>
											<input type="file" class="filestyle upload_cakePic validate[required]" name="upload_cakePic" id="custom_order_photocakepic">
										</div>
									</div>
									<?php }?>
								</li>
							<?php }?>
						</ul>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">6</span>
							<span class="display-table-cell pl-2"><?php _e( 'Choose decorations', 'cake' ); ?></span>
						</h4>
						<ul class="cake-decorate text-radio list-type row">
							<?php 
							$indexDecorate = 0;
							foreach ($field_mappings['custom_order_cake_decorate']['value'] as $value => $label) {
								$indexDecorate ++;
							?>
							
								<li class="m-input__checkbox col-md-4 custom_order_cake_decorate_<?php echo $value?>">
									<input type="checkbox" name="custom_order_cake_decorate[<?php echo $indexDecorate - 1?>]" id="<?php echo $value?>" class="check_input checkbox_input labelauty has_subop" value="<?php echo $value?>" aria-label="<?php echo $label?>" data-labelauty="<?php echo $label?>">
									<!--<label for="<?php echo $value?>" class="js-fixHeightChildText checkbox_label <?php echo $value?>">
										<div class="check_option check_size">
											<h5 class="js-fixHeightChildTitle check_option_caption">
												<span class="caption_wrap"></span>
											</h5>
										</div>
									</label>-->
									
									<?php if ($value == 'icingcookie') {?>
									<div id="optionbox01" class="suboption_box disable">
										<div class="sub_form">
										<div class="select-wrapper">
												<select name="custom_order_icingcookie_qty" class="form-control select select-primary static-select validate[required]">
												<option value="" selected><?php _e( 'choose qty', 'woocommerce' ); ?></option>
													<?php 
													$index = 0;
													foreach ($field_mappings['custom_order_icingcookie_qty']['value'] as $value => $label) {
														$index ++;
													?>
														<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_icingcookie_qty']['field']['default_value'] == $value  ? 'selected' : ''; ?>>
															<?php echo $label?>
														</option>
													<?php }?>
												</select>
											</div>
										</div>
										<div class="sub_form">
											<textarea name="custom_order_basecolor_text" class="subinfo txtLL empty validate[required]" placeholder="ご希望の形・サイズ・色をご記入ください。"></textarea>
										</div>
									</div>
									<?php }?>
									
									<?php if ($value == 'cupcake') {?>
									<div id="optionbox02" class="suboption_box disable">
										<div class="sub_form">
											<div class="select-wrapper">
											<select name="custom_order_cupcake_qty" class="form-control select select-primary static-select validate[required]">
											<option value="" selected><?php _e( 'choose qty', 'woocommerce' ); ?></option>
											<?php 
											$index = 0;
											foreach ($field_mappings['custom_order_cupcake_qty']['value'] as $value => $label) {
												$index ++;
											?>
												<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_cupcake_qty']['field']['default_value'] == $value  ? 'selected' : ''; ?>>
													<?php echo $label?>
												</option>
											<?php }?>
											</select>
										</div>
										</div>
										<div class="sub_form">
											<textarea name="custom_order_cpck_text" class="subinfo txtLL empty validate[required]" placeholder="ご希望のデザイン詳細をご記入ください。"></textarea>
										</div>
									</div>
									<?php }?>
									
									<?php if ($value == 'macaron') {?>
									<div id="optionbox03" class="suboption_box disable">
										<div class="sub_form">
											
											<div class="select-wrapper">
											<select name="custom_order_macaron_qty" class="form-control select select-primary static-select validate[required]">
												<option value="" selected><?php _e( 'choose qty', 'woocommerce' ); ?></option>
												<?php 
												$index = 0;
												foreach ($field_mappings['custom_order_macaron_qty']['value'] as $value => $label) {
													$index ++;
												?>
													<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_macaron_qty']['field']['default_value'] == $value  ? 'selected' : ''; ?>>
														<?php echo $label?>
													</option>
												<?php }?>
											</select>
											</div>
										</div>
										<div class="sub_form">
											<ul class="macaron-color text-radio list-type row">
												<?php 
												$index = 0;
												foreach ($field_mappings['custom_order_macaron_color']['value'] as $value => $label) {
													$index ++;
												?>
													<li class="m-input__radio col-md-12">
														<input type="radio" name="custom_order_macaron_color" id="macaron_color_<?php echo $value?>" class="radio_input validate[required]"
														<?php echo $field_mappings['custom_order_macaron_color']['field']['default_value'] == $value  ? 'checked' : ''?>
														value="<?php echo $value?>">
														<label for="macaron_color_<?php echo $value?>" class="js-fixHeightChildText radio_label cake_color_white">
															<div class="radio_option radio_size">
																<h5 class="js-fixHeightChildTitle radio_option_caption">
																	<span class="caption_wrap"><?php echo $label?></span>
																</h5>
															</div>
														</label>
													</li>
												<?php }?>
											</ul>
										</div>
										
										<div class="sub_form" id="custom_order_macaron_color_text_wraper" style="display:none;">
											<textarea name="custom_order_macaron_color_text" class="subinfo txtLL empty validate[required]" placeholder="色の詳細"></textarea>
										</div>
										
									</div>
									<?php }?>
									
									<?php if ($value == 'fruit') {?>
									<div id="optionbox011" class="suboption_box disable">
										<div class="sub_form">
											<ul class="fruit-text text-radio list-type row">
												<?php 
												$index = 0;
												foreach ($field_mappings['custom_order_fruit_detail']['value'] as $value => $label) {
													$index ++;
												?>
													<li class="m-input__radio col-md-12">
														<input type="radio" name="custom_order_fruit_detail" id="fruit_detail_<?php echo $value?>" class="radio_input validate[required]" 
														<?php echo $field_mappings['custom_order_fruit_detail']['field']['default_value'] == $value  ? 'checked' : ''?>
														value="<?php echo $value?>">
														<label for="fruit_detail_<?php echo $value?>" class="js-fixHeightChildText radio_label">
															<div class="radio_option radio_size">
																<h5 class="js-fixHeightChildTitle radio_option_caption">
																	<span class="caption_wrap"><?php echo $label?></span>
																</h5>
															</div>
														</label>
													</li>
												<?php }?>
											</ul>
										</div>
										<div class="sub_form" id="custom_order_fruit_detail_text_wraper" style="display:none;">
											<textarea name="custom_order_fruit_detail_text" class="subinfo txtLL empty validate[required]" placeholder="色の詳細"></textarea>
										</div>
									</div>
									<?php }?>
									
									<?php if ($value == 'flower') {?>
									<div id="optionbox06" class="suboption_box disable">
										<div class="sub_form">
											<ul class="macaron-color text-radio list-type row">
												<?php 
												$index = 0;
												foreach ($field_mappings['custom_order_flowercolor']['value'] as $value => $label) {
													$index ++;
												?>
													<li class="m-input__radio col-md-12">
														<input type="radio" name="custom_order_flowercolor" id="flower_color_<?php echo $value?>" class="radio_input validate[required]" 
														<?php echo $field_mappings['custom_order_flowercolor']['field']['default_value'] == $value  ? 'checked' : ''?>
														value="<?php echo $value?>">
														<label for="flower_color_<?php echo $value?>" class="js-fixHeightChildText radio_label">
															<div class="radio_option radio_size">
																<h5 class="js-fixHeightChildTitle radio_option_caption">
																	<span class="caption_wrap"><?php echo $label?></span>
																</h5>
															</div>
														</label>
													</li>
												<?php }?>
											</ul>
										</div>
									</div>
									<?php }?>
									<?php if ($value == 'print') {?>
									<!--<div id="optionbox07" class="suboption_box disable upload_cakePic_wraper">
										<div class="sub_form">
											<ul class="inspired_images"></ul>
											<span class="option_label"><?php _e( 'Upload pictures', 'woocommerce' ); ?></span>
											<div class="image_loading"></div>
											<input type="file" class="filestyle upload_cakePic validate[required]" name="upload_cakePic" id="custom_order_photocakepic">
										</div>
									</div>-->
									<?php }?>
									
									<?php if ($value == 'candy') {?>
									<div id="optionbox08" class="suboption_box disable">
										<div class="sub_form">
											<textarea name="custom_order_candy_text" class="subinfo txtLL empty validate[required]" placeholder="ご希望のデザイン詳細をご記入ください。"></textarea>
										</div>
									</div>
									<?php }?>
									
									<?php if ($value == 'figure') {?>
									<div id="optionbox09" class="suboption_box disable">
										<div class="sub_form">
											<textarea name="custom_order_doll_text" class="subinfo txtLL empty validate[required]" placeholder="ご希望のデザイン詳細をご記入ください。"></textarea>
										</div>
									</div>
									<?php }?>
									
									<?php if ($value == 'chocolatedeco') {?>
									<div id="optionbox10" class="suboption_box disable">
										<div class="sub_form">
											<textarea name="custom_order_chocolatedeco_text" class="subinfo txtLL empty validate[required]" placeholder="ご希望のデザイン詳細をご記入ください。"></textarea>
										</div>
									</div>
									<?php }?>
								</li>
							<?php }?>
						</ul>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">7</span>
							<span class="display-table-cell pl-2"><?php _e( 'Message Plate', 'woocommerce' ); ?></span>
						</h4>
						<ul class="cake-message text-radio list-type">
							<?php 
							$index = 0;
							foreach ($field_mappings['custom_order_msgplate']['value'] as $value => $label) {
								$index ++;
							?>
								<li class="m-input__radio">
									<input type="radio" name="custom_order_msgplate" id="custom_order_msgplate_<?php echo $value?>" class="radio_input validate[required]" 
									<?php echo $field_mappings['custom_order_msgplate']['field']['default_value'] == $value  ? 'checked' : ''?>
									value="<?php echo $value?>">
									<label for="custom_order_msgplate_<?php echo $value?>" class="js-fixHeightChildText radio_label">
										<div class="radio_option radio_size">
											<h5 class="js-fixHeightChildTitle radio_option_caption">
												<span class="caption_wrap"><?php echo $label?></span>
											</h5>
										</div>
									</label>
									
									<?php if ($value == 'msg_no') {?>
										
									<?php }?>
									
									<?php if ($value == 'msg_yes') {?>
										<div id="MessageOptionbox" class="suboption_box disable">
											<textarea name="custom_order_msgpt_text_yes" class="subinfo txtLL empty validate[required]" placeholder="ご希望のメッセージをご記入ください。"></textarea>
										</div>
									<?php }?>
								</li>
							<?php }?>
						</ul>
					</li>
					<li class="main-option upload_cakePic_wraper">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">8</span>
							<span class="display-table-cell pl-2"><?php _e( 'Inspired Pics', 'woocommerce' ); ?></span>
						</h4>
						<ul id="inspired_images" class="inspired_images">
							<?php if ($inspired_pic) {?>
							<li>
								<img alt="" src="<?php echo $inspired_pic?>"   class="cake_upload_preview" />
								<input type="hidden" class="filestyle" name="custom_order_cakePic[]" value="<?php echo basename($inspired_pic)?>">
								<span class="glyphicon glyphicon-remove remove-image" ></span>
							<?php }?>
						</ul>
						<div id="image_loading" class="image_loading"></div>
						<input type="file" class="filestyle upload_cakePic" name="upload_cakePic" id="custom_order_cakePic[]">
					</li>
				</ul>
			</div>
		</div>
		<!-- #third_step -->
		<div id="third_step" class="step_wraper" data-step="3">
			<h1 class="order-heading"><?php _e( 'Deliver Info', 'woocommerce' ); ?></h1>
			<?php if(!is_user_logged_in()) {?>
			<div class="woocommerce" id="returning_customer_wraper"><div class="woocommerce-info"><?php _e( 'Returning customer?', 'woocommerce' ); ?> <a href="#" class="showlogin"><?php _e( 'Click here to login', 'woocommerce' ); ?></a></div></div>
			<?php }?>
			<div class="m-section_content_selectOption">
				<ul class="about-deliver">
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">1</span>
							<span class="display-table-cell pl-2"><?php _e( 'How to get your cake?', 'woocommerce' ); ?></span>
						</h4>
						<div class="overflow-hidden panel-group">
							<?php 
							$index = 0;
							foreach ($field_mappings['custom_order_shipping']['value'] as $value => $label) {
								$index ++;
								$separate_label = explode('|', $label);
							?>
							<a class="panel text-center width-half pt-5 pb-5 pt-sm-4 pb-sm-4 <?php echo $index == 1 ? 'is-selected' : ''?>" id="<?php echo $value?>" data-order-type="<?php echo $value?>">
								<img class="panel__image" src="<?php bloginfo('template_directory'); echo $index == 1 ? '/images/form/pickup.png' : '/images/form/delivery.png';?>">
								<h4 class="mt-2"><?php echo @$separate_label[0]?></h4>
								<h6 class="text-gray heading-uppercase mt-1"><?php echo @$separate_label[1]?></h6>
								<div class="" style="opacity: 0">
									<input type="radio" name="custom_order_shipping" id="custom_order_shipping_<?php echo $value?>"  value="<?php echo $value?>" <?php if ($value == 'pickup') echo 'checked'?>/>
								</div>
							</a>
							<?php }?>
						</div>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">2</span>
							<span class="display-table-cell pl-2"><?php _e( 'Enter your information', 'woocommerce' ); ?></span>
						</h4>
						<div class="form-fields">
							<div class="row">
								<div class="field col-md-6">
									<label class="label"><?php _e( 'Last Name', 'woocommerce' ); ?></label>
									<input placeholder="佐藤" class="input validate[required]" required="required" type="text" name="custom_order_customer_name_last" id="customer_name_last" value="<?php echo get_user_meta($userID, 'last_name', true)?>">
								</div>
								<div class="field col-md-6">
									<label class="label"><?php _e( 'First Name', 'woocommerce' ); ?></label>
									<input placeholder="太郎" class="input validate[required]" required="required" type="text" name="custom_order_customer_name_first" id="customer_name_first" value="<?php echo get_user_meta($userID, 'first_name', true)?>">
								</div>
							</div>
							<div class="row">
								<div class="field col-md-6">
									<label class="label"><?php _e( 'Last name Kana', 'woocommerce' ); ?></label>
									<input placeholder="さとう" class="input validate[required]" required="required" type="text" name="custom_order_customer_name_last_kana" id="customer_name_last_kana" value="<?php echo get_user_meta($userID, 'last_name_kana', true)?>">
								</div>
								<div class="field col-md-6">
									<label class="label"><?php _e( 'First name Kana', 'woocommerce' ); ?></label>
									<input placeholder="たろう" class="input validate[required]" required="required" type="text" name="custom_order_customer_name_first_kana" id="customer_name_first_kana" value="<?php echo get_user_meta($userID, 'first_name_kana', true)?>">
								</div>
							</div>
							<div class="row">
								<div class="field col-md-6">
									<label class="label"><?php _e( 'Tel', 'woocommerce' ); ?><small class="help-info">ハイフンなし</small></label>
									<input placeholder="09012345678" class="input validate[required,custom[phone]]" required="required" type="tel" name="custom_order_customer_tel" id="customer_tel" value="<?php echo get_user_meta($userID, 'tel', true)?>">
								</div>
								<div class="field col-md-6">
									<label class="label"><?php _e( 'Email address', 'woocommerce' ); ?></label>
									<input placeholder="taro@kitt.jp" class="input validate[required,custom[email]]" required="required" type="email" name="custom_order_customer_email" id="customer_email" value="<?php echo $user_data->user_email?>">
								</div>
							</div>
							<div class="row">
								<div class="field col-md-6">
									<label class="label"><?php _e( 'Sex', 'woocommerce' ); ?></label>
									<ul class="account_sex text-radio list-type">
										<li class="m-input__radio">
											<input type="radio" name="custom_order_customer_sex" id="account_sex_male" class="radio_input validate[required]" <?php checked( get_user_meta(get_current_user_id(), 'sex', true), 'male', true )?> value="male">
											<label for="account_sex_male" class="js-fixHeightChildText radio_label">
												<div class="radio_option radio_size">
													<h5 class="js-fixHeightChildTitle radio_option_caption">
														<span class="caption_wrap"><?php _e( 'Male', 'woocommerce' ); ?></span>
													</h5>
												</div>
											</label>
										</li>
										<li class="m-input__radio">
											<input type="radio" name="custom_order_customer_sex" id="account_sex_female" class="radio_input validate[required]" <?php checked( get_user_meta(get_current_user_id(), 'sex', true), 'female', true )?> value="female">
											<label for="account_sex_female" class="js-fixHeightChildText radio_label">
												<div class="radio_option radio_size">
													<h5 class="js-fixHeightChildTitle radio_option_caption">
														<span class="caption_wrap"><?php _e( 'Female', 'woocommerce' ); ?></span>
													</h5>
												</div>
											</label>
										</li>
									</ul>
								</div>
								<div class="field col-md-6 birth-field">
									<label class="label"><?php _e( 'Birthday', 'cake' ); ?></label>
									<?php 
									$birth_date = get_user_meta( get_current_user_id(), 'birth_date', true);
									$default	= array( 'day' => 1, 'month' => 1, 'year' => 1980, );
									$birth_date = $birth_date ? $birth_date : $default;
									?>
									<p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide" >
										<select id="birth-date-year" name="custom_order_customer_birth_date[year]" required class="form-control select select-primary static-select">>
											<option value=""><?php echo __('Select Birth Year', 'woocommerce')?></option>
											<?php
								   				 foreach($yearMonthDays['years'] as $yearNumber) {
								   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $yearNumber, selected( $birth_date['year'], $yearNumber, false ) );
								   				 }
								   			 ?></select>
								   			 <select id="birth-date-month" name="custom_order_customer_birth_date[month]" required class="form-control select select-primary static-select">>
								   			 <option value=""><?php echo __('Select Birth Month', 'woocommerce')?></option>
								   			 <?php
								   				 foreach ( $yearMonthDays['months'] as $monthNumber => $monthText ) {
								   					 printf( '<option value="%1$s" %2$s>%3$s</option>', $monthNumber, selected( $birth_date['month'], $monthNumber, false ), $monthText );
								   				 }
								   			 ?></select>
								   			 <select id="birth-date-day" name="custom_order_customer_birth_date[day]" required class="form-control select select-primary static-select">>
								   			 <option value=""><?php echo __('Select Birth Day', 'woocommerce')?></option>
								   			 <?php
								   			 foreach($yearMonthDays['days'] as $dayNumber) {
								   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $dayNumber, selected( $birth_date['day'], $dayNumber, false ) );
								   				 }
								   			 ?></select>
								   		 </td>
									</p>
								</div>
							</div>
							<div class="mt-2 deliver-info disable">
								<h4 class="heading-form mt-4 mb-2 text-gray"><?php echo __('Where do you want your order delivered?', 'cake')?></h4>
								<div class="get-from-savedata <?php echo !is_user_logged_in() ? 'disable' : ''?>"><?php echo __('Do you wanna ship to saved adderess?', 'cake')?><input type="checkbox" name="user_saved_address" id="user_saved_address" value="1"></div>
								<div class="form-fields">
									<div class="row">
										<div class="field col-md-6">
											<label class="label">宛名</label>
											<input placeholder="" class="input validate[required]" required="required" type="text" name="custom_order_deliver_name" id="deliver_name" value="">
										</div>
										<div class="field col-md-6">
											<label class="label">店舗名</label>
											<input placeholder="" class="input validate[required]" required="required" type="text" name="custom_order_deliver_storename" id="deliver_storename" value="">
										</div>
									</div>
									<div class="row">
										<div class="field col-md-6">
											<label class="label">担当者様名</label>
											<input placeholder="" class="input validate[required]" required="required" type="text" name="custom_order_deliver_cipname" id="deliver_cipname" value="">
										</div>
										<div class="field col-md-6">
											<label class="label">電話番号</label>
											<input placeholder="0312345678" class="input validate[required]" required="required" type="tel" name="custom_order_deliver_tel" id="deliver_tel" value="">
										</div>
									</div>
									<div class="row">
										<div class="address-field">
											<div class="field col-md-12">
												<label class="label">住所</label>
												<input placeholder="郵便番号" class="input validate[required]" required="required" type="text" name="custom_order_deliver_postcode" id="deliver_postcode" value="">
											</div>
											<div class="field col-md-6">
												<div class="select-wrapper">
													<select name="custom_order_deliver_pref" id="deliver_state" class="form-control select select-primary static-select">
														<option value=""><?php echo __('Choose Prefecture', 'cake')?></option>
														<?php foreach ($default_county_states as $stateKey => $stateVal) {?>
															<option value="<?php echo $stateKey?>" <?php echo get_user_meta($userID, 'shipping_state', true) == $stateKey ? 'selected' : ''?>><?php echo $stateVal;?></option>
														<?php }?>
													</select>
												</div>
											</div>
											<div class="field col-md-6">
												<input placeholder="市区町村" class="input validate[required]" required="required" type="text" name="custom_order_deliver_city" id="deliver_city" value="">
											</div>
											<div class="field col-md-6">
												<input placeholder="番地等" class="input validate[required]" required="required" type="text" name="custom_order_deliver_addr1" id="deliver_addr1" value="">
											</div>
											<div class="field col-md-6">
												<input placeholder="ビル・マンション名等" class="input validate[required]" type="text" name="custom_order_deliver_addr2" id="deliver_addr2" value="">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">3</span>
							<span class="display-table-cell pl-2"><?php _e('When do you want your order delivered?', 'cake')?></span>
						</h4>
						<div class="row">
							<div class="col-md-6 columns">
								<label class="label mb-2">
									<i class="icon-outline-kitt_icons_calendar01"></i>
									<?php _e('Pick Up Date', 'woocommerce')?>
								</label>
								<div class="calendar"></div>
								<input type="hidden" name="custom_order_pickup_date" id="custom_order_pickup_date" value="<?php echo date('Y-m-d')?>"/>
							</div>
							<div class="col-md-6 columns">
								<label class="label mb-2">
									<i class="icon-outline-kitt_icons_clock"></i>
									<?php _e('Pick Up Time', 'woocommerce')?>
								</label>
								<div class="timepicker">
									<div class="timepick">
									<h3 class="input no-interaction text-center display-table width-full"><div class="display-table-cell"><output></output></div></h3>
										<div class="time-range display-table width-full mt-2">
											<div class="time-range__minus display-table-cell">
												<button type="button" class="button button--ghost circle">-</button>
											</div>
											<div class="display-table-cell">
												<input type="range" id="order_pickup_time" name="custom_order_pickup_time" min="15" max="23" step="0.5" value="15" data-rangeslider />
											</div>
											<div class="time-range__plus display-table-cell">
												<button type="button" class="button button--ghost circle">+</button>
											</div>
										</div>
										<!--/time-range-->
									</div>
									<!--/timepick-->
								</div>
								<!--/timepicker-->
							</div>
						</div>
					</li>
		            <!--Start show this only for first time order by user or guest-->
		            <?php if (!$userID || ($userID && empty($customer_orders))) {?>
		            <li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">4</span>
							<span class="display-table-cell pl-2"><?php _e('アンケート', 'cake')?></span>
						</h4>
						<div class="form-fields question-form">
		<div class="row">
			<div class="field col-xs-12">
				<label class="label required"><?php _e( '当店をどこで知りましたか？', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_a" class="radio_input validate[required]" value="SNS">
						<label for="q01_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">SNS</span></h5>
							</div>
						</label>
						<!--show this if SNS is checked-->
						<span class="dropdown" style="display: none;" id="engine_sns">
							<select name="survey[social]">
								<option value="Instagram">Instagram</option>
								<option value="facebook">facebook</option>
								<option value="twitter">twitter</option>
								<option value="LINE＠">LINE＠</option>
								<option value="その他（記入）">その他（記入）</option>
							</select>
							
							<!--show this if その他（記入） is selected-->
							<span class="block_textarea" style="display: none;" id="engine_sns_comment">
								<textarea name="survey[social_comment]" class="validate[required]" placeholder="ご希望の形についてご記入ください。"></textarea>
							</span>
							<!--/show this if その他（記入） is selected-->
						</span>
						<!--/show this if SNS is checked-->
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_b" class="radio_input validate[required]" value="知人の紹介">
						<label for="q01_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">知人の紹介</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_d" class="radio_input validate[required]" value="雑誌">
						<label for="q01_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">雑誌</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_e" class="radio_input validate[required]" value="ポスター">
						<label for="q01_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">ポスター</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_f" class="radio_input validate[required]" value="インターネット">
						<label for="q01_f" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">インターネット</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[engine]" id="q01_c" class="radio_input validate[required]" value="その他（記入）">
						<label for="q01_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">その他（記入）</span></h5>
							</div>
						</label>
						<!--show if その他（記入）is checked-->
						<span class="block_textarea" style="display: none;" id="engine_other">
							<textarea name="survey[engine_other]" class="validate[required]" placeholder="ご希望の形についてご記入ください。"></textarea>
						</span>
						<!--/show if その他（記入）is checked-->
					</li>
				</ul>
			</div>
			
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( '当店のケーキをご注文されたことがありますか？', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[placed]" id="q02_a" class="radio_input" value="はい">
						<label for="q02_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">はい</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[placed]" id="q02_b" class="radio_input" value="いいえ">
						<label for="q02_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">いいえ</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( '最近のご利用日を教えてください', 'woocommerce' ); ?></label>
				<div class="row">
					<div class="col-sm-4">
						<span class="dropdown"><select name="survey[use][year]" class="validate[required]">
							<!--show from 2017-->
							<option value="">年を選択</option>
							<?php for($i = $current_year - 1; $i <= $current_year; $i ++) { ?>
							<option value="<?php echo $i?>"><?php echo $i?></option>
							<?php }?>
							</select></span>
					</div>
					<div class="col-sm-4">
						<select name="survey[use][month]" class="validate[required]">
							<!--show All month-->
							<option value="">月を選択</option>
							<?php foreach($yearMonthDays['months'] as $monthNumber) { ?>
							<option value="<?php echo $monthNumber?>"><?php echo $monthNumber?></option>
							<?php }?>
						</select>
					</div>
					<div class="col-sm-4">
						<select name="survey[use][day]" class="validate[required]">
							<!--show All dates-->
							<option value="">日を選択</option>
							<?php foreach($yearMonthDays['days'] as $dayNumber) { ?>
							<option value="<?php echo $dayNumber?>日"><?php echo $dayNumber?>日</option>
							<?php }?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'ご利用回数は何回目ですか？', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[usage]" id="q04_a" class="radio_input" value="初めて">
						<label for="q04_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">初めて</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[usage]" id="q04_b" class="radio_input" value="2回目">
						<label for="q04_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">2回目</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[usage]" id="q04_c" class="radio_input" value="3回目以上">
						<label for="q04_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">3回目以上</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'お値段についてお聞かせください', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_a" class="radio_input" value="大変満足">
						<label for="q06_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">大変満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_b" class="radio_input" value="満足">
						<label for="q06_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_c" class="radio_input" value="普通">
						<label for="q06_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">普通</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_d" class="radio_input" value="やや不満">
						<label for="q06_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">やや不満</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[price]" id="q06_e" class="radio_input" value="不満">
						<label for="q06_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">不満</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'お味についてお聞かせください', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_a" class="radio_input" value="大変満足">
						<label for="q05_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">大変満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_b" class="radio_input" value="満足">
						<label for="q05_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_c" class="radio_input" value="普通">
						<label for="q05_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">普通</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_d" class="radio_input" value="やや不満">
						<label for="q05_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">やや不満</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[taste]" id="q05_e" class="radio_input" value="不満">
						<label for="q05_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">不満</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<!--added newly-->
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'デザインについてお聞かせください', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_a" class="radio_input" value="大変満足">
						<label for="q062_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">大変満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_b" class="radio_input" value="満足">
						<label for="q062_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">満足</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_c" class="radio_input" value="普通">
						<label for="q062_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">普通</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_d" class="radio_input" value="やや不満">
						<label for="q062_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">やや不満</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[design]" id="q062_e" class="radio_input" value="不満">
						<label for="q062_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">不満</span></h5>
							</div>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<!--/added newly-->
							
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( '特に良かった点についてお聞かせください', 'woocommerce' ); ?></label>
				<ul class="question_list text-radio list-type">
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_a" class="radio_input survey_particular" value="価格">
						<label for="q07_a" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">価格</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_b" class="radio_input survey_particular" value="味">
						<label for="q07_b" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">味</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_c" class="radio_input survey_particular" value="デザイン">
						<label for="q07_c" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">デザイン</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_d" class="radio_input survey_particular" value="接客サービス">
						<label for="q07_d" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">接客サービス</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_e" class="radio_input survey_particular" value="メニュー">
						<label for="q07_e" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">メニュー</span></h5>
							</div>
						</label>
					</li>
					<li class="m-input__radio">
						<input type="radio" name="survey[particular]" id="q07_f" class="radio_input survey_particular" value="その他">
						<label for="q07_f" class="js-fixHeightChildText radio_label">
							<div class="radio_option radio_size">
								<h5 class="js-fixHeightChildTitle radio_option_caption"><span class="caption_wrap">その他（記入）</span></h5>
							</div>
						</label>
						<!--show input_show when above radio is selected-->
						<div class="input_show" id="survey_particular_comment" style="display: none;">
							<textarea name="survey_comment" class="validate[required]" placeholder="ご希望の形についてご記入ください。"></textarea>
						</div>
					</li>
				</ul>
			</div>
		</div>
							
		<!--added newly-->
		<div class="row">
			<div class="field col-xs-12">
				<label class="label"><?php _e( 'ご意見・ご希望等ございましたら、お聞かせください', 'woocommerce' ); ?></label>
				<div class="input_textarea"><textarea name="survey[other_comment]" class="validate[required]" ></textarea></div>
			</div>
		</div>
		<!--/added newly-->
	</div>
		            </li>
		            <?php }?>
		           <!--End show this only for first time order by user or guest-->
				</ul>
			</div>
		</div>
		
		<div id="fourth_step" class="step_wraper" data-step="4">
			<h3><?php _e('Confirmation', 'cake')?></h3>
		</div>
		
		<div id="button_wraper">
			<input class="cdo-button submit_prev" type="button" name="submit_prev" id="submit_prev_3" value="<?php echo esc_html__( 'Previous', 'cake')?>" style="display: none;"/>
			<input class="cdo-button submit_next" type="button" name="submit_next" id="submit_next_3" data-text-end="<?php echo esc_html__( 'View Confirm', 'woocommerce')?>" data-text-next="<?php echo esc_html__( 'Next', 'cake')?>" value="<?php echo esc_html__( 'Next', 'cake')?>" />
		</div>
	</form>
	
	<form id="confirmation_form" method="post" action="">
		<input type="hidden" name="action" value="submit_form_order"/>
		<input type="hidden" name="confirmed" value="ok"/>
	</form>
	<div id="confirmation_wraper" class="disable">
		<div id="confirmation_content">
		</div>
		<div id="confirmation_footer" class="disable">
		<div class="row cancel-policy-check">
				<div class="col-md-12">
					<div class="notify">
						<h4><i class="fa fa-exclamation-circle" aria-hidden="true"></i>キャンセルポリシー</h4>
						<p>注文受付承認後キャンセルされる場合は以下のキャンセル料がかかります。</p>
						<table class="cancel-list">
							<tbody>
								<tr><th>当日</th><td>商品代金の100%</td></tr>
								<tr><th>前日</th><td>商品代金の80%</td></tr>
								<tr><th>2日前</th><td>商品代金の50%</td></tr>
								<tr><th>3日前</th><td>商品代金の30%</td></tr>
							</tbody>
						</table>
						<input type="checkbox" id="cpcheck" /><label class="normal-checklabel" for="check">上記のキャンセルポリシーに同意します</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input class="cdo-button submit_prev" type="button" name="submit_prev" value="<?php echo esc_html__( 'Previous', 'cake')?>" />
					<input type="button" class="cdo-button" name="submit" value="この注文内容でオーダーする" id="submit_form_order"/>
				</div>
			</div>
			
		</div>
	</div>
</div>
<div class="col-md-4 columns position-static pt-md-4 pt-sm-2 pb-sm-4">
	<div class="ordercake-cart-sidebar-container">
		<div class="cake-cart-sidebar">
			<div class="panel mb-3">
			<div id="cart_items">
				
			</div>
				<div class="text-center pt-1 pb-1" id="cart_empty_block">
					<img src="<?php bloginfo('template_directory'); ?>/images/form/ordersheet.png" width="80">
					<h5 class="mt-2 text-ppink heading-uppercase"><?php _e( 'No details yet', 'woocommerce' ); ?></h5>
				</div>
				<div class="border-top pt-3 mt-3 panel__full-width-item heading-uppercase disable" id="cart_total_wraper">
					<div class="row disable" id="sub_total">
						<div class="col-md-6 columns text-gray"><h6><?php _e( 'Subtotal', 'woocommerce' ); ?></h6></div>
						<div class="col-md-6 columns text-green text-right"><h6>0</h6></div>
					</div>
					<div class="row disable" id="shipping_fee">
						<div class="col-md-6 columns text-gray"><h6><?php _e( 'Delivery Fee', 'woocommerce' ); ?></h6></div>
						<div class="col-md-6 columns text-green text-right"><h6>0</h6></div>
					</div>
					<div class="row disable" id="total_tax">
						<div class="col-md-6 columns text-gray"><h6><?php _e( 'Total Tax', 'woocommerce' ); ?></h6></div>
						<div class="col-md-6 columns text-green text-right"><h6>0</h6></div>
					</div>
					<div class="row" id="cart_total">
						<div class="col-md-6 columns text-gray"><h4><?php _e( 'Estimation', 'woocommerce' ); ?></h4></div>
						<div class="col-md-6 columns text-green text-right"><h4>¥-</h4></div>
					</div>
					
					<div class="row disable" id="cart_notice">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade modal-custom" id="custom_order_login_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		<button type="button" class="close abs-right" data-dismiss="modal" aria-label="Close">
					<span class="linericon-cross" aria-hidden="true"></span>
				</button>
			<div class="modal-header">
				<h4 class="modal-title" ><?php echo __('Login', 'woocommerce')?></h4>
			</div>
			<div class="modal-body">
				<?php login_with_ajax();?>
			</div>
		</div>
	</div>
</div>