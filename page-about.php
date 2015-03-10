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

    <header class="page-header u-cf">

      <div class="col col1 colpad1left">

        <h3><?php the_title(); ?></h3>

      </div>

    </header>

    <section class="row cf">

      <div class="col col1 cf">

<?php
$directors = get_posts('post_type=director&posts_per_page=-1&orderby=rand');
foreach ($directors as $post) {
  $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'col1');
?>
  <div class="about-page-director">
    <a href="<?php echo get_the_permalink($post->ID); ?>">
      <img src="<?php echo $img[0]; ?>" />
    </a>
  </div>
<?php
}
?>

      </div>

      <div class="col col2">

        <div class="copy">

          <?php the_content(); ?>

        </div>

      </div>

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