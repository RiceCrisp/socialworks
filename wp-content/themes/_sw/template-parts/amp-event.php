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
            if ($lat && $lng) {
              echo do_shortcode('[google_map location="' . get_post_meta(get_the_ID(), '_event-location', true) . '"]');
            }
          endif; ?>
        </div>
      </div>
    </div>
  </section>
</article>
