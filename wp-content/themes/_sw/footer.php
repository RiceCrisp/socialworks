<div class="pre-footer">
  <div class="container row">
    <div class="col-lg-3 col-md-2 relative">
      <img class="lazy-load left-hands" data-src="/wp-content/uploads/2018/09/hands.png" alt="Hands" />
    </div>
    <div class="col-lg-6 col-md-8 form">
      <h3>Stay Connected with SocialWorks</h3>
      <p>Get the latest news and events in your inbox.</p>
      <!-- Begin Mailchimp Signup Form -->
      <div>
        <form action="https://socialworkschi.us19.list-manage.com/subscribe/post?u=c46c04c329c99060b3d59d4e0&amp;id=03262af15a" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
          <div id="mc_embed_signup_scroll" class="row">
            <div class="mc-field-group">
              <label for="mce-EMAIL" class="screen-reader-text">Email Address <span class="asterisk">*</span></label>
              <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email Address">
            </div>
            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_c46c04c329c99060b3d59d4e0_03262af15a" tabindex="-1" value=""></div>
            <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
            <div id="mce-responses" class="clear">
              <div class="response" id="mce-error-response" style="display:none"></div>
              <div class="response" id="mce-success-response" style="display:none"></div>
            </div>
          </div>
        </form>
      </div>
      <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
          <!-- <form>
            <input type="email" placeholder="Email Address" />
            <input type="submit" value="Sign Up" />
          </form> -->
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
