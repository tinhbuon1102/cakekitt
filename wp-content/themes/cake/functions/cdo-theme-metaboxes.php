<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */
function cake_get_sidebar() {
	global $wp_registered_sidebars;

	$sidebar_options = array();
	foreach ($wp_registered_sidebars as $sidebar_id => $sidebar) {
	$sidebar_options[ $sidebar_id ] = $sidebar['name'];
	}

	return $sidebar_options;

}

function cake_get_sidebar2() {
	global $wp_registered_sidebars;

	$sidebar_options = array();
	foreach ($wp_registered_sidebars as $sidebar_id => $sidebar) {
	$sidebar_options[ $sidebar_id ] = $sidebar['name'];
	}

	$sidebar_options = array_merge(array('default' => 'Default'),$sidebar_options);

	return $sidebar_options;

}

function cake_get_term_options( $taxonomy = 'category', $args = array() ) {

    $args['taxonomy'] = $taxonomy;
    // $defaults = array( 'taxonomy' => 'category' );
    $args = wp_parse_args( $args, array( 'taxonomy' => 'category' ) );

    $taxonomy = $args['taxonomy'];

    $terms = (array) get_terms( $taxonomy, $args );

    // Initate an empty array
    $term_options = array();
    if ( ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $term_options[ $term->slug ] = $term->name;
        }
    }

    return $term_options;
}

add_action( 'cmb2_init', 'cake_register_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */
function cake_register_metabox() {

	// Start with an underscore to hide fields from custom fields list
	
	//POST FORMAT
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_post_format_quote_options',
		'title'         => esc_html__( 'Post Formats', 'cake'),
		'object_types'  => array( 'post'), // Post type
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => esc_html__( 'Quote Text', 'cake'),
		'desc'             => esc_html__( 'Enter quote text', 'cake'),
		'id'               => 'cake_quote_text',
		'type'             => 'textarea_small',
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => esc_html__( 'Info Text', 'cake'),
		'desc'             => esc_html__( 'Enter info text', 'cake'),
		'id'               => 'cake_quote_info_text',
		'type'             => 'text',
	) );
	
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_post_format_link_options',
		'title'         => esc_html__( 'Post Formats', 'cake'),
		'object_types'  => array( 'post'), // Post type
	) );
	
	$cdo_cmb->add_field( array(
    'name'    => esc_html__('Link Format', 'cake'),
    'desc'    => esc_html__('Enter URL (Web Address).', 'cake'),
    'id'      => 'cake_link_embed',
    'type'    => 'text',
	) );
	
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_post_format_video_options',
		'title'         => esc_html__( 'Post Formats', 'cake'),
		'object_types'  => array( 'post'), // Post type
	) );
	
	$cdo_cmb->add_field( array(
    'name'    => esc_html__('Video Format', 'cake'),
    'desc'    => esc_html__('Enter video url, youtube or vimeo.', 'cake'),
    'id'      => 'cake_video_embed',
    'type'    => 'text',
	) );
	
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_post_format_audio_options',
		'title'         => esc_html__( 'Post Formats', 'cake'),
		'object_types'  => array( 'post'), // Post type
	) );
	
	$cdo_cmb->add_field( array(
    'name'    => esc_html__('Audio Format', 'cake'),
    'desc'    => esc_html__('Upload an audio or enter an URL.', 'cake'),
    'id'      => 'cake_audio_embed',
    'type'    => 'file',
    // Optional:
    'options' => array(
        'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
    ),
	) );
	
	//PRODUCT METABOX
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_product_options',
		'title'         => esc_html__( 'Product Options', 'cake'),
		'object_types'  => array( 'product'), // Post type
		'context'       => 'side',
		'priority'   => 'default',
	) );
	
	$cdo_cmb->add_field( array(
		'id'               => 'cake_product_style',
		'name'    => esc_html__('Accents Color', 'cake'),
		'desc'    => esc_html__('Accent color for product item if you use cdo_product_category shortcode', 'cake'),
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'blue' => esc_html__( 'Blue', 'cake'),
			'green'   => esc_html__( 'Green', 'cake'),
			'orange'   => esc_html__( 'Orange', 'cake'),
			'pink'   => esc_html__( 'Pink', 'cake'),
			'purple'   => esc_html__( 'Purple', 'cake'),
			'brown'   => esc_html__( 'Brown', 'cake'),
		),
	) );
	
	$cdo_cmb->add_field( array(
    'name'    => esc_html__('Subtitle', 'cake'),
    'desc'    => esc_html__('Will be displayed if you use cdo_product_category shortcodee', 'cake'),
    'id'      => 'cake_product_subtitle',
    'type'    => 'textarea',
	) );
	
	$cdo_cmb->add_field( array(
		'name' => esc_html__('Product Thumb', 'cake'),
		'desc' => esc_html__( 'Product thumb image 150 x 203. Will be displayed if you use cdo_product_category shortcodee', 'cake'),
		'id'   => 'cake_product_thumb',
		'type' => 'file',
	) );
	

	//SLIDER METABOX
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_slider_options',
		'title'         => esc_html__( 'Slider Options', 'cake'),
		'object_types'  => array( 'page', ), // Post type
	) );

	
	$cdo_cmb->add_field( array(
		'name'             => esc_html__( 'Slider', 'cake'),
		'desc'             => esc_html__( 'Select slider. If choose No Slider, the slider not display in page', 'cake'),
		'id'               => 'cake_slider_choose',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'no-slider' => esc_html__( 'No Slider', 'cake'),
			'slick-slider'   => esc_html__( 'Slick Slider', 'cake'),
			'slice-slider'   => esc_html__( 'Slice Slider', 'cake'),
			'parallax-slider'   => esc_html__( 'Parallax Slider', 'cake'),
			'cycle-slider'   => esc_html__( 'Cycle Slider', 'cake'),
		),
	) );
	
	
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_slick_slider_options',
		'title'         => esc_html__( 'Slick Slider', 'cake'),
		'object_types'  => array( 'page', ), // Post type
	) );
	
	$cdo_cmb->add_field( array(
    'name'    => esc_html__('Title', 'cake'),
    'id'      => 'cake_slick_slidertitle',
    'type'    => 'text',
	) );
	
	$cdo_cmb->add_field( array(
    'name'    => esc_html__('Subtitle', 'cake'),
    'id'      => 'cake_slick_slidersubtitle',
    'type'    => 'text',
	) );
	
	$cdo_cmb->add_field( array(
		'name' => esc_html__( 'Slider Post', 'cake'),
		'id'   => 'cake_slick_slider_post',
		'type' => 'select',
		'options'     => array(
			'sliderpost'   => esc_html__( 'Slider Post', 'cake'),
			'woocommercepost'    => esc_html__( 'Woocommerce Post', 'cake'),
		),
	) );
	
	
	$cdo_cmb->add_field( array(
		'name' => esc_html__( 'Woocommerce Post', 'cake'),
		'id'   => 'cake_slick_slider_woo_post',
		'type' => 'select',
		'options'     => array(
			'featured'   => esc_html__( 'Featured', 'cake'),
			'latestproduct'    => esc_html__( 'Latest Product', 'cake'),
			'top_rated'    => esc_html__( 'Top Rated', 'cake'),
		),
	) );
	
	$group_field_id = $cdo_cmb->add_field( array(
		'id'          => 'cake_slick_slider_post_item',
		'type'        => 'group',
		'description' => '',
		'options'     => array(
			'group_title'   => esc_html__( 'Slider {#}', 'cake'),
			'add_button'    => esc_html__( 'Add Slide', 'cake'),
			'remove_button' => esc_html__( 'Remove Slide', 'cake'),
			'sortable'      => true,
		),
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Label text', 'cake'),
		'id'   => 'cake_slick_slidertext',
		'type' => 'text',
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__('Image', 'cake'),
		'id'   => 'cake_slick_slider_image',
		'type' => 'file',
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link URL', 'cake'),
		'id'   => 'cake_slick_sliderlink',
		'type' => 'text',
	) );
	
	
	
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_parallax_slider_options',
		'title'         => esc_html__( 'Parallax Slider', 'cake'),
		'object_types'  => array( 'page', ), // Post type
	) );
	
	$group_field_id = $cdo_cmb->add_field( array(
		'id'          => 'cake_parallax_slider_item',
		'type'        => 'group',
		'description' => '',
		'options'     => array(
			'group_title'   => esc_html__( 'Slider {#}', 'cake'),
			'add_button'    => esc_html__( 'Add Slide', 'cake'),
			'remove_button' => esc_html__( 'Remove Slide', 'cake'),
			'sortable'      => true,
		),
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Title', 'cake'),
		'id'   => 'cake_sititle',
		'type' => 'text',
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Sub Title', 'cake'),
		'id'   => 'cake_sisubtitle',
		'type' => 'text',
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__('Image', 'cake'),
		'id'   => 'cake_siimage',
		'type' => 'file',
	) );
	

	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Text Description', 'cake'),
		'id'   => 'cake_sitextdescription',
		'type' => 'textarea',
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Button Label', 'cake'),
		'id'   => 'cake_sibuttonlabel',
		'type' => 'text',
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Button Link', 'cake'),
		'id'   => 'cake_sibuttonlink',
		'type' => 'text',
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Button Color', 'cake'),
		'id'   => 'cake_sibuttoncolor',
		'type' => 'select',
		'options'     => array(
			'purple'   => esc_html__( 'Purple', 'cake'),
			'green'    => esc_html__( 'Green', 'cake'),
			'blue' => esc_html__( 'Blue', 'cake'),
			'pink' => esc_html__( 'Pink', 'cake'),
			'orange' => esc_html__( 'Orange', 'cake'),
		),
	) );
	
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_slice_slider_options',
		'title'         => esc_html__( 'Slice Slider', 'cake'),
		'object_types'  => array( 'page', ), // Post type
	) );
	
	$cdo_cmb->add_field( array(
    'name'    => esc_html__('Title', 'cake'),
    'id'      => 'cake_slice_slidertitle',
    'type'    => 'text',
	) );
	
	$cdo_cmb->add_field( array(
    'name'    => esc_html__('Subtitle', 'cake'),
    'id'      => 'cake_slice_slidersubtitle',
    'type'    => 'text',
	) );
	
	$cdo_cmb->add_field( array(
		'name' => esc_html__('Full Image', 'cake'),
		'desc' => esc_html__( 'Show in mobile device only', 'cake'),
		'id'   => 'cake_slice_fullimage',
		'type' => 'file',
	) );
	
	$cdo_cmb->add_field( array(
		'name' => esc_html__( 'Slice Image', 'cake'),
		'id'   => 'cake_slice_sliderimg',
		'type' => 'file_list',
		'sortable'      => true,
		'repeatable'     => true,
		'repeatable_max' => 10,
		'options' => array( 
			'remove_row_text' => esc_html__( 'Remove Image', 'cake'),
			'add_upload_files_text' => esc_html__( 'Upload Image', 'cake'),
			'add_row_text' => esc_html__( 'Add More Image', 'cake'),
		),
	) );
	
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_cycle_slider_options',
		'title'         => esc_html__( 'Cycle Slider', 'cake'),
		'object_types'  => array( 'page', ), // Post type
	) );
	
	$group_field_id = $cdo_cmb->add_field( array(
		'id'          => 'cake_cycle_slider_item',
		'type'        => 'group',
		'description' => '',
		'options'     => array(
			'group_title'   => esc_html__( 'Slider {#}', 'cake'),
			'add_button'    => esc_html__( 'Add Slide', 'cake'),
			'remove_button' => esc_html__( 'Remove Slide', 'cake'),
			'sortable'      => true,
		),
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Title', 'cake'),
		'id'   => 'cake_cycle_slidertitle',
		'type' => 'text',
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Sub Title', 'cake'),
		'id'   => 'cake_cycle_slidersubtitle',
		'type' => 'text',
	) );
	
	$cdo_cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__('Image', 'cake'),
		'id'   => 'cake_cycle_sliderimg',
		'type' => 'file',
	) );
	

	
	//PAGES METABOX
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_page_options',
		'title'         => esc_html__( 'Page Options', 'cake'),
		'object_types'  => array( 'page', ), // Post type
	) );
	
	
	$cdo_cmb->add_field( array(
		'name'             => esc_html__( 'Page Header Image', 'cake'),
		'desc'             => esc_html__( 'Upload page header image as background.', 'cake'),
		'id'               => 'cake_page_header_img',
		'type' 			   => 'file',
	) );
	//added
	$cdo_cmb->add_field( array(
		'name'             => esc_html__( 'Page Header Text', 'cake'),
		'desc'             => esc_html__( 'text inside background.', 'cake'),
		'id'               => 'cake_page_header_text',
		'type' 			   => 'textarea',
	) );
	$cdo_cmb->add_field( array(
		'name'             => esc_html__( 'Image Options', 'cake'),
		'desc'             => '',
		'id'               => 'cake_page_background_position',
		'type'             => 'radio_inline',
		'options'          => array(
			'left' => esc_html__( 'Left', 'cake'),
			'center'   => esc_html__( 'Center', 'cake'),
			'right'     => esc_html__( 'Right', 'cake'),
		),
		'default'             => 'center',
	) );
	$cdo_cmb->add_field( array(
		'name'             => esc_html__( 'Text position', 'cake'),
		'desc'             => '',
		'id'               => 'cake_page_bgtext_position',
		'type'             => 'radio_inline',
		'options'          => array(
			'leftfloat' => esc_html__( 'Left', 'cake'),
			'nofloat'   => esc_html__( 'Center', 'cake'),
			'rightfloat'     => esc_html__( 'Right', 'cake'),
		),
		'default'             => 'nofloat',
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => esc_html__( 'Layout Options', 'cake'),
		'desc'             => '',
		'id'               => 'cake_page_layout',
		'type'             => 'radio_inline',
		'options'          => array(
			'sidebar-left' => esc_html__( 'Left Sidebar', 'cake'),
			'no-sidebar'   => esc_html__( 'No Sidebar', 'cake'),
			'sidebar-right'     => esc_html__( 'Right Sidebar', 'cake'),
		),
		'default'             => 'no-sidebar',
	) );
		
	$cdo_cmb->add_field( array(
		'name'             => esc_html__( 'Sidebar', 'cake'),
		'desc'             => esc_html__( 'Select sidebar to show in this page.', 'cake'),
		'id'               => 'cake_page_sidebar_widget',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => cake_get_sidebar(),
	) );
	
	//BLOG OPTIONS
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'cake_blog_options',
		'title'         => esc_html__( 'Blog Options', 'cake'),
		'object_types'  => array( 'page', ), // Post type
	) );
	
	$cdo_cmb->add_field( array(
		'name'     => esc_html__( 'Category', 'cake'),
		'desc'     => esc_html__( 'Select post category to include in blog page', 'cake'),
		'id'       => 'cake_blog_taxonomy',
		'type'     => 'multicheck',
		'options' => cake_get_term_options(),
	) );
	
	$cdo_cmb->add_field( array(
    'name'    => esc_html__('Showpost', 'cake'),
    'desc'    => esc_html__('Number of post. Set to -1 for show all post.', 'cake'),
    'id'      => 'cake_blog_showpost',
    'type'    => 'text',
	'default' => '3',
	) );
}