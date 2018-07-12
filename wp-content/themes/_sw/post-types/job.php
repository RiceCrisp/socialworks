<?php
// Register job post type
function _sw_job_post_type() {
  $labels = array(
    'name' => 'Jobs',
    'singular_name' => 'Job',
    'add_new_item' => 'Add New Job',
    'edit_item' => 'Edit Job',
    'new_item' => 'New Job',
    'view_item' => 'View Job',
    'search_items' => 'Search Jobs',
    'not_found' => 'No jobs found',
    'not_found_in_trash' => 'No jobs found in Trash',
    'parent_item_colon' => 'Parent Job:',
    'all_items' => 'All Jobs',
    'archives' => 'Job Archives',
    'insert_into_item' => 'Insert into job',
    'uploaded_to_this_item' => 'Uploaded to this job',
    'featured_image' => 'Featured image',
    'set_featured_image' => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image' => 'Use as featured image'
    );
  $args = array(
    'labels' => $labels,
    'description' => 'Sortable/filterable jobs',
    'public' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_menu' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 20,
    'menu_icon' => 'dashicons-welcome-learn-more',
    'capability_type' => 'post',
    'hierarchical' => false,
    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
    'register_meta_box_cb' => null,
    'taxonomies' => array(),
    'has_archive' => false,
    'rewrite' => array('slug'=>'jobs', 'with_front'=>false),
    'query_var' => true
    );
  register_post_type('job', $args);
}
add_action('init', '_sw_job_post_type');
