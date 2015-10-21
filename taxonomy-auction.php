<?php
/**
 * Custom template for Auction taxonomy.
 *
 * @link URL
 * @since 1.0.0
 *
 * @package Centric Pro for Case Antiques
 * @subpackage Component
 */

// Reconfigure the standard entry header
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'centricpro_do_item_image', 7 );
function centricpro_do_item_image(){
	?><div class="image-frame"><span class="helper"></span><?php
	genesis_do_post_image();
	?></div><?php
}

// Reconfigure standard post content output
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
remove_action( 'genesis_post_content', 'genesis_do_post_content' );
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

//* Wrap the loop inside div.genesis-loop
function centric_before_loop_while(){
	echo '<div class="genesis-loop">';
}
add_action( 'genesis_before_while', 'centric_before_loop_while', 1 );

//* Close div.genesis-loop
function centric_after_loop_endwhile(){
	echo '</div><!-- /.genesis-loop -->';
}
add_action( 'genesis_after_endwhile', 'centric_after_loop_endwhile', 998 );

function centric_auction_display_toggle(){
	echo '<ul class="auction-display-toggle">
			<li><a href="#" class="view-thumbnails">Thumbnails</a></li>
			<li><a href="#" class="view-table">Table</a></li>
		</ul>';
}
add_action( 'genesis_before_loop', 'centric_auction_display_toggle', 9 );

function centric_auction_table(){
	global $wp_query;
	$value    = get_query_var($wp_query->query_vars['taxonomy']);
	$current_term = get_term_by('slug',$value,$wp_query->query_vars['taxonomy']);

	$filepath = get_stylesheet_directory() . '/lib/includes/auction-table.datatables.html';
	$auction_table_format = file_get_contents( $filepath );
	$rows = array();

	$auction_name = $current_term->name;
	preg_match( '/([0-9]{4})\s([0-9]{2})\s([0-9]{2})/', $auction_name, $matches );
	if( $matches ){
		$auction_timestamp = strtotime( $matches[1] . '-' . $matches[2] . '-' .$matches[3] );
		$auction_date = date( 'D, M j, Y', $auction_timestamp );
		$auction_name = str_replace( $matches[0], $auction_date, $auction_name );
	}

	$table = sprintf( $auction_table_format, implode( "\n",  $rows ), $auction_name );
	echo $table;
}
add_action( 'genesis_after_endwhile', 'centric_auction_table', 999 );

// Reconfigure the standard entry footer
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

// Add search at the top of the page
// add_action( 'genesis_before_loop', 'centricpro_auction_search', 20 );
function centricpro_auction_search(){
?><p>Search will go here.</p><?php
}

genesis();
?>