<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Bookstore_Organizer
 * @subpackage Bookstore_Organizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bookstore_Organizer
 * @subpackage Bookstore_Organizer/admin
 * @author     Hamed Elahi
 */
class Bookstore_Organizer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $bookstore_organizer    The ID of this plugin.
	 */
	private $bookstore_organizer;

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
	 * @param      string    $bookstore_organizer       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $bookstore_organizer, $version ) {

		$this->bookstore_organizer = $bookstore_organizer;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bookstore_Organizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bookstore_Organizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->bookstore_organizer, plugin_dir_url( __FILE__ ) . 'css/bookstore-organizer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bookstore_Organizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bookstore_Organizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->bookstore_organizer, plugin_dir_url( __FILE__ ) . 'js/bookstore-organizer-admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->bookstore_organizer, 'SA_CORE', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

	function acf_json_load_point($paths)
	{	
		$paths[] = plugin_dir_path( __FILE__ ) . 'acf-json';

		return $paths;
	}

}
