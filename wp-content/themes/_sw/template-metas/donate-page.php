<?php
// Donate meta fields
function _sw_donate_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'donate-nonce');
  $donate_text = get_post_meta(get_the_ID(), '_donate-text', true); ?>
  <div id="donate-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <label for="donate-text">Secondary Text</label>
          <textarea id="donate-text" name="donate-text" class="text-editor"><?= $donate_text; ?></textarea>
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _sw_donate_meta() {
  global $post;
  if (get_post_meta($post->ID, '_wp_page_template', true) == 'templates/donate-page.php') {
    add_meta_box('donate-meta-box', 'Donate Page Template Options', '_sw_donate_meta_fields', 'page', 'normal', 'high');
  }
}
add_action('add_meta_boxes', '_sw_donate_meta');

// Save meta values
function _sw_save_donate_meta($post_id) {
  if (!isset($_POST['donate-nonce']) || !wp_verify_nonce($_POST['donate-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $donate_text = isset($_POST['donate-text']) ? $_POST['donate-text'] : '';
  update_post_meta($post_id, '_donate-text', $donate_text);
}
add_action('save_post', '_sw_save_donate_meta');
