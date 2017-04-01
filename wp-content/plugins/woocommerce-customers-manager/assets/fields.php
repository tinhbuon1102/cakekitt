<?php 
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_56cded5996525',
	'title' => 'WooCommerce Customer Manager - Email templates configurator',
	'fields' => array (
		array (
			'key' => 'field_56cdeded44a59',
			'label' => 'Guest to registerd user conversion -	email template',
			'name' => 'wccm_guest_to_registered_email_template',
			'type' => 'textarea',
			'instructions' => 'Paste a VALID HTML EMAIL CODE (not all the html tags can be used) in the following text area. It will be used as template for the email sent when a customer is converted from guest to registered. The email will contain the new credentials (user and password). 
NOTE: include the shortode [message_body] inside your custom template otherwise	the credentials will not be included.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '[message_body]',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56cdef0244a5a',
			'label' => 'Guest to registerd user conversion - include deafault WooCommerce email header and footer?',
			'name' => 'wccm_guest_to_registered_header_footer_inlcude',
			'type' => 'select',
			'instructions' => 'You can choose to include the default WooCommerce email header and footer in the guest to registered notification email',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'all' => 'Include header and footer',
				'header' => 'Include only the header',
				'footer' => 'Include only the footer',
				'none' => 'Do not include anything',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_56cf18adc9d89',
			'label' => 'Guest to registered user conversion - template preview',
			'name' => 'wcam_guest_to_registered_user_conversion_template_preview',
			'type' => 'html_email_preview_field',
			'instructions' => 'Here you can preview the template. To show the preview please hit "Save" button after having pasted the template HTML code.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'template_preview_to_show' => 'guest_to_registered',
		),
		array (
			'key' => 'field_56cdefbea2283',
			'label' => 'Customer notification - email template',
			'name' => 'wccm_customer_notification_email_template',
			'type' => 'textarea',
			'instructions' => 'Paste a VALID HTML EMAIL CODE (not all the html tags can be used) in the following text area. It will be used for user notifications emails (bulk emails or emails sent throught customer details page). 
NOTE: include the shortode [message_body] inside your custom template otherwise the notification message will not be included.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '[message_body]',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56cdf041a2284',
			'label' => 'Customer notification - include deafault WooCommerce email header and footer?',
			'name' => 'wccm_customer_notification_header_footer_inlcude',
			'type' => 'select',
			'instructions' => 'You can choose to include the default WooCommerce email header and footer in the customer notifications emails',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'all' => 'Include header and footer',
				'header' => 'Include only the header',
				'footer' => 'Include only the footer',
				'none' => 'Do not include anything',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_56cf18fac9d8a',
			'label' => 'Customer notification - template preview',
			'name' => 'wcam_customer_notification_template_preview',
			'type' => 'html_email_preview_field',
			'instructions' => 'Here you can preview the template. To show the preview please hit "Save" button after having pasted the template HTML code.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'template_preview_to_show' => 'customer_notification',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-email-templates-configurator',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
?>