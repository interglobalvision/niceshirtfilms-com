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
?>

    <a href="<?php the_permalink() ?>">
      <article <?php post_class('basic-post'); ?> id="post-<?php the_ID(); ?>" data-background="<?php echo $img[0]; ?>" data-background-large="<?php echo $imgLarge[0]; ?>">

        <h2><?php the_title(); ?></h2>

  <!--       <?php the_content(); ?> -->

      </article>
    </a>

<?php
  }
} else {
?>
    <article class="u-alert"><?php _e('Sorry, no posts matched your criteria :{'); ?></article>
<?php
} ?>

  <!-- end posts -->
  </section>

<?php
if( get_next_posts_link() || get_previous_posts_link() ) {
?>
  <!-- post pagination -->
  <nav id="pagination">
<?php
$previous = get_previous_posts_link('Newer');
$next = get_next_posts_link('Older');
if ($previous) {
  echo $previous;
}
if ($previous && $next) {
  echo ' &mdash; ';
}
if ($next) {
  echo $next;
}
?>
  </nav>
<?php
}
?>

<!-- end main-content -->

</main>

<?php
get_footer();
?>