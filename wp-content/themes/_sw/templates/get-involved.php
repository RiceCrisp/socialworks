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
  <section>
    <h2>Gallery</h2>
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
