<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/israelcurtis
 * @since      1.0.0
 *
 * @package    Curtis_Engrain
 * @subpackage Curtis_Engrain/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Curtis_Engrain
 * @subpackage Curtis_Engrain/admin
 * @author     Israel Curtis <israel.curtis@gmail.com>
 */
class Curtis_Engrain_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/curtis-engrain-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/curtis-engrain-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Add settings page in the menu for the admin area
	 *
	 * @since    1.0.0
	 */
	public function curtis_engrain_add_admin_menu() { 
		add_menu_page( 'Curtis Engrain', 'Curtis Engrain', 'manage_options', 'curtis_engrain', array( $this, 'curtis_engrain_settings_page' ) );
	}
	
	
	/**
	 * Output HTML content for our plugin settings page
	 *
	 * @since    1.0.0
	 */
	public function curtis_engrain_settings_page() { 
		?>
		<div class="wrap">
			<h1>Curtis Engrain Dev Assessment Plugin</h1>
			<p>You can use the shortcode <code>[list-units]</code> on any page or post to display a listing of all units grouped by Area<p>
			<p><em>Click the button below to begin importing unit records from the API</em></p>
			<div id="api-fetch" class="button button-primary">
				<h4>Import Units from API</h4>
			</div>
			<div id="status">
			</div>
			<ul id="response">
				<li></li>
			</ul>
		</div>
		<?php
	}
	
	
	/**
	 * Handle AJAX functions for the API import button on the settings page
	 * Uses hardcoded URL and API key to fetch JSON, creating new 'unit' posts
	 * Echoes HTML response to the $.ajax() function in curtis-engrain-admin.js
	 *
	 * @since    1.0.0
	 */
	public function curtis_engrain_api_call() {
		
		global $wpdb;
	
		$url = 'https://api.sightmap.com/v1/assets/1273/multifamily/units?per-page=250';
		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'API-Key' => '7d64ca3869544c469c3e7a586921ba37'
				)
		);
		$response = wp_remote_get( esc_url_raw( $url ), $args );
		$body_response = json_decode( wp_remote_retrieve_body( $response ), true );
		
		// EMPTY RESPONSE ERROR
		if ( !is_array( $body_response["data"] ) || empty( $body_response["data"] ) ) {
			echo "<li>no valid API response</li>";
			wp_die();
		}
		
		// PROCESS
		foreach ( $body_response["data"] as $unit ) {
			// currently skipping existing records, would need to know if updates from the API are preferred instead
			if ( post_exists( $unit['unit_number'] ) ) {
				echo "<li>".$unit['unit_number'] . " - already exists, skipped import</li>";
				continue;
			} else {
				$newunit = array(
					'post_title'    => $unit['unit_number'],
					'post_type'     => 'unit',
					'post_status'   => 'publish',
					'meta_input'    => array(
						'asset_id'      => $unit['asset_id'],
						'building_id'   => $unit['building_id'],
						'floor_id'      => $unit['floor_id'],
						'floor_plan_id' => $unit['floor_plan_id'],
						'area'          => $unit['area']
					),
				);
				$create = wp_insert_post( $newunit );
				if ( is_wp_error( $create ) || empty( $create ) ) {
					echo "<li>unit import failed</li>";
				} else {
					echo "<li>new unit created: " . $unit['unit_number'] . "</li>";
				}
			}
		}
		wp_die();
	}
	
	
	/**
	 * Configure metabox display for the 'unit' post edit screen
	 *
	 * @since    1.0.0
	 */
	public function engrain_meta_box() {
		remove_meta_box( 'slugdiv', 'unit', 'normal' );
		global $post;
		add_meta_box(
			'unit-meta',
			'UNIT #'.$post->post_title,
			array( $this, 'unit_meta_box_content' )
		);
	}
	
	/**
	 * Output HTML content for the 'unit' metabox
	 *
	 * @since    1.0.0
	 */
	public function unit_meta_box_content( $post ) {
		echo '<ul class="unit-metadata">';
		echo '<li><span>Asset ID: </span>' . get_post_meta( $post->ID, 'asset_id', true ).'</li>';
		echo '<li><span>Building ID: </span>' . get_post_meta( $post->ID, 'building_id', true ).'</li>';
		echo '<li><span>Floor ID: </span>' . get_post_meta( $post->ID, 'floor_id', true ).'</li>';
		echo '<li><span>Floor Plan ID: </span>' . get_post_meta( $post->ID, 'floor_plan_id', true ).'</li>';
		echo '<li><span>Area: </span>' . get_post_meta( $post->ID, 'area', true ).'</li>';
		echo '</ul>';
	}
	
	
	/**
	 * Disable the gutenberg post editor just for 'unit'
	 *
	 * @since    1.0.0
	 */
	public function unit_disable_gutenberg( $current_status, $post_type ) {
		if ( $post_type === 'unit' ) return false;
		return $current_status;
	}
	
	/**
	 * Modify columns for the 'unit' post lists
	 *
	 * @since    1.0.0
	 */
	public function unit_posts_columns( $columns ) {
		unset($columns['date']);
		return array_merge( $columns, ['floor_plan_id' => 'Floor Plan'] );
	}

	/**
	 * Fetch post meta for custom columns
	 *
	 * @since    1.0.0
	 */
	public function unit_posts_column_data( $column_key, $post_id ) {
		if ( $column_key == 'floor_plan_id' ) {
			echo get_post_meta($post_id, 'floor_plan_id', true);
		}
	}

	/**
	 * Allow sorting of our custom column
	 *
	 * @since    1.0.0
	 */	
	public function make_unit_column_sortable( $columns ) {
		$columns['floor_plan_id'] = 'floorplan';
		return $columns;
	}
	
	/**
	 * Modify query to sort my meta value when floorplan
	 *
	 * @since    1.0.0
	 */	
	public function ordering_column_floorplan( $query ) {
		if ( !is_admin() ) {
			return;
		}
		$orderby = $query->get('orderby');
		if ( $orderby == 'floorplan' ) {
			$query->set( 'meta_key', 'floor_plan_id' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}


}