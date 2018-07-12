<?php
// Register news post type
function _sw_news_post_type() {
  $labels = array(
    'name' => 'News',
    'singular_name' => 'News',
    'add_new_item' => 'Add New News',
    'edit_item' => 'Edit News',
    'new_item' => 'New News',
    'view_item' => 'View News',
    'search_items' => 'Search News',
    'not_found' => 'No news found',
    'not_found_in_trash' => 'No news found in Trash',
    'parent_item_colon' => 'Parent News:',
    'all_items' => 'All News',
    'archives' => 'News Archives',
    'insert_into_item' => 'Insert into news',
    'uploaded_to_this_item' => 'Uploaded to this news',
    'featured_image' => 'Featured image',
    'set_featured_image' => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image' => 'Use as featured image'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Sortable/filterable news',
    'public' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_menu' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 20,
    'menu_icon' => 'dashicons-format-aside',
    'capability_type' => 'post',
    'hierarchical' => false,
    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
    'register_meta_box_cb' => null,
    'taxonomies' => array(),
    'has_archive' => false,
    'rewrite' => array('slug'=>'news', 'with_front'=>false),
    'query_var' => true
  );
  register_post_type('news', $args);
}
add_action('init', '_sw_news_post_type');
