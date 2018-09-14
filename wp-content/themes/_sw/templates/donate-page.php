<?php
/* Template Name: Donate Page */

get_header(); ?>

<main id="main" template="donate-page">
  <?php
  get_template_part('template-parts/banner'); ?>
  <section class="dontate-ctas">
    <div class="container row">
      <div class="col-md-6 card-container">
        <div class="card">
          <?= get_post_meta(get_the_ID(), '_volunteer-card-1', true); ?>
        </div>
      </div>
      <div class="col-md-6 card-container">
        <div class="card">
          <?= get_post_meta(get_the_ID(), '_volunteer-card-2', true); ?>
        </div>
      </div>
    </div>
  </section>
  <section>
    <h2>Gallery</h2>
  </section>
  <section class="volunteer-kpis">
    <div class="container row">
      <?php
      $kpis = get_post_meta(get_the_ID(), '_volunteer-kpis', true);
      foreach ($kpis as $kpi) : ?>
        <div class="col-lg-4 card-container">
          <div class="card">
            <?= $kpi['img']; ?>
            <?= $kpi['content']; ?>
          </div>
        </div>
      <?php
      endforeach; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
