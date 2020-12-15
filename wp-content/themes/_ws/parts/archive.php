<div class="col-md-6 col-lg-4">
  <div class="card-link archive-view archive-default archive-<?= get_post_type(); ?>">
    <?= _ws_thumbnail(get_the_ID(), ['objectFit' => true, 'class' => 'archive-image']); ?>
    <div class="archive-body">
      <p class="post-date"><?= get_the_date(); ?></p>
      <div class="title-excerpt">
        <h3 class="post-title"><?= _ws_get_link(get_the_ID()); ?></h3>
      </div>
    </div>
  </div>
</div>
