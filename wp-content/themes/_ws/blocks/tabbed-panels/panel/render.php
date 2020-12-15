<?php
function _ws_block_panel($a = [], $content = '') {
  $svgs = get_option('svg');
  $uid = $a['uid'] ?? '';
  $icon = $a['icon'] ?? '';
  $heading = $a['heading'] ?? '';
  $i = array_search($icon, array_column($svgs, 'id'));
  $icon = $i === false ? '' : $svgs[$i];
  ob_start(); ?>
    <div
      id="panel-<?= $uid ?>"
      class="panel"
      data-icon="<?= urlencode(json_encode($icon)); ?>"
      data-heading="<?= $heading; ?>"
    >
      <?= $content; ?>
    </div>
  <?php
  return ob_get_clean();
}
