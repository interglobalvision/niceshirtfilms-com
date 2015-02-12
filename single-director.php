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
    $directorName = get_the_title();
?>

  <section id="director" <?php post_class(); ?>>

    <header id="director-header" class="page-header u-cf">

      <div class="col col1 colpad1left">

        <h2><?php the_title(); ?></h2>

      </div>

      <div class="col col1">

        <ul id="director-menu">
          <li id="director-showreel-playall" data-target="playall" class="director-menu-item director-menu-item-blue u-pointer">Play All</li>
          <li data-target="showreel" class="director-menu-item director-menu-item-blue active u-pointer">Back to reel</li>
          <li data-target="archive" class="director-menu-item u-pointer">Archive</li>
          <li data-target="biography" class="director-menu-item u-pointer">Biography</li>
        </ul>

      </div>

    </header>

    <section id="vimeo-player"></section>

<?php
global $post;

if (!empty($meta['_igv_showreel_1'][0])) {
?>
    <section id="director-showreel" class="director-section u-cf">
<?php
  $showreelVideos = get_post_meta( get_the_ID(), '_igv_showreel_1', true );
  foreach($showreelVideos as $video) {
    $post = get_post($video['video']);
    setup_postdata($post);
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-large');
    $imgLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-largest');
    $meta = get_post_meta($post->ID);
?>
      <div <?php post_class('director-showreel-video col col1 u-pointer u-background-cover u-fixed-ratio js-lazy-background js-load-overlay-vimeo'); ?>
        data-vimeo-id="<?php if (!empty($meta['_vimeo_id_value'][0])) { echo $meta['_vimeo_id_value'][0];} ?>"
        data-video-ratio="<?php if (!empty($meta['_vimeo_ratio_value'][0])) { echo $meta['_vimeo_ratio_value'][0];} ?>"
        data-thumb="<?php echo $img[0]; ?>"
        data-thumb-large="<?php echo $imgLarge[0]; ?>"
        data-director="<?php echo $directorName; ?>"
        data-title="<?php if (!empty($meta['_igv_title'][0])) { echo $meta['_igv_title'][0];} ?>"
        data-brand="<?php if (!empty($meta['_igv_brand'][0])) { echo $meta['_igv_brand'][0];} ?>"
      >
        <div class="u-fixed-ratio-dummy"></div>
        <div class="u-fixed-ratio-content">
          <div class="u-holder">
            <div class="u-held">
              <h2><em><?php if (!empty($meta['_igv_title'][0])) { echo $meta['_igv_title'][0];} ?></em></h2>
              <h3><?php if (!empty($meta['_igv_brand'][0])) { echo $meta['_igv_brand'][0];} ?></h3>
            </div>
          </div>
        </div>
      </div>
<?php
  }
  wp_reset_postdata();
?>
    </section>
<?php
}
?>

    <section id="director-archive" class="director-section">
<?php
$archive = get_posts(array(
	'post_type'       => 'video',
	'posts_per_page'	=> -1,
  'meta_query'      => array(
		array(
			'key' => '_igv_director',
			'value' => $post->ID,
			'type' => 'NUMERIC',
			'compare' => '='
		)
  )
));
if ($archive) {
?>
  <div class="u-cf">
    <div class="col2 colpad1left">
      <ul id="director-archive-tags">
        <li class="filter-tag filter-tag-active u-pointer" data-tag-slug="all">All</li>
<?php
$posttags = get_tags();
if ($posttags) {
  foreach($posttags as $tag) {
    echo '<li class="filter-tag u-pointer" data-tag-slug="' . $tag->slug . '">' . $tag->name . '</li>';
  }
}
?>
      </ul>
    </div>
  </div>
  <div class="u-cf">
<?php
  foreach($archive as $post) {
    setup_postdata($post);
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb');
    $imgLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-large');
    $meta = get_post_meta($post->ID);
?>
      <div <?php post_class('director-archive-video u-pointer u-background-cover u-fixed-ratio js-lazy-background js-load-inline-vimeo active'); ?>
        data-vimeo-id="<?php if (!empty($meta['_vimeo_id_value'][0])) { echo $meta['_vimeo_id_value'][0];} ?>"
        data-video-ratio="<?php if (!empty($meta['_vimeo_ratio_value'][0])) { echo $meta['_vimeo_ratio_value'][0];} ?>"
        data-thumb="<?php echo $img[0]; ?>"
        data-thumb-large="<?php echo $imgLarge[0]; ?>"
      >
        <div class="u-fixed-ratio-dummy"></div>
        <div class="u-fixed-ratio-content">
          <div class="u-holder">
            <div class="u-held">
              <h3><em><?php if (!empty($meta['_igv_title'][0])) { echo $meta['_igv_title'][0];} ?></em></h3>
              <h4><?php if (!empty($meta['_igv_brand'][0])) { echo $meta['_igv_brand'][0];} ?></h4>
            </div>
          </div>
        </div>
      </div>
<?php
  }
  wp_reset_postdata();
?>
  </div>
<?php
}
?>
    </section>

    <section id="director-biography" class="director-section u-cf">

      <div class="col col1">

        <?php the_post_thumbnail(); ?>

      </div>

      <div class="col col2">

        <div class="copy">
          <?php the_content(); ?>
        </div>

      </div>

    </section>

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