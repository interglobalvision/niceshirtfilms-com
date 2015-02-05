<?php
get_header();
?>

<!-- main content -->
<?php echo $_GET['s']; ?>
<?php $search_term =  $_GET['s']; ?>

<main id="main-content">

<?php
  $video_search_default = new WP_Query( array (
    'fields' => 'ids',
    'post_type' => 'video',
    's' => $search_term,
  ) );

  $video_search_tag = new WP_Query( array (
    'fields' => 'ids',
    'post_type' => 'video',
    'tag' => $search_term,
  ) );

  $video_search_ids = array_merge( $video_search_default->posts, $video_search_tag->posts );

  $video_search =  new WP_Query(array(
    'post_type' => 'any',
    'post__in'  => $video_search_ids, 
    'orderby'   => 'date', 
    'order'     => 'DESC'
  ) );

  $director_search = new WP_Query( array (
    'post_type' => 'director',
    's' => $search_term
  ) );
?>

<?php
  if( $video_search->have_posts() ) { 
?>
  <section id="video-search" class="u-cf">
  <h2>Videos that matched in title, brand or director, OR matched with a tag.</h2>
<?php
  while( $video_search->have_posts() ) {
    $video_search->the_post();
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-large');
    $imgLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-largest');
    $meta = get_post_meta($post->ID);
?>
<div <?php post_class('director-showreel-video col col1 u-pointer u-background-cover u-fixed-ratio js-lazy-background js-load-vimeo'); ?>
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
  </section>
<?php
  }
?>

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
