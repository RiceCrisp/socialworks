<?php
function _ws_block_video_lightbox($a = [], $content = '') {
  $a['className'] = 'wp-block-ws-video-lightbox card-link ' . ($a['className'] ?? '');
  $preview = $a['preview'] ?? '';
  $video = $a['video'] ?? '';
  if (!$video) {
    return;
  }
  ob_start();
    if ($preview) {
      echo _ws_image ($preview, ['class' => 'preview-image', 'size' => 'full']);
    } ?>
    <button class="lightbox-button" data-url="<?= $video; ?>" aria-label="<?= __('Watch Video', '_ws'); ?>">
      <div class="lightbox-button-icon">
        <svg viewBox="0 0 24 24"><path d="M5 2 L22 12 L5 22 Z"/></svg>
      </div>
    </button>
    <?php
  return _ws_block_wrapping($a, ob_get_clean(), 'div');
}
