<?php
/**
 * Centric-Pro for caseantiques.com
 *
 * @author  Michael Wender
 * @license GPL-2.0+
 */

//* Include widget class files
require_once( dirname( __FILE__ ) . '/featured-post-widget.php' );
require_once( dirname( __FILE__ ) . '/item-categories-and-tags.php' );
require_once( dirname( __FILE__ ) . '/past-auctions-widget.php' );

add_action( 'widgets_init', 'caseanti_load_widgets' );
/**
 * Register widgets for use in the Genesis theme.
 *
 * @since 1.7.0
 */
function caseanti_load_widgets() {

	register_widget( 'CaseAnti_Featured_Post' );
  register_widget( 'CaseAnti_Item_Categories_and_Tags' );
	register_widget( 'CaseAnti_Past_Auctions' );

}