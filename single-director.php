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

  <section id="director" <?php post_class(); ?>>

    <header id="director-header" class="page-header u-cf">
      <div class="header-left">
        <h1 id="page-title"><?php the_title(); ?></h1>
      </div>
      <div class="header-right">
        <a id="director-bio-toggle" class="u-pointer">
          BIOGRAPHY
        </a>
      </div>
    </header>

    <section id="director-biography">
      <div class="copy">
        <?php the_content(); ?>
      </div>
    </section>

    <section id="vimeo-player"></section>

    <section id="director-showreel">

    </section>

    <section id="director-archive">
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

  <ul id="director-archive-tags">
    <li class="filter-tag u-pointer highlight" data-tag-slug="all">All</li>
<?php
$posttags = get_tags();
if ($posttags) {
  foreach($posttags as $tag) {
    echo '<li class="filter-tag u-pointer" data-tag-slug="' . $tag->slug . '">' . $tag->name . '</li>';
  }
}
?>
  </ul>

<?php
  foreach($archive as $post) {
    setup_postdata($post);
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb');
    $imgLarge = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-thumb-large');
    $meta = get_post_meta($post->ID);
?>
      <div <?php post_class('director-archive-video u-pointer js-load-vimeo active'); ?> data-vimeo-id="<?php if (!empty($meta['_vimeo_id_value'][0])) { echo $meta['_vimeo_id_value'][0];} ?>" data-video-ratio="<?php if (!empty($meta['_vimeo_ratio_value'][0])) { echo $meta['_vimeo_ratio_value'][0];} ?>">
        <img src="" data-thumb="<?php echo $img[0]; ?>" data-thumb-large="<?php echo $imgLarge[0]; ?>" class="lazy-thumb" alt="<?php the_title(); ?>" />
        <div class="u-holder">
          <div class="u-held">
            <p><em><?php if (!empty($meta['_igv_title'][0])) { echo $meta['_igv_title'][0];} ?></em></p>
            <p><?php if (!empty($meta['_igv_brand'][0])) { echo $meta['_igv_brand'][0];} ?></p>
          </div>
        </div>
      </div>
<?php
  }
}
?>
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