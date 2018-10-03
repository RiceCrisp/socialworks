<?php
// Front page meta fields
function _sw_fp_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'fp-nonce');
  $fp_event_bg = get_post_meta(get_the_ID(), '_fp-event-bg', true);
  $fp_cta_1 = get_post_meta(get_the_ID(), '_fp-cta-1', true);
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
            </ul>
          </fieldset>
        </div>
      </li>
      <li>
        <div class="col-xs-12">
          <fieldset>
            <legend>CTA</legend>
            <ul>
              <li>
                <label for="fp-cta-1">Background Image</label>
                <textarea id="fp-cta-1" name="fp-cta-1" class="text-editor"><?= $fp_cta_1; ?></textarea>
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
            <ul class="row">
              <?php
              foreach ($fp_social_img as $i=>$img) : ?>
                <li class="col-lg-3 col-md-6">
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

  $fp_cta_1 = isset($_POST['fp-cta-1']) ? $_POST['fp-cta-1'] : '';
  update_post_meta($post_id, '_fp-cta-1', $fp_cta_1);

  $fp_locations = isset($_POST['fp-locations']) ? $_POST['fp-locations'] : '';
  update_post_meta($post_id, '_fp-locations', $fp_locations);

  $fp_social_img = isset($_POST['fp-social-img']) ? $_POST['fp-social-img'] : '';
  update_post_meta($post_id, '_fp-social-img', $fp_social_img);
}
add_action('save_post', '_sw_save_fp_meta');
