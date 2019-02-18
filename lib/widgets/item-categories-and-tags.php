<?php
/**
 * Centric-Pro for caseantiques.com
 *
 * @author  Michael Wender
 * @license GPL-2.0+
 */

/**
 * Case Antiques Auction Item Categories and Tags widget class.
 *
 * @since 1.0.0
 *
 * @package Centric-Pro\Widgets
 */
class CaseAnti_Item_Categories_and_Tags extends WP_Widget {

  /**
   * Holds widget settings defaults, populated in constructor.
   *
   * @var array
   */
  protected $defaults;

  /**
   * Constructor. Set the default widget options and create widget.
   *
   * @since 1.0.0
   */
  function __construct() {

    $this->defaults = array(
      'title' => '',
      'terms' => [],
    );

    $widget_ops = array(
      'classname'   => 'featured-item-cats-and-tags',
      'description' => __( 'Displays a collection of featured Item Categories and/or Tags', 'genesis' ),
    );

    $control_ops = array(
      'id_base' => 'featured-item-cats-and-tags',
      'width'   => 200,
      'height'  => 250,
    );

    parent::__construct( 'featured-item-cats-and-tags', __( 'Case Antiques - Featured Items Categories and Tags', 'genesis' ), $widget_ops, $control_ops );

    add_action( 'customize_controls_enqueue_scripts', [ $this, 'customizer_enqueue_scripts' ] );
  }

  /**
   * Loads scripts in the Customizer
   */
  function customizer_enqueue_scripts(){
    wp_enqueue_script( 'chosen', get_stylesheet_directory_uri() . '/js/chosen/chosen.jquery.min.js', ['jquery'], '1.8.7', false );
    wp_enqueue_style( 'chosen', get_stylesheet_directory_uri() . '/js/chosen/chosen.min.css', null, '1.8.7', $media = 'all' );
  }

  /**
   * Echo the widget content.
   *
   * @since 1.0.0
   *
   * @global WP_Query $wp_query Query object.
   * @global integer  $more
   *
   * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
   * @param array $instance The settings for the particular instance of the widget
   */
  function widget( $args, $instance ) {

    global $wp_query;

    //* Merge with defaults
    $instance = wp_parse_args( (array) $instance, $this->defaults );

    echo $args['before_widget'];

    //* Set up the item categories/tags
    $terms = [];
    if( is_array( $instance['terms'] ) && 0 < count( $instance['terms'] ) ){
      echo '<div class="row-flexbox around-sm">';
      foreach( $instance['terms'] as $term_id ){
        $term = get_term( $term_id );
        $thumbnail = $this->get_term_thumbnail( $term );
        echo '<div class="col-sm-4" style="">';
        echo '<div class="item-thumbnail" style="background: transparent url(\'' . $thumbnail['thumbnail_url'] . '\') center/cover no-repeat;" data-post-title="' . esc_attr(  $thumbnail['title'] ) . '"><a style="display: block; text-indent: -9999px; height: 250px;" href="' . get_term_link( $term ) . '">' . $term->name . '</a></div><h3><a href="' . get_term_link( $term ) . '">' . $term->name . '</a></h3>';
        echo '</div>';
      }
      echo '</div>';
    } else {
      echo '<p>No Categories/Terms selected. Please open the Customizer and select some Auction Item Categories/Terms to appear in this section.</p>';
    }

    echo $args['after_widget'];
  }

  /**
   * Update a particular instance.
   *
   * This function should check that $new_instance is set correctly.
   * The newly calculated value of $instance should be returned.
   * If "false" is returned, the instance won't be saved/updated.
   *
   * @since 1.0.0
   *
   * @param array $new_instance New settings for this instance as input by the user via form()
   * @param array $old_instance Old settings for this instance
   * @return array Settings to save or bool false to cancel saving
   */
  function update( $new_instance, $old_instance ) {
    $new_instance['title']     = strip_tags( $new_instance['title'] );
    if( ! is_array( $new_instance['terms'] ) )
      $new_instance['terms'] = [];

    return $new_instance;
  }

  /**
   * Echo the settings update form.
   *
   * @since 1.0.0
   *
   * @param array $instance Current settings
   */
  function form( $instance ) {

    //* Merge with defaults
    $instance = wp_parse_args( (array) $instance, $this->defaults );
    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'genesis' ); ?>:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
    </p>

    <script type="text/javascript">
    jQuery(function($){
      $('.chosen-select').chosen();
    });
    </script>
    <style type="text/css">
      .chosen-container{
        width: 100% !important;
      }
    </style>
    <p>
      <label for="<?php echo $this->get_field_id( 'terms' ); ?>"><?php _e( 'Categories/Tags', 'genesis' ); ?>:</label>
      <select name="<?php echo $this->get_field_name( 'terms' ) ?>[]" id="<?php echo $this->get_field_id( 'terms' ); ?>" multiple="multiple" class="chosen-select select-two-multi" style="width: 99%; display: block; clear: both;">
        <?php
        // Retrieve and display all Item Categories and Tags
        $item_categories = get_terms( 'item_category' );
        if( ! is_wp_error( $item_categories ) ){
          foreach( $item_categories as $item_category ){
            $id = $item_category->term_id;
            $name = $item_category->name;
            $compare = $id;
            $current = ( is_array( $instance['terms'] ) && in_array( $compare, $instance['terms'] ) )? $compare : '' ;
            echo '<option value="' . $id . '" ' . selected( $compare, $current, false ) . '>Category: ' . $name . '</option>';
          }
        }
        $item_tags = get_terms( 'item_tags' );
        if( ! is_wp_error( $item_tags ) ){
          foreach( $item_tags as $item_tag ){
            $id = $item_tag->term_id;
            $name = $item_tag->name;
            $compare = $id;
            $current = ( is_array( $instance['terms'] ) && in_array( $compare, $instance['terms'] ) )? $compare : '' ;
            echo '<option value="' . $id . '" ' . selected( $compare, $current, false ) . '>Tag: ' . $name . '</option>';
          }
        }
        ?>
      </select>
    </p>

    <hr class="div" />


    <?php

  }

  /**
   * Gets the term thumbnail.
   *
   * @param      object  $term   The term
   *
   * @return     array   The term thumbnail with thumbnail_url and title keys.
   */
  function get_term_thumbnail( $term ){
    if( is_wp_error( $term ) || ! is_object( $term ) )
      return;

    if( function_exists( 'get_field') ){
      $thumbnail = get_field( 'thumbnail', $term );
      if( $thumbnail )
        return [ 'thumbnail_url' => $thumbnail['url'], 'title' => $term->name ];
    }

    $args = [
      'post_type' => 'item',
      'orderby' => 'meta_value_num',
      'order' => 'DESC',
      'meta_key' => '_realized',
      'posts_per_page' => 1,
      'no_found_rows' => true,
      'tax_query' => [
        [
          'taxonomy'  => $term->taxonomy,
          'field'     => 'term_id',
          'terms'     => [ $term->term_id ]
        ]
      ],
      'meta_query' => [
        'relation'  => 'AND',
        [
          'key'     => '_highlight',
          'value'   => true,
          'compare' => '='
        ],
        [
          'key'     => '_realized',
          'value'   => '0',
          'compare' => '>'
        ]
      ]
    ];

    $query = new WP_Query( $args );
    if( $query->have_posts() ){
      while( $query->have_posts() ){
        $query->the_post();
        $thumbnail_url = $this->get_first_image( get_the_ID() );
        $title = get_the_title();
      }
      wp_reset_postdata();
      return [ 'thumbnail_url' => $thumbnail_url, 'title' => $title ];
    }
  }

  /**
   * Gets the first image attachment on a post.
   *
   * @param      int  $id     The post ID.
   *
   * @return     string  The first image URL.
   */
  function get_first_image( $id ){
    $attachment = get_children([
      'post_parent'     => $id,
      'post_type'       => 'attachment',
      'post_mime_type'  => 'image',
      'order'           => 'DESC',
      'numberposts'     => 1,
    ]);

    if( ! is_array( $attachment ) || empty( $attachment ) )
      return;

    $attachment = current( $attachment );
    return wp_get_attachment_url( $attachment->ID, 'large' );
  }

}
