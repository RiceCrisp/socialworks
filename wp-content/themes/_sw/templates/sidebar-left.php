<?php
/* Template Name: Sidebar Left */

get_header(); ?>

<div id="main" template="sidebar-left">
  <?php get_template_part('template-parts/banner'); ?>
  <div class="container row">
  	<?php get_sidebar(); ?>
  	<main class="col-sm-7 col-sm-offset-1">
  		<?php
  		if (have_posts()) : while (have_posts()) : the_post();
  			get_template_part('template-parts/page');
  		endwhile; endif; ?>
  	</main>
  </div>
</div>

<?php get_footer(); ?>
