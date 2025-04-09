<?php
/**
 * Centric Pro for Case Antiques
 *
 * @package Centric Pro\Templates
 * @author  Michael Wender
 * @license GPL-2.0+
 */
global $post;

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 ); // Remove item authorship info
remove_action( 'genesis_entry_footer', 'genesis_post_meta' ); // Remove `Filed Under:`

add_filter( 'genesis_build_crumbs', 'centric_item_build_crumbs', 10, 2 ); // Remove `Item`, add the auction to the breadcrumbs
add_action( 'genesis_entry_content', 'centric_item_auctioninfo', 8 ); // Add .moreinfo box to item content

//$itemNumber = intval( get_post_meta( $post->ID, '_item_number', true ) );
//if( ! $itemNumber )

add_action( 'genesis_entry_content', 'centric_item_attachments', 20 ); // Add attached images to item content

/**
 * Displays all images for an item
 *
 * @since 1.0.0
 *
 * @return void
 */
function centric_item_attachments() {
	global $post;

	$parent_title = get_the_title( $post->ID );

	$args = array(
		'post_parent' => $post->ID,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	$images = get_children( $args );

	if ( $images ) {
		$gallery_images = array();
		foreach ( $images as $attachment_id => $attachment ) {
			$image_ids[] = $attachment_id;
			$fullsize = wp_get_attachment_image_src( $attachment_id, 'fullsize' );
			$thumbnail = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );

			$atts = array(
				'alt' => esc_attr( $parent_title ),
			);

			$gallery_images[] = '<a href="' . $fullsize[0] . '" class="image gallery" data-flare-thumb="' . $thumbnail[0] . '">' . wp_get_attachment_image( $attachment_id, 'large', false, $atts ) . '</a>';
		}
		echo '<div class="item-gallery">' . implode( "\n", $gallery_images ) . '</div>';
	}
}

/**
 * Adds .moreinfo box to item content.
 *
 * @see get_metadata()   used to retrieve custom meta data added to an item's auction
 * @global object $post WordPress post object.
 *
 * @since 1.0.0
 *
 * @return void
 */
function centric_item_auctioninfo() {
	global $post;
	$terms = wp_get_object_terms( $post->ID, 'auction' );
?>
    <div class="moreinfo alignright">
    	<ul>
		<?php
	$high_est = get_post_meta( $post->ID, '_high_est', true );
	$low_est = get_post_meta( $post->ID, '_low_est', true );
	$realized = get_post_meta( $post->ID, '_realized', true );
	$hammerprice = get_post_meta( $post->ID, '_hammerprice', true );
	$lotnum = get_post_meta( $post->ID, '_lotnum', true );
	$lot_bidding_url = get_post_meta( $post->ID, '_lot_bidding_url', true );

	if ( $terms ) {
		foreach ( $terms as $term ) {
			if ( $term->taxonomy == 'auction' ) {
				$button_classes = array( 'button' );

				// ACF meta data
				/*
				$auction_meta = [
					'date'					=> get_metadata( 'auction', $term->term_id, 'date', true ),
					'show_realized'	=> get_metadata( 'auction', $term->term_id, 'show_realized', true ),
					'auction_id'		=> get_metadata( 'auction', $term->term_id, 'auction_id', true ),
					'bidsquare_id'	=> get_metadata( 'auction', $term->term_id, 'bidsquare_id', true ),
				];
				*/
				$show_realized = get_field( 'show_realized', $term );
				$show_realized = ( is_array( $show_realized ) && ! empty( $show_realized ) )? $show_realized[0] : false ;

				$auction_meta = [
					'date'				=> get_field( 'date', $term ),
					'show_realized'		=> $show_realized,
					'auction_id'		=> get_field( 'auction_id', $term ),
					'bidsquare_id'		=> get_field( 'bidsquare_id', $term ),
				];
				//echo '<li><pre>' . print_r( $auction_meta, true ) . '</pre></li>';
				$auction_timestamp = ( ! is_null( $auction_meta['date'] ) )? strtotime( $auction_meta['date'] ) : null ;
				//$current_timestamp = strtotime( date( 'Y-m-d', current_time( 'timestamp' ) ) );
				$current_timestamp = current_time( 'timestamp' );
				if ( is_null( $auction_timestamp ) || $current_timestamp < $auction_timestamp ) {
					$link_text = 'Bid Now';
					$button_classes[] = 'green';
				} else {
					if ( ! $realized )
						$realized = 'PASSED';
					$link_text = 'View Final Price';
				}
				// END ACF meta data

				// Display Realized Price
				if ( ! empty( $realized ) && is_numeric( $realized ) ) {
					echo '<li><h1 style="text-align: center;">SOLD! <span style="font-weight: normal">for ' . AuctionShortcodes::format_price( $realized ) . '.</span></h1><p class="note">(Note: Prices realized include a buyer\'s premium.)</p></li>';
				}

				// BID NOW: Live Auctioneers
				$liveAuctioneersId = false;
				$itemLiveAuctioneersId = get_post_meta( $post->ID, '_liveauctioneers_id', true );
				if( ! empty( $itemLiveAuctioneersId ) && is_numeric( $itemLiveAuctioneersId ) ){
					$liveAuctioneersId = $itemLiveAuctioneersId;
				} else if( ! empty( $auction_meta['auction_id'] ) ){
					$liveAuctioneersId = $auction_meta['auction_id'];
				}

				switch( $link_text ){
					case 'Bid Now':
						// Display LiveAuctioneers link or LotBiddingURL

						$display_bid_now_button = boolval( get_field( 'display_bid_now_button', $term ) );
						if( $display_bid_now_button && ! empty( $lot_bidding_url ) ){
							echo '<li><a class="' . implode( ' ', $button_classes ) . '" href="' . $lot_bidding_url . '" target="_blank" title="Online bidding for ' . esc_attr( get_the_title() ) . '">Bid Now Online</a></li>';
						}
						break;

					case 'View Final Price':
						echo '<li><p style="margin-bottom: 12px;">If you have items like this you wish to consign, click here for more information:</p><a class="' . implode( ' ', $button_classes ) . '" target="_blank" href="https://caseantiques.com/selling/" title="Selling with Case Antiques">Selling with Case</a></li>';

						break;
				}
			}
		}
	}

	if ( ! empty( $low_est ) ) echo '<li><strong>Low Estimate:</strong> ' . AuctionShortcodes::format_price( $low_est ) . '</li>';
	if ( ! empty( $high_est ) ) echo '<li><strong>High Estimate:</strong> ' . AuctionShortcodes::format_price( $high_est ) . '</li>';

	if ( ! empty( $hammerprice ) && is_numeric( $hammerprice ) ) echo '<li itemprop="offers" itemscope="itemscope" itemtype="http://schema.org/Offer"><strong>Hammer Price:</strong> <span itemprop="price">' . AuctionShortcodes::format_price( $hammerprice ) . '</span></li>';
?>
        <li><strong>Share this:</strong><br />
        	<div class="sharethis-inline-share-buttons"></div>
			</li>
		</ul>
    </div>
	<?php
}

/**
 * Modify the Genesis breadcrumbs
 *
 * @see wp_get_post_terms() retrieves this item's `auction`.
 * @see wp_get_referer() we use this to build a back link to the specific auction page that referered us here.
 * @global object $post WordPress post object.
 *
 * @since 1.0.0
 *
 * @param array   $crumbs Array of breadcrumbs.
 * @param array   $args   Breadcrumb arguments.
 * @return array Breadcrumbs.
 */
function centric_item_build_crumbs( $crumbs, $args ) {
	global $post;
	$terms = wp_get_post_terms( $post->ID, 'auction' );

	$paged = '';
	$paged_text = '';
	$url = parse_url( wp_get_referer() );
	if ( $url['path'] ) {
		preg_match( '/page\/([0-9]+)/', $url['path'], $matches );
		$page = ( $matches )? $matches[1] : null;
		$paged = ( $page )? 'page/' . $page . '/' : '';
		$paged_text = ' &ndash; Page ';
		$paged_text.= ( $page )? $page : '1';
	}

	if ( $terms ) {
		$auction = $terms[0];
		$crumbs[1] = '<a href="' . get_term_link( $auction->term_id, 'auction' ) . $paged . '" title="Back to ' . esc_attr( $auction->name ) . '">' . $auction->name . $paged_text . '</a> ' . $args['sep'] . ' ' . $post->post_title;
	}
	return $crumbs;
}

/**
 * Adds `Next` and `Previous` item navigation.
 *
 * @since x.x.x
 *
 * @return void
 */
function centric_item_next_prev_links() {
?>
<div class="item-nav-fixed">
	<?php
	$prev_post = get_adjacent_post( true, null, true, 'auction' );
	if ( $prev_post ) {
		$thumbnail = AuctionShortcodes::get_gallery_image( $prev_post->ID, true );
?>
	<a class="item-nav previous" href="<?php echo get_permalink( $prev_post->ID ); ?>" title="<?php _e( 'Previous post:', 'centric' ); echo ' ' . esc_attr( get_the_title( $prev_post->ID ) ) ?>">
		<span class="arrow">&nbsp;</span>
		<span class="preview" style="background-image: url(<?php echo $thumbnail; ?>)"><?php echo get_the_title( $prev_post->ID ) ?></span>
		<span class="label">Previous Item</span>
	</a>
	<?php
	}
	$next_post = get_adjacent_post( true, null, false, 'auction' );
	if ( $next_post ) {
		$thumbnail = AuctionShortcodes::get_gallery_image( $next_post->ID, true );
?>
	<a class="item-nav next" href="<?php echo get_permalink( $next_post->ID ); ?>" title="<?php _e( 'Next post:', 'centric' ); echo ' ' . esc_attr( get_the_title( $next_post->ID ) ) ?>">
		<span class="arrow">&nbsp;</span>
		<span class="preview" style="background-image: url(<?php echo $thumbnail; ?>)"><?php echo get_the_title( $next_post->ID ) ?></span>
		<span class="label">Next Item</span>
	</a>
	<?php } ?>
</div>
<?php
}
add_action( 'genesis_before_loop', 'centric_item_next_prev_links', 9 );

genesis();
