<?php
function _ws_block_social_links($a = [], $content = '') {
  $a['className'] = 'wp-block-ws-social-links ' . ($a['className'] ?? '');
  ob_start();
    echo $content;
  return _ws_block_wrapping($a, ob_get_clean(), 'ul');
}
