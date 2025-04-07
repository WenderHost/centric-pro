<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
function centric_pro_load_textdomain(){
  load_child_theme_textdomain( 'centric', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'centric' ) );  
}
add_action( 'init', 'centric_pro_load_textdomain' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Centric Theme', 'centric' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/centric/' );
define( 'CHILD_THEME_VERSION', '1.1' );

//* Load our widgets
include_once( get_stylesheet_directory() . '/lib/widgets/widgets.php' );

/* Includes */
require_once( get_stylesheet_directory() . '/lib/includes/acf.php' );
require_once( get_stylesheet_directory() . '/lib/includes/breadcrumbs.php' );
require_once( get_stylesheet_directory() . '/lib/includes/comments.php' );
require_once( get_stylesheet_directory() . '/lib/includes/enqueues.php' );
require_once( get_stylesheet_directory() . '/lib/includes/footer.php' );
require_once( get_stylesheet_directory() . '/lib/includes/gravatars.php' );
require_once( get_stylesheet_directory() . '/lib/includes/image-sizes.php' );
require_once( get_stylesheet_directory() . '/lib/includes/layout.php' );
require_once( get_stylesheet_directory() . '/lib/includes/misc.php' );
require_once( get_stylesheet_directory() . '/lib/includes/responsive-images.php' );
require_once( get_stylesheet_directory() . '/lib/includes/theme-support.php' );
require_once( get_stylesheet_directory() . '/lib/includes/top-bar.php' );
require_once( get_stylesheet_directory() . '/lib/includes/widgets.php' );

/**
 * Retrieve taxonomy parents with separator.
 *
 * @since 1.0.0
 *
 * @param string $taxonomy Optional taxonomy name, if you don't set it, it will get it from get_query_vars
 * @param int $id Optional, taxonomy ID, if you don't set it, it will get it from get_query_vars
 * @param bool $link Optional, default is false. Whether to format with link.
 * @param string $separator Optional, default is '/'. How to separate categories.
 * @param bool $nicename Optional, default is false. Whether to use nice name for display.
 * @param array $visited Optional. Already linked to categories to prevent duplicates.
 * @return string|WP_Error A list of category parents on success, WP_Error on failure.
 */
function get_taxonomy_parents( $taxonomy = null, $id = null, $link = false, $separator = '/', $nicename = false, $visited = array() ) {

  if ( ! isset( $taxonomy ) ) {
    $taxonomy = get_query_var( 'taxonomy' );
  }

  if ( ! isset( $id ) ) {
    $term = get_term_by( 'name', single_tag_title( '', false ), $taxonomy );
    $id = $term->term_id;
  }

  $chain = '';
  $parent = get_term( $id, $taxonomy );
  if ( is_wp_error( $parent ) )
    return $parent;

  if ( $nicename ) {
    $name = $parent->slug;
  } else {
    $name = $parent->name;
  }

  if ( $parent->parent && ( $parent->parent != $parent->term_id ) && ! in_array( $parent->parent, $visited ) )
  {
    $visited[] = $parent->parent;
    $chain .= get_taxonomy_parents( $taxonomy, $parent->parent, $link, $separator, $nicename, $visited );
  }

  if ( $link ) {
    $chain .= '<a href="' . get_term_link( $parent->slug, $taxonomy ) . '">' . $name . '</a>' . $separator;
  } else {
    $chain .= $parent->name . $separator;
  }

  return $chain;
}