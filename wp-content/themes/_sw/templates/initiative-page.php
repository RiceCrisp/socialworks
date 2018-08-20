<?php
/* Template Name: Initiative Page */

get_header(); ?>

<main id="main" template="initiative-page">
  <?php
  get_template_part('template-parts/banner'); ?>
  <section class="init-main">
    <div class="container row">
      <div class="col-md-4"></div>
      <div class="col-md-8 card-container">
        <?php
        if (have_posts()) : while (have_posts()) : the_post();
          ob_start();
          the_content();
          $content = ob_get_contents();
          ob_end_clean();
          echo do_shortcode('[card]' . $content . '[/card]');
        endwhile; endif; ?>
      </div>
    </div>
  </section>
  <?php
  $text = get_post_meta(get_the_ID(), '_init-text', true);
  $img = get_post_meta(get_the_ID(), '_init-img', true);
  if ($text || $img) : ?>
    <section class="init-secondary">
      <div class="container row">
        <div class="col-md-6">
          <?= $text; ?>
        </div>
        <div class="col-md-6">
          <?= $img; ?>
        </div>
      </div>
    </section>
  <?php
  endif; ?>
  <section class="hero" style="background-image:url(<?= wp_get_attachment_image_src(get_post_meta(get_the_ID(), '_init-kpis-img', true), 'large')[0]; ?>)">
  </section>
  <section class="init-kpis">
    <?php
    $kpis = get_post_meta(get_the_ID(), '_init-kpis', true); ?>
    <div class="container row">
      <?php
      foreach ($kpis as $kpi) : ?>
        <div class="col-md-4 card-container">
          <div class="kpi orange">
            <?= $kpi; ?>
          </div>
        </div>
      <?php
      endforeach; ?>
      <div class="col-xs-12 center">
        <a href="#" class="btn">Register for OpenMike</a>
      </div>
    </div>
  </section>
  <section>
    <div class="container row">
      <div class="col-xs-12 center">
        <h2>Gallery</h2>
      </div>
    </div>
  </section>
  <section class="fp-news">
    <?php
    $news = get_posts(array('post_type'=>'news', 'post_status'=>'publish', 'posts_per_page'=>3)); ?>
    <div class="container row">
      <div class="col-xs-12 center">
        <h2>In The News</h2>
      </div>
      <?php
      foreach ($news as $article) : ?>
        <div class="col-md-4 card-container">
          <?= do_shortcode('[card]
            <p class="subhead">' . get_post_meta($article->ID, '_news-source', true) . '<br />' . get_the_date('', $article->ID) . '</p>
            <h3>' . $article->post_title . '</h3>
          [/card]'); ?>
        </div>
      <?php
      endforeach; ?>
      <div class="col-xs-12 center">
        <a href="#" class="btn">See All News</a>
      </div>
    </div>
  </section>
  <section>
    <div class="container row">
      <div class="col-xs-12 center">
        <h2>Partnerships</h2>
      </div>
      <?php
      $partners = get_post_meta(get_the_ID(), '_init_partners', true) ?: array();
      foreach ($partners as $partner) : ?>
        <div class="col-lg-3 col-md-6">
          <?= _sw_img($partner, 'standard', true); ?>
        </div>
      <?php
      endforeach; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
