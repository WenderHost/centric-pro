<?php

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
  'header-selector' => '.site-title a',
  'header-text'     => false,
  'height'          => 160, // 80
  'width'           => 720, // 360
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
  'header',
  'nav',
  'subnav',
  'site-inner',
  'footer-widgets',
  'footer',
) );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
  'centric-pro-charcoal' => __( 'Centric Charcoal', 'centric' ),
  'centric-pro-green'    => __( 'Centric Green', 'centric' ),
  'centric-pro-orange'   => __( 'Centric Orange', 'centric' ),
  'centric-pro-purple'   => __( 'Centric Purple', 'centric' ),
  'centric-pro-red'      => __( 'Centric Red', 'centric' ),
  'centric-pro-yellow'   => __( 'Centric Yellow', 'centric' ),
) );