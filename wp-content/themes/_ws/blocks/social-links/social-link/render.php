<?php
function _ws_block_social_link($a = [], $content = '') {
  // $a['className'] = 'wp-block-ws-icon-list ' . ($a['className'] ?? '');
  $icon = $a['icon'] ?? '';
  $url = $a['url'] ?? '';
  ob_start(); ?>
    <li>
      <a href="<?= $url; ?>" target="_blank" rel="noopener noreferrer">
        [svg id="<?= $icon; ?>"]
      </a>
    </li>
    <?php
  return ob_get_clean();
}
