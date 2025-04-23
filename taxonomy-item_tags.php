<?php
/**
 * Custom template for Item_Tags taxonomy.
 *
 * @link URL
 * @since 1.0.0
 *
 * @package Centric Pro for Case Antiques
 * @subpackage Component
 */

// Reconfigure the standard entry header
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

function centricpro_do_item_image() {
  ?><div class="image-frame"><span class="helper"></span><?php
  genesis_do_post_image();
  ?></div><?php
}
add_action( 'genesis_entry_header', 'centricpro_do_item_image', 7 );

// Reconfigure standard post content output
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
remove_action( 'genesis_post_content', 'genesis_do_post_content' );
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

//* Reformat the auction name and insert it as our breadcrumb
function centric_auction_breadcrumb() {
  global $wp_query;
  $value    = get_query_var( $wp_query->query_vars['taxonomy'] );
  $current_term = get_term_by( 'slug', $value, $wp_query->query_vars['taxonomy'] );
  $auction_name = $current_term->name;
  preg_match( '/([0-9]{4})\s([0-9]{2})\s([0-9]{2})/', $auction_name, $matches );
  if ( $matches ) {
    $auction_timestamp = strtotime( $matches[1] . '-' . $matches[2] . '-' .$matches[3] );
    $auction_date = date( 'l, F j, Y', $auction_timestamp );
    $auction_name = str_replace( $matches[0], $auction_date, $auction_name );
  }
  echo '<h3 class="breadcrumb">' . $auction_name . '</h3>';
}
add_action( 'genesis_before_loop', 'centric_auction_breadcrumb' );

//* Wrap the loop inside div.genesis-loop
function centric_before_loop_while() {
  echo '<div class="genesis-loop">';
}
add_action( 'genesis_before_while', 'centric_before_loop_while', 1 );

//* Close div.genesis-loop
function centric_after_loop_endwhile() {
  echo '</div><!-- /.genesis-loop -->';
}
add_action( 'genesis_after_endwhile', 'centric_after_loop_endwhile', 998 );

//* Display the auction's description
function centric_auction_description() {
  echo '<div class="auction-description">
    ' . apply_filters( 'the_content', term_description() ) . genesis_do_taxonomy_title_description() . '
  </div>';
}
add_action( 'genesis_before_loop', 'centric_auction_description', 11 );
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );

//* Add a thumbnail/table view toggle
function centric_auction_display_toggle() {
  echo '<div class="auction-display-toggle">View Options: <ul>
      <li><a href="#" class="view-thumbnails active" title="Thumbnail View"></a></li>
      <li><a href="#" class="view-table" title="Table View"></a></li>
    </ul></div>';
}
add_action( 'genesis_before_loop', 'centric_auction_display_toggle', 12 );

//* Insert HTML for the thumbnail view
function centric_auction_thumbnails() {
  $filepath = get_stylesheet_directory() . '/lib/includes/auction-thumbnails.datatables.html';
  echo file_get_contents( $filepath );
}
add_action( 'genesis_before_loop', 'centric_auction_thumbnails', 13 );

//* Insert HTML for the table view
function centric_auction_table() {
  global $wp_query;
  $value    = get_query_var( $wp_query->query_vars['taxonomy'] );
  $current_term = get_term_by( 'slug', $value, $wp_query->query_vars['taxonomy'] );

  $date = get_metadata( 'auction', $current_term->term_id, 'date', true );
  if ( $date ) {
    $auction_date = new DateTime( $date );
    $todays_date = new DateTime( current_time( 'mysql' ) );
    $interval = $auction_date->diff( $todays_date );
  }

  $auction_name = $current_term->name;
  preg_match( '/([0-9]{4})\s([0-9]{2})\s([0-9]{2})/', $auction_name, $matches );
  if ( $matches ) {
    $auction_timestamp = strtotime( $matches[1] . '-' . $matches[2] . '-' .$matches[3] );
    $auction_date = date( 'l, F j, Y', $auction_timestamp );
    $auction_name = str_replace( $matches[0], $auction_date, $auction_name );
  }

  $filepath = get_stylesheet_directory() . '/lib/includes/auction-table.datatables.estimated.html'; // auction-table.datatables.estimated.html
  $auction_table_format = file_get_contents( $filepath );

  $table = str_replace( '{{title}}', $auction_name, $auction_table_format );

  echo $table;
}
add_action( 'genesis_before_loop', 'centric_auction_table', 14 );

// Reconfigure the standard entry footer
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

genesis();
?>
