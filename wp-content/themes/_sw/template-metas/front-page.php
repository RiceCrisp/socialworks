<?php
// Front page meta fields
function _sw_fp_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'fp-nonce');
  $fp_event_bg = get_post_meta(get_the_ID(), '_fp-event-bg', true);
  $fp_cta_1 = get_post_meta(get_the_ID(), '_fp-cta-1', true);
  $fp_cta_1_bg = get_post_meta(get_the_ID(), '_fp-cta-1-bg', true);
  $fp_cta_2 = get_post_meta(get_the_ID(), '_fp-cta-2', true);
  $fp_cta_2_btn_text = get_post_meta(get_the_ID(), '_fp-cta-2-btn-text', true);
  $fp_cta_2_btn_url = get_post_meta(get_the_ID(), '_fp-cta-2-btn-url', true);
  $fp_cta_2_btn_url = get_post_meta(get_the_ID(), '_fp-cta-2-btn-url', true);
  $fp_locations = get_post_meta(get_the_ID(), '_fp-locations', true) ?: array();
  $fp_social_img = get_post_meta(get_the_ID(), '_fp-social-img', true) ?: array('', '', '', ''); ?>
  <div id="fp-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>Event</legend>
            <ul>
              <li>
                <label for="fp-event-bg">Background Image</label>
                <div class="row">
                  <button class="button media-selector" target="#fp-event-bg">Select Image</button>
                  <input id="fp-event-bg" name="fp-event-bg" class="flex-1" type="text" value="<?= $fp_event_bg; ?>">
                </div>
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
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>Locations</legend>
            <ul class="sortable-container">
              <?php
              foreach ($fp_locations as $i=>$loc) : ?>
                <li class="sortable-item">
                  <div class="sortable-header">
                    <span class="dashicons dashicons-move sortable-handle"></span>
                    <span class="dashicons dashicons-trash sortable-delete"></span>
                  </div>
                  <ul class="sortable-content">
                    <li>
                      <label for="fp-locations-<?= $i; ?>-address">Address</label>
                      <textarea id="fp-locations-<?= $i; ?>-address" name="fp-locations[<?= $i; ?>][address]" class="text-editor"><?= $loc['address']; ?></textarea>
                    </li>
                    <li class="row">
                      <div class="col-sm-6">
                        <label for="fp-locations-<?= $i; ?>-lat">Latitude</label>
                        <input id="fp-locations-<?= $i; ?>-lat" name="fp-locations[<?= $i; ?>][lat]" type="number" value="<?= $loc['lat']; ?>" />
                      </div>
                      <div class="col-sm-6">
                        <label for="fp-locations-<?= $i; ?>-lng">Longitude</label>
                        <input id="fp-locations-<?= $i; ?>-lng" name="fp-locations[<?= $i; ?>][lng]" type="number" value="<?= $loc['lng']; ?>" />
                      </div>
                    </li>
                  </ul>
                </li>
              <?php
              endforeach; ?>
            </ul>
            <button id="add-location" class="button">Add Location</button>
          </fieldset>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>Social Images</legend>
            <ul>
              <?php
              foreach ($fp_social_img as $i=>$img) : ?>
                <li>
                  <label for="fp-social-img-<?= $i; ?>">Text</label>
                  <?= _sw_media_selector('fp-social-img-' . $i, 'fp-social-img[' . $i . ']', $img); ?>
                </li>
              <?php
              endforeach; ?>
            </ul>
          </fieldset>
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _sw_fp_meta() {
  global $post;
  if (get_post_meta($post->ID, '_wp_page_template', true) == 'templates/front-page.php') {
    add_meta_box('fp-meta-box', 'Front Page Template Options', '_sw_fp_meta_fields', 'page', 'normal', 'high');
  }
}
add_action('add_meta_boxes', '_sw_fp_meta');

// Save meta values
function _sw_save_fp_meta($post_id) {
  if (!isset($_POST['fp-nonce']) || !wp_verify_nonce($_POST['fp-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $fp_event_bg = isset($_POST['fp-event-bg']) ? $_POST['fp-event-bg'] : '';
  update_post_meta($post_id, '_fp-event-bg', $fp_event_bg);

  $fp_cta_1_bg = isset($_POST['fp-cta-1-bg']) ? $_POST['fp-cta-1-bg'] : '';
  update_post_meta($post_id, '_fp-cta-1-bg', $fp_cta_1_bg);

  $fp_cta_2 = isset($_POST['fp-cta-2']) ? $_POST['fp-cta-2'] : '';
  update_post_meta($post_id, '_fp-cta-2', $fp_cta_2);

  $fp_cta_2_btn_text = isset($_POST['fp-cta-2-btn-text']) ? $_POST['fp-cta-2-btn-text'] : '';
  update_post_meta($post_id, '_fp-cta-2-btn-text', $fp_cta_2_btn_text);

  $fp_cta_2_btn_link = isset($_POST['fp-cta-2-btn-link']) ? $_POST['fp-cta-2-btn-link'] : '';
  update_post_meta($post_id, '_fp-cta-2-btn-link', $fp_cta_2_btn_link);

  $fp_locations = isset($_POST['fp-locations']) ? $_POST['fp-locations'] : '';
  update_post_meta($post_id, '_fp-locations', $fp_locations);
}
add_action('save_post', '_sw_save_fp_meta');
