<?php
function _ws_block_tiles($a = [], $content = '') {
  $a['className'] = 'wp-block-ws-tiles ' . ($a['className'] ?? '');
  $gutters = isset($a['gutters']) && $a['gutters'] ? 'has-gutters' : '';
  ob_start(); ?>
    <div class="grid <?= $gutters; ?>">
      <?= $content; ?>
    </div>
  <?php
  return _ws_block_wrapping($a, ob_get_clean(), 'div');
}
