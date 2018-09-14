<?php
// Volunteer meta fields
function _sw_volunteer_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'volunteer-nonce');
  $volunteer_ctas = get_post_meta(get_the_ID(), '_volunteer-ctas', true) ?: array('', '');
  $volunteer_kpis = get_post_meta(get_the_ID(), '_volunteer-kpis', true) ?: array('', '', ''); ?>
  <div id="volunteer-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>CTA Cards</legend>
            <div class="row">
              <?php
              foreach ($volunteer_ctas as $i=>$cta) : ?>
                <ul class="col-sm-6">
                  <li>
                    <label for="volunteer-ctas-<?= $i; ?>-img">Card <?= $i; ?> Image</label>
                    <?= _sw_media_selector('volunteer-ctas-' . $i . '-img', 'volunteer-ctas[' . $i . '][img]', $cta['img']); ?>
                  </li>
                  <li>
                    <label for="volunteer-ctas-<?= $i; ?>-text">Card <?= $i; ?> Text</label>
                    <?= _sw_media_selector('volunteer-ctas-' . $i . '-text', 'volunteer-ctas[' . $i . '][text]', $cta['text']); ?>
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
              foreach ($volunteer_kpis as $i=>$kpi) : ?>
                <ul class="col-sm-4">
                  <li>
                    <label for="volunteer-kpis-<?= $i; ?>-img">Card <?= $i; ?> Image</label>
                    <?= _sw_media_selector('volunteer-kpis-' . $i . '-img', 'volunteer-kpis[' . $i . '][img]', $kpi['img']); ?>
                  </li>
                  <li>
                    <label for="volunteer-kpis-<?= $i; ?>-text">Card <?= $i; ?> Text</label>
                    <?= _sw_media_selector('volunteer-kpis-' . $i . '-text', 'volunteer-kpis[' . $i . '][text]', $kpi['text']); ?>
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
function _sw_volunteer_meta() {
  global $post;
  if (get_post_meta($post->ID, '_wp_page_template', true) == 'templates/volunteeriative-page.php') {
    add_meta_box('volunteer-meta-box', 'Volunteer Page Template Options', '_sw_volunteer_meta_fields', 'page', 'normal', 'high');
  }
}
add_action('add_meta_boxes', '_sw_volunteer_meta');

// Save meta values
function _sw_save_volunteer_meta($post_id) {
  if (!isset($_POST['volunteer-nonce']) || !wp_verify_nonce($_POST['volunteer-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $volunteer_ctas = isset($_POST['volunteer-ctas']) ? $_POST['volunteer-ctas'] : '';
  update_post_meta($post_id, '_volunteer-ctas', $volunteer_ctas);

  $volunteer_kpis = isset($_POST['volunteer-kpis']) ? $_POST['volunteer-kpis'] : '';
  update_post_meta($post_id, '_volunteer-kpis', $volunteer_kpis);
}
add_action('save_post', '_sw_save_volunteer_meta');
