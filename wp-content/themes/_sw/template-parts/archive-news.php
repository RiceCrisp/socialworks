<div class="col-lg-4 col-md-6 card-container">
  <?= do_shortcode('[card class="link-container" img="' . get_the_ID() . '"]
    <p class="subhead">' . get_post_meta(get_the_ID(), '_news-source', true) . '<br />' . get_the_date('', get_the_ID()) . '</p>
    <a href="' . get_permalink(get_the_ID()) . '"><h3>' . get_the_title() . '</h3></a>
  [/card]'); ?>
</div>
