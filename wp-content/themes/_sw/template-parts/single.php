<article id="post-<?php the_ID(); ?>" <?php post_class('event-single'); ?>>
  <?php
  get_template_part('template-parts/banner'); ?>
  <section>
    <div class="container row">
      <div class="col-xl-8 col-xl-offset-2 col-lg-10 col-lg-offset-1">
        <div class="text">
          <?php the_content(); ?>
          <hr />
          <?= do_shortcode('[share_buttons buttons="facebook, twitter"]'); ?>
        </div>
      </div>
    </div>
  </section>
</article>
