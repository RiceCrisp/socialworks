<?php
// Initiatives meta fields
function _sw_init_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'init-nonce');
  $init_text = get_post_meta(get_the_ID(), '_init-text', true);
  $init_img = get_post_meta(get_the_ID(), '_init-img', true);
  $init_kpis_img = get_post_meta(get_the_ID(), '_init-kpis-img', true);
  $init_kpis = get_post_meta(get_the_ID(), '_init-kpis', true) ?: array('', '', '');
  $init_partners = get_post_meta(get_the_ID(), '_init-partners', true) ?: array('', '', '', '', '', ''); ?>
  <div id="init-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>Secondary Text</legend>
            <ul>
              <li>
                <label for="init-text">Text</label>
                <textarea id="init-text" name="init-text" class="text-editor"><?= $init_text; ?></textarea>
              </li>
              <li>
                <label for="init-img">Image</label>
                <?= _sw_img('init-img', 'init-img', $init_img); ?>
              </li>
            </ul>
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
              <?php
              foreach ($init_kpis as $i=>$kpi) : ?>
                <li>
                  <label for="init-kpis-<?= $i; ?>">KPI</label>
                  <textarea id="init-kpis-<?= $i; ?>" name="init-kpis[<?= $i; ?>]" class="text-editor"><?= $kpi; ?></textarea>
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

  $init_text = isset($_POST['init-text']) ? $_POST['init-text'] : '';
  update_post_meta($post_id, '_init-text', $init_text);

  $init_img = isset($_POST['init-img']) ? $_POST['init-img'] : '';
  update_post_meta($post_id, '_init-img', $init_img);

  $init_kpis_img = isset($_POST['init-kpis-img']) ? $_POST['init-kpis-img'] : '';
  update_post_meta($post_id, '_init-kpis-img', $init_kpis_img);

  $init_kpis = isset($_POST['init-kpis']) ? $_POST['init-kpis'] : '';
  update_post_meta($post_id, '_init-kpis', $init_kpis);

  $init_partners = isset($_POST['init-partners']) ? $_POST['init-partners'] : '';
  update_post_meta($post_id, '_init-partners', $init_partners);
}
add_action('save_post', '_sw_save_init_meta');
