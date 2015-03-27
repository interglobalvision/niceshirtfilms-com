<?php
get_header();
?>

<!-- main content -->

<main id="main-content">

  <!-- main posts loop -->
  <section id="posts">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'post-background');
    $imgLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'post-background-large');
    $imgExtraLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'post-background-extra-large');
    $meta = get_post_meta($post->ID);

    if (is_single_type('video', $post)) {
/* video post type */
?>
    <article
      <?php post_class('home-post home-video'); ?>
      id="post-<?php the_ID(); ?>"
      data-vimeo-id="<?php if (!empty($meta['_vimeo_id_value'][0])) { echo $meta['_vimeo_id_value'][0];} ?>"
      data-video-ratio="<?php if (!empty($meta['_vimeo_ratio_value'][0])) { echo $meta['_vimeo_ratio_value'][0];} ?>"
    >
      <div class="post-main u-pointer js-lazy-background" data-thumb="<?php echo $img[0]; ?>" data-thumb-large="<?php echo $imgLarge[0]; ?>" data-thumb-extra-large="<?php echo $imgExtraLarge[0]; ?>">

        <div class="u-holder">
          <div class="u-held">

            <div class="post-header">
              <h2>
<?php
if (!empty($meta['_igv_director'][0])) {
  echo echoDirectorName($meta['_igv_director'][0]);
}
?>
              </h2>
              <h3>
<?php
if (!empty($meta['_igv_title'][0])) {
  echo '<em>' . $meta['_igv_title'][0] . '</em>';
}
if (!empty($meta['_igv_title'][0]) && !empty($meta['_igv_brand'][0])) {
  echo ' &middot; ';
}
if (!empty($meta['_igv_brand'][0])) {
  echo $meta['_igv_brand'][0];
}
?>
              </h3>
            </div>

            <div class="post-header-hover">
              <img src="<?php bloginfo('stylesheet_directory'); ?>/img/optimized/ns-play.svg" alt="PLAY"/>
            </div>

          </div>
        </div>

        <div class="home-video-player">

        </div>

      </div>

      <div class="post-copy">
        <nav class="post-copy-close u-pointer">
          <?php echo file_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-close.svg'); ?>
        </nav>

        <div class="post-copy-container">
          <div class="copy u-marginauto">
            <div class="u-align-center">
             <h3 class="video-post-title">
<?php
if (!empty($meta['_igv_director'][0])) {
  echo echoDirectorName($meta['_igv_director'][0]);
}
?>
             </h3>
             <h4 class="video-post-subtitle">
<?php
if (!empty($meta['_igv_title'][0])) {
  echo '<em>' . $meta['_igv_title'][0] . '</em>';
}
if (!empty($meta['_igv_brand'][0])) {
  echo ' &middot; ' . $meta['_igv_brand'][0];
}
?>
              </h4>
            </div>

            <?php the_content(); ?>

            <p class="u-align-center">
              <a class="js-ajax-director" href="<?php echo get_the_permalink($meta['_igv_director'][0]) ?>">
                <?php echo file_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-director.svg'); ?>
              </a>
            </p>

          </div>
        </div>

      </div>

    </article>
<?php
    } else {
/* news post type */
?>
      <article
<?php
if (!empty($meta['_igv_readmore'][0])) {
  $readMore = $meta['_igv_readmore'][0];
} else {
  $readMore = false;
}
if (!empty($meta['_igv_thumbbackground'][0])) {
  $thumbBackground = $meta['_igv_thumbbackground'][0];
} else {
  $thumbBackground = false;
}

if ($readMore) {
  post_class('home-post home-news news-read-more');
} else {
  post_class('home-post home-news');
}
?> id="post-<?php the_ID(); ?>">
<?php
if ($thumbBackground) {
  $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'post-background');
  $imgLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'post-background-large');
?>
        <div class="post-main u-pointer js-lazy-background" data-thumb="<?php echo $img[0]; ?>" data-thumb-large="<?php echo $imgLarge[0]; ?>" data-thumb-extra-large="<?php echo $imgExtraLarge[0]; ?>" style="background-color: <?php if (!empty($meta['_igv_color'][0])) { echo $meta['_igv_color'][0];} ?>">
<?php
} else {
?>
        <div class="post-main u-pointer" style="background-color: <?php if (!empty($meta['_igv_color'][0])) { echo $meta['_igv_color'][0];} ?>">
<?php
}
?>
          <div class="u-holder">
            <div class="u-held">
              <div class="post-header">
                <h3>
                  <?php
if (!empty($meta['_igv_longtitle'][0])) {
  echo $meta['_igv_longtitle'][0];
} else {
  the_title();
}
                  ?>
                </h3>
              </div>
              <div class="post-header-hover">
                <img src="<?php bloginfo('stylesheet_directory'); ?>/img/optimized/ns-readmore.svg" alt="READ MORE"/>
              </div>
            </div>
          </div>
        </div>

        <div class="post-copy">
          <nav class="post-copy-close u-pointer">
            <?php echo file_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-close.svg'); ?>
          </nav>
          <div class="post-copy-container">
            <div class="copy u-marginauto">
              <div class="u-align-center">
                <h4 class="basic-post-title">
                  <?php the_time('j F Y'); ?>
                </h4>
              </div>
<?php
the_content();

if (!empty($meta['_igv_director'][0])) {
?>

              <p class="u-align-center">
                <a class="js-ajax-director" href="<?php echo get_the_permalink($meta['_igv_director'][0]) ?>">
                  <?php echo file_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-director.svg'); ?>
                </a>
              </p>
<?php
}
?>
            </div>
          </div>
        </div>

      </article>
<?php
    }
  }
} else {
?>
    <article class="u-alert"><?php _e('Sorry, no posts matched your criteria :{'); ?></article>
<?php
} ?>

  <!-- end posts -->
  </section>

  <?php get_template_part('partials/pagination'); ?>

<!-- end main-content -->

</main>

<?php
get_footer();
?>
