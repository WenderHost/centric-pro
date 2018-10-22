<?php

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'centric_remove_comment_form_allowed_tags' );
function centric_remove_comment_form_allowed_tags( $defaults ) {

  $defaults['comment_notes_after'] = '';
  return $defaults;

}