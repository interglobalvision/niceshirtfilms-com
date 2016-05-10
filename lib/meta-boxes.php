<?php
/* Get post objects for select field options */
function get_post_objects( $query_args ) {
  $args = wp_parse_args( $query_args, array(
    'post_type' => 'post',
  ) );
  $posts = get_posts( $args );
  $post_options = array();
  if ( $posts ) {
    foreach ( $posts as $post ) {
      $post_options [ $post->ID ] = $post->post_title;
    }
  }
  return $post_options;
}

/**
 * Hook in and add metaboxes. Can only happen on the 'cmb2_init' hook.
 */
add_action( 'cmb2_init', 'igv_cmb_metaboxes' );
function igv_cmb_metaboxes() {
  if (isset($_GET['post'])) {
    $post_ID = $_GET['post'];
  } else {
    $post_ID = null;
  }

  // Start with an underscore to hide fields from custom fields list
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

  // Post Metabox
  $posts_metabox = new_cmb2_box( array(
    'id'         => 'post_metabox',
    'title'      => __( 'Post Options', 'cmb' ),
    'object_types'      => array( 'post' ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
  ) );

  $posts_metabox -> add_field( array(
    'name' => 'Long title',
    'desc' => 'longer title that also allows formatting',
    'id' => $prefix . 'longtitle',
    'type' => 'wysiwyg'
  ) );
  $posts_metabox -> add_field( array(
    'name' => 'Read More post?',
    'desc' => 'show read more',
    'id' => $prefix . 'readmore',
    'type' => 'checkbox'
  ) );
  $posts_metabox -> add_field( array(
    'name' => 'Video Background WebM',
    'desc' => 'Video clip use for title background (WebM)',
    'id' => $prefix . 'videobackground_webm',
    'type' => 'file'
  ) );
  $posts_metabox -> add_field( array(
    'name' => 'Video Background MP4',
    'desc' => 'Video clip use for title background (mp4)',
    'id' => $prefix . 'videobackground_mp4',
    'type' => 'file'
  ) );
  $posts_metabox -> add_field( array(
    'name' => 'Thumbnail background?',
    'desc' => 'shows thumbnail as title background',
    'id' => $prefix . 'thumbbackground',
    'type' => 'checkbox'
  ) );
  $posts_metabox -> add_field( array(
    'name' => 'Background Color Picker',
    'id'   => $prefix . 'color',
    'type' => 'colorpicker',
    'default'  => '#000000',
    'repeatable' => false,
  ) );
  $posts_metabox -> add_field( array(
    'name' => 'Associated Director',
    'desc' => 'link to director page?',
    'id'   => $prefix . 'director',
    'type' => 'colorpicker',
    'type'    => 'select',
    'options' => get_post_objects($director_args),
  ) );

  // Director Metaboxes
  $director_metabox = new_cmb2_box( array( 
    'id'         => 'director_metabox',
    'title'      => __( 'Director', 'cmb' ),
    'object_types'      => array( 'video' ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
  ) );
  $director_metabox -> add_field( array(
    'name'    => __( 'Title', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'title',
    'type'    => 'text',
  ) );
  $director_metabox -> add_field( array(
    'name'    => __( 'Brand', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'brand',
    'type'    => 'text',
  ) );
  $director_metabox -> add_field( array(
    'name'    => __( 'Director', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'director',
    'type'    => 'select',
    'options' => get_post_objects($director_args),
  ) ) ;

  // Showreel Metaboxes
  $showreel_metabox = new_cmb2_box( array( 
    'id'         => 'showreel_metabox',
    'title'      => __( 'Showreel', 'cmb' ),
    'object_types'      => array( 'director', ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
  ) );

  $showreel_metabox -> add_field( array(
    'name'    => __( '', 'cmb2' ),
    'desc'    => __( 'Drag videos from the left column to the right column to attach them to this page.<br />You may rearrange the order of the videos in the right column by dragging and dropping.', 'cmb2' ),
    'id'      => $prefix . 'showreel',
    'type'    => 'custom_attached_posts',
    'options' => array(
      'query_args' => $showreel_args, // override the get_posts args
      'show_thumbnails' => true
    ),
  ) );

  // Stills Metaboxex
  $stills_metabox = new_cmb2_box( array( 
    'id'         => 'stills_metabox',
    'title'      => __( 'Stills', 'cmb' ),
    'object_types'      => array( 'director', ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
  ) );

  $stills_group_id = $stills_metabox -> add_field( array(
    'id'          => $prefix . 'stills',
    'type'        => 'group',
    'description' => __( 'Director Stills', 'cmb2' ),
    'options'     => array(
      'group_title'   => __( 'Still {#}', 'cmb2' ), // {#} gets replaced by row number
      'add_button'    => __( 'Add Another Still', 'cmb2' ),
      'remove_button' => __( 'Remove Still', 'cmb2' ),
      'sortable'      => true, // beta
      // 'closed'     => true, // true to have the groups closed by default
    ),
  ) );

  $stills_metabox->add_group_field( $stills_group_id, array(
    'name' => 'Still Image',
    'id'   => 'still_image',
    'type' => 'file',
    'options' => array(
      'url' => false, // Hide the text input for the url
    ),
  ) );



  // Person Metabox
  $person_metabox = new_cmb2_box( array(
    'id'         => 'person_metabox',
    'title'      => __( 'Person Details', 'cmb' ),
    'object_types'      => array( 'people' ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
  ) );

  $person_metabox -> add_field( array(
    'name'    => __( 'Title', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'title',
    'type'    => 'text',
  ) );
  $person_metabox -> add_field( array(
    'name'    => __( 'Phone number', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'phone',
    'type'    => 'text',
  ) );
  $person_metabox -> add_field( array(
    'name'    => __( 'Email', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'email',
    'type'    => 'text_email',
  ) );
  $person_metabox -> add_field( array(
    'name'    => __( 'Twitter', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'twitter',
    'type'    => 'text',
  ) );
  $person_metabox -> add_field( array(
    'name'    => __( 'Facebook', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'facebook',
    'type'    => 'text',
  ) );


  // About
  $about_page = get_page_by_title('About');

  $about_metabox = new_cmb2_box( array(
    'id'           => $prefix . 'metabox',
    'title'        => __( 'About Page Metabox', 'cmb2' ),
    'object_types' => array( 'page', ),
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true,
    'show_on'      => array( 'id' => array( $about_metabox->ID ) ),
  ) );

  $about_metabox->add_field( array(
    'name' => __( 'GIF', 'cmb2' ),
    'desc' => __( 'if set displays instead of all the shirts on the left', 'cmb2' ),
    'id'   => $prefix . 'gif',
    'type' => 'file',
  ) );
}

?>
