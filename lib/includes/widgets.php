<?php

//* Add support for 4-column footer widgets
add_theme_support( 'genesis-footer-widgets', 4 );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Register widget areas
genesis_register_sidebar( array(
  'id'          => 'top-bar-widgets',
  'name'        => __( 'Top Bar', 'centric' ),
  'description' => __( 'This bar appears above the site header.', 'centric' ),
) );
genesis_register_sidebar( array(
  'id'          => 'home-widgets-1',
  'name'        => __( 'Home 1', 'centric' ),
  'description' => __( 'This is the first section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
  'id'          => 'home-widgets-2',
  'name'        => __( 'Home 2', 'centric' ),
  'description' => __( 'This is the second section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
  'id'          => 'home-widgets-3',
  'name'        => __( 'Home 3', 'centric' ),
  'description' => __( 'This is the third section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
  'id'          => 'home-widgets-4',
  'name'        => __( 'Home 4', 'centric' ),
  'description' => __( 'This is the fourth section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
  'id'          => 'home-widgets-5',
  'name'        => __( 'Home 5', 'centric' ),
  'description' => __( 'This is the fifth section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
  'id'          => 'home-widgets-6',
  'name'        => __( 'Home 6', 'centric' ),
  'description' => __( 'This is the sixth section of the home page.', 'centric' ),
) );