<div class="col-lg-4 col-md-6">
  <div class="card card-link archive-view archive-card">
    <?= _ws_thumbnail(get_the_ID(), ['objectFit' => true, 'class' => 'square card-image']); ?>
    <div class="card-body">
      <p class="post-date"><?= get_the_date(); ?></p>
      <div class="title-excerpt">
        <h3 class="post-title"><?= _ws_get_link(get_the_ID()); ?></h3>
      </div>
    </div>
  </div>
</div>
