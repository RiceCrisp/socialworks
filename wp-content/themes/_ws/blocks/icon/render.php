<?php
function _ws_block_icon($a = [], $content = '') {
  $a['className'] = 'wp-block-ws-icon ' . ($a['className'] ?? '');
  $icon = $a['icon'] ?? '';
  $size = $a['size'] ?? 'artboard';
  $iconColorHex = $a['iconColor'] ?? '';
  $iconColorSlug = _ws_get_color_slug($a['iconColor'] ?? '') ?: '';
  $iconBackgroundColorHex = $a['iconBackgroundColor'] ?? '';
  $iconBackgroundColorSlug = _ws_get_color_slug($a['iconBackgroundColor'] ?? '') ?: '';
  $text = $a['text'] ?? false;
  $textAlign = isset($a['textAlign']) && $a['textAlign'] ? 'has-text-align-' . $a['textAlign'] : '';
  $a['className'] .= $textAlign;
  $iconStyles = '';
  $iconClasses = ['icon-svg', 'size-' . $size];
  if ($iconBackgroundColorHex) {
    array_push($iconClasses, 'has-background-color');
    if ($iconBackgroundColorSlug) {
      array_push($iconClasses, 'has-' . $iconBackgroundColorSlug . '-background-color');
    }
    else {
      $iconStyles .= 'background-color:' . $iconBackgroundColorHex . ';';
    }
  }
  if ($iconColorHex) {
    array_push($iconClasses, 'has-color');
    if ($iconColorSlug) {
      array_push($iconClasses, 'has-' . $iconColorSlug . '-color');
    }
    else {
      $iconStyles .= 'color:' . $iconColorHex . ';';
    }
  }
  if ($text) {
    $a['className'] .= ' icon-text-' . $text;
  }
  ob_start(); ?>
    <div class="<?= $a['className']; ?>">
      <div class="<?= implode(' ', $iconClasses); ?>" <?= $iconStyles ? 'style="' . $iconStyles . '"' : ''; ?>>
        <?= '[svg id="' . $icon . '"]'; ?>
      </div>
      <?php
      if ($text) : ?>
        <div class="icon-text">
          <?= $content; ?>
        </div>
        <?php
      endif; ?>
    </div>
  <?php
  return ob_get_clean();
}
