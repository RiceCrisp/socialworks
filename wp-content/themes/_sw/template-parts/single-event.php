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
          <p>
            <b>Location</b>
            <?= get_post_meta(get_the_ID(), '_event-location', true); ?>
          </p>
          <a href="#" class="btn">Get Tickets</a>
          <div class="google-map">
            <div class="map-canvas"></div>
            <div class="location">

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="orange">
    <div class="container row">
      <div class="col-md-6">

      </div>
      <div class="col-md-6">

      </div>
    </div>
  </section>
</article>
