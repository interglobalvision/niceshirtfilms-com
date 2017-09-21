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
    $stills = get_post_meta( get_the_ID(), '_igv_stills', true );
?>

  <section id="director" <?php post_class(); ?>>

    <header id="director-header" class="page-header u-cf">

      <div class="col col1 colpad1left">

        <h3><?php the_title(); ?></h3>

      </div>

      <div class="col col1">

        <ul id="director-menu" class="font-size-smaller u-cf">
          <li id="director-showreel-play" class="director-menu-item director-menu-item-blue u-pointer"><a href="#play-all">Play All</a></li>
          <li id="director-showreel-label" class="director-menu-item director-menu-item-blue u-pointer"><a href="#">Showreel</a></li>
          <li id="director-menu-archive" class="director-menu-item u-pointer"><a href="#archive">Archive</a></li>
    <?php
    if (!empty($stills)) {
    ?>
          <li id="director-menu-stills" class="director-menu-item u-pointer"><a href="#stills">Stills</a></li>
    <?php
    }
    ?>
          <li id="director-menu-biography" class="director-menu-item u-pointer"><a href="#biography">Biography</a></li>
        </ul>

      </div>

    </header>

    <section id="vimeo-player"></section>

<?php
global $post;

if (!empty($meta['_igv_showreel'])) {
?>
    <section id="director-showreel" class="director-section u-cf">
<?php
  $showreelVideos = get_post_meta( get_the_ID(), '_igv_showreel', true );
  foreach($showreelVideos as $video) {
    $post = get_post($video);
    if(!empty($post)) {
      setup_postdata($post);
      $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-large');
      $imgLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-larger');
      $imgExtraLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-largest');
      $video_meta = get_post_meta($post->ID);
?>
      <div <?php post_class('director-showreel-video col col1 u-pointer u-background-cover u-fixed-ratio js-lazy-background js-load-overlay-vimeo'); ?>
        data-slug="<?php echo $post->post_name; ?>"
        data-vimeo-id="<?php if (!empty($video_meta['_vimeo_id_value'][0])) { echo $video_meta['_vimeo_id_value'][0];} ?>"
        data-video-ratio="<?php if (!empty($video_meta['_vimeo_ratio_value'][0])) { echo $video_meta['_vimeo_ratio_value'][0];} ?>"
        data-thumb="<?php echo $img[0]; ?>"
        data-thumb-large="<?php echo $imgLarge[0]; ?>"
        data-thumb-extra-large="<?php echo $imgExtraLarge[0]; ?>"
        data-director="<?php echo $directorName; ?>"
        data-title="<?php if (!empty($video_meta['_igv_title'][0])) { echo $video_meta['_igv_title'][0];} ?>"
        data-brand="<?php if (!empty($video_meta['_igv_brand'][0])) { echo $video_meta['_igv_brand'][0];} ?>"
      >
        <div class="u-fixed-ratio-dummy"></div>
        <div class="u-fixed-ratio-content">
          <div class="u-holder">
            <div class="u-held">
              <h3><em><?php if (!empty($video_meta['_igv_title'][0])) { echo $video_meta['_igv_title'][0];} ?></em></h3>
              <h3><?php if (!empty($video_meta['_igv_brand'][0])) { echo $video_meta['_igv_brand'][0];} ?></h3>
            </div>
          </div>
        </div>
      </div>
<?php
    }
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
  <div id="inline-player-bar" class="u-cf">
    <div class="col col1">
      <nav id="inline-player-previous" class="inline-player-nav u-pointer">
        <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-arrow-left.svg'); ?>
      </nav>
      <nav id="inline-player-next" class="inline-player-nav u-pointer">
        <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-arrow-right.svg'); ?>
      </nav>
    </div>
    <div class="col col2">
      <h3 id="inline-nowplaying">
        <span id="inline-brand"></span>
        <span id="inline-middot"> &middot; </span>
        <span id="inline-title" class="font-italic"></span>
      </h3>
    </div>
  </div>
<div class="u-cf">
    <div class="col col2 colpad1left">
      <ul id="director-archive-tags" class="font-size-smallest u-cf">
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
    $imgExtraLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-larger');
    $meta = get_post_meta($post->ID);
?>
      <a href="#<?php echo "video-" . $post->post_name; ?>">
        <div <?php post_class('director-archive-video u-pointer u-background-cover u-fixed-ratio js-lazy-background active'); ?>
          id="director-archive-video-<?php echo $post->post_name; ?>"
          data-vimeo-id="<?php if (!empty($meta['_vimeo_id_value'][0])) { echo $meta['_vimeo_id_value'][0];} ?>"
          data-video-ratio="<?php if (!empty($meta['_vimeo_ratio_value'][0])) { echo $meta['_vimeo_ratio_value'][0];} ?>"
          data-thumb="<?php echo $img[0]; ?>"
          data-thumb-large="<?php echo $imgLarge[0]; ?>"
          data-thumb-extra-large="<?php echo $imgExtraLarge[0]; ?>"
          data-title="<?php if (!empty($meta['_igv_title'][0])) { echo $meta['_igv_title'][0];} ?>"
          data-brand="<?php if (!empty($meta['_igv_brand'][0])) { echo $meta['_igv_brand'][0];} ?>"
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
  wp_reset_postdata();
?>
  </div>
<?php
}
?>
    </section>
<?php
if (!empty($stills)) {
?>
    <section id="director-stills" class="director-section u-cf">
      <div id="stills-container">
<?php
  foreach( $stills as $still_id ) {
    $still_image = wp_get_attachment_image($still_id, 'stills');
    $still_full_image = wp_get_attachment_image($still_id, 'full', null, array (
      'class' => 'full-still',
    ) );
    $caption = get_post_field('post_excerpt', $still_id);
?>
  <div class="still js-load-overlay-image u-pointer" data-caption="<?php echo $caption; ?>" data-director="<?php echo $directorName; ?>">
      <?php echo $still_image; ?>
      <?php echo $still_full_image; ?>
    </div>
<?php
  }
?>

      </div>
    </section>
<?php
}
?>

    <section id="director-biography" class="director-section u-cf">

      <div class="col col1 u-force-width">

        <?php the_post_thumbnail('width-500'); ?>

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
