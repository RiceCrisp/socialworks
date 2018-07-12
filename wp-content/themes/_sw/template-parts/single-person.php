<article id="post-<?php the_ID(); ?>" <?php post_class(get_post_type() . '-single'); ?>>
  <?php get_template_part('template-parts/banner'); ?>
  <div class="single-content">
    <div class="container row">
      <div class="col-sm-4">
        <img src="<?= get_post_meta(get_the_ID(), '_person-img', true); ?>" alt="<?= get_the_title(); ?>" />
        <h3 class="role"><?=get_post_meta(get_the_ID(), '_person-role', true); ?></h3>
      </div>
      <div class="col-sm-8">
        <?php
        the_content();
        wp_link_pages(array(
          'before' => '<div class="page-links">' . esc_html__('Pages:', '_sw'),
          'after' => '</div>'
        )); ?>
      </div>
    </div>
  </div>
</article>
