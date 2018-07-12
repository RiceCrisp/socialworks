<?php
// Register testimonial post type
function _ws_testimonial_post_type() {
  $labels = array(
    'name' => 'Testimonial',
    'singular_name' => 'Testimonial',
    'add_new_item' => 'Add New Testimonial',
    'edit_item' => 'Edit Testimonial',
    'new_item' => 'New Testimonial',
    'view_item' => 'View Testimonial',
    'search_items' => 'Search Testimonials',
    'not_found' => 'No testimonials found',
    'not_found_in_trash' => 'No testimonials found in Trash',
    'parent_item_colon' => 'Parent Testimonial:',
    'all_items' => 'All Testimonials',
    'archives' => 'Testimonial Archives',
    'insert_into_item' => 'Insert into testimonial',
    'uploaded_to_this_item' => 'Uploaded to this testimonial',
    'featured_image' => 'Featured image',
    'set_featured_image' => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image' => 'Use as featured image'
    );
  $args = array(
    'labels' => $labels,
    'description' => 'Sortable/filterable testimonials',
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_menu' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 20,
    'menu_icon' => 'dashicons-testimonial',
    'capability_type' => 'post',
    'hierarchical' => false,
    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
    'register_meta_box_cb' => null,
    'taxonomies' => array(),
    'has_archive' => false,
    'rewrite' => array('testimonials'),
    'query_var' => true
    );
  register_post_type('testimonial', $args);
}
add_action('init', '_ws_testimonial_post_type');

// Fill meta box
function _ws_testimonial_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'testimonial-nonce');
  $testimonial_video = get_post_meta(get_the_ID(), '_testimonial-video', true); ?>
  <div id="testimonial-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <label for="testimonial-video">Video Link</label>
          <input id="testimonial-video" name="testimonial-video" type="text" value="<?= $testimonial_video; ?>" />
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _ws_testimonial_meta() {
  add_meta_box('testimonial_meta', 'Testimonial', '_ws_testimonial_meta_fields', 'testimonial', 'normal', 'high');
}
add_action('admin_init', '_ws_testimonial_meta');

// Save meta values
function _ws_save_testimonial_meta($post_id) {
  if (!isset($_POST['testimonial-nonce']) || !wp_verify_nonce($_POST['testimonial-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $testimonial_video = isset($_POST['testimonial-video']) ? $_POST['testimonial-video'] : '';
  update_post_meta($post_id, '_testimonial-video', $testimonial_video);
}
add_action('save_post', '_ws_save_testimonial_meta');
