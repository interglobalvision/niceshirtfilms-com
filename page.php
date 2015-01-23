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

  <section id="page" class="<?php post_class(); ?>">

    <header class="page-header">
      <h1 id="page-title"><?php the_title(); ?></h1>
    </header>

    <article class="copy">

      <?php the_content(); ?>

    </article>

  </section>

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