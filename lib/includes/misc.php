<?php

//* Prevent Page Scroll When Clicking the More Link
add_filter( 'the_content_more_link', 'remove_more_link_scroll' );
function remove_more_link_scroll( $link ) {
  $link = preg_replace( '|#more-[0-9]+|', '', $link );
  return $link;
}