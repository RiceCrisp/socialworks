<?php
// Front page meta fields
function _ws_fp_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'fp-nonce');
  $fp_cta_1 = get_post_meta(get_the_ID(), '_fp-cta-1', true);
  $fp_cta_1_bg = get_post_meta(get_the_ID(), '_fp-cta-1-bg', true);
  $fp_cta_2 = get_post_meta(get_the_ID(), '_fp-cta-2', true);
  $fp_cta_2_btn_text = get_post_meta(get_the_ID(), '_fp-cta-2-btn-text', true);
  $fp_cta_2_btn_url = get_post_meta(get_the_ID(), '_fp-cta-2-btn-url', true); ?>
  <div id="fp-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>CTA 1</legend>
            <ul>
              <li>
                <label for="fp-cta-1">Text</label>
                <textarea id="fp-cta-1" name="fp-cta-1" class="text-editor"><?= $fp_cta_1; ?></textarea>
              </li>
              <li>
                <label for="fp-cta-1-bg">Background Image</label>
                <div class="row">
                  <button class="button media-selector" target="#fp-cta-1-bg">Select Image</button>
                  <input id="fp-cta-1-bg" name="fp-cta-1-bg" class="flex-1" type="text" value="<?= $fp_cta_1_bg; ?>">
                </div>
              </li>
            </ul>
          </fieldset>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>CTA 2</legend>
            <ul>
              <li>
                <label for="fp-cta-2">Text</label>
                <textarea id="fp-cta-2" name="fp-cta-2"><?= $fp_cta_2; ?></textarea>
              </li>
              <li class="row">
                <div class="col-sm-6">
                  <label for="fp-cta-2-btn-text">Button Text</label>
                  <input id="fp-cta-2-btn-text" name="fp-cta-2-btn-text" type="text" value="<?= $fp_cta_2_btn_text; ?>">
                </div>
                <div class="col-sm-6">
                  <label for="fp-cta-2-btn-url">Button URL</label>
                  <input id="fp-cta-2-btn-url" name="fp-cta-2-btn-url" type="text" value="<?= $fp_cta_2_btn_url; ?>">
                </div>
              </li>
            </ul>
          </fieldset>
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _ws_fp_meta() {
  global $post;
  if (get_post_meta($post->ID, '_wp_page_template', true) == 'templates/front-page.php') {
    add_meta_box('fp-meta-box', 'Front Page Template Options', '_ws_fp_meta_fields', 'page', 'normal', 'high');
  }
}
add_action('add_meta_boxes', '_ws_fp_meta');

// Save meta values
function _ws_save_fp_meta($post_id) {
  if (!isset($_POST['fp-nonce']) || !wp_verify_nonce($_POST['fp-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $fp_cta_1 = isset($_POST['fp-cta-1']) ? $_POST['fp-cta-1'] : '';
  update_post_meta($post_id, '_fp-cta-1', $fp_cta_1);

  $fp_cta_1_bg = isset($_POST['fp-cta-1-bg']) ? $_POST['fp-cta-1-bg'] : '';
  update_post_meta($post_id, '_fp-cta-1-bg', $fp_cta_1_bg);

  $fp_cta_2 = isset($_POST['fp-cta-2']) ? $_POST['fp-cta-2'] : '';
  update_post_meta($post_id, '_fp-cta-2', $fp_cta_2);

  $fp_cta_2_btn_text = isset($_POST['fp-cta-2-btn-text']) ? $_POST['fp-cta-2-btn-text'] : '';
  update_post_meta($post_id, '_fp-cta-2-btn-text', $fp_cta_2_btn_text);

  $fp_cta_2_btn_link = isset($_POST['fp-cta-2-btn-link']) ? $_POST['fp-cta-2-btn-link'] : '';
  update_post_meta($post_id, '_fp-cta-2-btn-link', $fp_cta_2_btn_link);
}
add_action('save_post', '_ws_save_fp_meta');
