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
    $meta = get_post_meta($post->ID);

    if (is_single_type('video', $post)) {
/* video post type */
?>
    <a href="<?php echo get_the_permalink($meta['_igv_director'][0]) ?>#<?php if (!empty($meta['_vimeo_id_value'][0])) { echo $meta['_vimeo_id_value'][0];} ?>">
      <article <?php post_class('basic-post'); ?> id="post-<?php the_ID(); ?>" data-background="<?php echo $img[0]; ?>" data-background-large="<?php echo $imgLarge[0]; ?>">
        <div class="u-holder">
          <div class="u-held">
            <div class="basic-post-content">
              <h2 class="basic-post-title">
<?php
if (!empty($meta['_igv_director'][0])) {
  echo echoDirectorName($meta['_igv_director'][0]);
}
?>
              </h2>
              <h3 class="basic-post-subtitle">
<?php
if (!empty($meta['_igv_title'][0])) {
  echo '<em>' . $meta['_igv_title'][0] . '</em>';
}
if (!empty($meta['_igv_brand'][0])) {
  echo ' &middot; ' . $meta['_igv_brand'][0];
}
?>
              </h3>
            </div>
            <div class="basic-post-hover">
              PLAY
            </div>
          </div>
        </div>
      </article>
    </a>
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
if ($readMore) {
  post_class('news-post news-read-more');
} else {
  post_class('news-post');
}
?> id="post-<?php the_ID(); ?>">
        <div class="news-post-header u-pointer" style="background-color: <?php if (!empty($meta['_igv_color'][0])) { echo $meta['_igv_color'][0];} ?>">
          <div class="u-holder">
            <div class="u-held">
              <div class="news-header">
                <h2 class="news-title">
                  <?php the_title(); ?>
                </h2>
              </div>
              <div class="news-hover">
                READ MORE
              </div>
            </div>
          </div>
        </div>
        <div class="news-copy">
          <div class="copy u-marginauto">
            <?php the_content(); ?>
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