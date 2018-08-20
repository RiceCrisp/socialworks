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
</footer>

<?php wp_footer(); ?>

</body>
</html>
