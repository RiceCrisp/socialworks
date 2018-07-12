<?php
// Fill meta box
function _ws_seo_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'seo-nonce');
  $seo_title = get_post_meta(get_the_ID(), '_seo-title', true);
  $seo_desc = get_post_meta(get_the_ID(), '_seo-desc', true);
  $seo_keywords = get_post_meta(get_the_ID(), '_seo-keywords', true);
  $seo_canonical = get_post_meta(get_the_ID(), '_seo-canonical', true);
  $seo_no_index = get_post_meta(get_the_ID(), '_seo-no-index', true);
  $seo_no_follow = get_post_meta(get_the_ID(), '_seo-no-follow', true);
  $seo_disallow_search = get_post_meta(get_the_ID(), '_seo-disallow-search', true);
  global $post;
  $content = strip_tags($post->post_content);
  $content = strlen($content) > 160 ? substr($content, 0, 160) . '...' : substr($content, 0, 160);
  $content = get_the_excerpt() ? get_the_excerpt() : $content; ?>
  <div id="seo-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <p class="no-margin">These options provide control over Search Engine Optimization via meta tags.</p>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <b>Example of Google Search Result</b>
          <div class="seo-preview">
            <p id="seo-preview-title"><span class="title"><?= get_the_title() ?: 'Title'; ?></span><span class="appended"><?= get_option('seo_meta_title') && $post->ID!=get_option('page_on_front') ? ' ' . get_option('seo_meta_title') : ''; ?></span></p>
            <p id="seo-preview-url"><?= get_permalink(); ?></p>
            <p id="seo-preview-desc"><?= $content ?: 'Description'; ?></p>
          </div>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="seo-title">Title <small>If left blank, defaults to page title</small></label>
          <input type="text" id="seo-title" name="seo-title" value="<?= $seo_title; ?>" />
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="seo-desc">Description <small>If left blank, defaults to page excerpt/content</small></label>
          <textarea id="seo-desc" name="seo-desc"><?= $seo_desc; ?></textarea>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <label for="seo-keywords">Keywords</label>
          <input id="seo-keywords" name="seo-keywords" type="text" value="<?= $seo_keywords; ?>" />
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <a href="#" id="seo-advanced-options-toggle">Show/Hide Advanced Options</a>
          <ul id="seo-advanced-options">
            <?php
            $amp = array_key_exists(get_post_type(), get_option('amp') ?: array()); ?>
            <li <?= $amp ? 'class="disabled"' : ''; ?>>
              <label for="seo-canonical">Canonical URL <small><?= $amp ? 'Disabled for AMP' : 'If left blank, defaults to page url'; ?></small></label>
              <input id="seo-canonical" name="seo-canonical" type="text" value="<?= $seo_canonical; ?>" <?= $amp ? 'disabled' : ''; ?> />
            </li>
            <li class="inline-option">
              <input id="seo-no-index" name="seo-no-index" type="checkbox" <?= $seo_no_index ? 'checked' : ''; ?> />
              <label for="seo-no-index">NO INDEX <small>Requests that search engines do not index this page.</small></label>
            </li>
            <li class="inline-option">
              <input id="seo-no-follow" name="seo-no-follow" type="checkbox" <?= $seo_no_follow ? 'checked' : ''; ?> />
              <label for="seo-no-follow">NO FOLLOW <small>Requests that search engines do not follow links on this page.</small></label>
            </li>
            <li class="inline-option">
              <input id="seo-disallow-search" name="seo-disallow-search" type="checkbox" <?= $seo_disallow_search ? 'checked' : ''; ?> />
              <label for="seo-disallow-search">Disallow Search <small>Removes page from internal site search.</small></label>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _ws_seo_meta() {
  add_meta_box('seo-meta-box', 'SEO', '_ws_seo_meta_fields', get_post_types(), 'normal', 'high');
}
add_action('add_meta_boxes', '_ws_seo_meta');

// Save meta values
function _ws_save_seo_meta($post_id) {
  if (!isset($_POST['seo-nonce']) || !wp_verify_nonce($_POST['seo-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $seo_title = isset($_POST['seo-title']) ? $_POST['seo-title'] : '';
  update_post_meta($post_id, '_seo-title', $seo_title);

  $seo_desc = isset($_POST['seo-desc']) ? $_POST['seo-desc'] : '';
  update_post_meta($post_id, '_seo-desc', $seo_desc);

  $seo_keywords = isset($_POST['seo-keywords']) ? $_POST['seo-keywords'] : '';
  update_post_meta($post_id, '_seo-keywords', $seo_keywords);

  $seo_canonical = isset($_POST['seo-canonical']) ? $_POST['seo-canonical'] : '';
  update_post_meta($post_id, '_seo-canonical', $seo_canonical);

  $seo_no_index = isset($_POST['seo-no-index']) ? true : false;
  update_post_meta($post_id, '_seo-no-index', $seo_no_index);

  $seo_no_follow = isset($_POST['seo-no-follow']) ? true : false;
  update_post_meta($post_id, '_seo-no-follow', $seo_no_follow);

  $seo_disallow_search = isset($_POST['seo-disallow-search']) ? true : false;
  update_post_meta($post_id, '_seo-disallow-search', $seo_disallow_search);
}
add_action('save_post', '_ws_save_seo_meta');

// Exclude pages from internal search
function _ws_search_filter($query) {
	if ($query->is_search) {
		$posts_array = get_posts(array(
      'post_type' => get_post_types(),
      'post_status'=>'publish',
			'meta_key' => '_seo-disallow-search',
			'meta_value' => true,
      'posts_per_page' => -1
		));
		$post_ids = array_column($posts_array, 'ID');
		$query->set('post__not_in', $post_ids);
	}
	return $query;
}
add_action('pre_get_posts', '_ws_search_filter');
