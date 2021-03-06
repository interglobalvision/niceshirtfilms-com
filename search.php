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

  <header class="page-header u-cf">

      <div class="col col2 colpad1left">

      <h3>Search results for '<?php echo $search_term ?>'</h3>

      </div>

  </header>

<?php
if( !$video_search->have_posts() && !$director_search->have_posts() && !have_posts() ) {
?>
  <section class="row u-cf error">
    <article class="col col2 colpad1left u-alert">
      <?php _e('Sorry, nothing matched your criteria'); ?>
    </article>
  </section>
<?php
}
?>

<?php
if( $video_search->have_posts() ) {
?>
  <section id="video-search" class="search-result-section row u-cf">
    <div class="col col1">
      <h4 class="search-results-label font-light-gray font-italic">Videos that matched title, brand, director, or matched a tag</h4>
    </div>
    <div class="col col2">
<?php
  while( $video_search->have_posts() ) {
    $video_search->the_post();
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb');
    $imgLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-large');
    $imgExtraLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-larger');
    $meta = get_post_meta($post->ID);
?>
    <a href="<?php echo get_video_permalink($post, $meta); ?>">
      <div <?php post_class('active director-archive-video col col3 u-pointer u-background-cover u-fixed-ratio js-lazy-background'); ?>
          data-vimeo-id="<?php if (!empty($meta['_vimeo_id_value'][0])) { echo $meta['_vimeo_id_value'][0];} ?>"
          data-video-ratio="<?php if (!empty($meta['_vimeo_ratio_value'][0])) { echo $meta['_vimeo_ratio_value'][0];} ?>"
          data-thumb="<?php echo $img[0]; ?>"
          data-thumb-large="<?php echo $imgLarge[0]; ?>"
          data-thumb-extra-large="<?php echo $imgExtraLarge[0]; ?>"
      >
        <div class="u-fixed-ratio-dummy"></div>
        <div class="u-fixed-ratio-content">
          <div class="u-holder">
            <div class="u-held">
              <h4><em><?php if (!empty($meta['_igv_title'][0])) { echo $meta['_igv_title'][0];} ?></em></h4>
              <h4><?php if (!empty($meta['_igv_brand'][0])) { echo $meta['_igv_brand'][0];} ?></h4>
            </div>
          </div>
        </div>
      </div>
    </a>
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
  <section id="director-search" class="search-result-section row u-cf">
    <div class="col col1">
      <h4 class="search-results-label search-results-label-fix font-light-gray font-italic">Directors that matched the criteria</h4>
    </div>
    <div class="col col2">
<?php
  while ( $director_search->have_posts() ) {
    $director_search->the_post();
    $matched_content = matched_content($post);
?>
      <div class="search-result copy">
<?php
    if ( $matched_content ) {
?>
        <a href="<?php the_permalink() ?>#biography">
          <h3><?php the_title(); ?></h3>
          <p><?php echo $matched_content; ?></p>
        </a>
<?php
    } else {
?>
        <a href="<?php the_permalink() ?>">
          <h3><?php the_title(); ?></h3>
          <?php the_excerpt(); ?>
        </a>
<?php
    }
?>
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
if( have_posts() ) {
?>
  <section id="search" class="search-result-section row u-cf">
    <div class="col col1">
      <h4 class="search-results-label search-results-label-fix font-light-gray font-italic">Posts or pages that matched the criteria</h4>
    </div>
    <div class="col col2">
<?php
  while( have_posts() ) {
    the_post();
    $meta = get_post_meta($post->ID);
?>

    <article class="search-result copy">
      <a href="<?php the_permalink() ?>">
        <h3><?php the_title(); ?></h3>
        <?php the_excerpt(); ?>
      </a>
    </article>

<?php
  }
?>
    </div>
  </section>
<?php
}
?>

<!-- end main-content -->
</main>

<?php
get_footer();
?>
