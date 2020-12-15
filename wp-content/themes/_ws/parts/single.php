<div id="main" class="main single-view">
  <section class="wp-block-ws-section <?= has_post_thumbnail() ? 'has-background-image' : ''; ?>">
    <div class="container section-container">
      <div class="section-inner">
        <div class="wp-block-ws-split align-items-center">
          <div class="wp-block-ws-split-half">
            <?= _ws_block_breadcrumbs(); ?>
            <h1><?= get_the_title(); ?></h1>
          </div>
          <div class="wp-block-ws-split-half">
            <?= _ws_thumbnail(get_the_ID(), ['size' => 'large', 'class' => 'wp-block']); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="wp-block-ws-section <?= has_post_thumbnail() ? 'padding-top-50' : 'padding-top-0'; ?>">
    <div class="container section-container">
      <div class="section-inner">
        <div class="wp-block-ws-content-sidebar">
          <div class="row">
            <main class="col-lg-8 col-xl-7">
              <?php
              the_content();
              echo _ws_get_prev_next(); ?>
            </main>
            <aside class="col-lg-4 col-xl-4 col-xl-offset-1 sidebar">
              <div class="sidebar-block">
                <?= _ws_block_social_share(); ?>
              </div>
              <div class="sidebar-block">
                <p>Not what you're looking for?</p>
                <div class="wp-block">
                  <a class="button-arrow" href="/contact-us/">Get in touch</a>
                </div>
              </div>
            </aside>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
