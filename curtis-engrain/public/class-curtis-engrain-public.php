<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/israelcurtis
 * @since      1.0.0
 *
 * @package    Curtis_Engrain
 * @subpackage Curtis_Engrain/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Curtis_Engrain
 * @subpackage Curtis_Engrain/public
 * @author     Israel Curtis <israel.curtis@gmail.com>
 */
class Curtis_Engrain_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/curtis-engrain-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/curtis-engrain-public.js', array( 'jquery' ), $this->version, false );

	}
	
	
	/**
	 * Shortcode HTML output to list the units
	 *
	 * @since    1.0.0
	 */
	public function list_units_shortcode( $atts ) { 
	
		$areaone = get_posts(
			array(
				'posts_per_page'=> 250,
				'post_type'		=> 'unit',
				'fields'		=> 'ids',
				'meta_query' 	=> array(
					array(
						'key'     => 'area',
						'value'   => '1',
						'compare' => '=',
					),
				),
			)
		);
		$areagreater = get_posts(
			array(
				'posts_per_page'=> 250,
				'post_type'		=> 'unit',
				'fields'		=> 'ids',
				'meta_query' 	=> array(
					array(
						'key'     => 'area',
						'value'   => '1',
						'compare' => '>',
					),
				),
			)
		);
		$output = '<div class="unit-list">';
		$output .= '<h2>List of Units</h2>';
		$output .= '<div class="wp-block-columns is-layout-flex wp-container-8">';
		$output .= '<div class="wp-block-column is-layout-flow">';
		$output .= '<h4>Areas of One</h4>';
		$output .= $this->unit_listing_render( $areaone );
		$output .= '</div>';
		$output .= '<div class="wp-block-column is-layout-flow">';
		$output .= '<h4>Areas Greater Than One</h4>';
		$output .= $this->unit_listing_render( $areagreater );
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		return $output;
	}
	
	/**
	 * Builds HTML to render individual Unit listings
	 *
	 * @since    1.0.0
	 */
	public function unit_listing_render( $results ) {
		if ( empty( $results ) ) return;
		$output = "";
		foreach ( $results as $unitID ) {
			$output .= '<h5>Unit #' . get_the_title( $unitID ) . '</h5>';
			$output .= '<ul class="unit-metadata">';
			$output .= '<li><span>Asset ID: </span>' . get_post_meta( $unitID, 'asset_id', true ).'</li>';
			$output .= '<li><span>Building ID: </span>' . get_post_meta( $unitID, 'building_id', true ).'</li>';
			$output .= '<li><span>Floor ID: </span>' . get_post_meta( $unitID, 'floor_id', true ).'</li>';
			$output .= '<li><span>Floor Plan ID: </span>' . get_post_meta( $unitID, 'floor_plan_id', true ).'</li>';
			$output .= '</ul>';
		}
		return $output;
	}

}
