<?php

/* Get post objects for select field options */
function get_post_objects( $query_args ) {
  $args = wp_parse_args( $query_args, array(
      'posts_per_page' => -1,
    ) );
  $posts = get_posts( $args );
  $post_options = array(" " => null);
  if ( $posts ) {
    foreach ( $posts as $post ) {
      $post_options [ $post->ID ] = $post->post_title;
    }
  }
  return $post_options;
}


add_filter( 'cmb2_meta_boxes', 'cmb_sample_metaboxes' );
function cmb_sample_metaboxes( array $meta_boxes ) {

  if (isset($_GET['post'])) {
    $post_ID = $_GET['post'];
  } else {
    $post_ID = null;
  }

  $prefix = '_igv_';

  $director_args = array(
    'post_type' => 'director'
  );

  $showreel_args = array(
    'post_type'   => 'video',
    'meta_query' => array(
      array(
        'key' => '_igv_director',
        'value' => $post_ID,
        'type' => 'NUMERIC',
        'compare' => '='
      )
    )
  );

  $meta_boxes['posts_metabox'] = array(
    'id'         => 'post_metabox',
    'title'      => __( 'Post Options', 'cmb' ),
    'object_types'      => array( 'post' ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    'fields'     => array(
      array(
        'name' => 'Long title',
        'desc' => 'longer title that also allows formatting',
        'id' => $prefix . 'longtitle',
        'type' => 'wysiwyg'
      ),
      array(
        'name' => 'Read More post?',
        'desc' => 'show read more',
        'id' => $prefix . 'readmore',
        'type' => 'checkbox'
      ),
      array(
        'name' => 'Thumbnail background?',
        'desc' => 'shows thumbnail as title background',
        'id' => $prefix . 'thumbbackground',
        'type' => 'checkbox'
      ),
      array(
        'name' => 'Background Color Picker',
        'id'   => $prefix . 'color',
        'type' => 'colorpicker',
        'default'  => '#000000',
        'repeatable' => false,
      ),
      array(
        'name' => 'Assiciated Director',
        'desc' => 'link to director page?',
        'id'   => $prefix . 'director',
        'type' => 'colorpicker',
        'type'    => 'select',
        'options' => get_post_objects($director_args),
      ),
    )
  );

  $meta_boxes['director_metabox'] = array(
    'id'         => 'director_metabox',
    'title'      => __( 'Director', 'cmb' ),
    'object_types'      => array( 'video' ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    'fields'     => array(
      array(
        'name'    => __( 'Title', 'cmb' ),
        'desc'    => __( '', 'cmb' ),
        'id'      => $prefix . 'title',
        'type'    => 'text',
      ),
      array(
        'name'    => __( 'Brand', 'cmb' ),
        'desc'    => __( '', 'cmb' ),
        'id'      => $prefix . 'brand',
        'type'    => 'text',
      ),
      array(
        'name'    => __( 'Director', 'cmb' ),
        'desc'    => __( '', 'cmb' ),
        'id'      => $prefix . 'director',
        'type'    => 'select',
        'options' => get_post_objects($director_args),
      ),
    )
  );

  $meta_boxes['showreel_metabox'] = array(
    'id'         => 'showreel_metabox',
    'title'      => __( 'Showreel', 'cmb' ),
    'object_types'      => array( 'director', ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
    'fields'     => array(
      array(
        'name'    => __( '', 'cmb2' ),
        'desc'    => __( 'Drag videos from the left column to the right column to attach them to this page.<br />You may rearrange the order of the videos in the right column by dragging and dropping.', 'cmb2' ),
        'id'      => $prefix . 'showreel',
        'type'    => 'custom_attached_posts',
        'options' => array(
          'query_args' => $showreel_args, // override the get_posts args
          'show_thumbnails' => true
        ),
      )
    ),
  );

  $meta_boxes['person_metabox'] = array(
    'id'         => 'person_metabox',
    'title'      => __( 'Person Details', 'cmb' ),
    'object_types'      => array( 'people' ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    'fields'     => array(
      array(
        'name'    => __( 'Title', 'cmb' ),
        'desc'    => __( '', 'cmb' ),
        'id'      => $prefix . 'title',
        'type'    => 'text',
      ),
      array(
        'name'    => __( 'Phone number', 'cmb' ),
        'desc'    => __( '', 'cmb' ),
        'id'      => $prefix . 'phone',
        'type'    => 'text',
      ),
      array(
        'name'    => __( 'Email', 'cmb' ),
        'desc'    => __( '', 'cmb' ),
        'id'      => $prefix . 'email',
        'type'    => 'text_email',
      ),
      array(
        'name'    => __( 'Twitter', 'cmb' ),
        'desc'    => __( '', 'cmb' ),
        'id'      => $prefix . 'twitter',
        'type'    => 'text',
      ),      array(
        'name'    => __( 'Facebook', 'cmb' ),
        'desc'    => __( '', 'cmb' ),
        'id'      => $prefix . 'facebook',
        'type'    => 'text',
      ),
    )
  );

  return $meta_boxes;
}


add_action( 'cmb2_init', 'yourprefix_register_about_page_metabox' );
function yourprefix_register_about_page_metabox() {

  $prefix = '_igv_';

  $cmb_about_page = new_cmb2_box( array(
      'id'           => $prefix . 'metabox',
      'title'        => __( 'About Page Metabox', 'cmb2' ),
      'object_types' => array( 'page', ),
      'context'      => 'normal',
      'priority'     => 'high',
      'show_names'   => true,
      'show_on'      => array( 'id' => array( 2, 5 ) ),
    ) );

  $cmb_about_page->add_field( array(
      'name' => __( 'GIF', 'cmb2' ),
      'desc' => __( 'if set displays instead of all the shirts on the left', 'cmb2' ),
      'id'   => $prefix . 'gif',
      'type' => 'file',
    ) );
}


?>