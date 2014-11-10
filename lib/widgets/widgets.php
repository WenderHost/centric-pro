<?php
/**
 * Centric-Pro for caseantiques.com
 *
 * @author  Michael Wender
 * @license GPL-2.0+
 */

//* Include widget class files
require_once( dirname( __FILE__ ) . '/featured-post-widget.php' );

add_action( 'widgets_init', 'caseanti_load_widgets' );
/**
 * Register widgets for use in the Genesis theme.
 *
 * @since 1.7.0
 */
function caseanti_load_widgets() {

	register_widget( 'CaseAnti_Featured_Post' );

}