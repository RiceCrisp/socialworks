<?php
// Initiatives meta fields
function _sw_init_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'init-nonce');
  $init_title = get_post_meta(get_the_ID(), '_init-title', true);
  $init_side = get_post_meta(get_the_ID(), '_init-side', true);
  $init_text = get_post_meta(get_the_ID(), '_init-text', true);
  $init_img = get_post_meta(get_the_ID(), '_init-img', true);
  $init_tabs = get_post_meta(get_the_ID(), '_init-tabs', true) ?: array();
  $init_kpis_img = get_post_meta(get_the_ID(), '_init-kpis-img', true);
  $init_kpis_video = get_post_meta(get_the_ID(), '_init-kpis-video', true);
  $init_kpis = get_post_meta(get_the_ID(), '_init-kpis', true) ?: array('', '', '');
  $init_kpis_btn_text = get_post_meta(get_the_ID(), '_init-kpis-btn-text', true);
  $init_kpis_btn_url = get_post_meta(get_the_ID(), '_init-kpis-btn-url', true);
  $init_gallery = get_post_meta(get_the_ID(), '_init-gallery', true) ?: array('', '', '', '', '', '', '', '');
  $init_partners = get_post_meta(get_the_ID(), '_init-partners', true) ?: array(); ?>
  <div id="init-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>First Tab</legend>
            <ul>
              <li class="row">
                <div class="col-xs-12">
                  <label for="init-title">Title</label>
                  <input id="init-title" name="init-title" type="text" value="<?= $init_title; ?>" />
                </div>
              </li>
              <li class="row">
                <div class="col-xs-12">
                  <label for="init-side">Side Image</label>
                  <?= _sw_media_selector('init-side', 'init-side', $init_side); ?>
                </div>
              </li>
              <li>
                <label for="init-text">Secondary Text</label>
                <textarea id="init-text" name="init-text" class="text-editor"><?= $init_text; ?></textarea>
              </li>
              <li>
                <label for="init-img">Secondary Image</label>
                <?= _sw_media_selector('init-img', 'init-img', $init_img); ?>
              </li>
            </ul>
          </fieldset>
        </div>
      </li>
      <li>
        <div class="col-xs-12">
          <fieldset>
            <legend>Extra Tabs <small>Optional</small></legend>
            <ul class="sortable-container">
              <?php
              foreach ($init_tabs as $i=>$tab) : ?>
                <li class="sortable-item">
                  <div class="sortable-header">
                    <span class="dashicons dashicons-move sortable-handle"></span>
                    <span class="dashicons dashicons-trash sortable-delete"></span>
                  </div>
                  <ul class="sortable-content">
                    <li>
                      <label for="init-tabs-<?= $i; ?>-title">Title</label>
                      <input id="init-tabs-<?= $i; ?>-title" name="init-tabs[<?= $i; ?>][title]" type="text" value="<?= $tab['title']; ?>" />
                    </li>
                    <li>
                      <label for="init-tabs-<?= $i; ?>-content">Content</label>
                      <textarea id="init-tabs-<?= $i; ?>-content" name="init-tabs[<?= $i; ?>][content]" class="text-editor"><?= $tab['content']; ?></textarea>
                    </li>
                  </ul>
                </li>
              <?php
              endforeach; ?>
            </ul>
            <button id="add-tab" class="button">Add Tab</button>
          </fieldset>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>KPI's</legend>
            <ul>
              <li>
                <label for="init-kpis-img">Background Image</label>
                <?= _sw_media_selector('init-kpis-img', 'init-kpis-img', $init_kpis_img); ?>
              </li>
              <li>
                <label for="init-kpis-video">Video</label>
                <input id="init-kpis-video" name="init-kpis-video" type="text" value="<?= $init_kpis_video; ?>" />
              </li>
              <?php
              foreach ($init_kpis as $i=>$kpi) : ?>
                <li>
                  <label for="init-kpis-<?= $i; ?>">KPI <?= $i+1; ?></label>
                  <textarea id="init-kpis-<?= $i; ?>" name="init-kpis[<?= $i; ?>]" class="text-editor"><?= $kpi; ?></textarea>
                </li>
              <?php
              endforeach; ?>
            </ul>
          </fieldset>
        </div>
      </li>
      <li class="row">
        <div class="col-sm-6">
          <label for="init-kpis-btn-text">KPI Button Text</label>
          <input id="init-kpis-btn-text" name="init-kpis-btn-text" type="text" value="<?= $init_kpis_btn_text; ?>" />
        </div>
        <div class="col-sm-6">
          <label for="init-kpis-btn-url">KPI Button URL</label>
          <input id="init-kpis-btn-url" name="init-kpis-btn-url" type="text" value="<?= $init_kpis_btn_url; ?>" />
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>Gallery</legend>
            <ul class="sortable-container">
              <?php
              foreach ($init_gallery as $i=>$gallery) : ?>
                <li class="sortable-item">
                  <div class="sortable-header">
                    <span class="dashicons dashicons-move sortable-handle"></span>
                    <span class="dashicons dashicons-trash sortable-delete"></span>
                  </div>
                  <ul class="sortable-content gallery-content">
                    <li>
                      <label for="init-gallery-<?= $i; ?>">Image <?= $i+1; ?></label>
                      <?= _sw_media_selector('init-gallery-' . $i, 'init-gallery[' . $i . ']', $gallery); ?>
                    </li>
                  </ul>
                </li>
              <?php
              endforeach; ?>
            </ul>
          </fieldset>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>Partnerships</legend>
            <ul class="sortable-container">
              <?php
              foreach ($init_partners as $i=>$partner) : ?>
                <li class="sortable-item">
                  <div class="sortable-header">
                    <span class="dashicons dashicons-move sortable-handle"></span>
                    <span class="dashicons dashicons-trash sortable-delete"></span>
                  </div>
                  <ul class="sortable-content">
                    <li>
                      <label for="init-partners-<?= $i; ?>">Image</label>
                      <?= _sw_media_selector('init-partners-' . $i, 'init-partners[' . $i . ']', $partner); ?>
                    </li>
                  </ul>
                </li>
              <?php
              endforeach; ?>
            </ul>
            <button id="add-partner" class="button">Add Partnership</button>
          </fieldset>
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _sw_init_meta() {
  global $post;
  if (get_post_meta($post->ID, '_wp_page_template', true) == 'templates/initiative-page.php') {
    add_meta_box('init-meta-box', 'Initiative Page Template Options', '_sw_init_meta_fields', 'page', 'normal', 'high');
  }
}
add_action('add_meta_boxes', '_sw_init_meta');

// Save meta values
function _sw_save_init_meta($post_id) {
  if (!isset($_POST['init-nonce']) || !wp_verify_nonce($_POST['init-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $init_title = isset($_POST['init-title']) ? $_POST['init-title'] : '';
  update_post_meta($post_id, '_init-title', $init_title);

  $init_side = isset($_POST['init-side']) ? $_POST['init-side'] : '';
  update_post_meta($post_id, '_init-side', $init_side);

  $init_text = isset($_POST['init-text']) ? $_POST['init-text'] : '';
  update_post_meta($post_id, '_init-text', $init_text);

  $init_img = isset($_POST['init-img']) ? $_POST['init-img'] : '';
  update_post_meta($post_id, '_init-img', $init_img);

  $init_tabs = isset($_POST['init-tabs']) ? $_POST['init-tabs'] : '';
  update_post_meta($post_id, '_init-tabs', $init_tabs);

  $init_kpis_img = isset($_POST['init-kpis-img']) ? $_POST['init-kpis-img'] : '';
  update_post_meta($post_id, '_init-kpis-img', $init_kpis_img);

  $init_kpis_video = isset($_POST['init-kpis-video']) ? $_POST['init-kpis-video'] : '';
  update_post_meta($post_id, '_init-kpis-video', $init_kpis_video);

  $init_kpis = isset($_POST['init-kpis']) ? $_POST['init-kpis'] : '';
  update_post_meta($post_id, '_init-kpis', $init_kpis);

  $init_kpis_btn_text = isset($_POST['init-kpis-btn-text']) ? $_POST['init-kpis-btn-text'] : '';
  update_post_meta($post_id, '_init-kpis-btn-text', $init_kpis_btn_text);

  $init_kpis_btn_url = isset($_POST['init-kpis-btn-url']) ? $_POST['init-kpis-btn-url'] : '';
  update_post_meta($post_id, '_init-kpis-btn-url', $init_kpis_btn_url);

  $init_gallery = isset($_POST['init-gallery']) ? $_POST['init-gallery'] : '';
  update_post_meta($post_id, '_init-gallery', $init_gallery);

  $init_partners = isset($_POST['init-partners']) ? $_POST['init-partners'] : '';
  update_post_meta($post_id, '_init-partners', $init_partners);
}
add_action('save_post', '_sw_save_init_meta');
