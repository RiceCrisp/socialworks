<?php
if (post_password_required()) {
  return;
} ?>

<section id="comments" class="comments-area">
  <div class="container row">
    <div class="col-xs-12">
      <?php
      if (have_comments()) : ?>
        <h3 class="comments-title">
          <?= get_comments_number() . ' Comments'; ?>
        </h3>
        <?php
        if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
          <nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', '_ws'); ?></h2>
            <div class="nav-links">
              <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', '_ws')); ?></div>
              <div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', '_ws')); ?></div>
            </div>
          </nav>
        <?php
        endif; ?>
        <ol class="comment-list">
          <?php
          wp_list_comments(array(
            'style' => 'ul',
            'short_ping' => true
          )); ?>
        </ol>
        <?php
        if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
          <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', '_ws'); ?></h2>
            <div class="nav-links">
              <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', '_ws')); ?></div>
              <div class="nav-next"><?php next_comments_link( esc_html__('Newer Comments', '_ws')); ?></div>
            </div>
          </nav>
        <?php
        endif;
      endif;

      // If comments are closed and there are comments, let's leave a little note, shall we?
      if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', '_ws'); ?></p>
      <?php
      endif;
      comment_form(); ?>
    </div>
  </div>
</section>
