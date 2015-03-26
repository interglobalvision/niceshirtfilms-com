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

/* news post type */
?>
      <article
<?php
if (!empty($meta['_igv_thumbbackground'][0])) {
  $thumbBackground = $meta['_igv_thumbbackground'][0];
} else {
  $thumbBackground = false;
}

post_class('home-post home-news');

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
            </div>
          </div>
        </div>

        <div class="post-copy">
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
