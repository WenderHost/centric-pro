<?php
/**
 * Centric-Pro for caseantiques.com
 *
 * @author  Michael Wender
 * @license GPL-2.0+
 */

/**
 * Case Antiques Past Auctions widget class.
 *
 * @since 1.0.0
 *
 * @package Centric-Pro\Widgets
 */
class CaseAnti_Past_Auctions extends WP_Widget {

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

		);

		$widget_ops = array(
			'classname'   => 'past-auctions pastauctions',
			'description' => __( 'Displays past auctions in a three column layout', 'genesis' ),
		);

		$control_ops = array(
			'id_base' => 'caseanti-past-auctions',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'caseanti-past-auctions', __( 'Case Antiques - Past Auctions', 'genesis' ), $widget_ops, $control_ops );

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

		echo '<section class="widget widget_text"><div class="widget-wrap"><div class="textwidget"><h2>Past Auctions</h2></div></div></section>';

		//* Query all auctions
		$args = array(
			'parent' => 0,
			'order' => 'DESC',
		);
		$auctions = get_terms( 'auction', $args );
		if( $auctions ){
			$filtered_auctions = array();
			foreach( $auctions as $auction ){
				$name = $auction->name;
				preg_match( '/[0-9]{4}\s[0-9]{1,2}\s[0-9]{1,2}/', $name, $matches );
				if( $matches ){
					$date = str_replace( ' ', '-', $matches[0] );
					$filtered_name = str_replace( $matches[0], date( 'M j, Y', strtotime( $date ) ), $name );
					$filtered_auctions[] = array( 'name' => $filtered_name, 'permalink' => get_term_link( $auction ) );
				}
			}
			if( ! empty( $filtered_auctions ) )
				$auctions = $filtered_auctions;

			$cols = 3;
			$auctions_per_col = round( count( $auctions )/$cols );

			$columns = array_chunk( $auctions, $auctions_per_col );

			for( $x = 0; $x < $cols; $x++ ){
				$format = '<section class="widget widget_nav_menu"><div class="widget-wrap"><div class=""><ul class="menu">%1$s</ul></div></div></section>';
				$column_auctions = $columns[$x];
				$html = array();
				foreach( $column_auctions as $auction ){
					$name = $auction->name;
					$html[] = '<li><a href="' . $auction['permalink'] . '">' . $auction['name'] . '</a></li>';
				}
				$auction_columns[] = sprintf( $format, implode( "\n", $html ) );
			}

			echo implode( "\n", $auction_columns );
		}

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

	}

	/**
	 * Echo the settings update form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {

	}

}
