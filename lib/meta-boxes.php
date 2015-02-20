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



/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */
add_action( 'cmb2_init', 'cmb_sample_metaboxes' );

function cmb_sample_metaboxes() {

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
		'post_type'			=> 'video',
	    'meta_query' => array(
			array(
				'key' => '_igv_director',
				'value' => $post_ID,
				'type' => 'NUMERIC',
				'compare' => '='
			)
		)
  );

  // Add metabox: Post Options
	$posts_metabox = new_cmb2_box( array(
		'id'         => 'post_metabox',
		'title'      => __( 'Post Options', 'cmb' ),
		'object_types'      => array( 'post' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
  ));
  
  // Add field: Long Title
  $posts_metabox->add_field( array(
    'name' => 'Long title',
    'desc' => 'longer title that also allows formatting',
    'id' => $prefix . 'longtitle',
    'type' => 'wysiwyg'
  ) );
  
  // Add field: Read More?
  $posts_metabox->add_field( array(
    'name' => 'Read More post?',
    'desc' => 'show read more',
    'id' => $prefix . 'readmore',
    'type' => 'checkbox'
  ) );
  
  // Add field: Thumbnail Background
  $posts_metabox->add_field( array(
    'name' => 'Thumbnail background?',
    'desc' => 'shows thumbnail as title background',
    'id' => $prefix . 'thumbbackground',
    'type' => 'checkbox'
  ) );
  
  // Add field: Background Color
  $posts_metabox->add_field( array(
    'name' => 'Background Color Picker',
    'id'   => $prefix . 'color',
    'type' => 'colorpicker',
    'default'  => '#000000',
    'repeatable' => false,
  ) );

  // Add metabox: Director
	$director_metabox = new_cmb2_box( array(
		'id'         => 'director_metabox',
		'title'      => __( 'Director', 'cmb' ),
		'object_types'      => array( 'video' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
  ) );
  
  // Add field: Title
  $director_metabox->add_field( array(
    'name'    => __( 'Title', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'title',
    'type'    => 'text',
  ) );
  
  // Add field: Brand
  $director_metabox->add_field( array(
    'name'    => __( 'Brand', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'brand',
    'type'    => 'text',
  ) );
  
  // Add field: Director
  $director_metabox->add_field( array(
    'name'    => __( 'Director', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => $prefix . 'director',
    'type'    => 'select',
    'options' => get_post_objects($director_args),
  ) );

  // Add metabox: Showreel
  $showreel_1_metabox = new_cmb2_box( array(
    'id'         => 'showreel_1_metabox',
    'title'      => __( 'Showreel 1', 'cmb' ),
    'object_types'      => array( 'director', ), // Post type
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
  ) );

  // Add field: Group Showreel
  $showreel_1_metabox->add_field( array(
    'id'          => $prefix . 'showreel_1',
    'type'        => 'group',
    'description' => __( '', 'cmb' ),
    'options'     => array(
      'group_title'   => __( 'Video {#}', 'cmb' ), // {#} gets replaced by row number
      'add_button'    => __( 'Add Another Video', 'cmb' ),
      'remove_button' => __( 'Remove Video', 'cmb' ),
      'sortable'      => true, // beta
    )
  ) );
  
  // Add field: Video
  $showreel_1_metabox->add_field( array(
    'name'    => __( 'Video', 'cmb' ),
    'desc'    => __( '', 'cmb' ),
    'id'      => 'video',
    'type'    => 'select',
    'options' => get_post_objects($showreel_args),
  ) );
}
?>
