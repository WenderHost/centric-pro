<?php

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary navigation menu
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'centric' ) ) );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Reposition Page Title
add_action( 'genesis_before', 'centric_post_title' );
function centric_post_title() {

  if ( is_page() and !is_page_template() ) {
    remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    add_action( 'genesis_after_header', 'centric_open_post_title', 1 );
    add_action( 'genesis_after_header', 'genesis_do_post_title', 2 );
    add_action( 'genesis_after_header', 'centric_close_post_title', 3 );
  } elseif ( is_category() ) {
    remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
    add_action( 'genesis_after_header', 'centric_open_post_title', 1 ) ;
    add_action( 'genesis_after_header', 'genesis_do_taxonomy_title_description', 2 );
    add_action( 'genesis_after_header', 'centric_close_post_title', 3 );
  } elseif ( is_search() ) {
        remove_action( 'genesis_before_loop', 'genesis_do_search_title' );
        add_action( 'genesis_after_header', 'centric_open_post_title', 1 ) ;
        add_action( 'genesis_after_header', 'genesis_do_search_title', 2 );
        add_action( 'genesis_after_header', 'centric_close_post_title', 3 );
    }

}

function centric_body_classes( $classes ){
  global $post;
  if( isset( $post ) ){
    $classes[] = $post->post_type . '-' . $post->post_name;
  }
  return $classes;
}
add_filter( 'body_class', 'centric_body_classes' );

function centric_attributes_header( $attributes ){
  //global $post;
  //$post_type = get_post_type();
  //if( $post_type = 'epkb_post_type_1' || 'knowledge-base' == $post->post_name )
  $attributes['class'] = 'site-header';
  return $attributes;
}
add_filter( 'genesis_attr_site-header', 'centric_attributes_header' );

function centric_open_post_title() {
  echo '<div class="page-title"><div class="wrap">';
}

function centric_close_post_title() {
  echo '</div></div>';
}