<?php


/**
 * Displays the #top-bar above the site-header.
 *
 * @since 1.0.0
 *
 * @return void
 */
add_action( 'genesis_before_header', 'centric_top_bar' );
function centric_top_bar(){
  if( ! is_active_sidebar( 'top-bar-widgets' ) )
    return;
?>
<div id="top-bar"><?php dynamic_sidebar( 'top-bar-widgets' ) ?></div>
<?php
}