<?php
new cake_theme_customizer();

class cake_theme_customizer
{
    public function __construct()
    {
        //add_action ('admin_menu', array(&$this, 'cake_customizer_admin'));
        add_action( 'customize_register', array(&$this, 'cake_customize_manager_demo' ));
		add_action( 'customize_register', array(&$this, 'cake_remove_customizer_settings' ), 20 );
    }

    /**
     * Add the Customize link to the admin menu
     * @return void
     */
    public function cake_customizer_admin() {
        //add_theme_page( 'Customize', 'Customize', 'edit_theme_options', 'customize.php' );
    }

    /**
     * Customizer manager demo
     * @param  WP_Customizer_Manager $wp_customize
     * @return void
     */
    public function cake_customize_manager_demo( $wp_customize )
    {
		
		$this->cake_customizer_general_sections( $wp_customize );
		$this->cake_customizer_header_sections( $wp_customize );
		$this->cake_customizer_blog_sections( $wp_customize );
		$this->cake_customizer_shop_sections( $wp_customize );
		$this->cake_customizer_social_sections( $wp_customize );
		$this->cake_customizer_typography_sections( $wp_customize );
		$this->cake_customizer_styling_sections( $wp_customize );
		$this->cake_customizer_after_content_sections( $wp_customize );
		$this->cake_customizer_footer_sections( $wp_customize );
		
    }
	
	/**
     * Customizer Remove Setting
     * @param  WP_Customizer_Manager $wp_customize
     * @return void
     */
	public function cake_remove_customizer_settings( $wp_customize ){

	  //$wp_customize->remove_panel('nav');
	  $wp_customize->remove_section('static_front_page');

	}
	

    //GENERAL SECTION
	private function cake_customizer_general_sections( $wp_customize )
    {
		

		$wp_customize->add_section('general_settings_section', array(
		'title'				=> esc_html__('General', 'cake'),
		'priority'			=> 36,
		));
		
		//enable or disable responsive layout
		$wp_customize->add_setting( 'cake_responsive_layout', array(
        'default'   		=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_responsive_layout', array(
		'label'   		=> esc_html__('Responsive Layout', 'cake'),
		'description'	=> esc_html__('Activate the responsive layout. If enabled, the website will change its shape for mobile devices.','cake'),
		'section' 		=> 'general_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//enable or disable loader effect
		$wp_customize->add_setting( 'cake_loader_effect', array(
        'default'   		=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_loader_effect', array(
		'label'   		=> esc_html__('Loader Effect', 'cake'),
		'description'	=> esc_html__('Activate/deactivate loader effect.','cake'),
		'section' 		=> 'general_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ) );
		
		//custom logo
		$wp_customize->add_setting( 'cake_custom_logo', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_file_url',
        ));

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'cake_custom_logo', array(
		'label'   		=> esc_html__('Custom Logo', 'cake'),
		'description'	=> esc_html__('Upload an image that will represent your website logo.','cake'),
		'section' 		=> 'general_settings_section',
		'settings'   	=> 'cake_custom_logo',
        ) ) );
		
		//animation logo
		$wp_customize->add_setting( 'cake_animation_logo', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));

        $wp_customize->add_control( 'cake_animation_logo', array(
		'label'   		=> esc_html__('Animation Logo', 'cake'),
		'description'	=> esc_html__('Enable/disable animation around logo','cake'),
		'section' 		=> 'general_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ) );
		
		//shape logo
		$wp_customize->add_setting( 'cake_shape_logo', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));

        $wp_customize->add_control( 'cake_shape_logo', array(
		'label'   		=> esc_html__('Shape around logo?', 'cake'),
		'description'	=> esc_html__('Enable/disable circle shape around logo','cake'),
		'section' 		=> 'general_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ) );
		
		//404 text
		$wp_customize->add_setting( 'cake_404_text', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_textarea',
        ) );

        $wp_customize->add_control( 'cake_404_text', array(
		'label'   		=> esc_html__( '404 Text', 'cake' ),
		'description'	=> esc_html__('Place here your text 404 page not found message.','cake'),
		'section' 		=> 'general_settings_section',
		'type'    		=> 'textarea',
		'settings'   	=> 'cake_404_text',
        ) );

	}
	
	//HEADER SECTION
	private function cake_customizer_header_sections( $wp_customize )
    {
		
		$wp_customize->add_panel( 'header_panel', array(
		  'title' => esc_html__('Header', 'cake'),
		  'priority' => 37 // Mixed with top-level-section hierarchy.
		) );
		
		
		//Header section
		$wp_customize->add_section('header_settings_section', array(
		'title'			=> esc_html__('Main', 'cake'),
		'priority'		=> 36,
		'panel' => 'header_panel',
		));
		
		//Main Menu Type
		$wp_customize->add_setting( 'cake_header_type', array(
        'default'       	=> 'fixed',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_header_type', array(
		'label'   		=> esc_html__('Header Type', 'cake'),
		'description'	=> esc_html__('Choose header type.','cake'),
		'section' 		=> 'header_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array(
						'static'		=> esc_html__('Static top', 'cake'),
						'fixed'			=> esc_html__('Fixed top', 'cake')
						),
        ) );
		
		//Search Icon
		$wp_customize->add_setting( 'cake_top_search_icon', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_top_search_icon', array(
		'label'   		=> esc_html__('Search Icon', 'cake'),
		'description'	=> esc_html__('Enable/disable search icon.','cake'),
		'section' 		=> 'header_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Search Type
		$wp_customize->add_setting( 'cake_search_type', array(
        'default'       	=> 'product_search',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_search_type', array(
		'label'   		=> esc_html__('Search Type', 'cake'),
		'description'	=> esc_html__('Choose search type.','cake'),
		'section' 		=> 'header_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('blog_search'=>esc_html__('Blog Search', 'cake'), 'product_search'=>esc_html__('Product Search', 'cake')),
        ) );
		
		//Login Link
		$wp_customize->add_setting( 'cake_login_link', array(
        'default'      		=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_login_link', array(
		'label'   		=> esc_html__('Login Link', 'cake'),
		'description'	=> esc_html__('Enable/disable login link.','cake'),
		'section' 		=> 'header_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Cart Icon
		$wp_customize->add_setting( 'cake_cart_icon', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_cart_icon', array(
		'label'   		=> esc_html__('Cart Icon', 'cake'),
		'description'	=> esc_html__('Enable/disable cart icon.','cake'),
		'section' 		=> 'header_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Wishlist Icon
		$wp_customize->add_setting( 'cake_wishlist_icon', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_wishlist_icon', array(
		'label'   		=> esc_html__('Wishlist Icon', 'cake'),
		'description'	=> esc_html__('Enable/disable wishlist icon.','cake'),
		'section' 		=> 'header_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Page Header
		$wp_customize->add_setting( 'cake_page_header_img', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_file_url',
        ) );

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'cake_page_header_img', array(
		'label'   		=> esc_html__('Page Header Image', 'cake'),
		'description'	=> esc_html__('Upload an image for background header','cake'),
		'section' 		=> 'header_settings_section',
		'settings'   	=> 'cake_page_header_img',
        ) ) );
		
		
		//Header Nav Section	
		$wp_customize->add_section('header_nav_settings_section', array(
		'title'			=> esc_html__('Popup Navigation', 'cake'),
		'priority'		=> 36,
		'panel' 		=> 'header_panel',
		));
		
		//Enable Pop up Navigation
		$wp_customize->add_setting( 'cake_popup_nav', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_popup_nav', array(
		'label'   		=> esc_html__('Popup Navigation', 'cake'),
		'description'	=> esc_html__('Enable/disable popup navigation.','cake'),
		'section' 		=> 'header_nav_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Navigation Column
		$wp_customize->add_setting( 'cake_nav_column', array(
        'default'       	=> '3',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));
		$wp_customize->add_control('cake_nav_column', array(
		'label'   		=> esc_html__('Navigation Column', 'cake'),
		'section' 		=> 'header_nav_settings_section',
		'type'    		=> 'select',
		'choices' 		=> array('1'=>esc_html__('1 Column', 'cake'), '2'=>esc_html__('2 Column', 'cake'), '3'=>esc_html__('3 Column', 'cake'), '4'=>esc_html__('4 Column', 'cake')),
		'settings'   	=> 'cake_nav_column',
		));
		
		//Navigation Label
		$wp_customize->add_setting( 'cake_nav_label', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_nav_label', array(
		'label'   		=> esc_html__('Navigation Label', 'cake'),
		'section' 		=> 'header_nav_settings_section',
		'type'    		=> 'text',
		'settings'   	=> 'cake_nav_label',
		));
		
		//Description Label
		$wp_customize->add_setting( 'cake_about_label', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_about_label', array(
		'label'   		=> esc_html__('Description Label', 'cake'),
		'section' 		=> 'header_nav_settings_section',
		'type'    		=> 'text',
		'settings'   	=> 'cake_about_label',
		));
		
		//Description Text
		$wp_customize->add_setting( 'cake_about_text', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_textarea',
        ) );

        $wp_customize->add_control( 'cake_about_text', array(
		'label'   		=> esc_html__( 'Description Text', 'cake' ),
		'description'	=> esc_html__('Describe your company here. Will be displayed in popup navigation.','cake'),
		'section' 		=> 'header_nav_settings_section',
		'type'    		=> 'textarea',
		'settings'   	=> 'cake_about_text',
        ) );
		
		
		//Social Media Nav On/Off
		$wp_customize->add_setting( 'cake_social_nav_onoff', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_social_nav_onoff', array(
		'label'   		=> esc_html__('Social Icon', 'cake'),
		'description'	=> esc_html__('Enable/disable social icon.','cake'),
		'section' 		=> 'header_nav_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Social Media Nav Label
		$wp_customize->add_setting( 'cake_social_nav_label', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_social_nav_label', array(
		'label'   		=> esc_html__('Social Navigation Label', 'cake'),
		'section' 		=> 'header_nav_settings_section',
		'type'    		=> 'text',
		'settings'   	=> 'cake_social_nav_label',
		));
	}
	
	//BLOG SECTION
	private function cake_customizer_blog_sections( $wp_customize )
    {
		$wp_customize->add_section('blog_settings_section', array(
		'title'			=> esc_html__('Blog', 'cake'),
		'priority'		=> 38,
		));
		
		//Sidebar Position
		$wp_customize->add_setting( 'cake_blog_sidebar', array(
        'default'       	=> 'sidebar-right',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );
		$wp_customize->add_control( 'cake_blog_sidebar', array(
		'label'   		=> esc_html__('Sidebar Position for Blog', 'cake'),
		'description'	=> esc_html__('Select a sidebar position for blog related pages. It will be applied to single posts, index page, archive and search pages.','cake'),
		'section' 		=> 'blog_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('sidebar-right'=>esc_html__('Sidebar Right', 'cake'), 'sidebar-left'=>esc_html__('Sidebar Left', 'cake'), 'no-blog-sidebar'=>esc_html__('No Sidebar', 'cake')),
        ) );
		
		//Read More Text
		$wp_customize->add_setting( 'cake_readmore_text', array(
        'default'       	=> esc_html__('Read More', 'cake'),
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_readmore_text', array(
		'label'   		=> esc_html__('Read More Text', 'cake'),
		'description'	=> esc_html__('Replace read more text in blog posts.','cake'),
		'section' 		=> 'blog_settings_section',
		'type'    		=> 'text',
		));
		
		//Blog Title
		$wp_customize->add_setting( 'cake_blog_title', array(
        'default'       	=> esc_html__('Blog', 'cake'),
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_blog_title', array(
		'label'   		=> esc_html__('Blog Title', 'cake'),
		'description'	=> esc_html__('It will be applied to single posts page.','cake'),
		'section' 		=> 'blog_settings_section',
		'type'    		=> 'text',
		));
		
		//Author Meta
		$wp_customize->add_setting( 'cake_author_meta', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_author_meta', array(
		'label'   		=> esc_html__('Enable Author Name', 'cake'),
		'description'	=> esc_html__('If the option is on, the name of author will be displayed on post.','cake'),
		'section' 		=> 'blog_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Comment Meta
		$wp_customize->add_setting( 'cake_comment_meta', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_comment_meta', array(
		'label'   		=> esc_html__('Enable Comment Count', 'cake'),
		'description'	=> esc_html__('If the option is on, the comment count will be displayed on post.','cake'),
		'section' 		=> 'blog_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Date Meta
		$wp_customize->add_setting( 'cake_date_meta', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_date_meta', array(
		'label'   		=> esc_html__('Enable Post Date', 'cake'),
		'description'	=> esc_html__('If the option is on, the post date will be displayed on post.','cake'),
		'section' 		=> 'blog_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Tag Meta
		$wp_customize->add_setting( 'cake_tag_meta', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_tag_meta', array(
		'label'   		=> esc_html__('Enable Post Tags', 'cake'),
		'description'	=> esc_html__('If the option is on, the post tag will be displayed on post inner pages.','cake'),
		'section' 		=> 'blog_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//Category Meta
		$wp_customize->add_setting( 'cake_category_meta', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_category_meta', array(
		'label'   		=> esc_html__('Enable Post Category', 'cake'),
		'description'	=> esc_html__('If the option is on, the post category will be displayed on post.','cake'),
		'section' 		=> 'blog_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
	}
	
	
	// SHOP SECTION
	private function cake_customizer_shop_sections( $wp_customize )
    {
		$wp_customize->add_section('shop_settings_section', array(
		'title'			=> esc_html__('Shop', 'cake'),
		'priority'		=> 39,
		));
		
		//product column
		$wp_customize->add_setting( 'cake_product_column', array(
        'default'       	=> '3',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );
		
		$wp_customize->add_control( 'cake_product_column', array(
		'label'   		=> esc_html__('Product Column', 'cake'),
		'description'	=> esc_html__('Select product column layout','cake'),
		'section' 		=> 'shop_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('2'=>esc_html__('2 COlumn','cake'), '3'=>esc_html__('3 Column','cake'), '4'=>esc_html__('4 Column','cake')),
        ) );
		
		//product per page
		$wp_customize->add_setting( 'cake_product_per_page', array(
        'default'       	=> '9',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );
		
		$wp_customize->add_control('cake_product_per_page', array(
		'label'   		=> esc_html__('Product Per Page', 'cake'),
		'description'	=> esc_html__('Number of product to display in shop page','cake'),
		'section' 		=> 'shop_settings_section',
		'type'    		=> 'text',
		));
		
		//gallery thumb in single product page
		$wp_customize->add_setting( 'cake_product_gallery_thumb', array(
        'default'       	=> 'true',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_product_gallery_thumb', array(
		'label'   		=> esc_html__('Enable Gallery Thumb', 'cake'),
		'description'	=> esc_html__('If the option is on, the gallery thumb will be displayed on single product page','cake'),
		'section' 		=> 'shop_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//gallery thumb carousel timeout
		$wp_customize->add_setting( 'cake_product_gallery_thumb_carousel', array(
        'default'       	=> '0',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));

        $wp_customize->add_control( 'cake_product_gallery_thumb_carousel', array(
		'label'   		=> esc_html__('Gallery Thumb Carousel Timeout', 'cake'),
		'description'	=> esc_html__('Carousel timeout value for gallery thumb. Default is 0, set to 0 if you want disable carousel','cake'),
		'section' 		=> 'shop_settings_section',
		'type'    		=> 'text',
        ));
		
		//widget in single product page
		$wp_customize->add_setting( 'cake_product_single_widget', array(
        'default'       	=> 'false',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ));

        $wp_customize->add_control( 'cake_product_single_widget', array(
		'label'   		=> esc_html__('Enable Widget in Single Product', 'cake'),
		'description'	=> esc_html__('If the option is on, the widgets displayed on single product page','cake'),
		'section' 		=> 'shop_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ));
		
		//product sale label
		$wp_customize->add_setting( 'cake_product_sale_label', array(
        'default'       	=> 'sale',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));

        $wp_customize->add_control( 'cake_product_sale_label', array(
		'label'   		=> esc_html__('Product Sale Label', 'cake'),
		'description'	=> esc_html__('Change sale label text on product','cake'),
		'section' 		=> 'shop_settings_section',
		'type'    		=> 'text',
        ));
		
		//product outstock label
		$wp_customize->add_setting( 'cake_product_outstock_label', array(
        'default'       	=> 'no stock',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));

        $wp_customize->add_control( 'cake_product_outstock_label', array(
		'label'   		=> esc_html__('Product Out Stock Label', 'cake'),
		'description'	=> esc_html__('Change out stock label text on product','cake'),
		'section' 		=> 'shop_settings_section',
		'type'    		=> 'text',
        ));
		
		
	}

	
	
	// SOCIAL SECTION
	private function cake_customizer_social_sections( $wp_customize )
    {
		
		$wp_customize->add_section('social_settings_section', array(
		'title'			=> esc_html__('Social', 'cake'),
		'priority'		=> 40,
		));
		
		 
		//adding social icon
		$wp_customize->add_setting( 'cake_rss', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_rss', array(
		'label'   		=> esc_html__('RSS Feed address', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_facebook', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_facebook', array(
		'label'   		=> esc_html__('Facebook page/profile URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_twitter', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_twitter', array(
		'label'   		=> esc_html__('Twitter URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_instagram', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_instagram', array(
		'label'   		=> esc_html__('Instagram Profile URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_linkedin', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_linkedin', array(
		'label'   		=> esc_html__('LinkedIn Profile URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_flickr', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_flickr', array(
		'label'   		=> esc_html__('Flickr Page URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_google-plus', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_google-plus', array(
		'label'   		=> esc_html__('Google Plus Page URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_dribbble', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_dribbble', array(
		'label'   		=> esc_html__('Dribbble Profile URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_pinterest', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_pinterest', array(
		'label'   		=> esc_html__('Pinterest URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_skype', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_skype', array(
		'label'   		=> esc_html__('Skype Username', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_github', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_github', array(
		'label'   		=> esc_html__('Github URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_youtube', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_youtube', array(
		'label'   		=> esc_html__('YouTube URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_vimeo', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_vimeo', array(
		'label'   		=> esc_html__('Vimeo Page URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_tumblr', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_tumblr', array(
		'label'   		=> esc_html__('Tumblr URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_behance', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_behance', array(
		'label'   		=> esc_html__('Behance Profile URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_vk', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_vk', array(
		'label'   		=> esc_html__('VK URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_xing', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_xing', array(
		'label'   		=> esc_html__('Xing URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));

		$wp_customize->add_setting( 'cake_soundcloud', array(
        'default'       	=> '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ));
		$wp_customize->add_control('cake_soundcloud', array(
		'label'   		=> esc_html__('SoundCloud URL', 'cake'),
		'section' 		=> 'social_settings_section',
		'type'    		=> 'text',
		));


	}
	
	
	// TYPOGRAPHY SECTION
	private function cake_customizer_typography_sections( $wp_customize )
    {
		
		$getgooglefont = cake_google_font_array();
		
		
		$wp_customize->add_section('typography_settings_section', array(
		'title'			=> esc_html__('Typography', 'cake'),
		'priority'		=> 41,
		));
		
		//body typography
		$wp_customize->add_setting( 'cake_body_typo', array(
        'default'       	=> 'Lato',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_body_typo', array(
		'label'   		=> esc_html__('Body Font Options', 'cake'),
		'description'	=> esc_html__('Font Family','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices' 		=> $getgooglefont,
        ) );
		
		$wp_customize->add_setting( 'cake_body_fs', array(
        'default'       	=> '14',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_body_fs', array(
		'description'	=> esc_html__('Font Size','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_body_flh', array(
        'default'       	=> '25',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_body_flh', array(
		'description'	=> esc_html__('Line Height','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_body_fw', array(
        'default'       => 'normal',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_body_fw', array(
		'description'	=> esc_html__('Font Weight','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices'		=> array('light'=>esc_html__('Light 300', 'cake'), 'normal'=>esc_html__('Normal 400', 'cake'), 'semibold'=>esc_html__('Semibold 600', 'cake'), 'bold'=>esc_html__('Bold 700', 'cake'))
        ) );
		
		$wp_customize->add_setting( 'cake_body_fc', array(
        'default'       	=> '#a1a2a6',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_body_fc', array(
		'description'	=> esc_html__('Font Color','cake'),
		'section' 		=> 'typography_settings_section',
		'settings'   	=> 'cake_body_fc',
        ) ) );
		
		//logo font
		$wp_customize->add_setting( 'cake_logo_typo', array(
        'default'       	=> 'Pacifico',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_logo_typo', array(
		'label'   		=> esc_html__('Logo Font Options', 'cake'),
		'description'	=> esc_html__('Font Family','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices' 		=> $getgooglefont,
        ) );
		
		$wp_customize->add_setting( 'cake_logo_fs', array(
        'default'       	=> '16',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_logo_fs', array(
		'description'	=> esc_html__('Font Size','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_logo_flh', array(
        'default'       	=> '22',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_logo_flh', array(
		'description'	=> esc_html__('Line Height','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_logo_fw', array(
        'default'       	=> 'normal',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_logo_fw', array(
		'description'	=> esc_html__('Font Weight','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices'		=> array('light'=>esc_html__('Light 300', 'cake'), 'normal'=>esc_html__('Normal 400', 'cake'), 'semibold'=>esc_html__('Semibold 600', 'cake'), 'bold'=>esc_html__('Bold 700', 'cake'))
        ) );
		
		$wp_customize->add_setting( 'cake_logo_fc', array(
        'default'       	=> '#a1a2a6',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_logo_fc', array(
		'description'	=> esc_html__('Font Color','cake'),
		'section' 		=> 'typography_settings_section',
		'settings'   	=> 'cake_logo_fc',
        ) ) );
		
		
		//menu typography
		$wp_customize->add_setting( 'cake_menu_typo', array(
        'default'       	=> 'Lato',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_menu_typo', array(
		'label'   		=> esc_html__('Menu Font Options', 'cake'),
		'description'	=> esc_html__('Font Family','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices' 		=> $getgooglefont,
        ) );
		
		$wp_customize->add_setting( 'cake_menu_fs', array(
        'default'       	=> '16',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_menu_fs', array(
		'description'	=> esc_html__('Font Size','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_menu_flh', array(
        'default'       	=> '22',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_menu_flh', array(
		'description'	=> esc_html__('Line Height','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_menu_fw', array(
        'default'       	=> 'normal',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_menu_fw', array(
		'description'	=> esc_html__('Font Weight','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices'		=> array('light'=>esc_html__('Light 300', 'cake'), 'normal'=>esc_html__('Normal 400', 'cake'), 'semibold'=>esc_html__('Semibold 600', 'cake'), 'bold'=>esc_html__('Bold 700', 'cake'))
        ) );
		
		$wp_customize->add_setting( 'cake_menu_fc', array(
        'default'       	=> '#a1a2a6',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_menu_fc', array(
		'description'	=> esc_html__('Font Color','cake'),
		'section' 		=> 'typography_settings_section',
		'settings'   	=> 'cake_menu_fc',
        ) ) );
		
		//heading font
		$wp_customize->add_setting( 'cake_h1_typo', array(
        'default'       	=> 'Montserrat',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h1_typo', array(
		'label'   		=> esc_html__('H1 Font Options', 'cake'),
		'description'	=> esc_html__('Font Family','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices' 		=> $getgooglefont,
        ) );
		
		$wp_customize->add_setting( 'cake_h1_fs', array(
        'default'       => '45',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h1_fs', array(
		'description'	=> esc_html__('Font Size','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h1_flh', array(
        'default'       => '50',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h1_flh', array(
		'description'	=> esc_html__('Line Height','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h1_fw', array(
        'default'       => 'normal',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h1_fw', array(
		'description'	=> esc_html__('Font Weight','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices'		=> array('light'=>esc_html__('Light 300', 'cake'), 'normal'=>esc_html__('Normal 400', 'cake'), 'semibold'=>esc_html__('Semibold 600', 'cake'), 'bold'=>esc_html__('Bold 700', 'cake'))
        ) );
		
		$wp_customize->add_setting( 'cake_h1_fc', array(
        'default'       => '#808080',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_h1_fc', array(
		'description'	=> esc_html__('Font Color','cake'),
		'section' 		=> 'typography_settings_section',
		'settings'   	=> 'cake_h1_fc',
        ) ) );
		
		
		//H2
		$wp_customize->add_setting( 'cake_h2_typo', array(
        'default'       => 'Montserrat',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h2_typo', array(
		'label'   		=> esc_html__('H2 Font Options', 'cake'),
		'description'	=> esc_html__('Font Family','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices' 		=> $getgooglefont,
        ) );
		
		$wp_customize->add_setting( 'cake_h2_fs', array(
        'default'       => '40',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h2_fs', array(
		'description'	=> esc_html__('Font Size','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h2_flh', array(
        'default'       => '45',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h2_flh', array(
		'description'	=> esc_html__('Line Height','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h2_fw', array(
        'default'       => 'normal',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h2_fw', array(
		'description'	=> esc_html__('Font Weight','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices'		=> array('light'=>esc_html__('Light 300', 'cake'), 'normal'=>esc_html__('Normal 400', 'cake'), 'semibold'=>esc_html__('Semibold 600', 'cake'), 'bold'=>esc_html__('Bold 700', 'cake'))
        ) );
		
		$wp_customize->add_setting( 'cake_h2_fc', array(
        'default'       => '#808080',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_h2_fc', array(
		'description'	=> esc_html__('Font Color','cake'),
		'section' 		=> 'typography_settings_section',
		'settings'   	=> 'cake_h2_fc',
        ) ) );
		
		//H3
		$wp_customize->add_setting( 'cake_h3_typo', array(
        'default'       => 'Montserrat',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h3_typo', array(
		'label'   		=> esc_html__('H3 Font Options', 'cake'),
		'description'	=> esc_html__('Font Family','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices' 		=> $getgooglefont,
        ) );
		
		$wp_customize->add_setting( 'cake_h3_fs', array(
        'default'       => '24',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h3_fs', array(
		'description'	=> esc_html__('Font Size','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h3_flh', array(
        'default'       => '30',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h3_flh', array(
		'description'	=> esc_html__('Line Height','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h3_fw', array(
        'default'       => 'normal',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h3_fw', array(
		'description'	=> esc_html__('Font Weight','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices'		=> array('light'=>esc_html__('Light 300', 'cake'), 'normal'=>esc_html__('Normal 400', 'cake'), 'semibold'=>esc_html__('Semibold 600', 'cake'), 'bold'=>esc_html__('Bold 700', 'cake'))
        ) );
		
		$wp_customize->add_setting( 'cake_h3_fc', array(
        'default'       => '#808080',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_h3_fc', array(
		'description'	=> esc_html__('Font Color','cake'),
		'section' 		=> 'typography_settings_section',
		'settings'   	=> 'cake_h3_fc',
        ) ) );
		
		//H4
		$wp_customize->add_setting( 'cake_h4_typo', array(
        'default'       => 'Montserrat',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h4_typo', array(
		'label'   		=> esc_html__('H4 Font Options', 'cake'),
		'description'	=> esc_html__('Font Family','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices' 		=> $getgooglefont,
        ) );
		
		$wp_customize->add_setting( 'cake_h4_fs', array(
        'default'       => '18',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h4_fs', array(
		'description'	=> esc_html__('Font Size','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h4_flh', array(
        'default'       => '25',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h4_flh', array(
		'description'	=> esc_html__('Line Height','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h4_fw', array(
        'default'       => 'normal',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h4_fw', array(
		'description'	=> esc_html__('Font Weight','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices'		=> array('light'=>esc_html__('Light 300', 'cake'), 'normal'=>esc_html__('Normal 400', 'cake'), 'semibold'=>esc_html__('Semibold 600', 'cake'), 'bold'=>esc_html__('Bold 700', 'cake'))
        ) );
		
		$wp_customize->add_setting( 'cake_h4_fc', array(
        'default'       => '#808080',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_h4_fc', array(
		'description'	=> esc_html__('Font Color','cake'),
		'section' 		=> 'typography_settings_section',
		'settings'   	=> 'cake_h4_fc',
        ) ) );
		
		//H5
		$wp_customize->add_setting( 'cake_h5_typo', array(
        'default'       => 'Montserrat',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h5_typo', array(
		'label'   		=> esc_html__('H5 Font Options', 'cake'),
		'description'	=> esc_html__('Font Family','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices' 		=> $getgooglefont,
        ) );
		
		$wp_customize->add_setting( 'cake_h5_fs', array(
        'default'       => '14',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h5_fs', array(
		'description'	=> esc_html__('Font Size','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h5_flh', array(
        'default'       => '25',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h5_flh', array(
		'description'	=> esc_html__('Line Height','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h5_fw', array(
        'default'       => 'normal',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h5_fw', array(
		'description'	=> esc_html__('Font Weight','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices'		=> array('light'=>esc_html__('Light 300', 'cake'), 'normal'=>esc_html__('Normal 400', 'cake'), 'semibold'=>esc_html__('Semibold 600', 'cake'), 'bold'=>esc_html__('Bold 700', 'cake'))
        ) );
		
		$wp_customize->add_setting( 'cake_h5_fc', array(
        'default'       => '#808080',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_h5_fc', array(
		'description'	=> esc_html__('Font Color','cake'),
		'section' 		=> 'typography_settings_section',
		'settings'   	=> 'cake_h5_fc',
        ) ) );
		
		//H6
		$wp_customize->add_setting( 'cake_h6_typo', array(
        'default'       => 'Montserrat',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h6_typo', array(
		'label'   		=> esc_html__('H6 Font Options', 'cake'),
		'description'	=> esc_html__('Font Family','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices' 		=> $getgooglefont,
        ) );
		
		$wp_customize->add_setting( 'cake_h6_fs', array(
        'default'       => '12',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h6_fs', array(
		'description'	=> esc_html__('Font Size','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h6_flh', array(
        'default'       => '23',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_h6_flh', array(
		'description'	=> esc_html__('Line Height','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'text',
        ) );
		
		$wp_customize->add_setting( 'cake_h6_fw', array(
        'default'       => 'normal',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_h6_fw', array(
		'description'	=> esc_html__('Font Weight','cake'),
		'section' 		=> 'typography_settings_section',
		'type'    		=> 'select',
		'choices'		=> array('light'=>esc_html__('Light 300', 'cake'), 'normal'=>esc_html__('Normal 400', 'cake'), 'semibold'=>esc_html__('Semibold 600', 'cake'), 'bold'=>esc_html__('Bold 700', 'cake'))
        ) );
		
		$wp_customize->add_setting( 'cake_h6_fc', array(
        'default'       => '#808080',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_h6_fc', array(
		'description'	=> esc_html__('Font Color','cake'),
		'section' 		=> 'typography_settings_section',
		'settings'   	=> 'cake_h6_fc',
        ) ) );
		
	}
	
	// STYLING SECTION
	private function cake_customizer_styling_sections( $wp_customize )
    {
		
		$wp_customize->add_section('styling_settings_section', array(
		'title'			=> esc_html__('Styling', 'cake'),
		'priority'		=> 42,
		));
		
		//layout
		$wp_customize->add_setting( 'cake_layout_type', array(
        'default'       => 'fullwidth',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );
		
		$wp_customize->add_control( 'cake_layout_type', array(
		'label'   		=> esc_html__('Layout', 'cake'),
		'description'	=> esc_html__('Select Layout Type.','cake'),
		'section' 		=> 'styling_settings_section',
		'type'    		=> 'select',
		'choices' 		=> array('fullwidth'=>esc_html__('Full Width','cake'), 'boxed'=>esc_html__('Boxed','cake')),
        ) );
		
		//predifined color
		$wp_customize->add_setting( 'cake_predifined_skin_color', array(
        'default'       => 'pink',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );
		
		$wp_customize->add_control( 'cake_predifined_skin_color', array(
		'label'   		=> esc_html__('Predifined Skin Color', 'cake'),
		'description'	=> esc_html__('Select Predifined Skin Color','cake'),
		'section' 		=> 'styling_settings_section',
		'type'    		=> 'select',
		'choices' 		=> array('pink'=>esc_html__('Pink','cake'), 'green'=>esc_html__('Green','cake'), 'blue'=>esc_html__('Blue','cake'), 'orange'=>esc_html__('Orange','cake'), 'purple'=>esc_html__('Purple','cake'), 'custom'=>esc_html__('Custom Skin','cake')),
        ) );
		
		//primary color
		$wp_customize->add_setting( 'cake_custom_skin_color', array(
        'default'       => '#f88c91',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_custom_skin_color', array(
		'label'   		=> esc_html__('Custom Skin Color', 'cake'),
		'section' 		=> 'styling_settings_section',
		'settings'   	=> 'cake_custom_skin_color',
        ) ) );
		
		//body background
		$wp_customize->add_setting( 'cake_body_background', array(
        'default'       => '#f4f3ef',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );
		
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_body_background', array(
		'label'   		=> esc_html__('Body Background', 'cake'),
		'section' 		=> 'styling_settings_section',
		'settings'   	=> 'cake_body_background',
        ) ) );
		
		$wp_customize->add_setting( 'cake_body_background_repeat', array(
        'default'       => '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );
		
		$wp_customize->add_control( 'cake_body_background_repeat', array(
		'description'	=> esc_html__('Background Repeat','cake'),
		'section' 		=> 'styling_settings_section',
		'type'    		=> 'select',
		'choices' 		=> array('no-repeat'=>esc_html__('No Repeat','cake'), 'repeat'=>esc_html__('Repeat All','cake'),  'repeat-x'=>esc_html__('Repeat Horizontally','cake'), 'repeat-y'=>esc_html__('Repeat Vertically','cake')),
        ) );
		
		$wp_customize->add_setting( 'cake_body_background_size', array(
        'default'       => 'auto',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );
		
		$wp_customize->add_control( 'cake_body_background_size', array(
		'description'	=> esc_html__('Background Size','cake'),
		'section' 		=> 'styling_settings_section',
		'type'    		=> 'select',
		'choices' 		=> array('cover'=>esc_html__('Cover','cake'), 'repeat'=>esc_html__('Repeat All','cake'),  'repeat-x'=>esc_html__('Repeat Horizontally','cake'), 'repeat-y'=>esc_html__('Repeat Vertically','cake')),
        ) );
		
		
		$wp_customize->add_setting( 'cake_body_background_attachment', array(
        'default'       => '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );
		
		$wp_customize->add_control( 'cake_body_background_attachment', array(
		'description'	=> esc_html__('Background Attachment','cake'),
		'section' 		=> 'styling_settings_section',
		'type'    		=> 'select',
		'choices' 		=> array('fixed'=>esc_html__('Fixed','cake'), 'scroll'=>esc_html__('Scroll','cake')),
        ) );
		
		$wp_customize->add_setting( 'cake_body_background_position', array(
        'default'       => '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );
		
		$wp_customize->add_control( 'cake_body_background_position', array(
		'description'	=> esc_html__('Background Position','cake'),
		'section' 		=> 'styling_settings_section',
		'type'    		=> 'select',
		'choices' 		=> array('left top'=>esc_html__('Left Top','cake'), 'left center'=>esc_html__('Left Center','cake'), 'left bottom'=>esc_html__('Left Bottom','cake'), 'center top'=>esc_html__('Center Top','cake'), 'center center'=>esc_html__('Center Center','cake'), 'center bottom'=>esc_html__('Center Bottom','cake'), 'right top'=>esc_html__('Right Top','cake'), 'right center'=>esc_html__('Right Center','cake'), 'right bottom'=>esc_html__('Right Bottom','cake')),
        ) );
		
		$wp_customize->add_setting( 'cake_body_background_image', array(
        'default'       => '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_file_url',
        ) );
		
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'cake_body_background_image',
				array(
					'description'	=> esc_html__('Background Image', 'cake'),
					'section'		=> 'styling_settings_section',
					'settings'		=> 'cake_body_background_image'
				)
			)
		);
		
		//footer color
		$wp_customize->add_setting( 'cake_footer_color', array(
        'default'       => '#ffffff',
		'sanitize_callback'	=> 'cake_sanitize_hex_color',
        ) );
		
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cake_footer_color', array(
		'label'   		=> esc_html__('Footer Color', 'cake'),
		'section' 		=> 'styling_settings_section',
		'settings'   	=> 'cake_footer_color',
        ) ) );
		
		
		
	}
	
	
	// AFTER CONTENT SECTION
	private function cake_customizer_after_content_sections( $wp_customize )
    {
		
		$wp_customize->add_section('after_content_settings_section', array(
		'title'			=> esc_html__('After Content', 'cake'),
		'priority'		=> 43,
		));
		
		 
		//after content sidebar
        $wp_customize->add_setting( 'cake_enable_after_content_sidebar', array(
            'default'   => 'false',
			'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_enable_after_content_sidebar', array(
		'label'   		=> esc_html__('After Content Sidebar', 'cake'),
		'description'	=> esc_html__('Enable/Disable after content sidebar.','cake'),
		'section' 		=> 'after_content_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ) );


	}
	
	
	// FOOTER SECTION
	private function cake_customizer_footer_sections( $wp_customize )
    {
		
		$wp_customize->add_section('footer_settings_section', array(
		'title'			=> esc_html__('Footer', 'cake'),
		'priority'		=> 44,
		));
		
		 
		//adding footer logo
		$wp_customize->add_setting( 'cake_footer_logo', array(
        'default'       => '',
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_file_url',
        ) );
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'cake_footer_logo',
				array(
					'label' 		=> esc_html__('Footer Logo', 'cake'),
					'description'	=> '',
					'section'		=> 'footer_settings_section',
					'settings'		=> 'cake_footer_logo'
				)
			)
		);
		
		//enable or disable footer widget
		$wp_customize->add_setting( 'cake_footer_widget', array(
            'default'   => 'true',
			'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );

        $wp_customize->add_control( 'cake_footer_widget', array(
		'label'   		=> esc_html__('Footer Widget', 'cake'),
		'description'	=> esc_html__('Enable/Disable footer widget.','cake'),
		'section' 		=> 'footer_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('true'=>esc_html__('On', 'cake'), 'false'=>esc_html__('Off', 'cake')),
        ) );
		
		//adding footer column widget
        $wp_customize->add_setting( 'cake_footer_widget_column', array(
            'default'        => '3column',
			'sanitize_callback'	=> 'cake_customizer_library_sanitize_choices',
        ) );
		$wp_customize->add_control( 'cake_footer_widget_column', array(
		'label'   		=> esc_html__('Footer Widget Column', 'cake'),
		'description'	=> esc_html__('Select footer column widget layout.','cake'),
		'section' 		=> 'footer_settings_section',
		'type'    		=> 'radio',
		'choices' 		=> array('3column'=>"3 Column", '4column'=>"4 Column"),
        ) );
		
		//adding footer payment logo
		$wp_customize->add_setting( 'cake_footer_payment_text', array(
        'default'       => esc_html__('Payment Method','cake'),
		'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );

        $wp_customize->add_control( 'cake_footer_payment_text', array(
		'label'	=> esc_html__('Footer Payment Text','cake'),
		'section' 		=> 'footer_settings_section',
		'type'    		=> 'text',
        ) );
        $wp_customize->add_setting( 'cake_footer_payment_logo', array(
            'default'        => '',
			'sanitize_callback'	=> 'cake_customizer_library_sanitize_text',
        ) );
		$wp_customize->add_control( 'cake_footer_payment_logo', array(
		'label'   		=> esc_html__( 'Footer Payment Logo', 'cake' ),
		'description'	=> esc_html__('Enter image URL separated by comma','cake'),
		'section' 		=> 'footer_settings_section',
		'type'    		=> 'textarea',
		'settings'   	=> 'cake_footer_payment_logo',
        ) );
		
		$wp_customize->add_setting( 'cake_footer_designby_logo', array(
            'default'        => '',
			'sanitize_callback'	=> 'cake_customizer_library_sanitize_file_url',
        ) );
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'cake_footer_designby_logo',
				array(
					'label' 		=> esc_html__('Footer Design by Logo', 'cake'),
					'description'	=> '',
					'section'		=> 'footer_settings_section',
					'settings'		=> 'cake_footer_designby_logo'
				)
			)
		);


	}
}
?>