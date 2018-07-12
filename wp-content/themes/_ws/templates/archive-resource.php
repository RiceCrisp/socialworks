<?php
/* Template Name: Resource Archive */

get_header(); ?>

<main id="main" class="archive archive-resource" template="archive-resource">
  <?php
  get_template_part('template-parts/banner');
  if (have_posts()) : while (have_posts()) : the_post();
    if (get_the_content()) {
      echo do_shortcode('[text]' . get_the_content() . '[/text]');
    }
  endwhile; endif;
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;
  $type = isset($_GET['type']) ? array('taxonomy'=>'resource_type', 'field'=>'slug', 'terms'=>$_GET['type']) : '';
	$loop = new WP_Query(array('post_type'=>'resource', 'post_status'=>'publish', 'paged'=>$paged, 'tax_query'=>array(
    'relation' => 'AND',
    $type
  )));
	if ($loop->have_posts()) : ?>
    <section class="container row infinite-scroll">
      <input class="loop-var" type="hidden" value="<?= htmlentities(json_encode($loop)); ?>" />
      <form class="col-xs-12 filters">
        <select id="resource-type" name="type">
          <option value="all" selected>All Resources</option>
          <?php
          $types = get_terms(array('taxonomy' => 'resource_type'));
          foreach ($types as $type) {
            echo '<option value="' . $type->slug . '" ' . (isset($_GET['type']) && $_GET['type'] == $type->slug ? 'selected' : '') . '>' . $type->name . '</option>';
          }
          ?>
        </select>
        <input type="submit" />
      </form>
      <?php
			while ($loop->have_posts()) : $loop->the_post();
				get_template_part('template-parts/archive', get_post_type());
			endwhile; ?>
    </section>
  <?php
  endif; wp_reset_postdata(); ?>
</main>

<?php get_footer(); ?>
