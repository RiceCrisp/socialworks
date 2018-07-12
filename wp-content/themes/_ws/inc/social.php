<?php
// Fill meta box
function _ws_social_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'social-nonce');
  $social_title = get_post_meta(get_the_ID(), '_social-title', true);
  $social_desc = get_post_meta(get_the_ID(), '_social-desc', true);
  $social_img = get_post_meta(get_the_ID(), '_social-img', true);
  $social_twitter = get_post_meta(get_the_ID(), '_social-twitter', true); ?>
  <div id="social-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <p class="no-margin">This information is placed in meta tags and used by social networks to create rich sharable objects.</p>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="social-title">Title <small>If left blank, defaults to SEO title</small></label>
          <input id="social-title" name="social-title" type="text" value="<?= $social_title; ?>" />
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="social-desc">Description <small>If left blank, defaults to SEO description</small></label>
          <textarea id="social-desc" name="social-desc"><?= $social_desc; ?></textarea>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="social-img">Image <small>If left blank, defaults to page thumbnail</small></label>
          <div class="row">
            <button class="button media-selector" target="#social-img">Select Image</button>
            <input id="social-img" class="flex-1" name="social-img" type="text" value="<?= $social_img; ?>" />
          </div>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="social-twitter">Twitter Username</label>
          <div class="row">
            <div class="pre-input">@</div>
            <input id="social-twitter" name="social-twitter" class="flex-1" type="text" value="<?= $social_twitter; ?>" />
          </div>
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _ws_social_meta() {
  add_meta_box('social-meta-box', 'Social', '_ws_social_meta_fields', get_post_types(), 'normal', 'high');
}
add_action('add_meta_boxes', '_ws_social_meta');

// Save meta values
function _ws_save_social_meta($post_id) {
  if (!isset($_POST['social-nonce']) || !wp_verify_nonce($_POST['social-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $social_title = isset($_POST['social-title']) ? $_POST['social-title'] : '';
  update_post_meta($post_id, '_social-title', $social_title);

  $social_desc = isset($_POST['social-desc']) ? $_POST['social-desc'] : '';
  update_post_meta($post_id, '_social-desc', $social_desc);

  $social_img = isset($_POST['social-img']) ? $_POST['social-img'] : '';
  update_post_meta($post_id, '_social-img', $social_img);

  $social_twitter = isset($_POST['social-twitter']) ? $_POST['social-twitter'] : '';
  update_post_meta($post_id, '_social-twitter', $social_twitter);
}
add_action('save_post', '_ws_save_social_meta');
