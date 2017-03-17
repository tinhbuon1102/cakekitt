=== WCP Contact Form ===
Contributors: webcodin
Tags: simple, contact, form, contact form, simple contact form, dynamic form, contact me, contact us, contactus, contact form plugin, email, email message, notifications, admin notifications, customer notifications, customer, form to email, wordpress contact form, subscribe, CSV, CSV export, form builder, builder, captcha, validation, dynamic fields, LESS, dynamic CSS, reCAPTCHA, indicator, autoresponder
Requires at least: 3.5.0
Tested up to: 4.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: trunk

The contact form plugin with dynamic fields, reCAPTCHA and other features that makes it easy to add custom contact form on your site in a few clicks

== Description ==

> **NB!** In case if you made plugin customization, especially directly in the plugin files that placed in the WordPress plugins directory, please **make backup of your customization before update of plugin version**.

Main feature of our contact form is ready-to-use set of the fields includes CAPTCHA, that you can immediately use after installation. However, please not that you can use **ONLY ONE** contact form - it is **not a FORMS BUILDER** plugin.

All that you need it is install plugin, check form settings and add contact form in two ways:

1. As shortcode via TinyMCE toolbar button;
2. As widget to a page sidebar.

As additional options of the contact form, you can find dynamic fields with various types, custom form styles and notifications for administrator and users.

More information about our plugin as well as plugin support you can find on our [demo site](http://wpdemo.webcodin.com/):

* [**actual plugin documentation**](http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/getting-started/);
* [**FAQ**](http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/faq/);
* [**support**](http://wpdemo.webcodin.com/stay-in-touch/);
* [**live demo**](http://wpdemo.webcodin.com/wcp-contact-form-live-demo/);

If you find issues or have any questions about the plugin, please feel free to ask questions:

* directly via [**support form**](http://wpdemo.webcodin.com/stay-in-touch/) on our demo site;
* directly via support email address **support@webcodin.com**;
* [**Support Tab**](https://wordpress.org/support/plugin/wcp-contact-form) on WordPress.ORG.

> Minimum required PHP version is **5.3.0**;

= Features =

* Ready-to-use fields preset after plugin instalation;
* Dynamic form fields with various parameters that can be reordered (via Drag&Drop) and deleted;
* Three types of captcha: Alphabetic Captcha (EN only), Numeric Captcha and reCAPTCHA (including option for reCAPTCHA translation that based on default WordPress language that defined in the admin panel and other options);
* Custom styles for contact form via plugin settings;
* Three variants of the form "successful submission": success notification message (without redirect), redirect on separate "Thank You" page and redirect on some URL.
* Optional HTML5 validation and editable error messages for non-HTML5 validation;
* AutoResponder for users and administrator with variables for autoresponder letters (supports of TinyMCE editor);
* Visual indicator for the new messages at the admin toolbar.
* Inbox page for message list with read/unread statuses and single detailed page for each message;
* List of the messages can be exported to CSV format based on selected fields in the form settings;
* "Quick Reply" button to the Inbox (message list) and form details page that allows to open standard mail client and send quick reply to sender.
* Optional ability to enqueue scripts and styles only for the pages with contact form
* Filter and Action Hooks for developers (from v2.5.0)

= Latest updates for plugin v.3.0.1 =

* drop down (select and multiple select) field type;
* radio buttons field type;
* optional user roles for "Inbox" page access;
* predefined CSS classes for custom form layout, variouse field sizes and text alignment;
* predefined CSS classes for RTL;
* possibility to add custom class for form wrapper.

= Ready-to-Use Fields Preset =

Ready-to-use fields preset after plugin instalation includs following fields: 

* Name (text field type)
* Email (email field type)
* Phone (text field type)
* Subject (text field type)
* Message (textarea field type)
* Alphabetic Captcha (can be easy changed to Numeric Captcha or reCAPTCHA via field additional settings)

= Dynamic Form Fields =

Dynamic form fields include following field types: 

* Text;
* Email
* Numeric;
* Textarea;
* Checkbox(es);
* Radio Buttons;
* Drop Down (select and multiple select);
* Captchas: Alphabetic (EN only) / Numeric / reCAPTCHA.

and support following **base parameters**: 

* Type;
* Field Name (technical field name for internal usage - NOT display label);
* Visibility;
* Required;
* Export to CSV.

Also, fields support following **additional parameters** (based on field type):

* Display Label;
* Placeholder;
* Field Key;
* CSS Class;
* Description;
* ...and more...

= Form Styling =

Contact form fields can be styled with following options:

* Border: size, style and color or no-border;
* Background color or no-background;
* Color settings for labels, text inside fields, required markers ect.;
* Button text and background colors.

= For Developers =

* possibility to customize the plugin by creating a duplicate templates and styles in the active theme folder;
* possibolity to use filter and Action Hooks for developers (from v2.5.0).

More information about [**features for developers**](http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/for-developers/custom-templates/) you can find on our demo site in the corresponding section of the plugin documentation. However, please note that in documentation provided **ONLY references** and **NOT "ready solutions"**, so if you don't have specific knowledges in PHP and plugins development and customization this documentation won't be useful for you.
 
== Installation ==

**Installation form WordPress.ORG**

1. Download a copy of the plugin.
2. Unzip and upload 'wcp-contact-form' to a sub directory in '/wp-content/plugins/'.
3. Activate the plugins through the 'Plugins' menu in WordPress.
4. Click on "Contact Form" in your WordPress Dashboard left side menu pane. Under "Contact Form" section you can find "Inbox" page where you can check all received messages and "Settings" page where you are able to configure form parameters.

**Installation form WordPress Admin Panel**

1. Log into your WordPress Dashboard;
2. Go to "Plugins" > "Add New" and search for the plugin "WCP Contact Form";
3. Click "Install" and then click on "Activate" to install and activate the plugin so you can use it;
4. Click on "Contact Form" in your WordPress Dashboard left side menu pane. Under "Contact Form" section you can find "Inbox" page where you can check all received messages and "Settings" page where you are able to configure form parameters.

More information you can find in the [plugin documentation](http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/getting-started/plugin-installation/) on our demo site.

= 3 easy steps to start using of our contact form on a page =

1. check plugin "Settings" page and customize form options for your purposes ("Contact Form" > "Settings");
2. create new page or use existed ("Pages" > "All Pages"/"Add New");
3. add shortcode via TinyMCE toolbar button and save the page. In case, if you **doesn't have TinyMCE toolbar button**, you can simply add form shortcode [wcp_contactform id="wcpform_1"] manually. As ID value you can use any unique value.

New messages can be found in "Contact Form" > "Inbox".

More information you can find in the [plugin documentation](http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/getting-started/usage-on-page-shortcode/) on our demo site.

= 3 easy steps to start using of our contact form at a sidebar =

1. check plugin "Settings" page and customize form options for your purposes ("Contact Form" > "Settings");
2. go to the "Appearance" > "Widgets" sections;
3. add "WCP Contact Form" widget to the necessary sidebar

New messages can be found in "Contact Form" > "Inbox".

More information you can find in the [plugin documentation](http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/getting-started/usage-in-sidebar-widget/) on our demo site.

== Frequently Asked Questions ==

> Please note, [ actual plugin documentation](http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/getting-started/), [FAQ](http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/faq/) and [plugin support](http://wpdemo.webcodin.com/stay-in-touch/) you can find on our demo site.

If you find issues or have any questions about the plugin, please feel free to ask questions:

* directly via [**support form**](http://wpdemo.webcodin.com/stay-in-touch/) on our demo site;
* directly via support email address **support@webcodin.com**;
* [**Support Tab**](https://wordpress.org/support/plugin/wcp-contact-form) on WordPress.ORG.

You can find most popular users question to plugin support devided by groups from the plugin [**FAQ**](http://wpdemo.webcodin.com/wordpress-plugin-wcp-contact-form/documentation/faq/) section below.

**General Questions**

*Common questions about WCP Contact Form Plugin*

* [How can I add more than one form?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-add-more-than-one-form/)
* [How can I disable "Inbox" and get emails only on email address?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-can-disable-inbox/)
* [How can I add a form to a page?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-add-a-form-to-a-page/)                                                              
* [How can I add a form to a sidebar?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-add-a-form-to-a-sidebar/)
* [Where can I find received messages?](http://wpdemo.webcodin.com/hrf_faq/where-can-i-find-received-messages/)

**Email Notifications Setup**

*Questions related to issues with receiving of email notifications*

* [Why I don't receive email notifications?](http://wpdemo.webcodin.com/hrf_faq/can-change-email-notifications-settings/)

**Email Notifications Setup**

*Questions related to setup of email notifications (autoresponder)*

* [Where can I change email notifications settings?](http://wpdemo.webcodin.com/hrf_faq/where-can-i-change-email-address-administrator-notifications/)
* [Where can I change email address for administrator notifications?](http://wpdemo.webcodin.com/hrf_faq/where-can-i-change-email-address-administrator-notifications/)
* [How can I add several email addresses for admin notifications?](http://wpdemo.webcodin.com/hrf_faq/how-i-can-add-several-email-addresses-for-admin-notifications/)
* [How can I change "wordpress@mysite.com" to "mysite@mysite.com"?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-change-wordpressmysite-com-to-mysitemysite-com/)

**Issues with Captcha(s)**

*Questions related to issues with Captchas: Alphabetic Captcha, Numeric Captcha and reCaptcha*

* [How can I add reCAPTCHA to my form](http://wpdemo.webcodin.com/hrf_faq/where-can-i-add-recaptcha-to-my-form/)
* [What should I do if I use simple CAPTCHA (alphabetic or numeric) and it is blank?](http://wpdemo.webcodin.com/hrf_faq/what-should-i-do-if-simple-captcha-is-blank/)

**Issues with form configuration**

*Questions related to issues with form configuration*

* [What should I do if I use SiteOrigin Page Builder and added form shortcode do not work properly?](http://wpdemo.webcodin.com/hrf_faq/add-form-shortcode/)
* [How can I to configure form fields?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-to-configure-form-fields/)
* [How can I style contact form?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-style-contact-form/)
* [How can I customize "Send" button text and position?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-customize-send-button-text/)
* [How can I export data to CSV?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-export-data-to-csv/)

**Form validation, error and successful messages**

*Questions related to form validation, error and successful messages, redirect (page or URL) after successful form submission*

* [How can I use own "Thank You" page?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-use-own-thank-you-page/)
* [Where can I change success notification message?](http://wpdemo.webcodin.com/hrf_faq/where-can-i-change-success-notification-message/)
* [How can I enable HTML5 validation?](http://wpdemo.webcodin.com/hrf_faq/how-can-i-enable-html5-validation/)
* [Where can I change validation error messages?](http://wpdemo.webcodin.com/hrf_faq/where-can-i-change-validation-error-messages/)

**Other**

*Miscellaneous questions*

* [I can't find button in TinyMCE toolbar for shortcode adding?](http://wpdemo.webcodin.com/hrf_faq/i-cant-find-button-in-tinymce-toolbar-for-shortcode-adding/)
* [What should I do if I have "Possible Security Vulnerability" or other warning report from SiteLock or other security plugin?](http://wpdemo.webcodin.com/hrf_faq/what-should-i-do-if-i-have-possible-security-vulnerability-or-other-warning-report-from-sitelock-or-other-security-plugin/)
* [What should I do if I have error message about outdated PHP version after plugin installation?](http://wpdemo.webcodin.com/hrf_faq/what-should-i-do-if-i-have-error-message-about-outdated-php-version-after-plugin-installation/)
* [I have "Parse error: syntax error, unexpected T_STRING, expecting T_CONSTANT_ENCAPSED_STRING" after the plugin installation.](http://wpdemo.webcodin.com/hrf_faq/i-have-error-after-the-plugin-installation/)

== Screenshots ==

1. Form Sample :: Available Field Types
2. Form Sample :: Sample of a Form with Predefined CSS Classes 
3. Form Sample :: Simple of a Form with Alphabetic CAPTCHA and Custom Styling
4. Form Sample :: Form Widget with Numeric CAPTCHA and Custom Styling
5. Admin Panel :: Form Shordcode
6. Admin Panel :: Form Widget
7. Admin Panel :: Inbox
8. Admin Panel :: Inbox :: Group Actions
9. Admin Panel :: Inbox :: Detail
10. Admin Panel :: Settings :: Form Tab :: Default Configuration
11. Admin Panel :: Settings :: Form Tab :: Field Additional Settings
12. Admin Panel :: Settings :: Form Tab :: Custom Configuration and Field Additional Settings
13. Admin Panel :: Settings :: Style Tab
14. Admin Panel :: Settings :: Messages (Errors)Tab
15. Admin Panel :: Settings :: AutoResponder
16. Admin Panel :: Settings :: ReCAPTCHA Settings

== Changelog ==

= 3.1.0 = 

> **NB! We've made a lot of changes in the plugin styles and templates. In case if you made plugin manual customization via plugin files and styles, please make a backup of your customization to prevent files overwrite during plugin update. Also, we do not provide any garantee that your manual customization will work properly after plugin updates.**

* added: drop down (select and multiple select) field type;
* added: radio buttons field type;
* added: optional user roles for "Inbox" page access;
* added: predefined CSS classes for custom form layout, variouse field sizes and text alignment;
* added: predefined CSS classes for RTL;
* added: possibility to add custom class for form wrapper;
* removed: {$loggedin_user_email} variable form "Default Value" for "Email" field type by default;
* cleanup and actualization of plugin documentation;
* global changes in the plugin stylesheets; 

= 3.0.4 = 
* Hotfix for issue with a product duplication in the WooCommerce product listing.

= 3.0.3 = 
* Added possibility to use variables for user email address, user name, subject and message. These variables allows to create "reply" notification for site administrator. **NB!** This option was added by multiple users' requests, however we **HIGHLY DO NOT RECOMMEND** to use this option. It doesn't work with SMTP email configuration and doesn't work stable with common WordPress email configuration. We do not provide any guarantee of properly work of this option and you will use it at your own risk. 
* Added possibility to change standard WordPress "from email" and sender name. **NB!** This option was added by multiple users' requests, however we **HIGHLY DO NOT RECOMMEND** to use this option. It doesn't work with SMTP email configuration and doesn't work stable with common WordPress email configuration. We do not provide any guarantee of properly work of this option and you will use it at your own risk. 
* Added possibility to disable plugin styles including less-based styles via admin panel.
* Minor changes and styles fix

= 3.0.2 = 
* Fixed issue with HTML5 validation for the "checkbox" field with multiple values.
* Minor changes

= 3.0.1 = 
* Fixed small issue with showing TinyMCE content of email templates in admin panel area.

= 3.0.0 = 

> **NB! WCP Contact Form plugin v3.0 has global changes in the interface and functionality from previously versions. Just in case, please make backup before update from old versions, especially if you made plugin customization directly in the plugin core files or templates.**

* changed backend interface for plugin settings
* added support of additional fields parameters: "Display Label", "Placeholder", "Field Key", "CSS Class", "Description" and other (based on specific fields type)
* added support of group of checkboxes with additional ability to use custom callback function for developers
* added extended functionality for "Reply" button on the Inbox page. Now bu click of this button for standard mail client will be preset following values: user email, user name, subject and message

= 2.5.4 = 
* minor changes
* added full compatibility with SiteOrigin Page Builder

= 2.5.3 =  
* minor bugfix

= 2.5.2 = 
* minor changes

= 2.5.1 = 
* added: TinyMCE editors to the "Notifications" settings page for the "Message" fields of email notifications
* added: New "Numeric Captcha" field for the form
* changed: Show "Inbox" only for administrator
* Now you can use custom URL link as "Thank You" page for redirection after form submission

= 2.5.0 = 
* added: Filter and Action Hooks
* added: Support Right-to-Left Languages

= 2.4.1 = 
* changed: Form entries excluded from search result
* changed: Rules for enqueue of the reCaptcha JS
* changed: Method of checking for minimum required PHP version on a server
* Minor changes for compatibility with WordPress 4.4

= 2.4.0 = 
* fixed: Removed strip slashes from submited form fields
* added: Added ability to change color for "Required" marker via plugin settings

= 2.3.8 =
* fixed: Fixed issue with multiple form submit for some site configurations
* added: Added ability to change background / text colors on hover for "Submit" button via plugin settings

= 2.3.7 =
* minor changes

= 2.3.6 =
* added: Check of the minimum required PHP version on a server
* added: Lock of the "Submit" button during form submission

= 2.3.5 =
* added: "Quick Reply" button to the Inbox (message list) and form details page that allows to open standard mail client and send quick reply to sender.
* added: Option for reCAPTCHA translation that based on default WordPress language that defined in the admin panel
* minor changes in plugin core

= 2.3.4 =
* minor bugfixing

= 2.3.3 =
* minor bugfixing

= 2.3.2 =
* minor bugfixing

= 2.3.1 =
* added: Optional ability to enqueue scripts and styles only for the pages with contact form
* changed: Minor loading speed optimization

= 2.3.0 =
* added: New reCAPTCHA field for the form
* added: Indicator for unread inbox messages in admin toolbar
* minor styles changes
* minor bugfixing

= 2.2.0 =
* added: LESS-based dynamic CSS for custom form styles
* added: Drag&Drop re-order for fields configurator
* added: Success notification message after the form submission for form without "Thank You" page
* added: Ability to change type of the fields in the default fields preset
* added: New parameter "Submit Success" that allows to set submit success message for the form
* changed: Caption of the "Errors" tab changed to "Messages" value on the form settings page

= 2.1.3 =
* fixed: Issue with duplicate email notifications

= 2.1.2 =
* added: new parameter "Button Position" that allows to set submit button position

= 2.1.1 =
* added: filter hook 'wcp_contact_form_get_fields_settings' for developer needs
* added: new notifications varable **{$user_email}**
* added: setting for default "User Name" field for user notification variables

= 2.1.0 =
* added: new friendly "[wcp_contactform]" shortcode that can used instead "[scfp]"
* changed: now "id" parameter is not necessary for single shortcode on a page
* added: button in a TinyMCE editor that allows to add contact form in editor area by one click
* changed: layout of the "Settings" page
* added: notes for a parameters on the "Settings" page tabs
* added: possibility to form style customization 
* updated plugin documentation

= 2.0.1 =
* changed: "Refresh" button styling for CAPTCHA field 
* minor styles changes

= 2.0.0 =
* global changes of the plugin core and templates structure. **Beware!** You can have issues if you make some customization in the form templates manually by code!;
* added possibility to dynamic setup of the form fields. Fields can be added, deleted and reordered;
* added following field types: numeric, checkbox;
* added export to CSV format;
* added setting for default user notification email for forms with multiple email fields;
* added additional error message for numeric field type;
* Fixed: Issue with fatal error when trying to activate plugin for PHP 5.3;
* Fixed: Issue for AJAX request with enabled Zlib-compression;

= 1.2.0 =
* global changes of the plugin core;

= 1.1.0 =
* added form widget;
* added optional CAPTCHA field and editable error message;
* added ability to reset form options to default;
* added variables for notification messages;
* general cleanup and optimization;

= 1.0.0 =
* initial release.
