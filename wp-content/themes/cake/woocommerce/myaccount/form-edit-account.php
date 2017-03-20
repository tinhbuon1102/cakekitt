<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
	<p class="woocommerce-FormRow woocommerce-FormRow--first form-row form-row-first">
		<label for="account_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>

	<p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-last">
		<label for="account_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	
	<p class="woocommerce-FormRow woocommerce-FormRow--first form-row form-row-first">
		<label for="account_last_name_kana"><?php _e( 'Last name Kana', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name_kana" id="account_last_name_kana" value="<?php echo get_user_meta($user->ID, 'last_name_kana', true); ?>" />
	</p>
	
	<p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-last">
		<label for="account_first_name_kana"><?php _e( 'First name Kana', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name_kana" id="account_first_name_kana" value="<?php echo esc_attr( get_user_meta($user->ID, 'first_name_kana', true) ); ?>" />
	</p>
	<p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-first">
		<label for="account_tel"><?php _e( 'Tel', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_tel" id="account_tel" value="<?php echo get_user_meta($user->ID, 'tel', true); ?>" />
	</p>
	

	<p class="woocommerce-FormRow woocommerce-FormRow--first form-row form-row-last">
		<label for="account_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>
	
	<div class="woocommerce-FormRow woocommerce-FormRow--first form-row form-row-first">
		<label ><?php _e( 'Sex', 'Cake' ); ?> <span class="required">*</span></label>
		<ul class="form-row-wide text-radio list-type" style="list-style-type: none; margin: 0;">
			<li class="m-input__radio">
				<input type="radio" class="radio_input" name="account_sex" id="account_email_male" value="male" <?php checked( get_user_meta($user->ID, 'sex', true), 'male', true )?> required>
				<label for="account_email_male" class="radio_label"><?php _e( 'Male', 'Cake' ); ?> </label>
			</li>
			<li class="m-input__radio">
				<input type="radio" class="radio_input" name="account_sex" id="account_email_female" value="female" <?php checked( get_user_meta($user->ID, 'sex', true), 'female', true )?> required/>
				<label for="account_email_female" class="radio_label"><?php _e( 'Female', 'Cake' ); ?> </label>
			</li>
		</ul>
	</div>
	
	
	<?php 
	$yearMonthDays = kitt_get_year_month_day();
	$birth_date = get_user_meta( get_current_user_id(), 'birth_date', true);
	$default	= array( 'day' => 1, 'month' => 1, 'year' => 1980, );
	$birth_date = $birth_date ? $birth_date : $default;
	?>
	<p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-last" >
		<label for="birth_date"><?php _e( 'Birth date', 'cake' ); ?> <span class="required">*</span></label>
		<select id="birth-date-year" name="birth_date[year]" required>
			<option value=""><?php echo __('Select Birth Year')?></option>
			<?php
   				 foreach($yearMonthDays['years'] as $yearNumber) {
   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $yearNumber, selected( $birth_date['year'], $yearNumber, false ) );
   				 }
   			 ?></select>
   			 <select id="birth-date-month" name="birth_date[month]" required>
   			 <option value=""><?php echo __('Select Birth Month')?></option>
   			 <?php
   				 foreach ( $yearMonthDays['months'] as $monthNumber => $monthText ) {
   					 printf( '<option value="%1$s" %2$s>%3$s</option>', $monthNumber, selected( $birth_date['month'], $monthNumber, false ), $monthText );
   				 }
   			 ?></select>
   			 <select id="birth-date-day" name="birth_date[day]" required>
   			 <option value=""><?php echo __('Select Birth Day')?></option>
   			 <?php
   			 foreach($yearMonthDays['days'] as $dayNumber) {
   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $dayNumber, selected( $birth_date['day'], $dayNumber, false ) );
   				 }
   			 ?></select>
   		 </td>
	</p>
	
	<div class="clear"></div>
	<br />

	<fieldset>
		<legend><?php _e( 'Password Change', 'woocommerce' ); ?></legend>

		<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
			<label for="password_current"><?php _e( 'Current Password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" />
		</p>
		<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
			<label for="password_1"><?php _e( 'New Password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" />
		</p>
		<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
			<label for="password_2"><?php _e( 'Confirm New Password', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" />
		</p>
	</fieldset>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details' ); ?>
		<input type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" />
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
