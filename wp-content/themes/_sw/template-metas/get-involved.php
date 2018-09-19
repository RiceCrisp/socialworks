<?php
// Volunteer meta fields
function _sw_involved_meta_fields() {
  wp_nonce_field(basename(__FILE__), 'involved-nonce');
  $involved_ctas = get_post_meta(get_the_ID(), '_involved-ctas', true) ?: array('', '');
  $involved_kpis = get_post_meta(get_the_ID(), '_involved-kpis', true) ?: array('', '', ''); ?>
  <div id="involved-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-12">
          <fieldset>
            <legend>CTA Cards</legend>
            <ul class="row">
              <?php
              foreach ($involved_ctas as $i=>$cta) : ?>
                <li class="col-sm-6">
                  <label for="involved-ctas-<?= $i; ?>">Card <?= $i+1; ?></label>
                  <textarea id="involved-ctas-<?= $i; ?>" name="involved-ctas[<?= $i; ?>]" class="text-editor"><?= $cta; ?></textarea>
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
            <legend>KPI Cards</legend>
            <ul class="row">
              <?php
              foreach ($involved_kpis as $i=>$kpi) : ?>
                <li class="col-sm-4">
                  <label for="involved-kpis-<?= $i; ?>-">Card <?= $i+1; ?></label>
                  <textarea id="involved-kpis-<?= $i; ?>" name="involved-kpis[<?= $i; ?>]" class="text-editor"><?= $kpi; ?></textarea>
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
function _sw_involved_meta() {
  global $post;
  if (get_post_meta($post->ID, '_wp_page_template', true) == 'templates/get-involved.php') {
    add_meta_box('involved-meta-box', 'Volunteer Page Template Options', '_sw_involved_meta_fields', 'page', 'normal', 'high');
  }
}
add_action('add_meta_boxes', '_sw_involved_meta');

// Save meta values
function _sw_save_involved_meta($post_id) {
  if (!isset($_POST['involved-nonce']) || !wp_verify_nonce($_POST['involved-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $involved_ctas = isset($_POST['involved-ctas']) ? $_POST['involved-ctas'] : '';
  update_post_meta($post_id, '_involved-ctas', $involved_ctas);

  $involved_kpis = isset($_POST['involved-kpis']) ? $_POST['involved-kpis'] : '';
  update_post_meta($post_id, '_involved-kpis', $involved_kpis);
}
add_action('save_post', '_sw_save_involved_meta');
