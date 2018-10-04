<article id="post-<?php the_ID(); ?>" <?php post_class('event-single'); ?>>
  <?php
  get_template_part('template-parts/banner'); ?>
  <section>
    <div class="container row">
      <div class="col-lg-8">
        <div class="text">
          <?php the_content(); ?>
          <hr />
          <?= do_shortcode('[share_buttons buttons="facebook, twitter"]'); ?>
        </div>
      </div>
    </div>
  </section>
</article>
