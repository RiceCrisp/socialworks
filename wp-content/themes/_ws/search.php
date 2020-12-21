<?php
get_header(); ?>

<main id="main" class="main archive ajax" data-template="search">
  <section class="wp-block-ws-section padding-top-50 padding-bottom-50">
    <div class="block-background"></div>
    <div class="container section-container">
      <div class="section-inner">
        <div class="row">
          <div class="col-lg-10 col-xl-8 section-col">
            <h1><?= __('Search Results', '_ws'); ?></h1>
            <form class="wp-block-search archive-filters" role="search" method="get" action="/">
              <label for="wp-block-search__input" class="wp-block-search__label">Search</label>
              <input type="search" id="wp-block-search__input" class="wp-block-search__input" name="s" value="<?= $_GET['s'] ?? ''; ?>" placeholder="">
              <button type="submit" class="wp-block-search__button">Search</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="wp-block-ws-section padding-top-0 padding-bottom-50">
    <div class="block-background"></div>
    <div class="container">
      <div class="row archive-results" data-type="search">
      </div>
    </div>
  </section>
</main>

<?php
get_footer(); ?>
