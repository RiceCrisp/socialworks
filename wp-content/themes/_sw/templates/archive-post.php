<?php
/* Template Name: Post Archive */

get_header(); ?>

<main id="main" class="archive archive-post" template="archive-post">
  <?php
  get_template_part('template-parts/banner');
  if (have_posts()) : while (have_posts()) : the_post();
    if (get_the_content()) {
      echo do_shortcode('[text]' . get_the_content() . '[/text]');
    }
  endwhile; endif;
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;
	$loop = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'paged'=>$paged));
	if ($loop->have_posts()) : ?>
    <section class="container row infinite-scroll">
      <input class="loop-var" type="hidden" value="<?= htmlentities(json_encode($loop)); ?>" />
      <?php
			while ($loop->have_posts()) : $loop->the_post();
				get_template_part('template-parts/archive', get_post_type());
			endwhile; ?>
    </section>
  <?php
  endif; wp_reset_postdata(); ?>
</main>

<?php get_footer(); ?>