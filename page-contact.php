<?php
get_header();
?>

<!-- main content -->

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
    $meta = get_post_meta($post->ID);
?>

  <article id="page" <?php post_class(); ?>>

    <section id="map">
      <div id="map-copy">
        <?php the_content(); ?>
      </div>
      <div id="map-canvas"></div>
    </section>

    <section class="row">

<?php
$people = get_posts('post_type=people&posts_per_page=-1&orderby=menu_order');

foreach ($people as $post) {
  $meta = get_post_meta($post->ID);
?>
      <div class="contact-person col">
        <h3><?php echo $post->post_title; ?></h3>
<?php

  if (!empty($meta['_igv_title'][0])) {
    echo $meta['_igv_title'][0];
    echo '<br/>';
  }

  if (!empty($meta['_igv_phone'][0])) {
    echo $meta['_igv_phone'][0];
    echo '<br/>';
  }

  if (!empty($meta['_igv_email'][0])) {
    echo '<a href="mailto:' . $meta['_igv_email'][0] . '">' . $meta['_igv_email'][0] . '</a>';
    echo '<br/>';
  }

  if (!empty($meta['_igv_twitter'][0])) {
    echo '<a target="_blank" href="' . $meta['_igv_twitter'][0] . '">' . url_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-twitter.svg') . '</a>';
  }

  if (!empty($meta['_igv_facebook'][0])) {
    echo '<a target="_blank" href="' . $meta['_igv_facebook'][0] . '">' . url_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-facebook.svg') . '</a>';
  }

?>
      </div>
<?php
}
?>

  </article>

<?php
  }
} else {
?>
  <section class="error">
    <article class="u-alert"><?php _e('Sorry, nothing matched your criteria :{'); ?></article>
  </section>
<?php
} ?>

<!-- end main-content -->

</main>

<?php
get_footer();
?>
