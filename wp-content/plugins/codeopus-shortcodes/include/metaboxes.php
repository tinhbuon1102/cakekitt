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

add_action( 'cmb2_init', 'cdo_register_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */
function cdo_register_metabox() {
	//Testimonial
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'codeopus_testimonial_options',
		'title'         => __( 'Testimonial Options', 'codeopus'),
		'object_types'  => array( 'testimonial'), // Post type
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => __( 'Info', 'codeopus'),
		'desc'             => __( 'Enter description text an example company name and position', 'codeopus'),
		'id'               => 'cdo_testi_info',
		'type'             => 'text',
	) );
	
	
	//Team
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'codeopus_team_options',
		'title'         => __( 'Team Options', 'codeopus'),
		'object_types'  => array( 'team'), // Post type
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => __( 'Info', 'codeopus'),
		'desc'             => __( 'Enter the job position at the company', 'codeopus'),
		'id'               => 'cdo_team_info',
		'type'             => 'text',
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => __( 'Facebook', 'codeopus'),
		'desc'             => __( 'Enter facebook URL. Example : http://www.facebook.com/username', 'codeopus'),
		'id'               => 'cdo_team_fb',
		'type'             => 'text',
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => __( 'Twitter', 'codeopus'),
		'desc'             => __( 'Enter twitter URL. Example : http://www.twitter.com/username', 'codeopus'),
		'id'               => 'cdo_team_twitter',
		'type'             => 'text',
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => __( 'Google+', 'codeopus'),
		'desc'             => __( 'Enter google+ URL. Example : https://plus.google.com/u/0/108763868013266824234/posts', 'codeopus'),
		'id'               => 'cdo_team_google+',
		'type'             => 'text',
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => __( 'Other Social Profile', 'codeopus'),
		'desc'             => __( 'Put another Social Network URL. Example : &#x3C;a href=&#x22;http://linkedin.com&#x22; target=&#x22;_blank&#x22;&#x3E;&#x3C;i class=&#x22;fa-linkedin&#x22;&#x3E;&#x3C;/i&#x3E;&#x3C;/a&#x3E;', 'codeopus'),
		'id'               => 'cdo_team_social',
		'type'             => 'textarea',
	) );

	//Potfolio
	$cdo_cmb = new_cmb2_box( array(
		'id'            => 'codeopus_portfolio_options',
		'title'         => __( 'Portfolio Options', 'codeopus'),
		'object_types'  => array( 'portfolio'), // Post type
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => __( 'Lightbox URL', 'codeopus'),
		'desc'             => __( 'Enter image url or video url', 'codeopus'),
		'id'               => 'cdo_lightbox_url',
		'type'             => 'text',
	) );
	
	$cdo_cmb->add_field( array(
		'name'             => __( 'Custom URL', 'codeopus'),
		'desc'             => __( 'Enter custom url portfolio item', 'codeopus'),
		'id'               => 'cdo_custom_link',
		'type'             => 'text',
	) );
}