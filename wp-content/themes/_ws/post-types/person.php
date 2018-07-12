<?php
// Register person post type
function _ws_person_post_type() {
  $labels = array(
    'name' => 'Persons',
    'singular_name' => 'Person',
    'add_new_item' => 'Add New Person',
    'edit_item' => 'Edit Person',
    'new_item' => 'New Person',
    'view_item' => 'View Person',
    'search_items' => 'Search Persons',
    'not_found' => 'No persons found',
    'not_found_in_trash' => 'No persons found in Trash',
    'parent_item_colon' => 'Parent Person:',
    'all_items' => 'All Persons',
    'archives' => 'Person Archives',
    'insert_into_item' => 'Insert into person',
    'uploaded_to_this_item' => 'Uploaded to this person',
    'featured_image' => 'Featured image',
    'set_featured_image' => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image' => 'Use as featured image'
    );
  $args = array(
    'labels' => $labels,
    'description' => 'Sortable/filterable people',
    'public' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_menu' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 20,
    'menu_icon' => 'dashicons-businessman',
    'capability_type' => 'post',
    'hierarchical' => false,
    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
    'register_meta_box_cb' => null,
    'taxonomies' => array(),
    'has_archive' => false,
    'rewrite' => array('slug'=>'persons', 'with_front'=>false),
    'query_var' => true
    );
  register_post_type('person', $args);
}
add_action('init', '_ws_person_post_type');

// Fill meta box
function _ws_person_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'person-nonce');
  $person_img = get_post_meta(get_the_ID(), '_person-img', true);
  $person_role = get_post_meta(get_the_ID(), '_person-role', true); ?>
  <div id="team-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <label for="person-img">Profile Picture</label>
          <div class="row">
            <button class="button media-selector" target="#person-img" size="standard">Select Image</button>
            <input id="person-img" class="flex-1" name="person-img" type="text" value="<?= $person_img; ?>" />
          </div>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="person-role">Position</label>
          <input id="person-role" name="person-role" type="text" value="<?= $person_role; ?>" />
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _ws_person_meta() {
  add_meta_box('person_meta', 'Person', '_ws_person_meta_fields', 'person', 'normal', 'high');
}
add_action('admin_init', '_ws_person_meta');

// Save meta values
function _ws_save_person_meta($post_id) {
  if (!isset($_POST['person-nonce']) || !wp_verify_nonce($_POST['person-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $person_img = isset($_POST['person-img']) ? $_POST['person-img'] : '';
  update_post_meta($post_id, '_person-img', $person_img);

  $person_role = isset($_POST['person-role']) ? $_POST['person-role'] : '';
  update_post_meta($post_id, '_person-role', $person_role);
}
add_action('save_post', '_ws_save_person_meta');
