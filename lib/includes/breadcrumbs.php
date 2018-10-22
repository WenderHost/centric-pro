<?php

// Modify the Genesis Breadcrumb arguments
add_filter( 'genesis_breadcrumb_args', 'centric_breadcrumb_args' );
function centric_breadcrumb_args( $args ){
  $args['sep'] = ' &gt;&gt; ';
  return $args;
}