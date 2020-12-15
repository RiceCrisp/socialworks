<?php
function _ws_block_section($a, $content) {
  $a['className'] = 'wp-block-ws-section ' . ($a['className'] ?? '');
  $width = !empty($a['width']) ? ' width-' . $a['width'] : '';
  $paddingTop = isset($a['paddingTop']) ? ' padding-top-' . $a['paddingTop'] : '';
  $paddingBottom = isset($a['paddingBottom']) ? ' padding-bottom-' . $a['paddingBottom'] : '';
  $a['className'] .= $paddingTop . $paddingBottom . $width;
  ob_start(); ?>
    <div class="container section-container">
      <div class="section-inner">
        <?= $content; ?>
      </div>
    </div>
  <?php
  return _ws_block_wrapping($a, ob_get_clean());
}
