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
 * @param string $taxonomy  Optional taxonomy name. Defaults to query var.
 * @param int    $id        Optional taxonomy ID. Defaults to query var.
 * @param bool   $link      Optional. Whether to format with link. Default false.
 * @param string $separator Optional. Separator between terms. Default '/'.
 * @param bool   $nicename  Optional. Whether to use slug instead of name. Default false.
 * @param array  $visited   Optional. Prevents infinite loops. Default empty array.
 * @return string A list of category parents, or empty string on failure.
 */
function get_taxonomy_parents( $taxonomy = null, $id = null, $link = false, $separator = '/', $nicename = false, $visited = array() ) {

  if ( ! $taxonomy ) {
    $taxonomy = get_query_var( 'taxonomy' );
  }

  if ( ! $id ) {
    $term = get_term_by( 'name', single_tag_title( '', false ), $taxonomy );
    if ( ! $term || is_wp_error( $term ) ) {
      return ''; // Bail if invalid.
    }
    $id = $term->term_id;
  }

  $chain  = '';
  $parent = get_term( $id, $taxonomy );

  if ( ! $parent || is_wp_error( $parent ) ) {
    return ''; // Bail gracefully.
  }

  $name = $nicename ? $parent->slug : $parent->name;

  if ( $parent->parent && ( $parent->parent != $parent->term_id ) && ! in_array( $parent->parent, $visited, true ) ) {
    $visited[] = $parent->parent;
    $chain    .= get_taxonomy_parents( $taxonomy, $parent->parent, $link, $separator, $nicename, $visited );
  }

  if ( $link ) {
    $term_link = get_term_link( $parent, $taxonomy );
    if ( ! is_wp_error( $term_link ) ) {
      $chain .= '<a href="' . esc_url( $term_link ) . '">' . esc_html( $name ) . '</a>' . $separator;
    } else {
      $chain .= esc_html( $name ) . $separator; // Fallback if link failed.
    }
  } else {
    $chain .= esc_html( $name ) . $separator;
  }

  return $chain;
}