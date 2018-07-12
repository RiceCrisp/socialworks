<article id="post-<?php the_ID(); ?>" <?php post_class(get_post_type() . '-single'); ?>>
  <?php get_template_part('template-parts/banner'); ?>
  <div class="single-content">
    <div class="container row">
      <div class="col-xs-12">
        <?php the_post_thumbnail('profile-pic', array( 'class' => 'alignleft' ));?>
        <div class="body">
          <?php the_content(); ?>
        </div>
        <?php
        wp_link_pages(array(
          'before' => '<div class="page-links">' . esc_html__('Pages:', '_ws'),
          'after' => '</div>'
        )); ?>
      </div>
    </div>
  </div>
</article>
