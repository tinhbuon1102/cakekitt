<?php
    $id = $params['id'];
    $form = $params['form'];
    $errors = $form->getError();
    $errorSettings = SCFP()->getSettings()->getErrorsSettings();
    $fieldsSettings = SCFP()->getSettings()->getFieldsSettings();
    $formSettings = SCFP()->getSettings()->getFormSettings();
    $styleSettings = SCFP()->getSettings()->getStyleSettings();
    $formData = $form->getData();    
    $notifications = $form->getNotifications();
    
    $button_position = !empty($formSettings['button_position']) ? $formSettings['button_position'] : 'left';
    $no_border = !empty($styleSettings['no_border']) ? $styleSettings['no_border'] : '';
    $no_background = !empty($styleSettings['no_background']) ? $styleSettings['no_background'] : '';
    
    $content_classes = array() ;
    if (!empty($no_border)) {
        $content_classes[] = "scfp-form-noborder"; 
    }
    if (!empty($no_background)) {
        $content_classes[] = "scfp-form-nobackground"; 
    }
    if (!empty($formSettings['form_custom_css'])) {
        $content_classes[] = $formSettings['form_custom_css'];
    }
    $content_classes = !empty($content_classes) ? ' '.implode(' ', $content_classes) : '';
?>
<?php if( !empty( $errors ) ): ?>
<div class="scfp-form-error scfp-notifications<?php echo $content_classes;?>">
    <div class="scfp-form-notifications-content">
        <?php foreach( $errors as $errors_key => $errors_value ): ?>
            <div class="scfp-error-item"><span><?php echo $fieldsSettings[$errors_key]['name'];?>:</span> <?php  echo $errorSettings['errors'][$errors_value ] ; ?></div>
        <?php endforeach; ?>
    </div>
    <a class="scfp-form-notifications-close" title="Close" href="#">x</a>
</div>
<?php endif; ?>

<?php if( !empty( $notifications ) ): ?>
<div class="scfp-form-notification scfp-notifications<?php echo $content_classes;?>">
    <div class="scfp-form-notifications-content">
        <?php foreach( $notifications as $notification ): ?>
            <div class="scfp-notification-item"><?php echo $notification; ?></div>
        <?php endforeach; ?>
    </div>
    <a class="scfp-form-notifications-close" title="Close" href="javascript:void(0);">x</a> 
</div>
<?php endif; ?>
<?php 
$fields = SCFP()->getSettings()->getFieldsSettings();
$typeOptions = $fields['61a5691189ac']['choices']['list'];
 
?>
<style>
	#contact-section h3.title {display: none}
</style>
<div class="scfp-form-content<?php echo $content_classes;?>">
	<form class="scfp-form wpcf7-form" id="<?php echo $id;?>"  method="post" action=""<?php echo !empty($formSettings['html5_enabled']) ? '' : ' novalidate';?>>
        <input type="hidden" name="form_id" value="<?php echo $id;?>"/>
        <input type="hidden" name="action" value="scfp-form-submit"/>
		<div class="row">
			<div class="col-sm-6">
				<span class="wpcf7-form-control-wrap your-name">
					<input type="text" name="scfp-name" required value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="*お名前">
				</span>
			</div>
			<div class="col-sm-6">
				<span class="wpcf7-form-control-wrap text-company">
					<input type="text" name="scfp-subject" value="" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false" placeholder="会社名">
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<span class="wpcf7-form-control-wrap your-email">
					<input type="email" name="scfp-email" required value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="*メールアドレス">
				</span>
			</div>
			<div class="col-sm-6">
				<span class="wpcf7-form-control-wrap tel-257">
					<input type="tel" name="scfp-phone" required value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-required wpcf7-validates-as-tel" aria-required="true" aria-invalid="false" placeholder="電話番号">
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<span class="wpcf7-form-control-wrap menu-inquire">
					<div class="wpcf7-select-parent">
						<select name="scfp-61a5691189ac[]" class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required" aria-required="true" aria-invalid="false">
							<?php foreach ($typeOptions as $typeOption) {?>
							<option value="<?php echo $typeOption['value']?>"><?php echo $typeOption['label']?></option>
							<?php }?>
						</select>
						<div class="select-arrow icon-outline-kitt_icons_arrow_down"></div>
					</div>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<span class="wpcf7-form-control-wrap your-message">
					<textarea name="scfp-message" cols="40" required rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false" placeholder="*お問い合わせ内容"></textarea>
				</span>
			</div>
		</div>
		<p>
			<input type="submit" value="Send" class="wpcf7-form-control wpcf7-submit">
			<span class="ajax-loader"></span>
		</p>
		<div class="wpcf7-response-output wpcf7-display-none"></div>
	</form>
</div>
