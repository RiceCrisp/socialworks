<?php
function _ws_block_page_title($a = []) {
  $a['className'] = 'wp-block-ws-page-title ' . ($a['className'] ?? '');
  $heading = !empty($a['heading']) ? $a['heading'] : get_the_title();
  $textColorHex = $a['textColor'] ?? '';
  $textColorSlug = _ws_get_color_slug($a['textColor'] ?? '') ?: '';
  $textColorStyle = '';
  if ($textColorHex) {
    $a['className'] .= ' has-color';
    if ($textColorSlug) {
      $a['className'] .= ' has-' . $textColorSlug . '-color';
    }
    else {
      $textColorStyle = 'style="color:' . $textColorHex . ';"';
    }
  }
  ob_start(); ?>
    <h1 class="<?= $a['className']; ?>" <?= $textColorStyle; ?>><?= $heading; ?></h1>
  <?php
  return ob_get_clean();
}
