<div class="pre-footer">
  <div class="container row">
    <div class="col-lg-3 col-md-2 relative">
      <img class="lazy-load left-hands" data-src="/wp-content/uploads/2018/09/hands.png" alt="Hands" />
    </div>
    <div class="col-lg-6 col-md-8 form">
      <h3>Stay Connected with SocialWorks</h3>
      <p>Get the latest news and events in your inbox.</p>
      <form>
        <input type="email" placeholder="Email Address" />
        <input type="submit" value="Sign Up" />
      </form>
    </div>
    <div class="col-lg-3 col-md-2 relative">
      <img class="lazy-load right-hands" data-src="/wp-content/uploads/2018/09/hands.png" alt="Hands" />
    </div>
  </div>
</div>
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
