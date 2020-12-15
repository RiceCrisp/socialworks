<?php
function _ws_block_select_content($a = []) {
  $a['className'] = 'wp-block-ws-select-content ' . ($a['className'] ?? '');
  $horizontalScroll = isset($a['horizontalScroll']) && $a['horizontalScroll'] ? 'horizontal-scroll' : '';
  $ids = $a['ids'] ?? [];
  $type = count($ids) > 0 ? get_post_type($ids[0]) : 'none';
  preg_match('/is-style-([^\s]+)/', $a['className'], $matches);
  if (isset($matches[1])) {
    $type = $matches[1];
  }
  ob_start(); ?>
    <div class="row <?= $type; ?>-row <?= $horizontalScroll; ?>">
      <?php
      if (count($ids)) {
        global $post;
        foreach ($ids as $id) {
          $post = get_post($id);
          setup_postdata($post);
          get_template_part('parts/archive', $type);
        }
        wp_reset_postdata();
      } ?>
    </div>
  <?php
  return _ws_block_wrapping($a, ob_get_clean(), 'div');
}
