<?php 
$userID = (int) get_current_user_id();
$user_data = get_userdata( $userID );

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
		<div class="step fourth" data-step="4">
			<div class="circle">4</div>
			<div class="text">STEP4</div>
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
			<h1 class="order-heading">Select Cake Type</h1>
			<div class="m-section_content_selectOption">
				<ul class="cake-type">
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">1</span>
							<span class="display-table-cell pl-2">Choose cake type</span>
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
								<?php foreach ( $terms as $term_index => $term ) { ?>
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
			<h1 class="order-heading">About Cake Design</h1>
			<div class="m-section_content_selectOption">
				<ul class="about-design">
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">2</span>
							<span class="display-table-cell pl-2">Choose shape</span>
						</h4>
						<ul class="cake-shape text-radio list-type round-icon-select col_5">
							<?php 
							$cake_shape_index = 0;
							foreach ($field_mappings['custom_order_cake_shape']['value'] as $value => $label) {
								$cake_shape_index ++;
							?>
								<li class="m-input__radio">
									<input type="radio" name="custom_order_cake_shape" id="cake_shape_<?php echo $value?>" class="radio_input validate[required]" <?php echo $field_mappings['custom_order_cake_shape']['field']['default_value'] == $value || (!$field_mappings['custom_order_cake_shape']['field']['default_value'] && $index == 1)  ? '' : ''?> value="<?php echo $value?>">
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
								</li>
							<?php }?>
						</ul>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">3</span>
							<span class="display-table-cell pl-2">Choose flavor</span>
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
							<span class="title-number display-table-cell">4</span>
							<span class="display-table-cell pl-2"><?php echo __('Choose Size', 'cake')?></span>
						</h4>
						<div class="cake-size select-wrapper">
							<select name="custom_order_cakesize_square" class="form-control select select-primary disable" data-toggle="select">
								<option value=""><?php echo __('Choose Size', 'cake')?></option>
								<!--for round shape-->
								<?php 
								$index = 0;
								foreach ($field_mappings['custom_order_cakesize_square']['value'] as $value => $label) {
									$index ++
								?>
									<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_cakesize_square']['field']['default_value'] == $value  ? 'selected' : ''; ?>>
										<?php echo $label?>
									</option>
								<?php }?>
							</select>
							
							<select name="custom_order_cakesize_round" class="form-control select select-primary validate[required]" data-toggle="select">
								<option value=""><?php echo __('Choose Size', 'cake')?></option>
								<!--for round shape-->
								<?php 
								$index = 0;
								foreach ($field_mappings['custom_order_cakesize_round']['value'] as $value => $label) {
									$index ++;
								?>
									<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_cakesize_round']['field']['default_value'] == $value  ? 'selected' : ''; ?>>
										<?php echo $label?>
									</option>
								<?php }?>
							</select>
						</div>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">5</span>
							<span class="display-table-cell pl-2">Choose color</span>
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
										<a href="#" class="btn btn-default cp-select" id="custom_order_color_picker">Color Picker</a>
										<div class="selected-color"></div>
										<input type="hidden" name="custom_order_cakecolor_other" id="custom_order_cakecolor_other" value=""/>
									</div>
									<?php }?>
								</li>
							<?php }?>
						</ul>
					</li>
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">6</span>
							<span class="display-table-cell pl-2">Choose decorations</span>
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
												<select name="custom_order_icingcookie_qty" class="form-control select select-primary" data-toggle="select">
												<option value="" selected>choose qty</option>
													<?php 
													$index = 0;
													foreach ($field_mappings['custom_order_icingcookie_qty']['value'] as $value => $label) {
														$index ++;
													?>
														<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_icingcookie_qty']['field']['default_value'] == $value || (!$field_mappings['custom_order_icingcookie_qty']['field']['default_value'] && $index == 1)  ? 'selected' : ''; ?>>
															<?php echo $label?>
														</option>
													<?php }?>
												</select>
											</div>
										</div>
										<div class="sub_form">
											<textarea name="custom_order_basecolor_text" class="subinfo txtLL empty" placeholder="ご希望の形・サイズ・色をご記入ください。"></textarea>
										</div>
									</div>
									<?php }?>
									
									<?php if ($value == 'cupcake') {?>
									<div id="optionbox02" class="suboption_box disable">
										<div class="sub_form">
											<div class="select-wrapper">
											<select name="custom_order_cupcake_qty" class="form-control select select-primary" data-toggle="select">
											<option value="" selected>choose qty</option>
											<?php 
											$index = 0;
											foreach ($field_mappings['custom_order_cupcake_qty']['value'] as $value => $label) {
												$index ++;
											?>
												<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_cupcake_qty']['field']['default_value'] == $value || (!$field_mappings['custom_order_cupcake_qty']['field']['default_value'] && $index == 1)  ? 'selected' : ''; ?>>
													<?php echo $label?>
												</option>
											<?php }?>
											</select>
										</div>
										</div>
										<div class="sub_form">
											<span class="option_label">デザイン</span>
											<textarea name="custom_order_cpck_text" class="subinfo txtLL empty" placeholder="ご希望のデザイン詳細をご記入ください。"></textarea>
										</div>
									</div>
									<?php }?>
									
									<?php if ($value == 'macaron') {?>
									<div id="optionbox03" class="suboption_box disable">
										<div class="sub_form">
											
											<div class="select-wrapper">
											<select name="custom_order_macaron_qty" class="form-control select select-primary" data-toggle="select">
												<option value="" selected>choose qty</option>
												<?php 
												$index = 0;
												foreach ($field_mappings['custom_order_macaron_qty']['value'] as $value => $label) {
													$index ++;
												?>
													<option value="<?php echo $value?>" <?php echo $field_mappings['custom_order_macaron_qty']['field']['default_value'] == $value || (!$field_mappings['custom_order_macaron_qty']['field']['default_value'] && $index == 1)  ? 'selected' : ''; ?>>
														<?php echo $label?>
													</option>
												<?php }?>
											</select>
											</div>
										</div>
										<div class="sub_form">
											<span class="option_label">色</span>
											<ul class="macaron-color text-radio list-type row">
												<?php 
												$index = 0;
												foreach ($field_mappings['custom_order_macaron_color']['value'] as $value => $label) {
													$index ++;
												?>
													<li class="m-input__radio col-md-12">
														<input type="radio" name="custom_order_macaron_color" id="macaron_color_<?php echo $value?>" class="radio_input"
														<?php echo $field_mappings['custom_order_macaron_color']['field']['default_value'] == $value || (!$field_mappings['custom_order_macaron_color']['field']['default_value'] && $index == 1)  ? 'checked' : ''?>
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
									</div>
									<?php }?>
									
									<?php if ($value == 'flower') {?>
									<div id="optionbox06" class="suboption_box disable">
										<div class="sub_form">
											<span class="option_label">色</span>
											<ul class="macaron-color text-radio list-type row">
												<?php 
												$index = 0;
												foreach ($field_mappings['custom_order_flowercolor']['value'] as $value => $label) {
													$index ++;
												?>
													<li class="m-input__radio col-md-12">
														<input type="radio" name="custom_order_flowercolor" id="flower_color_<?php echo $value?>" class="radio_input" 
														<?php echo $field_mappings['custom_order_flowercolor']['field']['default_value'] == $value || (!$field_mappings['custom_order_flowercolor']['field']['default_value'] && $index == 1)  ? 'checked' : ''?>
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
									<div id="optionbox07" class="suboption_box disable upload_cakePic_wraper">
										<div class="sub_form">
											<ul class="inspired_images"></ul>
											<span class="option_label">写真アップロード</span>
											<div class="image_loading"></div>
											<input type="file" class="filestyle upload_cakePic" name="upload_cakePic" id="custom_order_photocakepic">
										</div>
									</div>
									<?php }?>
									
									<?php if ($value == 'candy') {?>
									<div id="optionbox08" class="suboption_box disable">
										<div class="sub_form">
											<span class="option_label">デザイン</span>
											<textarea name="custom_order_candy_text" class="subinfo txtLL empty" placeholder="ご希望のデザイン詳細をご記入ください。"></textarea>
										</div>
									</div>
									<?php }?>
									
									<?php if ($value == 'figure') {?>
									<div id="optionbox09" class="suboption_box disable">
										<div class="sub_form">
											<span class="option_label">デザイン</span>
											<textarea name="custom_order_doll_text" class="subinfo txtLL empty" placeholder="ご希望のデザイン詳細をご記入ください。"></textarea>
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
							<span class="display-table-cell pl-2">Message Plate</span>
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
											<textarea name="custom_order_msgpt_text_yes" class="subinfo txtLL empty" placeholder="ご希望のメッセージをご記入ください。"></textarea>
										</div>
									<?php }?>
								</li>
							<?php }?>
						</ul>
					</li>
					<li class="main-option upload_cakePic_wraper">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">8</span>
							<span class="display-table-cell pl-2">Inspired Pics</span>
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
			<h1 class="order-heading">Deliver Info</h1>
			<div class="m-section_content_selectOption">
				<ul class="about-deliver">
					<li class="main-option">
						<h4 class="heading-form display-table mb-3">
							<span class="title-number display-table-cell">1</span>
							<span class="display-table-cell pl-2">How to get your cake?</span>
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
							<span class="display-table-cell pl-2">Enter your information</span>
						</h4>
						<div class="form-fields">
							<div class="row">
								<div class="field col-md-6">
									<label class="label">姓</label>
									<input placeholder="佐藤" class="input validate[required]" required="required" type="text" name="custom_order_customer_name_last" id="customer_name_last" value="<?php echo get_user_meta($userID, 'last_name', true)?>">
								</div>
								<div class="field col-md-6">
									<label class="label">名</label>
									<input placeholder="太郎" class="input validate[required]" required="required" type="text" name="custom_order_customer_name_first" id="customer_name_first" value="<?php echo get_user_meta($userID, 'first_name', true)?>">
								</div>
							</div>
							<div class="row">
								<div class="field col-md-6">
									<label class="label">姓(ふりがな)</label>
									<input placeholder="さとう" class="input validate[required]" required="required" type="text" name="custom_order_customer_name_last_kana" id="customer_name_last_kana" value="<?php echo get_user_meta($userID, 'last_name_kana', true)?>">
								</div>
								<div class="field col-md-6">
									<label class="label">名(ふりがな)</label>
									<input placeholder="たろう" class="input validate[required]" required="required" type="text" name="custom_order_customer_name_first_kana" id="customer_name_first_kana" value="<?php echo get_user_meta($userID, 'first_name_kana', true)?>">
								</div>
							</div>
							<div class="row">
								<div class="field col-md-6">
									<label class="label">電話番号<small class="help-info">ハイフンなし</small></label>
									<input placeholder="09012345678" class="input validate[required,custom[phone]]" required="required" type="tel" name="custom_order_customer_tel" id="customer_tel" value="<?php echo get_user_meta($userID, 'tel', true)?>">
								</div>
								<div class="field col-md-6">
									<label class="label">メールアドレス</label>
									<input placeholder="taro@kitt.jp" class="input validate[required,custom[email]]" required="required" type="email" name="custom_order_customer_email" id="customer_email" value="<?php echo $user_data->user_email?>">
								</div>
							</div>
							
							<div class="mt-2 deliver-info disable">
								<h4 class="heading-form mt-4 mb-2 text-gray">Where do you want your order delivered?</h4>
								<div class="form-fields">
									<div class="row">
										<div class="field col-md-6">
											<label class="label">宛名</label>
											<input placeholder="" class="input validate[required]" required="required" type="text" name="custom_order_deliver_name" id="deliver_name" value="<?php echo get_user_meta($userID, 'shipping_last_name', true)?>">
										</div>
										<div class="field col-md-6">
											<label class="label">店舗名</label>
											<input placeholder="" class="input validate[required]" required="required" type="text" name="custom_order_deliver_storename" id="deliver_storename" value="<?php echo get_user_meta($userID, 'shipping_company', true)?>">
										</div>
									</div>
									<div class="row">
										<div class="field col-md-6">
											<label class="label">担当者様名</label>
											<input placeholder="" class="input validate[required]" required="required" type="text" name="custom_order_deliver_cipname" id="deliver_cipname" value="<?php echo get_user_meta($userID, 'shipping_first_name', true)?>">
										</div>
										<div class="field col-md-6">
											<label class="label">電話番号</label>
											<input placeholder="0312345678" class="input validate[required]" required="required" type="tel" name="custom_order_deliver_tel" id="deliver_tel" value="<?php echo get_user_meta($userID, 'shipping_phone', true)?>">
										</div>
									</div>
									<div class="row">
										<div class="address-field">
											<div class="field col-md-12">
												<label class="label">住所</label>
												<input placeholder="郵便番号" class="input validate[required]" required="required" type="text" name="custom_order_deliver_postcode" id="deliver_postcode" value="<?php echo get_user_meta($userID, 'shipping_postcode', true)?>">
											</div>
											<div class="field col-md-6">
												<div class="select-wrapper">
													<select name="custom_order_deliver_pref" id="deliver_state" class="form-control select select-primary" data-toggle="select">
														<option value=""><?php echo __('Choose Prefecture', 'cake')?></option>
														<?php foreach ($default_county_states as $stateKey => $stateVal) {?>
															<option value="<?php echo $stateKey?>" <?php echo get_user_meta($userID, 'shipping_state', true) == $stateKey ? 'selected' : ''?>><?php echo $stateVal;?></option>
														<?php }?>
													</select>
												</div>
											</div>
											<div class="field col-md-6">
												<input placeholder="市区町村" class="input validate[required]" required="required" type="text" name="custom_order_deliver_city" id="deliver_city" value="<?php echo get_user_meta($userID, 'shipping_city', true)?>">
											</div>
											<div class="field col-md-6">
												<input placeholder="番地等" class="input validate[required]" required="required" type="text" name="custom_order_deliver_addr1" id="deliver_addr1" value="<?php echo get_user_meta($userID, 'shipping_address_1', true)?>">
											</div>
											<div class="field col-md-6">
												<input placeholder="ビル・マンション名等" class="input validate[required]" type="text" name="custom_order_deliver_addr2" id="deliver_addr2" value="<?php echo get_user_meta($userID, 'shipping_address_2', true)?>">
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
							<span class="display-table-cell pl-2">When do you want your order delivered?</span>
						</h4>
						<div class="row">
							<div class="col-md-6 columns">
								<label class="label mb-2">
									<i class="icon-outline-kitt_icons_calendar01"></i>
									Pick Up Date
								</label>
								<div class="calendar"></div>
								<input type="hidden" name="custom_order_pickup_date" id="custom_order_pickup_date" value="<?php echo date('Y-m-d')?>"/>
							</div>
							<div class="col-md-6 columns">
								<label class="label mb-2">
									<i class="icon-outline-kitt_icons_clock"></i>
									Pick Up Time
								</label>
								<div class="timepicker">
									<div class="timepick">
									<h3 class="input no-interaction text-center display-table width-full"><div class="display-table-cell"><output></output></div></h3>
										<div class="time-range display-table width-full mt-2">
											<div class="time-range__minus display-table-cell">
												<button type="button" class="button button--ghost circle">-</button>
											</div>
											<div class="display-table-cell">
												<input type="range" id="order_pickup_time" name="custom_order_pickup_time" min="1" max="24" step="1" value="9" data-rangeslider />
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
				</ul>
			</div>
		</div>
		
		<div id="fourth_step" class="step_wraper" data-step="4">
			<h3>Confirmation</h3>
		</div>
		
		<div id="button_wraper">
			<input class="cdo-button submit_prev" type="button" name="submit_prev" id="submit_prev_3" value="<?php echo esc_html__( 'Previous', 'cake')?>" />
			<input class="cdo-button submit_next" type="button" name="submit_next" id="submit_next_3" data-text-end="<?php echo esc_html__( 'View Confirm', 'cake')?>" data-text-next="<?php echo esc_html__( 'Next', 'cake')?>" value="<?php echo esc_html__( 'Next', 'cake')?>" />
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
				<div class="modal fade" id="custom_order_login_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none;">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" ><?php echo __('Login or Register', 'cake')?></h4>
							</div>
							<div class="modal-body">
								<?php login_with_ajax();?>
							</div>
						</div>
					</div>
				</div>
			<div class="row">
				<div class="col-md-12">
					<input class="cdo-button submit_prev" type="button" name="submit_prev" value="<?php echo esc_html__( 'Previous', 'cake')?>" />
					<input type="button" class="cdo-button" name="submit" value="Submit" id="submit_form_order"/>
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
					<h5 class="mt-2 text-ppink heading-uppercase">No details yet</h5>
				</div>
				<div class="border-top pt-3 mt-3 panel__full-width-item heading-uppercase disable" id="cart_total_wraper">
					<div class="row" id="sub_total">
						<div class="col-md-8 columns text-gray"><h6>Sub Total</h6></div>
						<div class="col-md-4 columns text-green text-right"><h6>0</h6></div>
					</div>
					<div class="row" id="shipping_fee">
						<div class="col-md-8 columns text-gray"><h6>Shipping Fee</h6></div>
						<div class="col-md-4 columns text-green text-right"><h6>0</h6></div>
					</div>
					<div class="row disable" id="total_tax">
						<div class="col-md-8 columns text-gray"><h6>Total Tax</h6></div>
						<div class="col-md-4 columns text-green text-right"><h6>0</h6></div>
					</div>
					<div class="row" id="cart_total">
						<div class="col-md-8 columns text-gray"><h4>Estimation</h4></div>
						<div class="col-md-4 columns text-green text-right"><h4>¥6,500</h4></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>