<?php
// Register resource type taxonomy
function _ws_register_resource_type() {
  $labels = array(
    'name' => 'Resource Types',
    'singular_name' => 'Resource Type',
    'all_items' => 'All Resource Types',
    'edit_item' => 'Edit Resource Type',
    'view_item' => 'View Resource Type',
    'update_item' => 'Update Resource Type',
    'add_new_item' => 'Add New Resource Type',
    'new_item_name' => 'New Resource Type Name',
    'parent_item' => 'Parent Resource Type',
    'parent_item_colon' => 'Parent Resource Type:',
    'search_items' => 'Search Resource Types',
    'popular_items' => 'Popular Resource Types',
    'separate_items_with_commas' => 'Separate resource types with commas',
    'add_or_remove_items' => 'Add or remove resource types',
    'choose_from_most_used' => 'Choose from the most used resource types',
    'not_found' => 'No resource types found.'
  );
  register_taxonomy(
    'resource_type',
    array('resource'),
    array(
      'labels' => $labels,
      'public' => true,
      'show_ui' => true,
      'show_in_menus' => true,
      'show_in_nav_menus' => true,
      'show_tagcloud' => false,
      'show_in_quick_edit' => true,
      'meta_box_cb' => null,
      'show_admin_column' => true,
      'description' => 'Taxonomy for resources',
      'hierarchical' => true,
      'query_var' => true,
      'rewrite' => true,
      'capabilities' => array(),
      'sort' => false
    )
  );
}
add_action('init', '_ws_register_resource_type');
