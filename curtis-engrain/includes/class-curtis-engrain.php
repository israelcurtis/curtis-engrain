<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/israelcurtis
 * @since      1.0.0
 *
 * @package    Curtis_Engrain
 * @subpackage Curtis_Engrain/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, init hooks, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Curtis_Engrain
 * @subpackage Curtis_Engrain/includes
 * @author     Israel Curtis <israel.curtis@gmail.com>
 */
class Curtis_Engrain {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Curtis_Engrain_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CURTIS_ENGRAIN_VERSION' ) ) {
			$this->version = CURTIS_ENGRAIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'curtis-engrain';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_init_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Curtis_Engrain_Loader. Orchestrates the hooks of the plugin.
	 * - Curtis_Engrain_i18n. Defines internationalization functionality.
	 * - Curtis_Engrain_Admin. Defines all hooks for the admin area.
	 * - Curtis_Engrain_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-curtis-engrain-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-curtis-engrain-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-curtis-engrain-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-curtis-engrain-public.php';

		$this->loader = new Curtis_Engrain_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Curtis_Engrain_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Curtis_Engrain_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register hooks to be fired when Wordpress finishes loading, before any headers are sent
	 * These are functions critical to the structure of the CMS, loaded both for admin and public areas
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_init_hooks() {
	
		$this->loader->add_action( 'init', $this, 'register_unit_cpt' );
	
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Curtis_Engrain_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'curtis_engrain_add_admin_menu' );
		$this->loader->add_action( 'wp_ajax_fetch_units', $plugin_admin, 'curtis_engrain_api_call' );
		$this->loader->add_action( 'add_meta_boxes_unit', $plugin_admin, 'engrain_meta_box' );
	
		$this->loader->add_filter( 'use_block_editor_for_post_type', $plugin_admin, 'unit_disable_gutenberg', 10, 2 );
		$this->loader->add_filter( 'manage_unit_posts_columns', $plugin_admin, 'unit_posts_columns', 10, 1 );
		$this->loader->add_action( 'manage_unit_posts_custom_column', $plugin_admin, 'unit_posts_column_data', 10, 2 );
		$this->loader->add_filter( 'manage_edit-unit_sortable_columns', $plugin_admin, 'make_unit_column_sortable', 10, 1 );
		$this->loader->add_action( 'pre_get_posts', $plugin_admin, 'ordering_column_floorplan' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Curtis_Engrain_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_shortcode( 'list-units', $plugin_public, 'list_units_shortcode' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Curtis_Engrain_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 * Register the Custom Post Type UNIT
	 *
	 * @since    1.0.0
	 */
	public function register_unit_cpt() {
		$args = [
			'label'  => esc_html__( 'Units', 'curtis-engrain' ),
			'labels' => [
				'menu_name'          => esc_html__( 'Units', 'curtis-engrain' ),
				'name_admin_bar'     => esc_html__( 'Unit', 'curtis-engrain' ),
				'add_new'            => esc_html__( 'Add Unit', 'curtis-engrain' ),
				'add_new_item'       => esc_html__( 'Add new Unit', 'curtis-engrain' ),
				'new_item'           => esc_html__( 'New Unit', 'curtis-engrain' ),
				'edit_item'          => esc_html__( 'Edit Unit', 'curtis-engrain' ),
				'view_item'          => esc_html__( 'View Unit', 'curtis-engrain' ),
				'update_item'        => esc_html__( 'View Unit', 'curtis-engrain' ),
				'all_items'          => esc_html__( 'All Units', 'curtis-engrain' ),
				'search_items'       => esc_html__( 'Search Units', 'curtis-engrain' ),
				'parent_item_colon'  => esc_html__( 'Parent Unit', 'curtis-engrain' ),
				'not_found'          => esc_html__( 'No Units found', 'curtis-engrain' ),
				'not_found_in_trash' => esc_html__( 'No Units found in Trash', 'curtis-engrain' ),
				'name'               => esc_html__( 'Units', 'curtis-engrain' ),
				'singular_name'      => esc_html__( 'Unit', 'curtis-engrain' ),
			],
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite_no_front'    => false,
			'show_in_menu'        => true,
			'menu_position'       => 2,
			'menu_icon'           => 'dashicons-building',
			'supports' => [
				'title',
				// 'editor',
				// 'custom-fields',
			],
			
			'rewrite' => true
		];
		register_post_type( 'unit', $args );
	}

}