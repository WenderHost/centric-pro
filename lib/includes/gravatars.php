<?php

//* Modify the size of the Gravatar in author box
add_filter( 'genesis_author_box_gravatar_size', 'centric_author_box_gravatar_size' );
function centric_author_box_gravatar_size( $size ) {

  return 96;

}

//* Modify the size of the Gravatar in comments
add_filter( 'genesis_comment_list_args', 'centric_comment_list_args' );
function centric_comment_list_args( $args ) {

    $args['avatar_size'] = 60;
  return $args;

}