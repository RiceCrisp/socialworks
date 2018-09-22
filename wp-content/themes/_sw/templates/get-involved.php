<?php
/* Template Name: Get Involved */

get_header(); ?>

<main id="main" template="get-involved">
  <?php
  get_template_part('template-parts/banner'); ?>
  <section class="involved-ctas">
    <div class="container row">
      <?php
      $ctas = get_post_meta(get_the_ID(), '_involved-ctas', true);
      foreach ($ctas as $cta) : ?>
        <div class="col-md-6 card-container">
          <div class="card">
            <div class="card-content">
              <?= $cta; ?>
            </div>
          </div>
        </div>
      <?php
      endforeach; ?>
    </div>
  </section>
  <section class=pb-gallery>
    <div class="container">
      <div class="row">
        <?php
        $gallerys = get_post_meta(get_the_ID(), '_involved-gallery', true);
        foreach ($gallerys as $gallery) : ?>
          <div class="col-md-3 col-xs-6 center">
            <?= _sw_img($gallery, 'standard', true); ?>
          </div>
        <?php
        endforeach; ?>
      </div>
    </div>
  </section>
  <section class="involved-kpis">
    <div class="container row">
      <?php
      $kpis = get_post_meta(get_the_ID(), '_involved-kpis', true);
      foreach ($kpis as $kpi) : ?>
        <div class="col-lg-4 card-container">
          <div class="card">
            <div class="card-content">
              <?= $kpi; ?>
            </div>
          </div>
        </div>
      <?php
      endforeach; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
