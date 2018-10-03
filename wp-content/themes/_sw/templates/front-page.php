<?php
/* Template Name: Front Page */

get_header(); ?>

<main id="main" template="front-page">
  <?php
  get_template_part('template-parts/banner'); ?>
  <section>
    <div class="container row">
      <?php
      $inits = get_posts(array(
        'post_type' => 'page',
        'post_parent' => get_page_by_title('Initiatives')->ID,
        'post_status' => 'publish',
        'posts_per_page' => -1
      ));
      foreach ($inits as $init) : ?>
        <div class="col-md-6 card-container">
          <?php
          echo do_shortcode('[card img="' . $init->ID . '"]
            <h3>' . $init->post_title . '</h3>
            <p>' . _sw_excerpt($init->ID) . '</p>
            <div>
              <a href="' . get_permalink($init->ID) . '" class="btn">Learn More</a>
            </div>
          [/card]'); ?>
        </div>
      <?php
      endforeach; ?>
    </div>
  </section>
  <section class="hero" style="background-image:url(<?= get_post_meta(get_the_ID(), '_fp-event-bg', true); ?>)">
  </section>
  <section class="fp-events">
    <?php
    $events = get_posts(array('post_type'=>'event', 'post_status'=>'publish', 'posts_per_page'=>2)); ?>
    <div class="container row">
      <div class="col-xs-12 center">
        <h2>SocialWorks Events</h2>
      </div>
      <?php
      foreach ($events as $event) : ?>
        <div class="col-md-6 card-container">
          <?= do_shortcode('[card class="link-container" img="' . $event->ID . '"]
            <p class="subhead">' . date("F d, Y", strtotime(get_post_meta($event->ID, '_event-start-date', true))) . '</p>
            <a href="' . get_permalink($event->ID) . '"><h3>' . $event->post_title . '</h3></a>
            <p>' . _sw_excerpt($event->ID) . '</p>
          [/card]'); ?>
        </div>
      <?php
      endforeach; ?>
      <div class="col-xs-12 center">
        <a href="/events/" class="btn">All Events</a>
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
          <?= do_shortcode('[card class="link-container" img="' . $article->ID . '"]
            <p class="subhead">' . get_post_meta($article->ID, '_news-source', true) . '<br />' . get_the_date('', $article->ID) . '</p>
            <a href="' . get_permalink($article->ID) . '"><h3>' . $article->post_title . '</h3></a>
          [/card]'); ?>
        </div>
      <?php
      endforeach; ?>
      <div class="col-xs-12 center">
        <a href="/news/" class="btn">See All News</a>
      </div>
    </div>
  </section>
  <?php
  $cta_1 = get_post_meta(get_the_ID(), '_fp-cta-1', true);
  if ($cta_1) : ?>
    <section>
      <div class="container row">
        <div class="col-xl-8 col-xl-offset-2 col-lg-10 col-lg-offset-1 center">
          <?= $cta_1; ?>
        </div>
      </div>
    </section>
  <?php
  endif; ?>
  <section class="fp-map orange">
    <div class="row no-padding">
      <div class="col-md-4 kpi">
        <p><span class="big">22</span><br />Schools Affected</p>
        <hr />
        <p><span class="big">$220K</span><br />Money Donated</p>
      </div>
      <div class="col-md-8 no-padding">
        <div class="google-map">
          <div class="map-canvas"></div>
          <?php
          $locs = get_post_meta(get_the_ID(), '_fp-locations', true);
          foreach ($locs as $loc) : ?>
            <input class="location" type="hidden" value="<?= $loc['lat']; ?>~%~%~<?= $loc['lng']; ?>~%~%~<?= $loc['address']; ?>" />
          <?php
          endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="container row">
      <div class="col-xs-12 center">
        <h2>@socialworks_chi</h2>
        <?= do_shortcode('[social_icons]'); ?>
      </div>
      <?php
      $imgs = get_post_meta(get_the_ID(), '_fp-social-img', true) ?: array();
      foreach ($imgs as $img) : ?>
        <div class="col-lg-3 col-md-6">
          <?= _sw_img($img, 'standard', true); ?>
        </div>
      <?php
      endforeach; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
