<?php

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
function _centric_pro_custom_background_cb(){
// $background is the saved custom image, or the default image.
    $background = set_url_scheme( get_background_image() );

    // $color is the saved custom color.
    // A default has to be specified in style.css. It will not be printed here.
    $color = get_background_color();

    if ( $color === get_theme_support( 'custom-background', 'default-color' ) ) {
        $color = false;
    }

    if ( ! $background && ! $color ) {
        if ( is_customize_preview() ) {
            echo '<style type="text/css" id="custom-background-css"></style>';
        }
        return;
    }

    $style = $color ? "background-color: #$color;" : '';

    if ( $background ) {
        $image = ' background-image: url("' . esc_url_raw( $background ) . '");';

        // Background Position.
        $position_x = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
        $position_y = get_theme_mod( 'background_position_y', get_theme_support( 'custom-background', 'default-position-y' ) );

        if ( ! in_array( $position_x, array( 'left', 'center', 'right' ), true ) ) {
            $position_x = 'left';
        }

        if ( ! in_array( $position_y, array( 'top', 'center', 'bottom' ), true ) ) {
            $position_y = 'top';
        }

        $position = " background-position: $position_x $position_y;";

        // Background Size.
        $size = get_theme_mod( 'background_size', get_theme_support( 'custom-background', 'default-size' ) );

        if ( ! in_array( $size, array( 'auto', 'contain', 'cover' ), true ) ) {
            $size = 'auto';
        }

        $size = " background-size: $size;";

        // Background Repeat.
        $repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );

        if ( ! in_array( $repeat, array( 'repeat-x', 'repeat-y', 'repeat', 'no-repeat' ), true ) ) {
            $repeat = 'repeat';
        }

        $repeat = " background-repeat: $repeat;";

        // Background Scroll.
        $attachment = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );

        if ( 'fixed' !== $attachment ) {
            $attachment = 'scroll';
        }

        $attachment = " background-attachment: $attachment;";

        $style .= $image . $position . $size . $repeat . $attachment;
    }
?>
<style type="text/css" id="custom-background-css">
body.custom-background .home-featured { <?php echo trim( $style ); ?> }
</style>
<?php
}
$custom_background_args = [
  'wp-head-callback' => '_centric_pro_custom_background_cb'
];
add_theme_support( 'custom-background', $custom_background_args );

//* Add support for custom header
add_theme_support( 'custom-header', array(
  'header-selector' => '.site-title a',
  'header-text'     => false,
  'height'          => 160, // 80
  'width'           => 720, // 360
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