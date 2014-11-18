<?php
/**
 * Centric Pro for Case Antiques
 *
 * @package Centric Pro\Templates
 * @author  Michael Wender
 * @license GPL-2.0+
 */

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 ); // Remove item authorship info
remove_action( 'genesis_entry_footer', 'genesis_post_meta' ); // Remove `Filed Under:`

add_filter( 'genesis_build_crumbs', 'centric_item_build_crumbs', 10, 2 ); // Remove `Item`, add the auction to the breadcrumbs
add_action( 'genesis_entry_content', 'centric_item_auctioninfo', 8 ); // Add .moreinfo box to item content
add_action( 'genesis_entry_content', 'centric_item_attachments', 20 ); // Add attached images to item content

function centric_item_attachments(){
	global $post;

	$args = array(
		'post_parent' => $post->ID,
		'post_type' => 'attachment',
		'post_mime_type' => 'image'
	);
	$images = get_children( $args );

	if( $images ){
		$gallery_images = array();
		foreach( $images as $attachment_id => $attachment ){
			$image_ids[] = $attachment_id;
			$fullsize = wp_get_attachment_image_src( $attachment_id, 'fullsize' );
			$thumbnail = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
			$gallery_images[] = '<a href="' . $fullsize[0] . '" class="image" data-target="flare" data-flare-scale="fitmax" data-flare-gallery="gallery1" data-flare-thumb="' . $thumbnail[0] . '">' . wp_get_attachment_image( $attachment_id, 'large' ) . '</a>';
		}
		echo '<div class="item-gallery">' . implode( "\n", $gallery_images ) . '</div>';
	}
}

/**
 * Adds .moreinfo box to item content.
 *
 * @see get_metadata() 		used to retrieve custom meta data added to an item's auction
 * @global object $post WordPress post object.
 *
 * @since 1.0.0
 *
 * @return void
 */
function centric_item_auctioninfo(){
	global $post;
	$terms = wp_get_object_terms( $post->ID, 'auction' );
	?>
    <div class="moreinfo alignright">
    	<ul>
		<?php
		if( $terms ){
			foreach( $terms as $term ){
				if( $term->taxonomy == 'auction' ){
					$meta = get_metadata( 'auction', $term->term_id, 'meta', true );

					$auction_timestamp = strtotime( get_metadata( 'auction', $term->term_id, 'date', true ) );
					$current_timestamp = strtotime( date( 'Y-m-d', current_time( 'timestamp' ) ) );
					$link_text = ( $current_timestamp > $auction_timestamp )? 'View Final Price' : 'Bid Now';

					if( !empty( $meta['auction_id'] ) ){
						if( !$lotnum ) $lotnum = get_post_meta( $post->ID, '_lotnum', true );

						echo '<li><a class="button" target="_blank" href="http://www.liveauctioneers.com/itemLookup/'.$meta['auction_id'].'/'.$lotnum.'">'.$link_text.'</a></li>';
					}
					if( null != get_post_meta( $post->ID, '_igavel_lotnum', true ) ){
						$igavel_lotnum = get_post_meta( $post->ID, '_igavel_lotnum', true );
						$igavel_item_url = 'http://bid.igavelauctions.com/Bidding.taf?_function=detail&Auction_uid1=' . $igavel_lotnum;
						echo '<li><a class="button" target="_blank" href="' . $igavel_item_url . '" title="View ' . esc_attr( get_the_title() ) . ' on iGavel">' . $link_text . '</a></li>';
					}
				}
			}
		}

		$high_est = get_post_meta( $post->ID, '_high_est', true );
		$low_est = get_post_meta( $post->ID, '_low_est', true );
		$realized = get_post_meta( $post->ID, '_realized', true );
		if(!empty($low_est)) echo '<li><strong>Low Estimate:</strong> '.AuctionShortcodes::format_price( $low_est ). '</li>';
		if(!empty($high_est)) echo '<li><strong>High Estimate:</strong> '.AuctionShortcodes::format_price( $high_est ). '</li>';
		if( !empty( $realized ) ) echo '<li itemprop="offers" itemscope="itemscope" itemtype="http://schema.org/Offer"><strong>Realized:</strong> <span itemprop="price">'.AuctionShortcodes::format_price( $realized ). '</span></li>';
		?>
		<li><strong>More Information:</strong><br />
        For more information on this or any other item, email
        us at: <a href="mailto:info@caseantiques.com?subject=Case%20Antiques%20Inquiry:%20<?php echo esc_attr( str_replace( ' ', '%20', get_the_title( $post->ID ) ) ) ?>">info@caseantiques.com</a></li>
        <li><strong>Share this:</strong><br />
        	<span class="st_facebook" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" displayText="Facebook"></span>
        	<span class="st_twitter" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" displayText="Twitter"></span>
        	<span class="st_pinterest" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" displayText="Pinterest"></span>
        	<span class="st_plusone" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" displayText="Google+"></span>
        	<span class="st_email" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" displayText="Email"></span>
        	<span class="st_sharethis" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" displayText="sharethis"></span>
		<script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'wp.0d3e1902-6506-4b74-84aa-2bb87fc215f9'});var st_type='wordpress3.0.1';</script></li>
		</ul>
    </div>
	<?php
}

/**
 * Modify the Genesis breadcrumbs
 *
 * @see wp_get_post_terms()	retrieves this item's `auction`.
 * @see wp_get_referer()	we use this to build a back link to the specific auction page that referered us here.
 * @global object $post WordPress post object.
 *
 * @since 1.0.0
 *
 * @param array $crumbs Array of breadcrumbs.
 * @param array $args Breadcrumb arguments.
 * @return array Breadcrumbs.
 */
function centric_item_build_crumbs( $crumbs, $args ){
	global $post;
	$terms = wp_get_post_terms( $post->ID, 'auction' );

	$paged = '';
	$paged_text = '';
	$url = parse_url( wp_get_referer() );
	if( $url['path'] ){
		preg_match( '/page\/([0-9]+)/', $url['path'], $matches );
		if( $matches )
			$page = $matches[1];
		$paged = ( $page )? 'page/' . $page . '/' : '';
		$paged_text = ' &ndash; Page ';
		$paged_text.= ( $page )? $page : '1';
	}

	if( $terms ){
		$auction = $terms[0];
		$crumbs[1] = '<a href="' . get_term_link( $auction->term_id, 'auction' ) . $paged . '" title="Back to ' . esc_attr( $auction->name ) . '">' . $auction->name . $paged_text . '</a> ' . $args['sep'] . ' ' . $post->post_title;
	}
	return $crumbs;
}

genesis();
