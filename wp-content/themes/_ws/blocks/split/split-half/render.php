<?php
function _ws_block_split_half($a = [], $content = '') {
  $a['className'] = 'wp-block-ws-split-half ' . ($a['className'] ?? '');
  $extendTop = isset($a['extendTop']) && $a['extendTop'] ? 'extend-top' : '';
  $extendBottom = isset($a['extendBottom']) && $a['extendBottom'] ? 'extend-bottom' : '';
  $a['className'] .= $extendTop . ' ' . $extendBottom;
  ob_start(); ?>
    <?= $content; ?>
  <?php
  return _ws_block_wrapping($a, ob_get_clean(), 'div');
}
