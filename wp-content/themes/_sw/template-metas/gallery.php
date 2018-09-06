<?php
// Gallery meta fields
function _sw_gallery_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'gallery-nonce');
  $gallery_type = get_post_meta(get_the_ID(), '_gallery-type', true);
  $gallery_tax = get_post_meta(get_the_ID(), '_gallery-tax', true); ?>
  <div id="gallery-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <label for="gallery-type">Post Type</label>
          <select id="gallery-type" name="gallery-type">
            <?php
            $types = get_post_types(array('public'=>true));
            foreach ($types as $type) {
              echo '<option value="' . $type . '"' . ($gallery_type==$type ? ' selected' : '') . '>' . get_post_type_object($type)->labels->singular_name . '</option>';
            } ?>
          </select>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="gallery-tax">Filter</label>
          <select id="gallery-tax" name="gallery-tax">
            <option value="">None</option>
            <?php
            $taxes = get_taxonomies(array('public'=>true), 'objects');
            foreach ($taxes as $tax) {
              echo '<option value="' . $tax->name . '"' . ($gallery_tax==$tax->name ? ' selected' : '') . '>' . $tax->labels->singular_name . '</option>';
            } ?>
            <option value="year" <?= $gallery_tax=='year' ? 'selected' : ''; ?>>Year</option>
            <option value="event" <?= $gallery_tax=='event' ? 'selected' : ''; ?>>Event</option>
          </select>
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _sw_gallery_meta() {
  global $post;
  if (get_post_meta($post->ID, '_wp_page_template', true) == 'templates/gallery-page.php') {
    add_meta_box('gallery-meta-box', 'Gallery Template Options', '_sw_gallery_meta_fields', 'page', 'normal', 'high');
  }
}
add_action('add_meta_boxes', '_sw_gallery_meta');

// Save meta values
function _sw_save_gallery_meta($post_id) {
  if (!isset($_POST['gallery-nonce']) || !wp_verify_nonce($_POST['gallery-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $gallery_type = isset($_POST['gallery-type']) ? $_POST['gallery-type'] : '';
  update_post_meta($post_id, '_gallery-type', $gallery_type);

  $gallery_tax = isset($_POST['gallery-tax']) ? $_POST['gallery-tax'] : '';
  update_post_meta($post_id, '_gallery-tax', $gallery_tax);
}
add_action('save_post', '_sw_save_gallery_meta');
