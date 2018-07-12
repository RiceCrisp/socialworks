<?php
// Register dev manual menu
function _sw_dev_manual_menu() {
	add_options_page('Dev Manual', 'Dev Manual', 'manage_options', 'dev_manual', '_sw_dev_manual');
}
add_action('admin_menu', '_sw_dev_manual_menu');

// Create dev manual page
function _sw_dev_manual() { ?>
	<div class="wrap options-page dev-manual">
		<form class=action="options.php" method="post">
			<h1>Dev Manual</h1>
      <section>
        <h2>Button</h2>
        <p>To style a link like a button add the class <i>btn</i> or <i>button</i>.</p>
        <pre>&lt;a class="btn" href="#"&gt;Link&lt;/a&gt;</pre>
      </section>
      <section>
        <h2>Grid</h2>
        <p>The theme utilizes a flexbox-based grid system. All naming conventions are standard, so the grid should easily be replaced with a float-based system, if legacy support is required.</p>
        <pre>&lt;div class="container row"&gt;
  &lt;div class="col-xl-4 col-xl-offset-4 col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12"&gt;
    Content
  &lt;/div&gt;
&lt;/div&gt;</pre>
      </section>
      <section>
        <h2>Google Maps API</h2>
        <p>Although there is a Google Maps shortcode for handling simple, single location maps, at some point you may need to include a map with multiple locations. If you create a div element with the class <i>google-map</i>, then all you need to do is include a div with the class <i>map-canvas</i> and list all locations as hidden input elements with the class <i>location</i>. The value of each input will need to be a string with latitude, longitude, permalink, title, and address, each delimited by the string "~%~%~". You will also need to make the Google Maps API script dependent on our wp-js script (and removing async or defer attributes), if it isn't already. This can be accomplished by uncommented/modifying the relevent code in the functions.php file.</p>
        <pre>&lt;div class="google-map"&gt;
  &lt;div class="map-canvas">&lt;/div&gt;
  &lt;input class="location" type="hidden" value="&lt;?= $lat1 . '~%~%~' . $lng1 . '~%~%~' . $link1 . '~%~%~' . $title1 . '~%~%~' . $location1; ?&gt;" /&gt;
  &lt;input class="location" type="hidden" value="&lt;?= $lat2 . '~%~%~' . $lng2 . '~%~%~' . $link2 . '~%~%~' . $title2 . '~%~%~' . $location2; ?&gt;" /&gt;
&lt;/div&gt;</pre>
      </section>
      <section>
        <h2>Infinite Scroll</h2>
        <p>If you want to make a section have infinite scroll, add the class <i>infinite-scroll</i> to the parent element. The javascript also looks for the class <i>archive-paged</i> (to confirm that there is more than one page of results). All posts also exist as paged content (better for SEO).</p>
        <p>If you want the infinite scroll to use a button instead of scroll, add the class <i>infinite-scroll-btn</i> to the parent element. Like <i>infinite-scroll</i>, the javascript also looks for the class <i>archive-paged</i> and all content is paged as well.</p>
        <p>If you're using a custom wp_query, put a hidden input with the class "loop-var" within the infinite scroll container and use php to print the loop value with some json encoding and html filtering.</p>
        <pre>&lt;div class="infinite-scroll"&gt;
  &lt;input class="loop-var" type="hidden" value="&lt;?= htmlentities(json_encode($loop)); ?&gt;" /&gt;
  This is where the posts would go.
&lt;/div&gt;</pre>
      </section>
      <section>
        <h2>Lazy Load</h2>
        <p>If you want an image to lazy load, just give it the class <i>lazy-load</i> and rename any src attributes to <i>data-src</i>. Can also be used for background images. Only works if the src is removed from the element.</p>
        <pre>&lt;img class="lazy-load" data-src="http://via.placeholder.com/500x500" /&gt;
&lt;div class="lazy-load" data-src="http://via.placeholder.com/500x500"&gt;&lt;/div&gt;</pre>
      </section>
      <section>
        <h2>Lightbox</h2>
        <p>To make a link open in a lightbox, just add the class <i>lightbox-link</i> to an anchor element. Currently only works for embeded video.</p>
        <pre>&lt;a class="lightbox-link" target="_blank" href="https://www.youtube.com/embed/aqz-KE-bpKQ"&gt;Link&lt;/a&gt;</pre>
      </section>
      <section>
        <h2>Media Selector</h2>
        <p>To include a media selector, you need a button and text input element. Add the class <i>media-selector</i>, and the the attribute <i>target</i> to the button, and then set the <i>target</i> attribute to the css selector of the text input. You can also add a <i>size</i> attribute to use a specific size of an image.
        <pre>&lt;div class="row"&gt;
  &lt;button class="button media-selector" target="#my-input" size="thumbnail"&gt;Select Image&lt;/button&gt;
  &lt;input id="my-input" class="flex-1" name="my-input" type="text" value="&lt;?= $my_input; &gt;" /&gt;
&lt;/div&gt;</pre>
      </section>
      <section>
        <h2>Post Selector</h2>
        <p>The post selector is an interface for choosing a specific post. Useful for Call to Action or Related Post areas. To implement just give an input the class <i>post-selector</i> (along with an id and name). If you want to restrict to the selections to a certain post type, add the attribute <i>post-type</i> and give it the slug value of the desired post type. The post id is the only value saved.</p>
        <pre>&lt;input id="my-id" name="my-id" class="post-selector" post-type="testimonial" value="&lt;?= $my_id; ?&gt;" /&gt;</pre>
      </section>
      <section>
        <h2>Slider (Carousel)</h2>
        <p>We use the "Tiny Slider" javascript library to create sliders. Out of the box, you just need a container element with the class <i>slider-container</i> and a child element with the class <i>slider</i>. The minimum amout of styling is included, so you'll need to add css/js to match whatever functionality you need. Check out the github page for more details <a href="https://github.com/ganlanyuan/tiny-slider" target="_blank">https://github.com/ganlanyuan/tiny-slider</a></p>
        <pre>&lt;div class="slider-container"&gt;
  &lt;div class="slider"&gt;
    &lt;div&gt;
      &lt;p&gt;Slide 1&lt;/p&gt;
    &lt;/div&gt;
    &lt;div&gt;
      &lt;p&gt;Slide 2&lt;/p&gt;
    &lt;/div&gt;
    &lt;div&gt;
      &lt;p&gt;Slide 3&lt;/p&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/div&gt;</pre>
      </section>
      <section>
        <h2>Sortable Items</h2>
        <p>Any items that are sortable will need to change their unique attributes whenever the order is changed. If you add the class <i>sortable-item</i> to the elements that you want to be sortable and the class <i>sortable-container</i> to the immediate containing element, these attributes (id, name, for, etc.) will automatically refresh themselves on start and reorder.</p>
        <p>To reorder the sortable items via drag-and-drop functionality, just the place an element with the class <i>sortable-handle</i> within each sortable item. To make the items deletable, do the same thing, but give it the class <i>sortable-delete</i>.</p>
        <pre>&lt;ul class="sortable-container"&gt;
  &lt;li class="sortable-item"&gt;
    &lt;div class="sortable-header"&gt;
      &lt;span class="dashicons dashicons-sort sortable-handle"&gt;&lt;/span&gt;
      &lt;span class="dashicons dashicons-trash sortable-delete"&gt;&lt;/span&gt;
    &lt;/div&gt;
    &lt;div class="sortable-content"&gt;
      &lt;label for="unique-00"&gt;Label&lt;/label&gt;
      &lt;input id="unique-00" name="unique[00]" /&gt;
    &lt;/div&gt;
  &lt;/li&gt;
  &lt;li class="sortable-item"&gt;
    &lt;div class="sortable-header"&gt;
      &lt;span class="dashicons dashicons-sort sortable-handle"&gt;&lt;/span&gt;
      &lt;span class="dashicons dashicons-trash sortable-delete"&gt;&lt;/span&gt;
    &lt;/div&gt;
    &lt;div class="sortable-content"&gt;
      &lt;label for="unique-00"&gt;Label&lt;/label&gt;
      &lt;input id="unique-00" name="unique[00]" /&gt;
    &lt;/div&gt;
  &lt;/li&gt;
&lt;/ul&gt;</pre>
        <p>You can also use the javascript function <i>checkOrder</i> to manually refresh these items. This is useful if you've implemented functionality for adding new items. Just run the function <i>checkOrder</i> after any items are added or removed.</p>
        <pre>function addNewItem() {
  jQuery('.sortable-container').append('
    &lt;li class="sortable-item"&gt;
      &lt;div class="sortable-header"&gt;
        &lt;span class="dashicons dashicons-sort sortable-handle"&gt;&lt;/span&gt;
        &lt;span class="dashicons dashicons-trash sortable-delete"&gt;&lt;/span&gt;
      &lt;/div&gt;
      &lt;div class="sortable-content"&gt;
        &lt;label for="unique-00"&gt;Label&lt;/label&gt;
        &lt;input id="unique-00" name="unique[00]" /&gt;
      &lt;/div&gt;
    &lt;/li&gt;
  ');
  checkOrder();
}</pre>
        <p><b>IMPORTANT: </b>Although we automatically sort everything on render, if at some point a sortable item shares the same id as another, their values will be duplicated/erased. To avoid this when sorting we give the array index a tilde, making it unique to other values, and then removing the tilde once everything is sorted. Therefore, when first constructing sortable items, try to give everything it's correct index with PHP. When adding a new element make the array index double zero (00) making it unique until it is automatically sorted on render.</p>
      </section>
		</form>
	</div>
	<?php
}
