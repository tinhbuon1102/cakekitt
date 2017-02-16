<?php
/* Register Custom Post Type for Portfolio */
add_action('init', 'codeopus_portfolio_post_type_init');
function codeopus_portfolio_post_type_init() {
  $labels = array(
    'name' => esc_html__('Portfolio', 'codeopus'),
    'singular_name' => esc_html__('Portfolio', 'codeopus'),
	'all_items' => esc_html__('All Portfolio', 'portfolio','codeopus'),
    'add_new' => esc_html__('Add New', 'codeopus'),
    'add_new_item' => esc_html__('Add New portfolio','codeopus'),
    'edit_item' => esc_html__('Edit portfolio','codeopus'),
    'new_item' => esc_html__('New portfolio','codeopus'),
    'view_item' => esc_html__('View portfolio','codeopus'),
    'search_items' => esc_html__('Search portfolio','codeopus'),
    'not_found' =>  esc_html__('No portfolio found','codeopus'),
    'not_found_in_trash' => esc_html__('No portfolio found in Trash','codeopus'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'rewrite' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'show_in_nav_menus' => false,
    'menu_position' => 900,
    'menu_icon' => 'dashicons-portfolio',
    'rewrite' => array(
      'slug' => 'portfolio_item',
      'with_front' => FALSE,
    ),    
    'supports' => array(
      'title',
      'editor',
      'thumbnail',
      'excerpt'
    )
  );

  register_post_type('portfolio',$args);
  
  register_taxonomy("portfolio_category", 
			    	array("portfolio"), 
			    	array( "hierarchical" => true, 
			    			"label" => esc_html__("Portfolio Categories",'codeopus'), 
			    			"singular_label" => esc_html__("Portfolio Categories",'codeopus'), 
			    			"rewrite" => true,
			    			"query_var" => true,
                "rewrite" => array(
                  "slug" => "portfolio_category"
                )
			    		)); 
}


/* Register Custom Post Type for Testimonials */
add_action('init', 'codeopus_testimonial_post_type_init');
function codeopus_testimonial_post_type_init() {
  $labels = array(
    'name' => esc_html__('Testimonial', 'codeopus'),
    'singular_name' => esc_html__('Testimonial', 'codeopus'),
	'all_items' => esc_html__('All Testimonial', 'testimonial','codeopus'),
    'add_new' => esc_html__('Add New', 'codeopus'),
    'add_new_item' => esc_html__('Add New Testimonial','codeopus'),
    'edit_item' => esc_html__('Edit Testimonial','codeopus'),
    'new_item' => esc_html__('New Testimonial','codeopus'),
    'view_item' => esc_html__('View Testimonial','codeopus'),
    'search_items' => esc_html__('Search Testimonial','codeopus'),
    'not_found' =>  esc_html__('No Testimonial found','codeopus'),
    'not_found_in_trash' => esc_html__('No Testimonial found in Trash','codeopus'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'rewrite' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'show_in_nav_menus' => false,
    'menu_position' => 1000,
    'menu_icon' => 'dashicons-format-status',
    'rewrite' => array(
      'slug' => 'testimonial_detail',
      'with_front' => FALSE,
    ),
    'supports' => array(
      'title',
      'thumbnail',
      'editor'
    )
  );
  register_post_type('testimonial',$args);
  
  register_taxonomy("testimonial_category", 
			array("testimonial"), 
			array( "hierarchical" => true, 
					"label" => esc_html__("Testimonial Categories",'codeopus'), 
					"singular_label" => esc_html__("Testimonial Categories",'codeopus'), 
					"rewrite" => true,
					"query_var" => true,
		"rewrite" => array(
		  "slug" => "testimonial_category"
		)
));  
}

/* Register Custom Post Type for staff */
add_action('init', 'codeopus_team_post_type_init');
function codeopus_team_post_type_init() {
  $labels = array(
    'name' => esc_html__('Team', 'codeopus'),
    'singular_name' => esc_html__('Team', 'codeopus'),
	'all_items' => esc_html__('All Team', 'team','codeopus'),
    'add_new' => esc_html__('Add New', 'codeopus'),
    'add_new_item' => esc_html__('Add New Staff','codeopus'),
    'edit_item' => esc_html__('Edit Team','codeopus'),
    'new_item' => esc_html__('New Team','codeopus'),
    'view_item' => esc_html__('View Team','codeopus'),
    'search_items' => esc_html__('Search Team','codeopus'),
    'not_found' =>  esc_html__('No Team Found','codeopus'),
    'not_found_in_trash' => esc_html__('No Team found in Trash','codeopus'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'rewrite' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'show_in_nav_menus' => false,
    'menu_position' => 1000,
    'menu_icon' => 'dashicons-groups',
    'rewrite' => array(
      'slug' => 'team_detail',
      'with_front' => FALSE,
    ),
    'supports' => array(
      'title',
      'thumbnail',
      'editor',
      'excerpt',
	  'tags'
    )
  );
  register_post_type('team',$args);
  
  register_taxonomy("team_category", 
			array("team"), 
			array( "hierarchical" => true, 
					"label" => esc_html__("Team Categories",'codeopus'), 
					"singular_label" => esc_html__("Team Categories",'codeopus'), 
					"rewrite" => true,
					"query_var" => true,
		"rewrite" => array(
		  "slug" => "team_category"
		)
)); 
  
}

/* ==============================================================================================
   Create Custom Column in Post Type
   ==============================================================================================*/
function codeopus_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
        return $post_thumbnail_img[0];
    }
}

function codeopus_columns_head($defaults) {
    $defaults['featured_image'] = esc_html__('Featured Image','codeopus');
    return $defaults;
}
 
// Show the featured image
function codeopus_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
        $post_featured_image = codeopus_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img src="' . $post_featured_image . '" width="100" height="100" />';
        }
    }
}

add_filter('manage_posts_columns', 'codeopus_columns_head');
add_action('manage_posts_custom_column', 'codeopus_columns_content', 10, 2);

//Portfolio category column
add_filter('manage_edit-codeopus_portfolio_columns', 'codeopus_portfolio_columns');
function codeopus_portfolio_columns($columns) {
    $columns['category'] = __('Portfolio Category','codeopus');
    return $columns;
}

add_action('manage_posts_custom_column',  'codeopus_portfolio_show_columns');
function codeopus_portfolio_show_columns($name) {
    global $post;
    switch ($name) {
        case 'category':
            $cats = get_the_term_list( $post->ID, 'portfolio_category', '', ', ', '' );
            echo $cats;
    }
}

//Testimonial category column
add_filter('manage_edit-codeopus_testimonial_columns', 'codeopus_testimonial_columns');
function codeopus_testimonial_columns($columns) {
    $columns['category'] = __('Testimonial Category','codeopus');
    return $columns;
}

add_action('manage_posts_custom_column',  'codeopus_testimonial_show_columns');
function codeopus_testimonial_show_columns($name) {
    global $post;
    switch ($name) {
        case 'category':
            $cats = get_the_term_list( $post->ID, 'testimonial_category', '', ', ', '' );
            echo $cats;
    }
}

//Team category column
add_filter('manage_edit-codeopus_team_columns', 'codeopus_team_columns');
function codeopus_team_columns($columns) {
    $columns['category'] = esc_html__('Team Category','codeopus');
    return $columns;
}

add_action('manage_posts_custom_column',  'codeopus_team_show_columns');
function codeopus_team_show_columns($name) {
    global $post;
    switch ($name) {
        case 'category':
            $cats = get_the_term_list( $post->ID, 'team_category', '', ', ', '' );
            echo $cats;
    }
}

?>