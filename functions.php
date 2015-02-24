<?php
function scripts_and_styles_method() {

  $templateuri = get_template_directory_uri() . '/js/';

  $jslib = $templateuri . 'library.js';
  wp_enqueue_script( 'jslib', $jslib,'','',true);
  $myscripts = $templateuri . 'main.js';
  wp_enqueue_script( 'myscripts', $myscripts,'','',true);

  wp_enqueue_style( 'site', get_stylesheet_directory_uri() . '/css/site.css' );

  // dashicons for admin
  if(is_admin()){
    wp_enqueue_style( 'dashicons' );
  }

}
add_action('wp_enqueue_scripts', 'scripts_and_styles_method');

if( function_exists( 'add_theme_support' ) ) {
  add_theme_support( 'post-thumbnails' );
}

if( function_exists( 'add_image_size' ) ) {
  add_image_size( 'admin-thumb', 150, 150, false );
  add_image_size( 'admin-video-thumb', 150, 84, true );
  add_image_size( 'opengraph', 1200, 630, true );

  add_image_size( 'post-background', 700, 280, true );
  add_image_size( 'post-background-large', 1100, 440, true );

  add_image_size( 'grid-thumb', 250, 167, true );
  add_image_size( 'grid-thumb-large', 400, 267, true );
  add_image_size( 'grid-thumb-largest', 700, 467, true );

}

// Register Nav Menus
/*
register_nav_menus( array(
	'menu_location' => 'Location Name',
) );
*/

get_template_part( 'lib/gallery' );
get_template_part( 'lib/post-types' );
get_template_part( 'lib/meta-boxes' );

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
function cmb_initialize_cmb_meta_boxes() {
  // Add CMB2 plugin
  if( ! class_exists( 'cmb2_bootstrap_202' ) )
    require_once 'lib/CMB2/init.php';

  // Add CMB2 Attached Posts Field plugin
  if ( ! function_exists( 'cmb2_attached_posts_fields_render' ) )
    require_once 'lib/cmb2-attached-posts/cmb2-attached-posts-field.php';
}

// Disable that freaking admin bar
add_filter('show_admin_bar', '__return_false');

// Turn off version in meta
function no_generator() { return ''; }
add_filter( 'the_generator', 'no_generator' );

// Show thumbnails in admin lists
add_filter('manage_posts_columns', 'new_add_post_thumbnail_column');
function new_add_post_thumbnail_column($cols){
  $cols['new_post_thumb'] = __('Thumbnail');
  return $cols;
}
add_action('manage_posts_custom_column', 'new_display_post_thumbnail_column', 5, 2);
function new_display_post_thumbnail_column($col, $id){
  switch($col){
    case 'new_post_thumb':
    if( function_exists('the_post_thumbnail') ) {
      echo the_post_thumbnail( 'admin-thumb' );
      }
    else
    echo 'Not supported in theme';
    break;
  }
}

// remove automatic <a> links from images in blog
function wpb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	if($image_set !== 'none') {
		update_option('image_default_link_type', 'none');
	}
}
add_action('admin_init', 'wpb_imagelink_setup', 10);

// Exact match search
add_filter( 'posts_search', 'exact_match_search', 20, 2 );
function exact_match_search( $search, $wp_query ) {
  global $wpdb;

  if ( empty( $search ) )
    return $search;

  $q = $wp_query->query_vars;

  $search = $searchand = '';

  foreach ( (array) $q['search_terms'] as $term ) {
    $term = esc_sql( like_escape( $term ) );
    $search .= "{$searchand}($wpdb->posts.post_title REGEXP '[[:<:]]{$term}[[:>:]]') OR ($wpdb->posts.post_content REGEXP '[[:<:]]{$term}[[:>:]]')";
    $searchand = ' AND ';
  }

  if ( ! empty( $search ) ) {
    $search = " AND ({$search}) ";
    if ( ! is_user_logged_in() )
      $search .= " AND ($wpdb->posts.post_password = '') ";
  }

  return $search;
}

// UTILITY FUNCTIONS

// get ID of page by slug
function get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if($page) {
		return $page->ID;
	} else {
		return null;
	}
}
// is_single for custom post type
function is_single_type($type, $post) {
  if (get_post_type($post->ID) === $type) {
    return true;
  } else {
    return false;
  }
}

// ALTER QUERY

add_action('pre_get_posts','alter_query');

function alter_query($query) {
	global $wp_query;

  if ( !$query->is_main_query() ) {
		return;
  }

  if (is_home()) {
  	$query-> set('post_type' , array('post', 'video'));
  }

	remove_all_actions ( '__after_loop');
}

// Director name from ID

function echoDirectorName($id) {
  $director = get_post($id);
  if ($director) {
    echo $director->post_title;
  }
}

// Check if the posts' content matched the search
function matched_content( $post ) {
  global $wp_query;

  $search_terms = $wp_query->query_vars['search_terms'];
  foreach( $search_terms as $search_term ) {
    if( stripos( $post->title, $search_term  ) ) {
      return FALSE;
    }

    if( stripos( $post->post_content, $search_term  ) ) {
      return strsntnc( $post->post_content, $search_term );
    }
  }
  return FALSE;
}

// Find and return the sentence from the haystack containing the needle
function strsntnc( $haystack, $needle ) {
  $sentences = explode('.', $haystack);
  foreach( $sentences as $sentence ) {
    stripos( $sentence, $needle );
    if( stripos( $sentence, $needle ) )
      return $sentence . ".";
  }
}
