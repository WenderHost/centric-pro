<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'centric', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'centric' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Centric Theme', 'centric' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/centric/' );
define( 'CHILD_THEME_VERSION', '1.1' );

//* Load our widgets
include_once( get_stylesheet_directory() . '/lib/widgets/widgets.php' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue Scripts
remove_action( 'genesis_meta', 'genesis_load_stylesheet' ); // Don't load style.css
add_action( 'wp_enqueue_scripts', 'centric_load_scripts' );
function centric_load_scripts() {
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,700|Spinnaker', array(), CHILD_THEME_VERSION );
	wp_enqueue_script( 'centric-global', get_bloginfo( 'stylesheet_directory' ) . '/js/global.js', array( 'jquery' ), filemtime( get_stylesheet_directory( '/js/global.js' ) ) , true );
	wp_enqueue_style( 'centric-pro', get_bloginfo( 'stylesheet_directory' ) . '/lib/css/main.css', array( 'dashicons' ), filemtime( get_stylesheet_directory( '/lib/css/main.css' ) ) );

	if( is_tax( 'auction' ) ){
		global $wp_query;
		$value    = get_query_var($wp_query->query_vars['taxonomy']);
		$current_term = get_term_by('slug',$value,$wp_query->query_vars['taxonomy']);
		wp_enqueue_script( 'datatables-user' );
		wp_localize_script( 'datatables-user', 'wpvars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'auction' => $current_term->term_id ) );
		wp_enqueue_style( 'datatables' );
	}
}

//* Footer Copyright
add_filter('genesis_footer_creds_text', 'caseanti_footer_creds_filter');
function caseanti_footer_creds_filter( $creds ) {
	$creds = '[footer_copyright] ' . get_bloginfo( 'title' ) . '. All rights reserved.<br />Auctions in Association with:<br />Case Antiques, Inc. - TNGL#5157, TNGBL#5387 - 2240 Sutherland Avenue, Suite 101 - Knoxville, TN 37919 - (865) 558-3033';
	return $creds;
}

//* Add new image sizes
add_image_size( 'featured-page', 960, 700, TRUE );
add_image_size( 'featured-post', 400, 300, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'height'          => 80,
	'width'           => 360,
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'site-inner',
	'footer-widgets',
	'footer',
) );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
	'centric-pro-charcoal' => __( 'Centric Charcoal', 'centric' ),
	'centric-pro-green'    => __( 'Centric Green', 'centric' ),
	'centric-pro-orange'   => __( 'Centric Orange', 'centric' ),
	'centric-pro-purple'   => __( 'Centric Purple', 'centric' ),
	'centric-pro-red'      => __( 'Centric Red', 'centric' ),
	'centric-pro-yellow'   => __( 'Centric Yellow', 'centric' ),
) );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary navigation menu
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'centric' ) ) );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Reposition Page Title
add_action( 'genesis_before', 'centric_post_title' );
function centric_post_title() {

	if ( is_page() and !is_page_template() ) {
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		add_action( 'genesis_after_header', 'centric_open_post_title', 1 );
		add_action( 'genesis_after_header', 'genesis_do_post_title', 2 );
		add_action( 'genesis_after_header', 'centric_close_post_title', 3 );
	} elseif ( is_category() ) {
		remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
		add_action( 'genesis_after_header', 'centric_open_post_title', 1 ) ;
		add_action( 'genesis_after_header', 'genesis_do_taxonomy_title_description', 2 );
		add_action( 'genesis_after_header', 'centric_close_post_title', 3 );
	} elseif ( is_search() ) {
        remove_action( 'genesis_before_loop', 'genesis_do_search_title' );
        add_action( 'genesis_after_header', 'centric_open_post_title', 1 ) ;
        add_action( 'genesis_after_header', 'genesis_do_search_title', 2 );
        add_action( 'genesis_after_header', 'centric_close_post_title', 3 );
    }

}

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

// Modify the Genesis Breadcrumb arguments
add_filter( 'genesis_breadcrumb_args', 'centric_breadcrumb_args' );
function centric_breadcrumb_args( $args ){
	$args['sep'] = ' &gt;&gt; ';
	return $args;
}

function centric_open_post_title() {
	echo '<div class="page-title"><div class="wrap">';
}

function centric_close_post_title() {
	echo '</div></div>';
}

//* Prevent Page Scroll When Clicking the More Link
add_filter( 'the_content_more_link', 'remove_more_link_scroll' );
function remove_more_link_scroll( $link ) {

	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;

}

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

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'centric_remove_comment_form_allowed_tags' );
function centric_remove_comment_form_allowed_tags( $defaults ) {

	$defaults['comment_notes_after'] = '';
	return $defaults;

}

//* Add support for 4-column footer widgets
add_theme_support( 'genesis-footer-widgets', 4 );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'top-bar-widgets',
	'name'        => __( 'Top Bar', 'centric' ),
	'description' => __( 'This bar appears above the site header.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-widgets-1',
	'name'        => __( 'Home 1', 'centric' ),
	'description' => __( 'This is the first section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-widgets-2',
	'name'        => __( 'Home 2', 'centric' ),
	'description' => __( 'This is the second section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-widgets-3',
	'name'        => __( 'Home 3', 'centric' ),
	'description' => __( 'This is the third section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-widgets-4',
	'name'        => __( 'Home 4', 'centric' ),
	'description' => __( 'This is the fourth section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-widgets-5',
	'name'        => __( 'Home 5', 'centric' ),
	'description' => __( 'This is the fifth section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-widgets-6',
	'name'        => __( 'Home 6', 'centric' ),
	'description' => __( 'This is the sixth section of the home page.', 'centric' ),
) );