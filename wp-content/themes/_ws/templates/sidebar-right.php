<?php
/* Template Name: Sidebar Right */

get_header(); ?>

<div id="main" template="sidebar-right">
  <?php get_template_part('template-parts/banner'); ?>
  <div class="container row">
  	<main class="col-sm-7">
  		<?php
  		if (have_posts()) : while (have_posts()) : the_post();
  			get_template_part('template-parts/page');
  		endwhile; endif; ?>
  	</main>
  	<?php get_sidebar(); ?>
  </div>
</div>

<?php get_footer(); ?>
