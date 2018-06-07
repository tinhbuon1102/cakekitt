<?php 
/*
 * This is the page users will see logged out. 
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
	<div class="lwa lwa-default woocommerce"><?php //class must be here, and if this is a template, class name should be that of template directory ?>
        <form class="lwa-form login" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" method="post">
        	<div class="lwa-form-block">
        	<span class="lwa-status"></span>
          <p class="form-row"><?php do_action('login_form'); ?></p>
           <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
           	<label for="username"><?php _e( 'Username', 'woocommerce' ); ?></label>
           	<span class="user"><input type="text" name="log" class="woocommerce-Input woocommerce-Input--text input-text" id="lwa_user_login" placeholder="<?php _e( 'Username', 'woocommerce' ); ?>"/></span>
           </p>
           <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
           	<label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
           	<span class="pass"><input type="password" name="pwd" class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php _e( 'Password', 'woocommerce' ); ?>" /></span>
           </p>
		   <p class="form-row"><input name="rememberme" type="checkbox" class="lwa-rememberme" value="forever" /> <label class="visible"><?php esc_html_e( 'Remember Me','login-with-ajax' ) ?></label></p>
           <p class="form-row">
           	<input type="submit" name="wp-submit" id="lwa_wp-submit" class="woocommerce-Button button" value="<?php esc_attr_e('Log In', 'login-with-ajax'); ?>" tabindex="100" />
                        <input type="hidden" name="lwa_profile_link" value="<?php echo esc_attr($lwa_data['profile_link']); ?>" />
                        <input type="hidden" name="login-with-ajax" value="login" />
						<?php if( !empty($lwa_data['redirect']) ): ?>
						<input type="hidden" name="redirect_to" value="<?php echo esc_url($lwa_data['redirect']); ?>" />
						<?php endif; ?>
           </p>
           <p class="woocommerce-LostPassword lost_password">
           	<?php if( !empty($lwa_data['remember']) ): ?>
						<a class="lwa-links-remember" href="<?php echo esc_attr(LoginWithAjax::$url_remember); ?>" title="<?php esc_attr_e('Password Lost and Found','login-with-ajax') ?>"><?php esc_attr_e('Lost your password?','login-with-ajax') ?></a>
						<?php endif; ?>
                        <?php if ( get_option('users_can_register') && !empty($lwa_data['registration']) ) : ?>
           </p>
				<p class="form-row">
					まだ会員でない方は、<a href="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" class="lwa-links-register lwa-links-modal"><?php esc_html_e('Register','login-with-ajax') ?></a>
                        <?php endif; ?>
				</p>
				
			<p class="form-row">
				<input type="button" class="woocommerce-Button button skip-authenticate-btn" value="<?php esc_attr_e('Order without Register/Login', 'login-with-ajax'); ?>" tabindex="101" />
			</p>	
            </div>
        </form>
        <?php if( !empty($lwa_data['remember']) && $lwa_data['remember'] == 1 ): ?>
        <form class="lwa-remember" action="<?php echo esc_attr(LoginWithAjax::$url_remember) ?>" method="post" style="display:none;">
        	<div>
        	<span class="lwa-status"></span>
            <table>
                <tr>
                    <td>
                        <strong><?php esc_html_e("Forgotten Password", 'login-with-ajax'); ?></strong>         
                    </td>
                </tr>
                <tr>
                    <td class="lwa-remember-email">  
                        <?php $msg = __("Enter username", 'login-with-ajax'); ?>
                        <input type="text" name="user_login" class="lwa-user-remember" value="<?php echo esc_attr($msg); ?>" onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}" />
                        <?php do_action('lostpassword_form'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="lwa-remember-buttons">
                        <input type="submit" value="<?php esc_attr_e("Get New Password", 'login-with-ajax'); ?>" class="lwa-button-remember" />
                        <a href="#" class="lwa-links-remember-cancel"><?php esc_html_e("Cancel", 'login-with-ajax'); ?></a>
                        <input type="hidden" name="login-with-ajax" value="remember" />
                    </td>
                </tr>
            </table>
            </div>
        </form>
        <?php endif; ?>
		<?php if( get_option('users_can_register') && !empty($lwa_data['registration']) && $lwa_data['registration'] == 1 ): ?>
		<div class="lwa-register lwa-register-default lwa-modal modal-custom" style="display:none;">
			<div class="modal-content woocommerce">
			<div class="modal-header">
			<h4 class="modal-title"><?php _e( 'Register', 'woocommerce' ); ?></h4>
			</div>
			<form class="lwa-register-form register" action="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" method="post">
				<div class="modal-body">
				<span class="lwa-status"></span>
				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<label for="reg_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
					<span class="email"><input type="text" name="user_email" id="user_email" class="woocommerce-Input woocommerce-Input--text input-text" size="25" <?php echo @$_SESSION['cake_custom_order'][3]['custom_order_customer_email']?> placeholder="<?php _e( 'Email address', 'woocommerce' ); ?>"/></span>
					
				</p>
				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
					<span class="user"><input type="text" name="user_login" id="user_login" class="woocommerce-Input woocommerce-Input--text input-text" size="25" value="<?php echo @$_SESSION['cake_custom_order'][3]['custom_order_customer_email']?>" placeholder="<?php _e( 'Username', 'woocommerce' ); ?>" /></span>
					
				</p>
				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<span class="pass"><input type="password" name="user_password" id="user_password" class="woocommerce-Input woocommerce-Input--text input-text" size="25" placeholder="<?php _e( 'Password', 'woocommerce' ); ?>" /></span>
				</p>
				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<label for="reg_password2"><?php _e( 'Confirm Password', 'cake' ); ?> <span class="required">*</span></label>
					<span class="pass"><input type="password" name="user_repeat_password" id="user_repeat_password" class="woocommerce-Input woocommerce-Input--text input-text" size="25" placeholder="<?php _e( 'Confirm Password', 'cake' ); ?>" /></span>
				</p>
				<?php do_action('register_form'); ?>
				<?php do_action('lwa_register_form'); ?>
				<p class="form-row">
					<?php _e( 'if already registered,', 'cake' ); ?><a href="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" class="lwa-links-login"><?php esc_html_e('Login','woocommerce') ?></a>
				</p>
				<p class="form-row">
					<input type="submit" name="wp-submit" id="wp-submit" class="woocommerce-Button button" value="<?php esc_attr_e('Register', 'login-with-ajax'); ?>" tabindex="100" />
				</p>
				<p class="form-row">
					<input type="button" class="woocommerce-Button button skip-authenticate-btn" value="<?php esc_attr_e('Order without Register/Login', 'login-with-ajax'); ?>" tabindex="101" />
				</p>
		        <input type="hidden" name="login-with-ajax" value="register" />
		        </div>
			</form>
			</div>
		</div>
		<?php endif; ?>
	</div>