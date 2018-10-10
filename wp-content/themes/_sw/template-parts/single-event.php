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
          <?php
          $location = get_post_meta(get_the_ID(), '_event-location', true);
          $tickets = get_post_meta(get_the_ID(), '_event-tickets', true);
          $lat = get_post_meta(get_the_ID(), '_event-lat', true);
          $lng = get_post_meta(get_the_ID(), '_event-lng', true);
          if ($location) : ?>
            <b>Location</b>
            <?php
            echo $location;
            if ($tickets) {
              echo '<a href="' . $tickets . '" class="btn">Get Tickets</a>';
            }
            if ($lat && $lng) : ?>
              <div class="google-map">
                <div class="map-canvas" zoom="7"></div>
                <input class="location" type="hidden" value="<?= $lat ?>~%~%~<?= $lng; ?>~%~%~<?= $location; ?>" />
              </div>
            <?php
            endif;
          endif; ?>
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
      $events = get_posts(array('post_type'=>'event', 'post_status'=>'publish', 'orderby'=>'meta_value_num', 'meta_key'=>'_event-sortable-start', 'posts_per_page'=>2));
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
