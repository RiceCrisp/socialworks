<header class="page-header" <?= get_query_var('amp') ? '' : 'style="' . _sw_thumbnail_background() . '"'; ?>>
  <?php
  $video = get_post_meta(get_the_ID(), '_banner-video', true);
  if ($video) : ?>
    <div class="video-container">
      <video autoplay muted loop>
        <source src="<?= $video; ?>" type="video/mp4">
      </video>
    </div>
  <?php
  endif; ?>
  <div class="container row">
    <div class="col-xs-12">
      <?php
      // Headline
      $tag = get_post_meta(get_the_ID(), '_banner-headline-type', true) ?: 'h1';
      if ($headline = get_post_meta(get_the_ID(), '_banner-headline', true)) {
        echo '<' . $tag . ' class="page-title">' . $headline . '</' . $tag . '>';
      } else {
        if (is_home()) {
          echo '<' . $tag . ' class="page-title">' . get_the_title(get_option('page_for_posts', true)) . '</' . $tag . '>';
        } else if (is_search()) {
          if (have_posts()) {
            echo '<h1 class="page-title">Search Results: ' . get_search_query() . '</h1>';
          } else {
            echo '<h1 class="page-title">Nothing Found</h1>';
          }
        } else if (is_404()) {
          echo '<h1 class="page-title">Page Not Found</h1>';
        } else if (is_archive()) {
          if (is_tax()) {
            echo '<h1 class="page-title">' . single_term_title('', false) . '</h1>';
          } else {
            echo '<h1 class="page-title">' . get_post_type_object($post->post_type)->labels->name . '</h1>';
          }
        } else {
          echo '<' . $tag . ' class="page-title">' . get_the_title() . '</' . $tag . '>';
        }
      }
      // Subheadline
      if ($subheadline = get_post_meta(get_the_ID(), '_banner-subheadline', true)) {
        echo '<div class="page-subtitle">' . $subheadline . '</div>';
      } ?>
    </div>
  </div>
</header>
