<?php
function _ws_block_split($a = [], $content = '') {
  $a['className'] = 'wp-block-ws-split ' . ($a['className'] ?? '');
  $alignment = isset($a['alignment']) && $a['alignment'] ? ' align-items-' . $a['alignment'] : '';
  $variant = isset($a['variant']) && $a['variant'] ? ' variant-' . $a['variant'] : '';
  $padding = isset($a['padding']) && $a['padding'] ? ' split-padding' : '';
  $mobileReverse = isset($a['mobileReverse']) && $a['mobileReverse'] ? ' row-reverse' : '';
  $a['className'] .= $alignment . $variant . $padding . $mobileReverse;
  ob_start(); ?>
    <?= $content; ?>
  <?php
  return _ws_block_wrapping($a, ob_get_clean(), 'div');
}
