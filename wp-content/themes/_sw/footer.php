<footer id="site-footer">
  <?php
  if (is_active_sidebar('footer')) : ?>
    <div class="footer-widgets-area">
      <div class="container">
        <ul class="row footer-widgets">
          <?php dynamic_sidebar('footer'); ?>
        </ul>
      </div>
    </div>
  <?php
  endif; ?>
  <div class="sub-footer">
    <div class="container row">
      <div class="col-md-4">
        <?= do_shortcode('[social_icons]'); ?>
      </div>
      <div class="col-md-8 copyright">
        <small>Copyright &copy; <?= date('Y'); ?>. All rights reserved. Walker Sands Communications</small>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
