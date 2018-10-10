<div class="col-lg-4 col-md-6 card-container">
  <?= do_shortcode('[card class="link-container" img="' . get_the_ID() . '"]
    <p class="subhead">' . date("F d, Y", strtotime(get_post_meta(get_the_ID(), '_event-start-date', true))) . '</p>
    <a href="' . get_permalink(get_the_ID()) . '"><h3>' . get_the_title() . '</h3></a>
    <p>' . _sw_excerpt(get_the_ID()) . '</p>
  [/card]'); ?>
</div>
