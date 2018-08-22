<div class="col col-sm-3 col-xs-12">
  <div id="post-<?php the_ID(); ?>" <?php post_class('archive-grid' . ' ' . get_post_type()); ?>>
  <?php
  if (has_post_thumbnail()) : ?>
    <div class="img" style="background-image:url(<?= the_post_thumbnail_url('large'); ?>)"></div>
  <?php
  endif; ?>
    <div class="content">
      <h2><a href="<?= get_permalink(); ?>"><?= get_the_title(); ?></a></h2>
      <?= _sw_taxonomies(); ?>
      <?= the_excerpt(); ?>
    </div>
  </div>
</div>