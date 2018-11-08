<?php
/* Template Name: Initiative Page */

get_header(); ?>

<main id="main" template="initiative-page">
  <?php
  get_template_part('template-parts/banner');
  $tabs = get_post_meta(get_the_ID(), '_init-tabs', true);
  if ($tabs) : ?>
    <div class="tabs-nav-container">
      <div class="container-fluid row no-padding">
        <ul class="col-xs-12 tabs-nav no-padding">
          <li>
            <?php
            $t = get_post_meta(get_the_ID(), '_init-title', true); ?>
            <a href="#<?= sanitize_title($t); ?>"><?= $t; ?></a>
          </li>
          <?php
          foreach ($tabs as $tab) : ?>
            <li>
              <a href="#<?= sanitize_title($tab['title']); ?>"><?= $tab['title']; ?></a>
            </li>
          <?php
          endforeach; ?>
        </ul>
      </div>
    </div>
    <ul class="tabs">
      <li idz="<?= sanitize_title($t); ?>">
        <section class="init-main">
          <div class="container row">
            <div class="col-md-4 main-img">
              <?php
              $main_img = get_post_meta(get_the_ID(), '_init-side', true) ?>
              <img class="lazy-load" data-src="<?= wp_get_attachment_image_src($main_img, 'standard')[0]; ?>" alt="<?= get_post_meta($main_img, '_wp_attachment_image_alt', true); ?>" />
            </div>
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
              <div class="col-xl-7col-lg-8 col-md-7">
                <?= $text; ?>
              </div>
              <div class="col-xl-offset-1 col-lg-4 col-md-5 col-sm-10 col-sm-offset-1">
                <img class="lazy-load" data-src="<?= wp_get_attachment_image_src($img, 'standard')[0]; ?>" alt="<?= get_post_meta($img, '_wp_attachment_image_alt', true); ?>" />
              </div>
            </div>
          </section>
        <?php
        endif; ?>
      </li>
      <?php
      foreach ($tabs as $tab) : ?>
        <li idz="<?= sanitize_title($tab['title']); ?>">
          <section>
            <div class="container row">
              <div class="col-xs-12">
                <?= $tab['content']; ?>
              </div>
            </div>
          </section>
        </li>
      <?php
      endforeach; ?>
    </ul>
  <?php
  else: ?>
    <section class="init-main">
      <div class="container row">
        <div class="col-md-4 main-img">
          <?= _sw_img(get_post_meta(get_the_ID(), '_init-side', true), 'standard', true); ?>
        </div>
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
          <div class="col-xl-7col-lg-8 col-md-7">
            <?= $text; ?>
          </div>
          <div class="col-xl-offset-1 col-lg-4 col-md-5 col-sm-10 col-sm-offset-1">
            <?= _sw_img($img, 'standard', true); ?>
          </div>
        </div>
      </section>
    <?php
    endif;
  endif; ?>
  <section class="hero" style="background-image:url(<?= wp_get_attachment_image_src(get_post_meta(get_the_ID(), '_init-kpis-img', true), 'large')[0]; ?>)">
    <?php
    $video = get_post_meta(get_the_ID(), '_init-kpis-video', true);
    if ($video) : ?>
      <div class="center">
        <a href="<?= $video; ?>" class="lightbox-link"><?= do_shortcode('[svg id="play"]'); ?></a>
      </div>
    <?php
    endif; ?>
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
      endforeach;
      $btn_text = get_post_meta(get_the_ID(), '_init-kpis-btn-text', true);
      $btn_url = get_post_meta(get_the_ID(), '_init-kpis-btn-url', true);
      if ($btn_text && $btn_url) : ?>
        <div class="col-xs-12 center">
          <a href="<?= $btn_url; ?>" class="btn"><?= $btn_text; ?></a>
        </div>
      <?php
      endif; ?>
    </div>
  </section>
  <section class="pb-gallery init-gallery">
    <div class="container">
      <div class="row">
        <?php
        $gallerys = get_post_meta(get_the_ID(), '_init-gallery', true);
        foreach ($gallerys as $gallery) : ?>
          <div class="col-md-3 col-xs-6 center">
            <?= _sw_img($gallery, 'standard', true); ?>
          </div>
        <?php
        endforeach; ?>
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
          <?= do_shortcode('[card img="' . $article->ID . '"]
            <p class="subhead">' . get_post_meta($article->ID, '_news-source', true) . '<br />' . get_the_date('', $article->ID) . '</p>
            <h3>' . $article->post_title . '</h3>
          [/card]'); ?>
        </div>
      <?php
      endforeach; ?>
      <div class="col-xs-12 center">
        <a href="/news/" class="btn">See All News</a>
      </div>
    </div>
  </section>
  <section class="init-partners">
    <div class="container row">
      <div class="col-xs-12 center">
        <h2>Partnerships</h2>
      </div>
      <?php
      $partners = get_post_meta(get_the_ID(), '_init-partners', true) ?: array();
      foreach ($partners as $partner) : ?>
        <div class="col-lg-4 col-md-6 center">
          <?= _sw_img($partner, 'standard', true); ?>
        </div>
      <?php
      endforeach; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
