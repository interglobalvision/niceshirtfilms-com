<?php
get_header();

/*
 * Search queries
 */
$search_term =  $_GET['s']; 

// Request video search by 's' (default)
$video_search_default = new WP_Query( array (
  'fields' => 'ids',
  'post_type' => 'video',
  's' => $search_term,
) );

// Request video search by 'tag'
$video_search_tag = new WP_Query( array (
  'fields' => 'ids',
  'post_type' => 'video',
  'tag' => $search_term,
) );

// If any of the video searches have posts
if( $video_search_default->have_posts() || $video_search_tag->have_posts() ) {

  // Merge IDs
  $video_search_ids = array_merge( $video_search_default->posts, $video_search_tag->posts );

  //  Request video search query by IDs
  $video_search =  new WP_Query(array(
    'post_type' => 'video',
    'post__in'  => $video_search_ids, 
    'orderby'   => 'date', 
    'order'     => 'DESC'
  ) );

} else {

  // Blank query
  $video_search =  new WP_Query();

}

// Request directors search by 's' (default)
$director_search = new WP_Query( array (
  'post_type' => 'director',
  's' => $search_term
) );

// Alter main search query to only search on posts and pages
query_posts( array(
  'post_type' => array( 'post', 'page' ),
  's' => $search_term
) ); 
?>

<!-- main content -->
<main id="main-content">
  <h1>Search results for: <?php echo $search_term ?></h1>
<?php
if( !$video_search->have_posts() && !$director_search->have_posts() && !have_posts() ) { 
?>
  <section class="error">
    <article class="u-alert"><?php _e('Sorry, nothing matched your criteria :{'); ?></article>
  </section>
<?php
}
?>

<?php
if( $video_search->have_posts() ) { 
?>
  <section id="video-search">
  <h2>Videos that matched in title, brand or director, OR matched with a tag.</h2>
  <div class="u-cf">
  <?php
  while( $video_search->have_posts() ) {
    $video_search->the_post();
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-large');
    $imgLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-largest');
    $meta = get_post_meta($post->ID);
  ?>
    <div <?php post_class('active director-archive-video col col3 u-pointer u-background-cover u-fixed-ratio js-lazy-background js-load-vimeo'); ?>
        data-vimeo-id="<?php if (!empty($meta['_vimeo_id_value'][0])) { echo $meta['_vimeo_id_value'][0];} ?>"
        data-video-ratio="<?php if (!empty($meta['_vimeo_ratio_value'][0])) { echo $meta['_vimeo_ratio_value'][0];} ?>"
        data-thumb="<?php echo $img[0]; ?>"
        data-thumb-large="<?php echo $imgLarge[0]; ?>"
      >
        <div class="u-fixed-ratio-dummy"></div>
        <div class="u-fixed-ratio-content">
          <div class="u-holder">
            <div class="u-held">
              <p><em><?php if (!empty($meta['_igv_title'][0])) { echo $meta['_igv_title'][0];} ?></em></p>
              <p><?php if (!empty($meta['_igv_brand'][0])) { echo $meta['_igv_brand'][0];} ?></p>
            </div>
          </div>
        </div>
      </div>
  <?php
  }
  ?>
    </div>
  </section>
<?php
}
?>

<?php
if( $director_search->have_posts() ) { 
?>
  <section id="director-search" class="u-cf">
    <h2>Directors that matched the critera</h2>
<?php 
  while( $director_search->have_posts() ) {
    $director_search->the_post();
  ?>

      <a href="<?php the_permalink() ?>"><h3 id="page-title"><?php the_title(); ?></h3></a>
  <?php } ?>
  </section>
<?php 
  }
?>

<?php
if( have_posts() ) {
?>
  <section id="search" class="<?php post_class(); ?>">
  <h2>Posts or pages that matched the criteria</h2>
  <?php
  while( have_posts() ) {
    the_post();
    $meta = get_post_meta($post->ID);
  ?>

    <article class="copy">
      <a href="<?php the_permalink() ?>"><h3 id="page-title"><?php the_title(); ?></h3></a>
    </article>

  <?php
  }
  ?>
  </section>
<?php
} 
?>

<!-- end main-content -->
</main>

<?php
get_footer();
?>
