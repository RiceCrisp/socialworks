<?php
// Register client post type
function _sw_client_post_type() {
  $labels = array(
    'name' => 'Clients',
    'singular_name' => 'Client',
    'add_new_item' => 'Add New Client',
    'edit_item' => 'Edit Client',
    'new_item' => 'New Client',
    'view_item' => 'View Client',
    'search_items' => 'Search Clients',
    'not_found' => 'No clients found',
    'not_found_in_trash' => 'No clients found in Trash',
    'parent_item_colon' => 'Parent Client:',
    'all_items' => 'All Clients',
    'archives' => 'Client Archives',
    'insert_into_item' => 'Insert into client',
    'uploaded_to_this_item' => 'Uploaded to this client',
    'featured_image' => 'Featured image',
    'set_featured_image' => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image' => 'Use as featured image'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Sortable/filterable clients',
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_menu' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 20,
    'menu_icon' => 'dashicons-groups',
    'capability_type' => 'post',
    'hierarchical' => false,
    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
    'register_meta_box_cb' => null,
    'taxonomies' => array(),
    'has_archive' => false,
    'rewrite' => array('slug'=>'clients'),
    'query_var' => true
  );
  register_post_type('client', $args);
}
add_action('init', '_sw_client_post_type');
