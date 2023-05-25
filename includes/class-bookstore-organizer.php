<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Bookstore_Organizer
 * @subpackage Bookstore_Organizer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bookstore_Organizer
 * @subpackage Bookstore_Organizer/includes
 */
class Bookstore_Organizer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bookstore_Organizer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $bookstore_organizer    The string used to uniquely identify this plugin.
	 */
	protected $bookstore_organizer;

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
		if ( defined( 'Bookstore_Organizer_CORE_VERSION' ) ) {
			$this->version = Bookstore_Organizer_CORE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->bookstore_organizer = 'bookstore-organizer';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bookstore_Organizer_Loader. Orchestrates the hooks of the plugin.
	 * - Bookstore_Organizer_i18n. Defines internationalization functionality.
	 * - Bookstore_Organizer_Admin. Defines all hooks for the admin area.
	 * - Bookstore_Organizer_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bookstore-organizer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bookstore-organizer-i18n.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bookstore-organizer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bookstore-organizer-public.php';

		$this->loader = new Bookstore_Organizer_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bookstore_Organizer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Bookstore_Organizer_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Bookstore_Organizer_Admin( $this->get_bookstore_organizer(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        	$this->loader->add_filter('acf/settings/load_json', $plugin_admin, 'acf_json_load_point');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Bookstore_Organizer_Public( $this->get_bookstore_organizer(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Action for register Book post type
		$this->loader->add_action('init', \BookstoreOrganizer\PostTypes\PostTypes::class, 'register_all_post_types');

		// Put any Book Taxonomy if you want
		$book_taxonomies = [
			'genre' => 'Genre',
			'author' => 'Author'
		];

		// Action for register custom taxonomies
		$taxonomies = new \BookstoreOrganizer\Taxonomies\Taxonomies($book_taxonomies);
		$this->loader->add_action('init', $taxonomies, 'register_all_taxonomies');

		// Add your metaboxes to create
		// [TODO]: the metaboxs array will be loaded from plugin option page in upcomming version
		$book_metaboxes = [
			'author' => 'Author',
			'isbn' => 'ISBN',
			'price' => 'Price'
		];
		
		
		// Add metaboxes action
		$metaboxes = new \BookstoreOrganizer\MetaBoxes\MetaBoxes($book_metaboxes) ;
		$this->loader->add_action('add_meta_boxes', $metaboxes, 'add_all_metaboxes');

		//Save metaboxes action
		$this->loader->add_action('save_post_hm_book', $metaboxes, 'bookstore_organizer_save_metaboxes');
		$this->loader->add_action('admin_notices', $metaboxes, 'book_metabox_validation_notice');


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
	public function get_bookstore_organizer() {
		return $this->bookstore_organizer;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bookstore_Organizer_Loader    Orchestrates the hooks of the plugin.
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

}
