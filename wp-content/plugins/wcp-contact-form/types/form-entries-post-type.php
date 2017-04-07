<?php

function scfp_form_entries_init() {
  $labels = array(
    'name'               => __('Entries', 'cake'), 
    'singular_name'      => __('Entry', 'cake'),
    'add_new'            => __('Add New', 'cake'),
    'add_new_item'       => __('Add New Entry', 'cake'),
    'edit_item'          => __('Edit Entry', 'cake'),
    'new_item'           => __('New Entry', 'cake'),
    'all_items'          => __('Inbox', 'cake'),
    'view_item'          => __('View Entry', 'cake'),
    'search_items'       => __('Search Entry', 'cake'),
    'not_found'          => __('No Entries Found', 'cake'),
    'not_found_in_trash' => __('No Entries Found in Trash', 'cake'),
    'parent_item_colon'  => '',   
    'menu_name'          => __('Entry', 'cake')
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => false,
    'show_ui'            => true,
    'show_in_menu'       => false,  
    'show_in_nav_menus'  => false,
    'show_in_admin_bar'   => false,
    'query_var'          => false,
    'rewrite'            => array( 'slug' =>  _x( 'form-entries', 'URL slug'),  'with_front' => false ),
    'capability_type'    => 'post',
    'capabilities' => array(
        'create_posts' => false,
    ),
    'map_meta_cap'       => true,      
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => 2,
    'exclude_from_search' => true,
    'supports'           => array( 
        'title', 
        'editor',
        'thumbnail', 
    ),       
  );

  register_post_type( 'form-entries', $args );
  
  flush_rewrite_rules();    
}
add_action( 'init', 'scfp_form_entries_init' );
