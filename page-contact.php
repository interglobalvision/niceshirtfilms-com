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

  <article id="page" class="<?php post_class(); ?>">

    <section id="map">
      <div id="map-copy">
        <?php the_content(); ?>
      </div>
      <div id="map-canvas"></div>
    </section>

    <section>

<?php
$people = get_posts('post_type=people&posts_per_page=-1&orderby=menu_order');
foreach ($people as $post) {
  $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'col1');
  $meta = get_post_meta($post->ID);
?>
      <div class="contact-person row u-cf">
        <div class="col col1 u-align-right">
          <img src="<?php echo $img[0]; ?>" />
        </div>

        <div class="col col2">
<?php
  echo $post->post_title;
  echo '<br/>';

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
    echo '<a href="' . $meta['_igv_twitter'][0] . '">' . $meta['_igv_twitter'][0] . '</a>';
  }

  if (!empty($meta['_igv_facebook'][0])) {
    echo '<a href="' . $meta['_igv_facebook'][0] . '">' . $meta['_igv_facebook'][0] . '</a>';
  }

?>
        </div>
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