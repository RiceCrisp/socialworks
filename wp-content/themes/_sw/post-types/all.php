<?php
// Fill meta box
function _sw_banner_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'banner-nonce');
  $banner_headline = get_post_meta(get_the_ID(), '_banner-headline', true);
  $banner_subheadline = get_post_meta(get_the_ID(), '_banner-subheadline', true);
  $banner_headline_type = get_post_meta(get_the_ID(), '_banner-headline-type', true);
  $banner_author = get_post_meta(get_the_ID(), '_banner-author', true);
  $banner_external = get_post_meta(get_the_ID(), '_banner-external', true); ?>
  <div id="banner-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <label for="banner-headline">Headline <small>If left blank, defaults to page title</small></label>
          <input type="text" id="banner-headline" name="banner-headline" value="<?= esc_html($banner_headline); ?>" />
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="banner-subheadline">Sub-Headline</label>
          <textarea id="banner-subheadline" name="banner-subheadline"><?= $banner_subheadline; ?></textarea>
        </div>
      </li>
      <!-- <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>Headline Element Type</legend>
            <ul class="headline-type">
              <li class="inline-option">
                <input id="banner-headline-type-h1" name="banner-headline-type" type="radio" value="h1" <?= $banner_headline_type == 'h1' || $banner_headline_type == '' ? 'checked' : ''; ?> />
                <label for="banner-headline-type-h1">h1</label>
              </li>
              <li class="inline-option">
                <input id="banner-headline-type-h2" name="banner-headline-type" type="radio" value="h2" <?= $banner_headline_type == 'h2' ? 'checked' : ''; ?> />
                <label for="banner-headline-type-h2">h2</label>
              </li>
              <li class="inline-option">
                <input id="banner-headline-type-p" name="banner-headline-type" type="radio" value="p" <?= $banner_headline_type == 'p' ? 'checked' : ''; ?> />
                <label for="banner-headline-type-p">p</label>
              </li>
            </ul>
          </fieldset>
        </div>
      </li> -->
      <li class="row">
        <div class="col-sm-6">
          <label for="banner-author">Author Override</label>
          <input id="banner-author" name="banner-author" type="text" value="<?= $banner_author; ?>" />
        </div>
        <div class="col-sm-6">
          <label for="banner-external">External URL <small>if article exists on external site</small></label>
          <input id="banner-external" name="banner-external" type="text" value="<?= $banner_external; ?>" />
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _sw_banner_meta() {
  add_meta_box('banner-meta-box', 'Page Options', '_sw_banner_meta_fields', get_post_types(), 'normal', 'high');
}
add_action('add_meta_boxes', '_sw_banner_meta');

// Add featured image position options
function _sw_featured_image_pos($html) {
  $banner_x = get_post_meta(get_the_ID(), '_banner-x', true);
  $banner_y = get_post_meta(get_the_ID(), '_banner-y', true);
  $html .= '<p><small>Recommended Size: 1500 x 750 (2:1)</small></p>
  <fieldset class="banner-align">
    <legend>Image Alignment</legend>
    <div class="row">
      <div>
        <label for="banner-x">X</label>
        <div class="row">
          <input id="banner-x" name="banner-x" type="number" min="0" max="100" value="' . $banner_x . '" />
          <div class="pre-input">%</div>
        </div>
      </div>
      <div>
        <label for="banner-y">Y</label>
        <div class="row">
          <input id="banner-y" name="banner-y" type="number" min="0" max="100" value="' . $banner_y . '" />
          <div class="pre-input">%</div>
        </div>
      </div>
    </div>
  </fieldset>';
  return $html;
}
add_filter('admin_post_thumbnail_html', '_sw_featured_image_pos');

// Save meta values
function _sw_save_banner_meta($post_id) {
  if (!isset($_POST['banner-nonce']) || !wp_verify_nonce($_POST['banner-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $banner_headline = isset($_POST['banner-headline']) ? $_POST['banner-headline'] : '';
  update_post_meta($post_id, '_banner-headline', $banner_headline);

  $banner_subheadline = isset($_POST['banner-subheadline']) ? $_POST['banner-subheadline'] : '';
  update_post_meta($post_id, '_banner-subheadline', $banner_subheadline);

  $banner_headline_type = isset($_POST['banner-headline-type']) ? $_POST['banner-headline-type'] : '';
  update_post_meta($post_id, '_banner-headline-type', $banner_headline_type);

  $banner_author = isset($_POST['banner-author']) ? $_POST['banner-author'] : '';
  update_post_meta($post_id, '_banner-author', $banner_author);

  $banner_external = isset($_POST['banner-external']) ? $_POST['banner-external'] : '';
  update_post_meta($post_id, '_banner-external', $banner_external);

  $banner_x = isset($_POST['banner-x']) ? $_POST['banner-x'] : '';
  update_post_meta($post_id, '_banner-x', $banner_x);

  $banner_y = isset($_POST['banner-y']) ? $_POST['banner-y'] : '';
  update_post_meta($post_id, '_banner-y', $banner_y);
}
add_action('save_post', '_sw_save_banner_meta');

function _sw_404_external_pages() {
  global $post;
  if ($post && is_singular() && get_post_meta($post->ID, '_banner-external', true)) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
  }
}
add_action('wp', '_sw_404_external_pages');
