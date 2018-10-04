<article id="post-<?php the_ID(); ?>" <?php post_class('event-single'); ?>>
  <?php
  get_template_part('template-parts/banner'); ?>
  <section>
    <div class="container row">
      <div class="col-lg-8">
        <div class="text">
          <?php
          the_content(); ?>
          <hr />
          <?= do_shortcode('[share_buttons buttons="facebook, twitter"]'); ?>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="info">
          <h3>Event Details</h3>
          <p>
            <b>Date &amp; Time</b>
            <br />
            <?php
            $date = get_post_meta(get_the_ID(), '_event-start-date', true);
            echo date('l, F jS', strtotime($date)); ?>
            <br />
            <?= get_post_meta(get_the_ID(), '_event-start-time', true); ?>
          </p>
          <b>Location</b>
          <?= get_post_meta(get_the_ID(), '_event-location', true); ?>
          <?php
          $tickets = get_post_meta(get_the_ID(), '_event-tickets', true);
          if ($tickets) {
            echo '<a href="' . $tickets . '" class="btn">Get Tickets</a>';
          } ?>
          <div class="google-map">
            <div class="map-canvas" zoom="7"></div>
            <input class="location" type="hidden" value="<?= get_post_meta(get_the_ID(), '_event-lat', true); ?>~%~%~<?= get_post_meta(get_the_ID(), '_event-lng', true); ?>~%~%~<?= get_post_meta(get_the_ID(), '_event-location', true); ?>" />
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="orange">
    <div class="container row">
      <div class="col-xs-12 center">
        <h2>Upcoming Events</h2>
      </div>
      <?php
      $events = get_posts(array('post_type'=>'event', 'post_status'=>'publish', 'posts_per_page'=>2));
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
    </div>
  </section>
</article>
