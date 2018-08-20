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
          <?= do_shortcode('[card]
            <h3>' . $init->post_title . '</h3>
            <p>' . _sw_excerpt($init->ID) . '</p>
            <a href="' . get_permalink($init->ID) . '" class="btn">Learn More</a>
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
          <?= do_shortcode('[card]
            <p class="subhead">' . date("F d, Y", strtotime(get_post_meta($event->ID, '_event-start-date', true))) . '</p>
            <h3>' . $event->post_title . '</h3>
            <p>' . _sw_excerpt($event->ID) . '</p>
          [/card]'); ?>
        </div>
      <?php
      endforeach; ?>
      <div class="col-xs-12 center">
        <a href="#" class="btn">All Events</a>
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
      <div class="col-xl-8 col-xl-offset-2 col-lg-10 col-lg-offset-1 center">
        <h2>Support CPS</h2>
        <p>For every 100K donated, SocialWorks will donate 10K to a school of our choosing. Your donations have helped provide children with the resources they deserve.</p>
        <a href="#" class="btn">Donate Now</a>
        <a href="#" class="btn-alt">Learn More</a>
      </div>
    </div>
  </section>
  <section class="map orange">
    <div class="row">
      <div class="col-md-4 kpi">
        <p><span class="big">22</span><br />Schools Affected</p>
        <hr />
        <p><span class="big">$220K</span><br />Money Donated</p>
      </div>
      <div class="col-md-8">
        <div class="google-map">
          <div class="map-canvas"></div>
          <input class="location" type="hidden" value="" />
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
  <?php
  // get_template_part('template-parts/banner');
  // if (have_posts()) : while (have_posts()) : the_post();
  //   get_template_part('template-parts/content', get_post_type());
  // endwhile; endif; ?>
</main>

<?php get_footer(); ?>
