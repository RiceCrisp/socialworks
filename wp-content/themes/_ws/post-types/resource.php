<?php
// Register resource post type
function _ws_resource_post_type() {
  $labels = array(
    'name' => 'Resources',
    'singular_name' => 'Resource',
    'add_new_item' => 'Add New Resource',
    'edit_item' => 'Edit Resource',
    'new_item' => 'New Resource',
    'view_item' => 'View Resource',
    'search_items' => 'Search Resources',
    'not_found' => 'No resources found',
    'not_found_in_trash' => 'No resources found in Trash',
    'parent_item_colon' => 'Parent Resource:',
    'all_items' => 'All Resources',
    'archives' => 'Resource Archives',
    'insert_into_item' => 'Insert into resource',
    'uploaded_to_this_item' => 'Uploaded to this resource',
    'featured_image' => 'Featured image',
    'set_featured_image' => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image' => 'Use as featured image'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Sortable/filterable resources',
    'public' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_menu' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 20,
    'menu_icon' => 'dashicons-analytics',
    'capability_type' => 'post',
    'hierarchical' => false,
    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
    'register_meta_box_cb' => null,
    'taxonomies' => array(),
    'has_archive' => false,
    'rewrite' => array('slug'=>'resources', 'with_front'=>false),
    'query_var' => true
  );
  register_post_type('resource', $args);
}
add_action('init', '_ws_resource_post_type');
