<?php
// Register user manual menu
function _sw_user_manual_menu() {
  add_options_page('User Manual', 'User Manual', 'manage_options', 'user_manual', '_sw_user_manual');
}
add_action('admin_menu', '_sw_user_manual_menu');

// Create user manual page
function _sw_user_manual() {
  ?>
  <div class="wrap options-page user-manual">
    <form class=action="options.php" method="post">
      <h1>User Manual</h1>
      <section>
        <h2>301 Redirects</h2>
        <p>Under the "Settings" menu there is a tab for creating 301 redirects. If you have a writable .htacces file, you'll be able to managed any simple 301 redirects on this page.</p>
      </section>
      <section>
        <h2>Caching</h2>
        <p>Under the "Settings" menu there is a tab for setting browser caching limits, assuming you have write permissions to the .htaccess file. More information can be found there.</p>
      </section>
      <section>
        <h2>Featured Image</h2>
        <p>The featured image is primarily used as the banner of the page. This is the rule more often than the exception. We also provide alignment percentages that affect the <i>background-position</i> css property. For example:</p>
        <ul>
          <li><b>Center</b>: X = 50%, Y = 50%</li>
          <li><b>Top Left</b>: X = 0%, Y = 0%</li>
          <li><b>Bottom Right</b>: X = 100%, Y = 100%</li>
          <li><b>Top Center</b>: X = 50%, Y = 0%</li>
        </ul>
        <p>And so on. If left empty will default to the theme standard (usually center or top-center). Both fields need to be filled out for the values to have an effect.</p>
      </section>
      <section>
        <h2>Images</h2>
        <?php
        global $_wp_additional_image_sizes;
        $standard = $_wp_additional_image_sizes['standard']; ?>
        <p>Out of the box, wordpress creates four image sizes: thumbnail (150px x 150px max), medium (300px x 300px max), large (640px x 640px max), and full (unmodified). We also add a new size called "Standard", that is optimized for use in our grid system. It is <?= $standard['width']; ?>px x <?= $standard['height']; ?>px max, which is capable of taking up the full width on Extra Small devices.</p>
      </section>
      <section>
        <h2>Page Options</h2>
        <p>All pages have a Page Options box that provides options for how the page displays.</p>
        <ul>
          <li><b>Headline:</b> The headline is displayed in the banner at the top of the page. If left blank, defaults to the page title.</li>
          <li><b>Sub-Headline:</b> If you want subtext under the headline, fill out this text field.</li>
          <li><b>Make Page Title/Headline an H1:</b> If you want the headline (or page title if headline is left blank) to be an H1 tag, then tick this checkbox. Otherwise the headline is just a paragraph tag.</li>
        </ul>
      </section>
      <section>
        <h2>SEO (Search Engine Optimization)</h2>
        <p>All pages and posts have an SEO box that provides SEO options.</p>
        <ul>
          <li><b>Title:</b> Appears in the browser tab. If left blank, defaults to the page title.</li>
          <li><b>Description:</b> This text is placed in the head of the html in a meta tag. If left blank, defaults to the excerpt. If excerpt is empty, then pulls content from text editor.</li>
          <li><b>Keywords:</b> This text is placed in the head of the html in a meta tag. Separate keywords with commas.</li>
          <li><b>Advanced Options</b>
            <ul>
              <li><b>Canonical URL:</b> If the content of this page is a duplicate of another, then put that url in this field. Canonical sort of acts as a 301 redirect but just for search engines. If left blank, defaults to page url.</li>
              <li><b>NO INDEX:</b> Sets a flag that lets search engines know not to index this page.</li>
              <li><b>NO FOLLOW:</b> Sets a flag that lets search engines know not to follow any links on this page.</li>
              <li><b>Disallow Search:</b> This checkbox removes the page from internal search. This only applies to internal search; search engines will still index the page and will still link to it.</li>
            </ul>
          </li>
        </ul>
      </section>
      <section>
        <h2>Shortcodes</h2>
        <div>
          <p><b>Contact Form</b></p>
          <p>Creates a simple contact form that sends an email to a specified user.</p>
          <ul>
            <li>
              <p><b>to</b><br /><i>(string) (optional)</i> The email address to where you want the notification to be sent. Defaults to the site admin email.</p>
            </li>
          </ul>
          <pre>[form to="name@url.com"]</pre>
        </div>
        <hr />
        <div>
          <p><b>Responsive Video</b></p>
          <p>Creates a responsive video iframe.</p>
          <ul>
            <li>
              <p><b>url</b><br /><i>(string)</i> The source of your video file.</p>
            </li>
            <li>
              <p><b>type</b><br /><i>(string) (optional)</i> The type of video. Possible values could be <i>local, youtube, vimeo, </i>etc.</p>
            </li>
            <li>
              <p><b>fullscreen</b><br /><i>(boolean) (optional)</i> Allows the video to be expanded to fullscreen.</p>
            </li>
          </ul>
          <pre>[responsive_video url="https://www.youtube.com/embed/aqz-KE-bpKQ" type="youtube" fullscreen=true]</pre>
        </div>
        <hr />
        <div>
          <p><b>SVG</b></p>
          <p>Creates an svg (with browser support by svg4everybody) from a given symbol id. Also useful for svg's in the WordPress text editor as WordPress deletes svg's when switching between "Visual" and "Text".</p>
          <ul>
            <li>
              <p><b>id</b><br /><i>(string)</i> The id of the symbol from sprites.svg (_sw/template-parts/sprites.svg)</p>
            </li>
            <li>
              <p><b>class</b><br /><i>(string) (optional)</i> Any class names that you want to give the SVG.</p>
            </li>
          </ul>
          <pre>[svg id="twitter" class="one-class two-class"]</pre>
        </div>
        <hr />
        <div>
          <p><b>Social Icons</b></p>
          <p>Displays a list of social media links as svg icons. Defaults to all fields that are filled out in Settings->Site Options.</p>
          <ul>
            <li>
              <p><b>icons</b><br /><i>(string) (optional)</i> Comma separated list of icons that you want to display.</p>
            </li>
          </ul>
          <pre>[social_icons icons="twitter, linkedin"]</pre>
        </div>
        <hr />
        <div>
          <p><b>Google Maps</b></p>
          <p>Creates a Google Map Embed code for the location.</p>
          <ul>
            <li>
              <p><b>location</b><br /><i>(string) (required)</i> Location name for which to search. Consider using full address to reduce the chance of finding the incorrect location.</p>
            </li>
          </ul>
          <pre>[google_maps location="Walker Sands Communications, West Monroe Street, Chicago, IL, United States"]</pre>
        </div>
      </section>
      <section>
        <h2>SVG Manager</h2>
        <p>This tab under the "Settings" menu is for adding, editing, and deleting SVG icons/data for the site. More information can be found there.</p>
      </section>
      <section>
        <h2>Site Options</h2>
        <p>Under the "Settings" menu there is a tab for handling most general settings for the site. These include fields such as contact info for Google Structured Data and social links. Visit this page to see all options.</p>
      </section>
      <section>
        <h2>Sitemap</h2>
        <p>All pages and posts have a Sitemap box that provides options for how a page appears in the xml sitemap. The sitemap is used by search engines to more easily index all pages and determine their hierarchy.</p>
        <ul>
          <li><b>Change Frequency:</b> Suggest how often a search engine should crawl the page. If page is updated frequently then perhaps change it do <i>Daily</i> or <i>Weekly</i>. If the page is rarely updated then perhaps change the value to <i>Yearly</i>. Default is <i>Monthly</i>.</li>
          <li><b>Priority:</b> This is a value between 0.0 and 1.0 that let's the search engine know which page you deem most important for crawlers.</li>
          <li><b>Remove Page from Sitemap:</b> If you want this page to be removed from the sitemap, then tick this box.</li>
        </ul>
      </section>
      <section>
        <h2>Social</h2>
        <p>All pages and posts have a Social box that provides options for how a page appears when shared on social media. These options populate meta tags in the head of the page that most social media utilize.</p>
        <ul>
          <li><b>Title:</b> This would be the title of the shared post. If left blank, defaults to the SEO title. If the SEO title is blank, then it defaults to the page title.<br />Social Title > SEO Title > Page Title</li>
          <li><b>Description:</b> This would appear as the content/excerpt of the shared post. If left blank, defaults in a similar fashion to title.<br />Social Description > SEO Description > Excerpt > Text Editor</li>
          <li><b>Image:</b> This would be the social post's associated image. If left blank, defaults to the featured image.</li>
          <li><b>Twitter Username:</b> This twitter account is associated with any tweets sharing this page.</li>
        </ul>
      </section>
      <section>
        <h2>Tracking &amp; Analytics</h2>
        <p>Under the "Settings" menu there is a tab for handling Google Analytics and Remarketing codes as well as any third party scripts that you may want to insert into the header or footer of the site.</p>
      </section>
    </form>
  </div>
  <?php
}
