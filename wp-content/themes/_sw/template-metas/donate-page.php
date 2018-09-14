<?php
// Donate meta fields
function _sw_donate_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'donate-nonce');
  $donate_ctas = get_post_meta(get_the_ID(), '_donate-ctas', true) ?: array('', '');
  $donate_kpis = get_post_meta(get_the_ID(), '_donate-kpis', true) ?: array('', '', ''); ?>
  <div id="donate-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>CTA Cards</legend>
            <div class="row">
              <?php
              foreach ($donate_ctas as $i=>$cta) : ?>
                <ul class="col-sm-6">
                  <li>
                    <label for="donate-ctas-<?= $i; ?>-img">Card <?= $i; ?> Image</label>
                    <?= _sw_media_selector('donate-ctas-' . $i . '-img', 'donate-ctas[' . $i . '][img]', $cta['img']); ?>
                  </li>
                  <li>
                    <label for="donate-ctas-<?= $i; ?>-text">Card <?= $i; ?> Text</label>
                    <?= _sw_media_selector('donate-ctas-' . $i . '-text', 'donate-ctas[' . $i . '][text]', $cta['text']); ?>
                  </li>
                </ul>
              <?php
              endforeach; ?>
            </div>
          </fieldset>
        </div>
      </li>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>KPI Cards</legend>
            <div class="row">
              <?php
              foreach ($donate_kpis as $i=>$kpi) : ?>
                <ul class="col-sm-4">
                  <li>
                    <label for="donate-kpis-<?= $i; ?>-img">Card <?= $i; ?> Image</label>
                    <?= _sw_media_selector('donate-kpis-' . $i . '-img', 'donate-kpis[' . $i . '][img]', $kpi['img']); ?>
                  </li>
                  <li>
                    <label for="donate-kpis-<?= $i; ?>-text">Card <?= $i; ?> Text</label>
                    <?= _sw_media_selector('donate-kpis-' . $i . '-text', 'donate-kpis[' . $i . '][text]', $kpi['text']); ?>
                  </li>
                </ul>
              <?php
              endforeach; ?>
            </div>
          </fieldset>
        </div>
      </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _sw_donate_meta() {
  global $post;
  if (get_post_meta($post->ID, '_wp_page_template', true) == 'templates/donateiative-page.php') {
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

  $donate_ctas = isset($_POST['donate-ctas']) ? $_POST['donate-ctas'] : '';
  update_post_meta($post_id, '_donate-ctas', $donate_ctas);

  $donate_kpis = isset($_POST['donate-kpis']) ? $_POST['donate-kpis'] : '';
  update_post_meta($post_id, '_donate-kpis', $donate_kpis);
}
add_action('save_post', '_sw_save_donate_meta');
