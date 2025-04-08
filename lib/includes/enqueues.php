<?php

//* Enqueue Scripts
remove_action( 'genesis_meta', 'genesis_load_stylesheet' ); // Don't load style.css

function centric_load_scripts() {
  global $post;
  $body_classes = get_body_class();

  $post_id = ( isset( $post ) && $post instanceof WP_Post )? $post->ID : null ;
  $is_elementor_page = ( in_array('elementor-page elementor-page-' . $post_id, $body_classes ) )? true : false ;

  wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,700|Spinnaker', array(), CHILD_THEME_VERSION );
  wp_enqueue_script( 'centric-global', get_bloginfo( 'stylesheet_directory' ) . '/js/global.js', array( 'jquery' ), filemtime( get_stylesheet_directory( '/js/global.js' ) ) , true );

  if( $is_elementor_page ){
    wp_enqueue_style( 'centric-elementor', get_bloginfo( 'stylesheet_directory' ) . '/lib/css/elementor.css', array( 'dashicons' ), filemtime( get_stylesheet_directory( '/lib/css/elementor.css' ) ) );
  } else {
    wp_enqueue_style( 'centric-pro', get_bloginfo( 'stylesheet_directory' ) . '/lib/css/main.css', array( 'dashicons' ), filemtime( get_stylesheet_directory( '/lib/css/main.css' ) ) );
  }

}
add_action( 'wp_enqueue_scripts', 'centric_load_scripts', 20 );

add_action('wp_head',function(){
  echo "\n".'<script type=\'text/javascript\' src=\'//platform-api.sharethis.com/js/sharethis.js#property=5bec5f75e08e62001b81a88f&product=social-ab\' async=\'async\'></script>'."\n";
},9999);