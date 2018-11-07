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

/* Includes */
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