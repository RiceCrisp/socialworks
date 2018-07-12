<?php
// Register team taxonomy
function _ws_register_team() {
  $labels = array(
    'name' => 'Teams',
    'singular_name' => 'Team',
    'all_items' => 'All Teams',
    'edit_item' => 'Edit Team',
    'view_item' => 'View Team',
    'update_item' => 'Update Team',
    'add_new_item' => 'Add New Team',
    'new_item_name' => 'New Team Name',
    'parent_item' => 'Parent Team',
    'parent_item_colon' => 'Parent Team:',
    'search_items' => 'Search Teams',
    'popular_items' => 'Popular Teams',
    'separate_items_with_commas' => 'Separate teams with commas',
    'add_or_remove_items' => 'Add or remove teams',
    'choose_from_most_used' => 'Choose from the most used teams',
    'not_found' => 'No teams found.'
  );
  register_taxonomy(
    'team',
    array('person'),
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
      'description' => 'Taxonomy for persons',
      'hierarchical' => true,
      'query_var' => true,
      'rewrite' => true,
      'capabilities' => array(),
      'sort' => false
    )
  );
}
add_action('init', '_ws_register_team');
