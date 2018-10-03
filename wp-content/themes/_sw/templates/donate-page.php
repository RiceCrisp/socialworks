<?php
/* Template Name: Donate Page */

get_header(); ?>

<main id="main" template="donate-page">
  <?php
  get_template_part('template-parts/banner'); ?>
  <section class="donate">
    <div class="container row">
      <div class="col-xs-12 card-container">
        <div class="card">
          <div class="row">
            <div class="col-xl-7 col-lg-7 donate-col">
              <?php
              if (have_posts()) : while (have_posts()) : the_post();
                the_content();
              endwhile; endif; ?>
            </div>
            <div class="col-xl-4 col-xl-offset-1 col-lg-5 col-lg-offset-0 col-md-8 col-md-offset-2 donate-col">
              <?= get_post_meta(get_the_ID(), '_donate-text', true); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
