<?php
function _ws_block_icon_list_item($a = [], $content = '') {
  $a['className'] = 'ws-block-icon-list ' . ($a['className'] ?? '');
  $icon = $a['icon'] ?? '';
  $iconColorHex = $a['iconColor'] ?? '';
  $iconColorSlug = _ws_get_color_slug($a['iconColor']) ?: '';
  $text = $a['text'] ?? '';
  $iconClasses = [];
  $iconStyles = '';
  if ($iconColorHex) {
    array_push($iconClasses, 'has-color');
    if ($iconColorSlug) {
      array_push($iconClasses, 'has-' . $iconColorSlug . '-color');
    }
    else {
      $iconStyles .= 'color:' . $iconColorHex . ';';
    }
  }
  ob_start();
    echo '<li>[svg id="' . $icon . '" class="' . implode(' ', $iconClasses) . '" style="' . $iconStyles . '"]<span>' . $text . '</span></li>';
  return ob_get_clean();
}
