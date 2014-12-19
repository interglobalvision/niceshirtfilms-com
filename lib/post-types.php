<?php
// Menu icons for Custom Post Types
function add_menu_icons_styles(){
?>
 
<style>
#adminmenu .menu-icon-director div.wp-menu-image:before {
    content: '\f498';
}
#adminmenu .menu-icon-video div.wp-menu-image:before {
    content: '\f498';
}
</style>
 
<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );


//Register Custom Post Types
add_action( 'init', 'register_cpt_director' );

function register_cpt_director() {

    $labels = array( 
        'name' => _x( 'Directors', 'director' ),
        'singular_name' => _x( 'Director', 'director' ),
        'add_new' => _x( 'Add New', 'director' ),
        'add_new_item' => _x( 'Add New Director', 'director' ),
        'edit_item' => _x( 'Edit Director', 'director' ),
        'new_item' => _x( 'New Director', 'director' ),
        'view_item' => _x( 'View Director', 'director' ),
        'search_items' => _x( 'Search Directors', 'director' ),
        'not_found' => _x( 'No Directors found', 'director' ),
        'not_found_in_trash' => _x( 'No Directors found in Trash', 'director' ),
        'parent_item_colon' => _x( 'Parent Director:', 'director' ),
        'menu_name' => _x( 'Directors', 'director' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'director', $args );
}



add_action( 'init', 'register_cpt_video' );

function register_cpt_video() {

    $labels = array( 
        'name' => _x( 'Videos', 'video' ),
        'singular_name' => _x( 'Video', 'video' ),
        'add_new' => _x( 'Add New', 'video' ),
        'add_new_item' => _x( 'Add New Video', 'video' ),
        'edit_item' => _x( 'Edit Video', 'video' ),
        'new_item' => _x( 'New Video', 'video' ),
        'view_item' => _x( 'View Video', 'video' ),
        'search_items' => _x( 'Search Videos', 'video' ),
        'not_found' => _x( 'No Videos found', 'video' ),
        'not_found_in_trash' => _x( 'No Videos found in Trash', 'video' ),
        'parent_item_colon' => _x( 'Parent Video:', 'video' ),
        'menu_name' => _x( 'Videos', 'video' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'video', $args );
}


